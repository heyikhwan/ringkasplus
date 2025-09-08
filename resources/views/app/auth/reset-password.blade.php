<x-auth-layout title="Reset Password">
    <form class="form w-100" id="kt_sign_in_form" action="{{ route('auth.reset-password.store') }}" method="POST">
        @csrf

        <div class="text-center mb-11">
            <h1 class="text-gray-900 fw-bolder mb-3">Reset Password</h1>
        </div>

        @if (session()->has('error'))
            <x-alert alertType="danger">
                <div class="fw-semibold">{{ session()->get('error') }}</div>
            </x-alert>
        @elseif (session()->has('success'))
            <x-alert alertType="success">
                <div class="fw-semibold">{{ session()->get('success') }}</div>
            </x-alert>
        @endif

        <div class="mb-8">
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="user_id" value="{{ $user_id }}">

            <x-form-input id="username" name="" class="bg-transparent" placeholder="Username"
                value="{{ $username }}" disabled />
        </div>
        <div class="mb-8">
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
        <div class="mb-8">
            <x-form-input type="password" name="password_confirmation" class="bg-transparent"
                placeholder="Konfirmasi Password" :required=true>
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
        <div class="mb-5">
            <div class="d-md-flex d-lg-block d-xl-flex flex-row justify-content-between mt-5">
                <div class="form-group mb-4">
                    <div class="captcha">
                        <img src="{{ captcha_src('flat') }}" id="captcha-quest">
                        <button type="button" class="btn btn-danger px-lg-4 py-lg-3"
                            onclick="reloadCaptcha()">&#x21bb;</button>
                    </div>
                </div>
            </div>

            <div>
                <input type="text" id="captcha" name="captcha"
                    class="form-control @error('captcha') is-invalid @enderror" placeholder="Tulis captcha"
                    autocomplete="off" required>

                @error('captcha')
                    <x-form-error id="captcha-error">{{ $message }}</x-form-error>
                @enderror
            </div>

        </div>
        <div class="d-grid gap-3">
            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                <span class="indicator-label">Simpan Password</span>
                <span class="indicator-progress">
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span> Tunggu sebentar...</span>
            </button>
            <a href="{{ route('login') }}" class="btn btn-light">Kembali ke halaman login</a>
        </div>
    </form>

    @push('scripts')
        <script>
            const reloadCaptcha = () => {
                document.getElementById('captcha-quest').src = '{{ captcha_src('flat') }}&' + Math.random()
            }

            $(document).ready(function() {
                $('#kt_sign_in_submit').on('click', function(e) {
                    e.preventDefault();
                    this.setAttribute("data-kt-indicator", "on");
                    this.disabled = true;

                    $('#kt_sign_in_form').submit();
                })
            })
        </script>
    @endpush
</x-auth-layout>
