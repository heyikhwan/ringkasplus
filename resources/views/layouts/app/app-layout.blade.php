<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>
        @if ($title == 'Ringkas Plus')
            {{ $title }}
        @else
            {{ $title }} - Ringkas Plus
        @endif
    </title>

    @include('layouts.app.partials.meta')
    <meta name="csrf-token" content="{!! csrf_token() !!}" />
    @include('layouts.app.partials.global-styles')
    <link href="{{ asset('app/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/fancybox/fancybox.css" />
    <link rel="stylesheet" href="{{ asset('app/assets/css/custom.css') }}">
    @stack('styles')
</head>

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-push-header="true"
    data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" class="app-default">
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            @include('layouts.app.partials.header', [
                'title' => $title ?? config('app.name'),
                'breadcrumbs' => $breadcrumbs ?? [],
            ])
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                @include('layouts.app.partials.sidebar')

                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">
                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <div id="kt_app_content_container" class="app-container container-fluid">
                                {{ $slot }}
                                <x-modal title="Modal" id="default-ikh-modal" />
                            </div>
                        </div>
                    </div>

                    <div id="kt_app_footer" class="app-footer">
                        <div
                            class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
                            <div class="text-gray-900 order-2 order-md-1">
                                <span class="text-muted fw-semibold me-1">{{ date('Y') }}&copy;</span> Create with
                                ❤️
                                <a href="https://ikhwan.web.id" target="_blank"
                                    class="text-gray-800 text-hover-primary">Ikhwanul Akhmad. DLY</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="ki-duotone ki-arrow-up">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
    </div>

    @include('layouts.app.partials.global-scripts')
    <script src="{{ asset('app/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/fancybox/fancybox.umd.js"></script>
    <script src="{{ asset('app/assets/js/custom.js') }}"></script>
    <script>
        const DATATABLE_ID = '{{ DATATABLE_ID }}';
        let IKHMODAL;
    </script>
    <script>
        $(document).ready(function() {
            @if (session()->has('success'))
                alertSweet(`{{ session('success') }}`, 'success');
            @endif
            @if (session()->has('warning'))
                alertSweet(`{{ session('warning') }}`, 'warning');
            @endif
            @if (session()->has('info'))
                alertSweet(`{{ session('info') }}`, 'info');
            @endif
            @if (session()->has('error'))
                alertSweet(`{{ session('error') }}`, 'error');
            @endif
        });
    </script>
    @stack('scripts')
</body>

</html>
