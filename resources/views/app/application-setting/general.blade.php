<x-app-layout title="{{ $title }}">
    <div class="d-flex flex-column flex-lg-row gap-3 gap-lg-0">
        @include('app.application-setting._sidebar')

        <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
            <form class="form w-100" action="{{ route($permission_name . '.general') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pengaturan Umum</h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-5">
                            <div class="col-12">
                                <div class="row g-5">
                                    <div class="col-lg-6 col-xl-4">
                                        <x-form-image label="Favicon"
                                            background="{{ getFileUrl($result['general_favicon'] ?? null, asset('app/assets/media/no-image.jpg')) }}"
                                            width="80px" height="80px" id="general_favicon" name="general_favicon"
                                            accept=".ico"
                                            help="format .ico, generate di <a href='https://realfavicongenerator.net/' target='_blank'>https://realfavicongenerator.net/</a>." />
                                    </div>

                                    <div class="col-lg-6 col-xl-4">
                                        <x-form-image label="Logo Terang"
                                            background="{{ getFileUrl($result['general_logo_light'] ?? null, asset('app/assets/media/no-image.jpg')) }}"
                                            width="140px" height="80px" id="general_logo_light"
                                            name="general_logo_light" accept=".png,.jpg,.jpeg,.svg,.webp"
                                            help="format PNG/JPG/JPEG/SVG/WebP, maksimal 5 MB." />
                                    </div>

                                    <div class="col-lg-6 col-xl-4">
                                        <x-form-image label="Logo Gelap"
                                            background="{{ getFileUrl($result['general_logo_dark'] ?? null, asset('app/assets/media/no-image.jpg')) }}"
                                            width="140px" height="80px" id="general_logo_dark"
                                            name="general_logo_dark" accept=".png,.jpg,.jpeg,.svg,.webp"
                                            help="format PNG/JPG/JPEG/SVG/WebP, maksimal 5 MB." />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <x-form-input label="Nama Aplikasi" name="general_application_name"
                                    value="{{ $result['general_application_name'] ?? '' }}" :required=true />
                            </div>

                            <div class="col-md-6">
                                <x-form-input label="Slogan Aplikasi" name="general_application_slogan"
                                    value="{{ $result['general_application_slogan'] ?? '' }}" />
                            </div>

                            <div class="col-12">
                                <x-form-textarea label="Deskripsi Aplikasi" name="general_application_description"
                                    :rows=4 value="{{ $result['general_application_description'] ?? '' }}" />
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <x-form-input label="Telepon" name="general_phone" class="ps-15"
                                    value="{{ $result['general_phone'] ?? '' }}">
                                    <x-slot:prepend>
                                        <i class="fa-solid fa-phone position-absolute text-muted ms-6"></i>
                                    </x-slot:prepend>
                                </x-form-input>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <x-form-input label="WhatsApp" name="general_whatsapp" class="ps-15"
                                    value="{{ $result['general_whatsapp'] ?? '' }}">
                                    <x-slot:prepend>
                                        <i class="fa-brands fa-whatsapp position-absolute text-muted ms-6"></i>
                                    </x-slot:prepend>
                                </x-form-input>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <x-form-input label="Email" name="general_email" class="ps-15"
                                    value="{{ $result['general_email'] ?? '' }}">
                                    <x-slot:prepend>
                                        <i class="fa-solid fa-envelope position-absolute text-muted ms-6"></i>
                                    </x-slot:prepend>
                                </x-form-input>
                            </div>

                            <div class="col-12">
                                <x-form-textarea label="Pesan WhatsApp" name="general_whatsapp_message" :rows=4
                                    value="{{ $result['general_whatsapp_message'] ?? '' }}" />
                            </div>

                            <div class="col-12">
                                <x-form-textarea label="Alamat" name="general_address" :rows=4
                                    value="{{ $result['general_address'] ?? '' }}" />
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                Simpan</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            Inputmask({
                "mask": "+62 999 9999 9999 9",
                "greedy": false,
            }).mask("#general_whatsapp");

            Inputmask({
                mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]",
                greedy: false,
                onBeforePaste: function(pastedValue, opts) {
                    pastedValue = pastedValue.toLowerCase();
                    return pastedValue.replace("mailto:", "");
                },
                definitions: {
                    "*": {
                        validator: '[0-9A-Za-z!#$%&"*+/=?^_`{|}~\-]',
                        cardinality: 1,
                        casing: "lower"
                    }
                }
            }).mask("#general_email");
        </script>
    @endpush
</x-app-layout>
