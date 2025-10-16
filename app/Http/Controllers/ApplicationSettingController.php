<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Services\ApplicationSettingService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ApplicationSettingController extends Controller implements HasMiddleware
{
    protected $title = 'Pengaturan Aplikasi';
    protected $view = 'app.application-setting';
    protected $permission_name = 'application-setting';

    protected $applicationSettingService;

    public function __construct(ApplicationSettingService $applicationSettingService)
    {
        $this->setupConstruct();

        $this->applicationSettingService = $applicationSettingService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:application-setting.general', only: ['general']),
            new Middleware('can:application-setting.seo', only: ['seo']),
            new Middleware('can:application-setting.social-media', only: ['socialMedia']),
        ];
    }

    public function general()
    {
        if (request()->isMethod('put')) {
            $data = request()->validate([
                'general_logo_dark' => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
                'general_logo_light' => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
                'general_favicon' => 'nullable|mimes:ico|max:5120',
                'general_application_name' => 'required|string|min:3|max:255',
                'general_application_slogan' => 'nullable|string|max:255',
                'general_application_description' => 'nullable|string',
                'general_phone' => 'nullable|string',
                'general_whatsapp' => 'nullable|string',
                'general_email' => 'nullable|email',
                'general_whatsapp_message' => 'nullable|string',
                'general_address' => 'nullable|string',
            ], [
                'general_logo_dark.max' => 'ukuran file maksimal 5MB',
                'general_logo_light.max' => 'ukuran file maksimal 5MB',
                'general_favicon.max' => 'ukuran file maksimal 5MB',
            ], [
                'general_logo_dark' => 'logo gelap',
                'general_logo_light' => 'logo terang',
                'general_favicon' => 'favicon',
                'general_application_name' => 'nama aplikasi',
                'general_application_slogan' => 'slogan aplikasi',
                'general_application_description' => 'deskripsi aplikasi',
                'general_phone' => 'telepon',
                'general_whatsapp' => 'whatsapp',
                'general_email' => 'email',
                'general_whatsapp_message' => 'pesan whatsapp',
                'general_address' => 'alamat',
            ]);

            try {
                $this->applicationSettingService->updateGeneralSetting($data);

                return redirect()->back()->with('success', BERHASIL_SIMPAN);
            } catch (AppException $e) {
                return redirect()->back()->with('error', $e->getMessage())->withInput();
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', GAGAL_SIMPAN)->withInput();
            }
        }

        $result = $this->applicationSettingService->getByKey('general_');

        return view($this->view . '.general', [
            'result' => $result
        ]);
    }

    public function socialMedia()
    {
        if (request()->isMethod('put')) {
            $data = request()->validate([
                'social_media_facebook' => 'nullable|url',
                'social_media_twitter' => 'nullable|url',
                'social_media_instagram' => 'nullable|url',
                'social_media_youtube' => 'nullable|url',
                'social_media_linkedin' => 'nullable|url',
                'social_media_tiktok' => 'nullable|url',
            ], [], [
                'social_media_facebook' => 'facebook',
                'social_media_twitter' => 'twitter',
                'social_media_instagram' => 'instagram',
                'social_media_youtube' => 'youtube',
                'social_media_linkedin' => 'linkedin',
                'social_media_tiktok' => 'tiktok',
            ]);

            try {
                $this->applicationSettingService->updateSocialMediaSetting($data);

                return redirect()->back()->with('success', BERHASIL_SIMPAN);
            } catch (AppException $e) {
                return redirect()->back()->with('error', $e->getMessage())->withInput();
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', GAGAL_SIMPAN)->withInput();
            }
        }

        $result = $this->applicationSettingService->getByKey('social_media_');

        return view($this->view . '.social-media', [
            'result' => $result
        ]);
    }
}
