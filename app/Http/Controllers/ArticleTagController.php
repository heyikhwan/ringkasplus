<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Http\Requests\ArticleTagRequest;
use App\Services\ArticleTagService;
use Illuminate\Routing\Controllers\Middleware;

class ArticleTagController extends Controller
{
    protected $title = 'Tag';
    protected $view = 'app.article-tag';
    protected $permission_name = 'article-tag';

    public $tagService;

    public function __construct(ArticleTagService $tagService)
    {
        $this->tagService = $tagService;

        $this->setupConstruct();
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:article-tag.index', only: ['index']),
            new Middleware('can:article-tag.create', only: ['create', 'store']),
            new Middleware('can:article-tag.edit', only: ['edit', 'update']),
            new Middleware('can:article-tag.destroy', only: ['destroy']),
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
        return $this->tagService->datatable($this->permission_name);
    }

    public function create()
    {
        notAjaxAbort();

        return view("{$this->view}.create");
    }

    public function store(ArticleTagRequest $request)
    {
        notAjaxAbort();

        $data = $request->validated();

        try {
            $this->tagService->create($data);

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

        $result = $this->tagService->findById(decode($id));

        return view("{$this->view}.edit", [
            'result' => $result
        ]);
    }

    public function update(ArticleTagRequest $request, $id)
    {
        notAjaxAbort();

        $data = $request->validated();

        try {
            $this->tagService->update(decode($id), $data);

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
            $this->tagService->destroy(decode($id));

            return responseSuccess(BERHASIL_HAPUS);
        } catch (AppException $e) {
            return responseFail($e->getMessage());
        } catch (\Throwable $th) {
            return responseFail(GAGAL_HAPUS);
        }
    }
}
