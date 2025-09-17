<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Traits\ActivityLogUser;
use App\Exceptions\AppException;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\ArticleCategoryRepository;

class ArticleCategoryService
{
    use ActivityLogUser;

    protected $logName = 'Kategori Artikel';

    protected $categoryRepository;

    public function __construct(ArticleCategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function datatable($permission_name)
    {
        $query = $this->categoryRepository->getBaseQuery();

        return DataTables::eloquent($query)
            ->addColumn('action', function ($row) use ($permission_name) {
                $primaryKey = encrypt($row->id);

                $authUser   = auth()->user();
                $items      = [];

                if ($authUser->can('update', $row)) {
                    $items[] = [
                        'permission' => "$permission_name.edit",
                        'title' => 'Ubah',
                        'icon' => '<i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>',
                        'attributes' => [
                            'data-title' => 'Ubah ' . $row->name,
                            'data-url' => route("$permission_name.edit", $primaryKey),
                            'onclick' => "actionModalData(this)"
                        ]
                    ];
                }

                if ($authUser->can('delete', $row)) {
                    $items[] = [
                        'permission' => "$permission_name.destroy",
                        'title' => 'Hapus',
                        'icon' => '<i class="ki-duotone ki-trash fs-2 text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>',
                        'class' => 'text-danger',
                        'attributes' => [
                            'onclick' => "forceDeleteDataDataTable('" . route("$permission_name.destroy", $primaryKey) . "')",
                        ]
                    ];
                }

                return view('components.button-dropdown', [
                    'items' => $items
                ])->render();
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function findById($id, $with = [])
    {
        return $this->categoryRepository->findById($id, $with);
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {
            $slug = Str::slug($request['name']);

            $result = $this->categoryRepository->create([
                'name' => $request['name'],
                'slug' => $slug,
                'status' => !empty($request['status'])
            ]);

            DB::commit();

            $this->activityCreate('Menambahkan kategori artikel', $result);

            return $result;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $request)
    {
        $result = $this->findById($id);
        if (!$result) {
            throw new AppException(DATA_TIDAK_DITEMUKAN);
        }

        DB::beginTransaction();

        try {
            $slug = $result->slug;
            if ($slug != Str::slug($request['name'])) {
                $slug = Str::slug($request['name']);
            }

            $result->update([
                'name' => $request['name'],
                'slug' => $slug,
                'status' => !empty($request['status']),
            ]);

            DB::commit();

            $this->activityUpdate('Mengubah data kategori artikel', $result);

            return $result;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy($id)
    {
        $result = $this->findById($id);
        if (!$result) {
            throw new AppException(DATA_TIDAK_DITEMUKAN);
        }

        try {
            $result = $this->categoryRepository->delete($id);

            $this->activityDelete('Menghapus data kategori artikel', $result);

            return $result;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
