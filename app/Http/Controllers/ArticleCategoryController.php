<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Http\Requests\ArticleCategoryRequest;
use App\Services\ArticleCategoryService;
use Illuminate\Routing\Controllers\Middleware;

class ArticleCategoryController extends Controller
{
    protected $title = 'Kategori';
    protected $view = 'app.article-category';
    protected $permission_name = 'article-category';

    public $categoryService;

    public function __construct(ArticleCategoryService $categoryService)
    {
        $this->categoryService = $categoryService;

        $this->setupConstruct();
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:article-category.index', only: ['index']),
            new Middleware('can:article-category.create', only: ['create', 'store']),
            new Middleware('can:article-category.edit', only: ['edit', 'update']),
            new Middleware('can:article-category.destroy', only: ['destroy']),
        ];
    }

    public function index()
    {
        $this->setBreadcrumbs([
            [
                'title' => $this->title
            ]
        ]);

        return view("{$this->view}.index");
    }

    public function datatable()
    {
        return $this->categoryService->datatable($this->permission_name);
    }

    public function create()
    {
        notAjaxAbort();

        return view("{$this->view}.create");
    }

    public function store(ArticleCategoryRequest $request)
    {
        notAjaxAbort();

        $data = $request->validated();

        try {
            $this->categoryService->create($data);

            return responseSuccess(BERHASIL_SIMPAN);
        } catch (AppException $e) {
            return responseFail($e->getMessage());
        } catch (\Throwable $th) {
            return responseFail(GAGAL_SIMPAN);
        }
    }

    public function edit($id)
    {
        notAjaxAbort();

        $result = $this->categoryService->findById(decode($id));

        return view("{$this->view}.edit", [
            'result' => $result
        ]);
    }

    public function update(ArticleCategoryRequest $request, $id)
    {
        notAjaxAbort();

        $data = $request->validated();

        try {
            $this->categoryService->update(decode($id), $data);

            return responseSuccess(BERHASIL_UPDATE);
        } catch (AppException $e) {
            return responseFail($e->getMessage());
        } catch (\Throwable $th) {
            return responseFail(GAGAL_UPDATE);
        }
    }

    public function destroy($id)
    {
        notAjaxAbort();

        try {
            $this->categoryService->destroy(decode($id));

            return responseSuccess(BERHASIL_HAPUS);
        } catch (AppException $e) {
            return responseFail($e->getMessage());
        } catch (\Throwable $th) {
            return responseFail(GAGAL_HAPUS);
        }
    }
}
