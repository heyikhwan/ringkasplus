<?php

namespace App\Services;

use App\Exceptions\AppException;
use App\Repositories\ApplicationSettingRepository;
use App\Traits\ActivityLogUser;
use App\Traits\UploadFileTrait;
use Illuminate\Support\Facades\DB;

class ApplicationSettingService
{
    use ActivityLogUser, UploadFileTrait;

    protected $logName = 'Application Setting';

    protected $applicationSettingRepository;

    public function __construct(ApplicationSettingRepository $applicationSettingRepository)
    {
        $this->applicationSettingRepository = $applicationSettingRepository;
    }

    public function getByKey($key)
    {
        return $this->applicationSettingRepository->getByKey($key)->pluck('value', 'key')->toArray();
    }

    public function updateGeneralSetting($request)
    {
        DB::beginTransaction();

        try {
            foreach ($request as $key => $value) {
                if ($key == 'general_logo_dark' && is_file($value)) {
                    $general_logo_dark = $this->applicationSettingRepository->findByKey('general_logo_dark')?->value;
                    if ($general_logo_dark) {
                        $this->deleteFileByUrl($general_logo_dark);
                    }
                    $value = $this->uploadFile($value, 'application_setting');
                }

                if (isset($request['general_logo_light']) && is_file($value)) {
                    $general_logo_light = $this->applicationSettingRepository->findByKey('general_logo_light')?->value;
                    if ($general_logo_light) {
                        $this->deleteFileByUrl($general_logo_light);
                    }
                    $value = $this->uploadFile($value, 'application_setting');
                }

                if (isset($request['general_favicon']) && is_file($value)) {
                    $general_favicon = $this->applicationSettingRepository->findByKey('general_favicon')?->value;
                    if ($general_favicon) {
                        $this->deleteFileByUrl($general_favicon);
                    }
                    $value = $this->uploadFile($value, 'application_setting');
                }

                if ($key == 'general_whatsapp') {
                    $value = preg_replace('/[^0-9]/', '', $value); // hilangkan semua selain angka
                    $value = preg_replace('/^62/', '', $value);    // hilangkan 62 di awal jika ada
                }

                $this->applicationSettingRepository->updateOrCreate($key, $value);
            }

            $this->activityUpdate('Mengubah pengaturan umum', null, $request);

            DB::commit();

            return true;
        } catch (AppException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function updateSocialMediaSetting($request)
    {
        DB::beginTransaction();

        try {
            foreach ($request as $key => $value) {
                $this->applicationSettingRepository->updateOrCreate($key, $value);
            }

            $this->activityUpdate('Mengubah pengaturan sosial media', null, $request);

            DB::commit();

            return true;
        } catch (AppException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
