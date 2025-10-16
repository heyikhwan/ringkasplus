<x-app-layout title="{{ $title }}">
    <div class="d-flex flex-column flex-lg-row gap-3 gap-lg-0">
        @include('app.application-setting._sidebar')

        <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
            <form class="form w-100" action="{{ route($permission_name . '.social-media') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pengaturan Sosial Media</h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-5">
                            <div class="col-md-6">
                                <x-form-input label="Facebook" name="social_media_facebook" class="ps-15"
                                    value="{{ $result['social_media_facebook'] ?? '' }}"
                                    placeholder="https://www.facebook.com/username">
                                    <x-slot:prepend>
                                        <i class="fa-brands fa-facebook position-absolute text-muted ms-6"></i>
                                    </x-slot:prepend>
                                </x-form-input>
                            </div>

                            <div class="col-md-6">
                                <x-form-input label="Instagram" name="social_media_instagram" class="ps-15"
                                    value="{{ $result['social_media_instagram'] ?? '' }}"
                                    placeholder="https://www.instagram.com/username">
                                    <x-slot:prepend>
                                        <i class="fa-brands fa-instagram position-absolute text-muted ms-6"></i>
                                    </x-slot:prepend>
                                </x-form-input>
                            </div>

                            <div class="col-md-6">
                                <x-form-input label="X (Twitter)" name="social_media_x" class="ps-15"
                                    value="{{ $result['social_media_x'] ?? '' }}"
                                    placeholder="https://www.x.com/username">
                                    <x-slot:prepend>
                                        <i class="fa-brands fa-x position-absolute text-muted ms-6"></i>
                                    </x-slot:prepend>
                                </x-form-input>
                            </div>

                            <div class="col-md-6">
                                <x-form-input label="Tiktok" name="social_media_tiktok" class="ps-15"
                                    value="{{ $result['social_media_tiktok'] ?? '' }}"
                                    placeholder="https://www.tiktok.com/@username">
                                    <x-slot:prepend>
                                        <i class="fa-brands fa-tiktok position-absolute text-muted ms-6"></i>
                                    </x-slot:prepend>
                                </x-form-input>
                            </div>

                            <div class="col-md-6">
                                <x-form-input label="Linkedin" name="social_media_linkedin" class="ps-15"
                                    value="{{ $result['social_media_linkedin'] ?? '' }}"
                                    placeholder="https://www.linkedin.com/in/username">
                                    <x-slot:prepend>
                                        <i class="fa-brands fa-linkedin position-absolute text-muted ms-6"></i>
                                    </x-slot:prepend>
                                </x-form-input>
                            </div>

                            <div class="col-md-6">
                                <x-form-input label="Youtube" name="social_media_youtube" class="ps-15"
                                    value="{{ $result['social_media_youtube'] ?? '' }}"
                                    placeholder="https://www.youtube.com/@username">
                                    <x-slot:prepend>
                                        <i class="fa-brands fa-youtube position-absolute text-muted ms-6"></i>
                                    </x-slot:prepend>
                                </x-form-input>
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
