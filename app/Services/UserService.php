<?php

namespace App\Services;

use App\Exceptions\AppException;
use App\Repositories\UserRepository;
use App\Traits\ActivityLogUser;
use App\Traits\UploadFileTrait;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserService
{
    use UploadFileTrait, ActivityLogUser;

    protected $logName = 'User';
    
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function datatable($permission_name)
    {
        $query = $this->userRepository->getBaseQuery();

        return DataTables::eloquent($query)
            ->addColumn('photo', function ($row) {
                return $row->photo
                    ? getFileUrl($row->photo)
                    : asset('app/assets/media/avatars/blank.png');
            })
            ->addColumn('action', function ($row) use ($permission_name) {
                $primaryKey = encrypt($row->id);

                // TODO: aktifkan permission
                $items = [
                    [
                        // 'permission' => 'user.edit',
                        'title' => 'Ubah',
                        'icon' => '<i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>',
                        'attributes' => [
                            'data-title' => 'Ubah ' . $row->name,
                            'data-url' => route("$permission_name.edit", $primaryKey),
                            'onclick' => "actionModalData(this)"
                        ]
                    ],
                    [
                        // 'permission' => 'user.reset-password',
                        'title' => 'Reset Password',
                        'icon' => '<i class="ki-duotone ki-lock-2 fs-2 text-info"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>',
                        'class' => 'text-info',
                        'attributes' => [
                            'onclick' => "resetPassword('" . route("$permission_name.reset-password", $primaryKey) . "')",
                        ]
                    ]
                ];

                if ($row->id != auth()->id()) {
                    $items[] = [
                        // 'permission' => 'user.delete',
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
        return $this->userRepository->findById($id, $with);
    }

    public function create($request)
    {
        $photoPath = null;
        if (isset($request['photo']) && is_file($request['photo'])) {
            $photoPath = $this->uploadFile($request['photo'], 'user');
        }

        $data = [
            'name' => $request['name'],
            'username' => $request['username'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'status' => !empty($request['status']),
            'photo' => $photoPath
        ];

        try {
            $result = $this->userRepository->create($data);

            $this->activityCreate('Menambahkan user baru', $result);

            return $result;
        } catch (\Throwable $e) {
            if ($photoPath) {
                $this->deleteFile($photoPath);
            }
            throw $e;
        }
    }

    public function update($id, $request)
    {
        $user = $this->findById($id);
        if (!$user) throw new AppException(DATA_TIDAK_DITEMUKAN);

        $oldPhoto = $user->photo;
        $newPhoto = $oldPhoto;

        if (isset($request['photo']) && is_file($request['photo'])) {
            $newPhoto = $this->uploadFile($request['photo'], 'user');
        }

        $data = [
            'name' => $request['name'],
            'username' => $request['username'],
            'email' => $request['email'],
            'status' => !empty($request['status']),
            'photo' => $newPhoto
        ];

        try {
            $result = $this->userRepository->update($id, $data);

            if ($newPhoto !== $oldPhoto && $oldPhoto) {
                $this->deleteFile($oldPhoto);
            }

            $this->activityUpdate('Mengubah data user', $result);

            return $result;
        } catch (\Throwable $e) {
            if ($newPhoto !== $oldPhoto) {
                $this->deleteFile($newPhoto);
            }
            throw $e;
        }
    }


    public function destroy($id)
    {
        $user = $this->findById($id);
        if (!$user) {
            throw new AppException(DATA_TIDAK_DITEMUKAN);
        }

        $photoPath = $user->photo;

        try {
            $result = $this->userRepository->delete($id);

            if ($result && $photoPath) {
                $this->deleteFile($photoPath);
            }

            $this->activityDelete('Menghapus data user', $user);

            return $result;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function resetPassword($id)
    {
        $user = $this->findById($id);
        if (!$user) {
            throw new AppException(DATA_TIDAK_DITEMUKAN);
        }

        try {
            $data = [
                'password' => Hash::make('12345678')
            ];

            $result = $this->userRepository->update($id, $data);

            $this->activityUpdate('Reset password user', $user);

            return $result;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
