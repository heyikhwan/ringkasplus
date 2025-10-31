<div class="fv-row">
    @if ($label)
        <x-form-label :for="$id" :required="$required">{{ $label }}</x-form-label>
    @endif

    <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}"
        {{ !empty($cols) ? 'cols="' . $cols . '"' : '' }} class="form-control @error($errorKey()) is-invalid @enderror"
        placeholder="{{ $placeholder }}" @if ($required) required @endif style="resize: none"
        {{ $attributes }}>{{ old($errorKey(), $value) }}</textarea>

    @error($errorKey())
        <x-form-error id="{{ $id }}-error">{{ $message }}</x-form-error>
    @enderror

    @if ($help)
        <x-form-help id="{{ $id }}-help">{{ $help }}</x-form-help>
    @endif
</div>

@pushOnce('scripts')
    <script src="{{ asset('app/assets/plugins/custom/tinymce/tinymce.min.js') }}"></script>
@endPushOnce

<script>
    $(document).ready(function() {
        @if ($variant === 'basic')
            tinymce.init({
                selector: '#{{ $id }}',
                license_key: 'gpl',
                menubar: false,
                plugins: 'lists link',
                toolbar: 'undo redo | bold italic underline | link | bullist numlist',
                height: 250,
                setup: function(editor) {
                    editor.on('change keyup', function() {
                        editor.save();
                    });
                }
            });
        @elseif ($variant === 'full')
            tinymce.init({
                selector: '#{{ $id }}',
                license_key: 'gpl',
                menubar: false,
                plugins: "anchor autolink charmap codesample emoticons fullscreen image link lists media searchreplace table visualblocks wordcount preview code",
                toolbar: "fullscreen | undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat | code | preview",
                height: 400,
                setup: function(editor) {
                    editor.on('change keyup', function() {
                        editor.save();
                    });
                },
                images_file_types: 'jpg,jpeg,png,gif,svg,webp',
                images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
                    const formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());

                    fetch("{{ route('upload.editor') }}", {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                reject('HTTP Error: ' + response.status);
                            }
                            return response.json();
                        })
                        .then(json => {
                            if (!json || typeof json.location !== 'string') {
                                reject('Invalid JSON: ' + JSON.stringify(json));
                                return;
                            }
                            resolve(json.location);
                        })
                        .catch(err => {
                            reject('Upload gagal: ' + err.message);
                        });
                }),
            });
        @endif
    });
</script>
