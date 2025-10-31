<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    @include('layouts.app.partials.global-styles')
</head>

<body id="kt_body" class="app-blank bgi-size-cover bgi-position-center bgi-no-repeat">
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <style>
            body {
                background-image: url('assets/media/auth/bg7.jpg');
            }
        </style>
        <div class="d-flex flex-column flex-center flex-column-fluid">
            <div class="d-flex flex-column flex-center text-center p-10">
                <div class="card card-flush w-lg-650px py-5">
                    <div class="card-body py-15 py-lg-20">
                        <h1 class="fw-bolder fs-2qx text-gray-900 mb-4">@yield('title')</h1>
                        <div class="fw-semibold fs-6 text-gray-500 mb-7">@yield('message')</div>
                        <div class="mb-11">
                            @yield('image')
                        </div>

                        <div class="mb-0">
                            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-primary">Kembali ke Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.app.partials.global-scripts')
</body>

</html>
