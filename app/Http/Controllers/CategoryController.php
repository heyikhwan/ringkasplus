<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Services\CategoryService;
use App\Http\Requests\CategoryRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class CategoryController extends Controller implements HasMiddleware
{
    protected $title = 'Kategori';
    protected $view = 'app.category';
    protected $permission_name = 'category';

    public $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;

        $this->setupConstruct();
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:category.index', only: ['index']),
            new Middleware('can:category.create', only: ['create', 'store']),
            new Middleware('can:category.edit', only: ['edit', 'update']),
            new Middleware('can:category.destroy', only: ['destroy']),
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

    public function store(CategoryRequest $request)
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

    public function update(CategoryRequest $request, $id)
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
