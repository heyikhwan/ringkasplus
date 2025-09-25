<x-app-layout title="Ubah Data Akun">
    <form action="{{ route("$permission_name.update", encode(auth()->user()->id)) }}" method="post" id="form"
        class="form w-100" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-5">
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="symbol symbol-100px symbol-circle">
                                <x-form-image
                                    background="{{ getFileUrl(auth()->user()->photo ?? null, asset('app/assets/media/avatars/blank.png')) }}"
                                    width="150px" height="150px" id="photo" name="photo"
                                    accept=".png,.jpg,.jpeg,.svg,.webp" />
                            </div>

                            <div class="mt-5">
                                <small class="text-muted">Bergabung pada
                                    {{ tanggal(auth()->user()->created_at) }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-5">
                            <div class="col-12">
                                <x-form-input label="Nama Lengkap" name="name" value="{{ auth()->user()->name }}"
                                    :required=true />
                            </div>

                            <div class="col-md-6">
                                <x-form-input label="Username" name="username" value="{{ auth()->user()->username }}"
                                    :required=true />
                            </div>

                            <div class="col-md-6">
                                <x-form-input type="email" label="Email" name="email"
                                    value="{{ auth()->user()->email }}" />
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end align-items-center gap-2">
                            <a href="{{ route("$permission_name.index") }}" class="btn btn-light">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>
