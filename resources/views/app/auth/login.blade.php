<x-auth-layout title="Masuk">
    <form class="form w-100" id="kt_sign_in_form" action="#" method="POST">
        <div class="text-center mb-11">
            <h1 class="text-gray-900 fw-bolder mb-3">Masuk</h1>
            <div class="text-gray-500 fw-semibold fs-6">Silahkan masuk dengan akun Anda</div>
        </div>

        <div class="fv-row mb-8">
            <input type="text" placeholder="Username" name="username" autocomplete="off"
                class="form-control bg-transparent" />
        </div>
        <div class="fv-row mb-3">
            <input type="password" placeholder="Password" name="password" autocomplete="off"
                class="form-control bg-transparent" />
        </div>
        <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
            <div></div>
            <a href="authentication/layouts/creative/reset-password.html" class="link-primary">Lupa Password ?</a>
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
