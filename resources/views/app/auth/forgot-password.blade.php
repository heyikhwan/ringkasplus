<x-auth-layout title="Lupa Password">
    <form class="form w-100" id="kt_sign_in_form" action="{{ route('auth.forgot-password') }}" method="POST">
        @csrf

        <div class="text-center mb-11">
            <h1 class="text-gray-900 fw-bolder mb-3">Lupa Password</h1>
            <div class="text-gray-500 fw-semibold fs-6">Silahkan masukkan username Anda</div>
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
            <x-form-input name="username" class="bg-transparent" placeholder="Username" :required=true />
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
                <span class="indicator-label">Reset Password</span>
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
