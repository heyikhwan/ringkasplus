<div class="row g-5">
    <div class="col-md-3">

        <div class="symbol symbol-100px symbol-circle">
            <x-form-image background="{{ getFileUrl($result->photo ?? null, asset('app/assets/media/avatars/blank.png')) }}" width="150px" height="150px" id="photo" name="photo" accept=".png,.jpg,.jpeg,.svg,.webp" />
        </div>
    </div>

    <div class="col">
        <div class="row g-5">
            <div class="col-12">
                <div class="fv-row">
                    <label for="name" class="required form-label">Nama Lengkap</label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="{{ old('name', $result->name ?? '') }}" placeholder="Input Nama Lengkap" required
                        autocomplete="off" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="fv-row">
                    <label for="username" class="required form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control"
                        placeholder="Input Username" value="{{ old('username', $result->username ?? '') }}" required
                        autocomplete="off" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="fv-row">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Input Email"
                        value="{{ old('email', $result->email ?? '') }}" autocomplete="off" />
                </div>
            </div>

            @if (!isset($result))
                <div class="col-md-6">
                    <div class="fv-row">
                        <label for="password" class="required form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Input Password" required autocomplete="off" />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="fv-row">
                        <label for="password_confirmation" class="required form-label">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-control" placeholder="Input Ulang Password" required autocomplete="off" />
                    </div>
                </div>
            @endif

            <div class="col-md-6">
                <div class="fv-row">
                    <label for="status" class="required form-label">Status</label>
                    <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="status" value="1" id="status"
                            @checked(old('status', $result->status ?? true)) />
                        <label class="form-check-label" for="status">
                            Aktif
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>