<x-auth-layout title="Masuk">
    <form class="form w-100" id="kt_sign_in_form" action="{{ route('login') }}" method="POST">
        @csrf

        <div class="text-center mb-11">
            <h1 class="text-gray-900 fw-bolder mb-3">Masuk</h1>
            <div class="text-gray-500 fw-semibold fs-6">Silahkan masuk dengan akun Anda</div>
        </div>

        @if (session()->has('error'))
            <x-alert alertType="danger"><div class="fw-semibold">{{ session()->get('error') }}</div></x-alert>
        @endif

        <div class="mb-8">
            <x-form-input name="username" class="bg-transparent" placeholder="Username" :required=true />
        </div>
        <div class="mb-3">
            <x-form-input type="password" name="password" class="bg-transparent" placeholder="Password" :required=true>
                <x-slot name="append">
                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                        data-kt-password-meter-control="visibility">
                        <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span
                                class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                        <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span
                                class="path2"></span><span class="path3"></span></i>
                    </span>
                </x-slot>
            </x-form-input>
        </div>
        <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
            <div></div>
            {{-- // TODO: Feat. lupa password --}}
            <a href="#" class="link-primary">Lupa Password ?</a>
        </div>
        <div class="d-grid gap-3">
            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                <span class="indicator-label">Masuk</span>
                <span class="indicator-progress">Tunggu sebentar...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
        </div>
    </form>
</x-auth-layout>
