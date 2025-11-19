<div class="row g-5">
    <div class="col-md-3">
        <div class="symbol symbol-100px symbol-circle">
            <x-form-image
                background="{{ getFileUrl($result->photo ?? null, asset('app/assets/media/avatars/blank.png')) }}"
                width="150px" height="150px" id="photo" name="photo" accept=".png,.jpg,.jpeg,.svg,.webp" />
        </div>
    </div>

    <div class="col">
        <div class="row g-5">
            <div class="col-12">
                <x-form-input label="Nama Lengkap" name="name" :value="$result->name ?? ''" :required=true />
            </div>

            <div class="col-md-6">
                <x-form-input label="Username" name="username" :value="$result->username ?? ''" :required=true />
            </div>

            <div class="col-md-6">
                <x-form-input type="email" label="Email" name="email" :value="$result->email ?? ''" />
            </div>

            @if (!isset($result))
                <div class="col-md-6">
                    <x-form-input type="password" label="Password" name="password" help="Password minimal 8 karakter"
                        :required=true>
                        <x-slot name="append">
                            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                data-kt-password-meter-control="visibility">
                                <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span
                                        class="path2"></span><span class="path3"></span><span
                                        class="path4"></span></i>
                                <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span
                                        class="path2"></span><span class="path3"></span></i>
                            </span>
                        </x-slot>
                    </x-form-input>
                </div>

                <div class="col-md-6">
                    <x-form-input type="password" label="Konfirmasi Password" name="password_confirmation"
                        :required=true>
                        <x-slot name="append">
                            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                data-kt-password-meter-control="visibility">
                                <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span
                                        class="path2"></span><span class="path3"></span><span
                                        class="path4"></span></i>
                                <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span
                                        class="path2"></span><span class="path3"></span></i>
                            </span>
                        </x-slot>
                    </x-form-input>
                </div>
            @endif

            <div class="col-12">
                <x-form-select2 label="Peran" id="roles" name="roles[]" url="{{ route('select2.roles') }}"
                    :required=true :options="['dropdownParent' => '#default-ikh-modal', 'key' => 'id', 'value' => 'name']" multiple>
                    @if (isset($result) && count($result->roles) > 0)
                        @foreach ($result->roles as $id => $name)
                            <option value="{{ $id }}" selected>{{ $name }}</option>
                        @endforeach
                    @endif
                </x-form-select2>
            </div>

            <div class="col-md-6">
                <x-form-switch label="Status" id="active" name="is_active" labelOn="Aktif" :checked="old('is_active', $result->is_active ?? true)" />
            </div>
        </div>
    </div>
</div>
