<div class="row g-8">
    <div class="col-md-8">
        <div class="row g-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-5">
                            <div class="col-12">
                                <x-form-input name="title" label="Judul" :value="old('title', $result->title ?? '')" :required=true />
                            </div>

                            <div class="col-12">
                                <x-form-input name="slug" label="Slug" :value="old('slug', $result->slug ?? '')" :required=true />
                            </div>

                            <div class="col-12">
                                <x-form-textarea name="excerpt" label="Ringkasan" :rows=4 :value="old('excerpt', $result->excerpt ?? '')"
                                    maxlength="150" />
                                <div class="form-text text-end char-counter">
                                    <span class="char-count">0</span>/<span class="char-limit">150</span> karakter
                                </div>
                            </div>

                            <div class="col-12">
                                <x-form-texteditor name="content" label="Konten" variant="full" :value="old('content', $result->content ?? '')" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Gambar</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-5">
                            <div class="col-md-6">
                                <div class="symbol symbol-100px symbol-circle">
                                    <x-form-image label="Gambar Utama"
                                        background="{{ getFileUrl($result->featured_image ?? null, asset('app/assets/media/no-image.jpg')) }}"
                                        width="300px" height="200px" id="featured_image" name="featured_image"
                                        accept=".png,.jpg,.jpeg,.svg,.webp" removeUrl="{{ isset($result) ? route('article.remove-image', [encode($result->id), 'featured_image']) : null }}"
                                        help="Direkomendasikan ukuran 600x400 px, format PNG/JPG/JPEG/SVG/WebP, maksimal 5 MB." />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="symbol symbol-100px symbol-circle">
                                    <x-form-image label="Thumbnail"
                                        background="{{ getFileUrl($result->thumbnail ?? null, asset('app/assets/media/no-image.jpg')) }}"
                                        width="300px" height="200px" id="thumbnail" name="thumbnail"
                                        accept=".png,.jpg,.jpeg,.svg,.webp" removeUrl="{{ isset($result) ? route('article.remove-image', [encode($result->id), 'thumbnail']) : null }}"
                                        help="Direkomendasikan ukuran 600x400 px, format PNG/JPG/JPEG/SVG/WebP, maksimal 5 MB. Jika tidak diunggah, otomatis menggunakan gambar utama." />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Pengaturan SEO</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-5">
                            <div class="col-12">
                                <x-form-input name="meta_title" label="Judul Meta" :value="old('meta_title', $result->meta_title ?? '')" />
                            </div>

                            <div class="col-12">
                                <x-form-textarea name="meta_description" label="Deskripsi Meta" :rows=4
                                    :value="old('meta_description', $result->meta_description ?? '')" maxlength="150" />
                                <div class="form-text text-end char-counter">
                                    <span class="char-count">0</span>/<span class="char-limit">150</span> karakter
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="row g-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Pengaturan Publikasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-5">
                            <div class="col-12">
                                <x-form-select name="status" label="Status" :options="$statusOptions" :defaultValue="old('status', $result->status ?? 'draft')"
                                    :disableSearch=true :required=true />
                            </div>

                            <div class="col-12">
                                <x-form-input name="published_at" label="Tanggal Publikasi" :value="old('published_at', $result->published_at ?? date('Y-m-d H:i:s'))"
                                    :required=true />
                            </div>

                            <div class="col-12">
                                <x-form-switch label="Artikel Unggulan" name="is_featured" labelOn="Ya"
                                    :checked="old('is_featured', $result->is_featured ?? false)" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Kategori & Tag</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-5">
                            <div class="col-12">
                                <x-form-select2 label="Kategori" id="category_id" name="categories[]"
                                    url="{{ route('select2.categories') }}" placeholder="Pilih Kategori" :required=true
                                    :options="['key' => 'slug', 'value' => 'name']" multiple>
                                    @if (isset($result) && count($result->categories) > 0)
                                        @foreach ($result->categories as $slug => $name)
                                            <option value="{{ $slug }}" selected>{{ $name }}</option>
                                        @endforeach
                                    @endif
                                </x-form-select2>
                            </div>

                            <div class="col-12">
                                <x-form-select2 label="Tag" id="tags" name="tags[]"
                                    url="{{ route('select2.tags') }}" placeholder="Pilih Tag" :options="['key' => 'slug', 'value' => 'name', 'tags' => true]"
                                    multiple>
                                    @if (isset($result) && count($result->tags) > 0)
                                        @foreach ($result->tags as $slug => $name)
                                            <option value="{{ $slug }}" selected>{{ $name }}</option>
                                        @endforeach
                                    @endif
                                </x-form-select2>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route("$permission_name.index") }}" class="btn btn-light"><i
                                    class="fas fa-arrow-left"></i> Kembali</a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(function() {
            $('#published_at').flatpickr({
                altInput: true,
                enableTime: true
            });

            const $title = $('#title');
            const $slug = $('#slug');
            let lastGeneratedSlug = $slug.val() || '';

            $title.on('input', function() {
                const generated = slugify($(this).val());
                if ($slug.val().trim() === '' || $slug.val() === lastGeneratedSlug) {
                    $slug.val(generated);
                    lastGeneratedSlug = generated;
                }
            });

            $slug.on('input', function() {
                if ($(this).val() !== lastGeneratedSlug) {
                    lastGeneratedSlug = $(this).val();
                }
            });
        });
    </script>
@endpush
