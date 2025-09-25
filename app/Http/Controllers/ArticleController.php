<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Http\Requests\ArticleRequest;
use App\Services\ArticleService;
use App\Services\CategoryService;
use App\Services\TagService;
use Illuminate\Routing\Controllers\Middleware;

class ArticleController extends Controller
{
    protected $title = 'Artikel';
    protected $view = 'app.article';
    protected $permission_name = 'article';

    public $articleService;
    public $categoryService;
    public $tagService;

    public function __construct(ArticleService $articleService, CategoryService $categoryService, TagService $tagService)
    {
        $this->articleService = $articleService;
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;

        $this->setupConstruct();
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:article.index', only: ['index']),
            new Middleware('can:article.create', only: ['create', 'store']),
            new Middleware('can:article.edit', only: ['edit', 'update']),
            new Middleware('can:article.destroy', only: ['destroy']),
        ];
    }

    public function index()
    {
        $this->setBreadcrumbs([
            [
                'title' => $this->title
            ]
        ]);

        return view("{$this->view}.index", [
            'statusOptions' => $this->articleService->getStatusOptions()
        ]);
    }

    public function datatable()
    {
        return $this->articleService->datatable($this->permission_name);
    }

    public function create()
    {
        $this->setBreadcrumbs([
            [
                'title' => $this->title,
                'url' => route("$this->permission_name.index")
            ],
            [
                'title' => 'Tambah Data'
            ]
        ]);

        return view("{$this->view}.create", [
            'statusOptions' => $this->articleService->getStatusOptions()
        ]);
    }

    public function store(ArticleRequest $request)
    {
        $data = $request->validated();

        try {
            $this->articleService->create($data);

            return redirect()->route("$this->permission_name.index")->with('success', BERHASIL_SIMPAN);
        } catch (AppException $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', GAGAL_SIMPAN)->withInput();
        }
    }

    public function edit($id)
    {
        $this->setBreadcrumbs([
            [
                'title' => $this->title,
                'url' => route("$this->permission_name.index")
            ],
            [
                'title' => 'Ubah Data'
            ]
        ]);

        $result = $this->articleService->findById(decode($id));

        $category_ids = $result->categories->pluck('id')->toArray();
        $result->categories = $this->categoryService->getAll(limit: 0, paginate: false, callback: function ($q) use ($category_ids) {
            $q->whereIn('id', $category_ids);
        })->pluck('name', 'slug')->toArray();

        $tag_ids = $result->tags->pluck('id')->toArray();
        $result->tags = $this->tagService->getAll(limit: 0, paginate: false, callback: function ($q) use ($tag_ids) {
            $q->whereIn('id', $tag_ids);
        })->pluck('name', 'slug')->toArray();

        return view("{$this->view}.edit", [
            'result' => $result,
            'statusOptions' => $this->articleService->getStatusOptions()
        ]);
    }

    public function update(ArticleRequest $request, $id)
    {
        $data = $request->validated();

        try {
            $this->articleService->update(decode($id), $data);

            return redirect()->route("$this->permission_name.index")->with('success', BERHASIL_SIMPAN);
        } catch (AppException $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', GAGAL_SIMPAN)->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $this->articleService->destroy(decode($id));

            return responseSuccess(BERHASIL_HAPUS);
        } catch (AppException $e) {
            return responseError($e->getMessage());
        } catch (\Throwable $th) {
            return responseError(GAGAL_HAPUS);
        }
    }

    public function removeImage($id, $field)
    {
        try {
            $this->articleService->removeImage(decode($id), $field);

            return responseSuccess(BERHASIL_HAPUS);
        } catch (AppException $e) {
            return responseError($e->getMessage());
        } catch (\Throwable $th) {
            return responseError(GAGAL_HAPUS);
        }
    }
}
