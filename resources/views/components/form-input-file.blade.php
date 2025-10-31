<div class="fv-row">
    @if ($label)
        <x-form-label :for="$id">{{ $label }}</x-form-label>
    @endif

    <div id="{{ $id }}-preview-wrapper" class="mb-3">
        @if (!empty($value))
            <div class="border rounded d-flex align-items-center justify-content-between p-3 bg-light file-preview">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-40px me-3">
                        <i class="fas fa-file-pdf text-danger fs-3"></i>
                    </div>
                    <div>
                        <div class="fw-semibold text-dark small mb-0 text-truncate file-name" style="max-width: 200px;">
                            {{ basename($value) }}
                        </div>
                        <small class="text-muted">PDF Dokumen</small>
                    </div>
                </div>
                <a href="{{ getFileUrl($value) }}" class="btn btn-icon btn-sm btn-light-primary"
                    data-fancybox="pdf-preview" data-type="iframe" title="Lihat File">
                    <i class="fas fa-eye"></i>
                </a>
            </div>
        @else
            <div class="border rounded d-flex align-items-center justify-content-center p-3 bg-light text-muted file-preview"
                style="height:56px;">
                <i class="fas fa-file me-2"></i> Belum ada file diunggah
            </div>
        @endif
    </div>

    <div class="position-relative">
        <input type="file" id="{{ $id }}" name="{{ $name }}"
            class="form-control @error($errorKey()) is-invalid @enderror {{ $class }}"
            accept="{{ $accept }}" @if ($required) required @endif
            aria-describedby="{{ $id }}-help">
    </div>

    @error($errorKey())
        <x-form-error id="{{ $id }}-error">{{ $message }}</x-form-error>
    @enderror

    @if ($help)
        <x-form-help id="{{ $id }}-help">{!! $help !!}</x-form-help>
    @endif
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('{{ $id }}');
            const wrapper = document.getElementById('{{ $id }}-preview-wrapper');

            input.addEventListener('change', function() {
                const file = this.files[0];

                wrapper.innerHTML = '';

                if (!file) {
                    wrapper.innerHTML = `
                        <div class="border rounded d-flex align-items-center justify-content-center p-3 bg-light text-muted file-preview" style="height:56px;">
                            <i class="fas fa-file me-2"></i> Belum ada file diunggah
                        </div>
                    `;
                    return;
                }

                let fileName = file.name;
                let ext = fileName.split('.').pop();
                let nameOnly = fileName.substring(0, fileName.length - ext.length - 1);
                if (nameOnly.length > 25) nameOnly = nameOnly.substring(0, 22) + '...';
                const shortName = nameOnly + '.' + ext;

                let iconClass = 'fas fa-file text-secondary fs-3';
                const extLower = ext.toLowerCase();
                if (extLower === 'pdf') iconClass = 'fas fa-file-pdf text-danger fs-3';
                else if (['jpg', 'jpeg', 'png', 'gif'].includes(extLower)) iconClass =
                    'fas fa-file-image text-info fs-3';
                else if (['doc', 'docx'].includes(extLower)) iconClass =
                    'fas fa-file-word text-primary fs-3';
                else if (['xls', 'xlsx'].includes(extLower)) iconClass =
                    'fas fa-file-excel text-success fs-3';

                wrapper.innerHTML = `
                    <div class="border rounded d-flex align-items-center justify-content-between p-3 bg-light file-preview">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40px me-3">
                                <i class="${iconClass}"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-dark small mb-0 text-truncate" style="max-width: 200px;">
                                    ${shortName}
                                </div>
                                <small class="text-muted">File baru</small>
                            </div>
                        </div>
                    </div>
                `;
            });
        });
    </script>
@endpush
