<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <a href="javascript:;" class="mx-auto">
            @if (getApplicationSetting('general_logo_dark', '', true))
                <img alt="Logo" src="{{ getFileUrl(getApplicationSetting('general_logo_dark', '', true)) }}"
                    class="h-25px app-sidebar-logo-default" />
            @else
                <h1 class="app-sidebar-logo-default text-white">
                    {{ getApplicationSetting('general_application_name', env('APP_NAME')) }}</h1>
            @endif

            @if (getApplicationSetting('general_logo_small', '', true))
                <img alt="Logo" src="{{ getFileUrl(getApplicationSetting('general_logo_small', '', true)) }}"
                    class="h-20px app-sidebar-logo-minimize" />
            @endif
        </a>
        <div id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-duotone ki-black-left-line fs-3 rotate-180">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
    </div>

    @include('layouts.app.partials.menu')

    <div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
        <a href="#"
            class="btn btn-flex flex-center btn-custom btn-primary overflow-hidden text-nowrap px-0 h-40px w-100"
            data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click"
            title="200+ in-house components and 3rd-party plugins">
            <span class="btn-label">Docs & Components</span>
            <i class="ki-duotone ki-document btn-icon fs-2 m-0">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </a>
    </div>
</div>
