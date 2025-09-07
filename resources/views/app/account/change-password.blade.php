<form action="{{ route($permission_name . '.change-password') }}" method="post" id="form" class="form w-100"
    onsubmit="submitModalDataTable(this); return false;">
    @csrf

    <div class="row g-5">
        <div class="col-12">
            <x-form-input type="password" label="Password Lama" name="old_password" :required=true>
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

        <div class="col-md-6">
            <x-form-input type="password" label="Password Baru" name="password" help="Password minimal 8 karakter"
                :required=true>
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

        <div class="col-md-6">
            <x-form-input type="password" label="Konfirmasi Password" name="password_confirmation" :required=true>
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
    </div>
</form>
