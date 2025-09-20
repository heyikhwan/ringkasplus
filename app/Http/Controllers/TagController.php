<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Http\Requests\TagRequest;
use App\Services\TagService;
use Illuminate\Routing\Controllers\Middleware;

class TagController extends Controller
{
    protected $title = 'Tag';
    protected $view = 'app.tag';
    protected $permission_name = 'tag';

    public $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;

        $this->setupConstruct();
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:tag.index', only: ['index']),
            new Middleware('can:tag.create', only: ['create', 'store']),
            new Middleware('can:tag.edit', only: ['edit', 'update']),
            new Middleware('can:tag.destroy', only: ['destroy']),
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

    public function store(TagRequest $request)
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

    public function update(TagRequest $request, $id)
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
