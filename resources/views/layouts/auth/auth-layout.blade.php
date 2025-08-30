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
    @stack('styles')
</head>

<body id="kt_body" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center bgi-no-repeat">
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <style>
            body {
                background-image: url("{{ asset('app/assets/media/auth/bg4.jpg') }}");

            }

            [data-bs-theme="dark"] body {
                background-image: url("{{ asset('app/assets/media/auth/bg4-dark.jpg') }}");
            }
        </style>
        <div class="d-flex flex-column flex-column-fluid flex-lg-row">
            <div class="d-flex flex-center w-lg-50 pt-15 pt-lg-0 px-10">
                <div class="d-flex flex-center flex-lg-start flex-column">
                    <a href="index.html" class="mb-7">
                        <img alt="Logo" src="{{ asset('app/assets/media/logos/default-dark.svg') }}" />
                    </a>
                    <h2 class="text-white fw-normal m-0">Branding tools designed for your business</h2>
                </div>
            </div>
            <div
                class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12 p-lg-20">
                <div class=" d-flex flex-column align-items-stretch flex-center ">
                    <div class="bg-body rounded-4 w-md-600px p-20">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.app.partials.global-scripts')
    @stack('scripts')
</body>

</html>
