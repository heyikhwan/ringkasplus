@php
    $time = time() + rand(0, 9999);
    $buttonAction = 'btnUploadImage' . $time;
    $imageInput = 'imageInput' . $time;
@endphp

<div class="fv-row">
    @if ($label)
        <x-form-label :for="$id" :required="$required">{{ $label }}</x-form-label>
    @endif

    @if ($action)
        <form action="{{ $action }}" method="POST" id="form-image-{{ $id }}">
            @csrf
        @else
            <div class="image-input-pembungkus">
    @endif

    <div class="image-input {{ $hasImage ? '' : 'image-input-empty' }}" data-kt-image-input="true"
        id="{{ $id }}">
        <div class="image-input-wrapper w-{{ $width }} h-{{ $height }}"
            style="background-image: url({{ $background }}); background-size: cover; background-repeat: no-repeat; background-position: center">
        </div>

        @if ($onlyShow === false)
            <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="click"
                title="Change image">
                <i class="bi bi-pencil-fill fs-7"></i>
                <input type="file" name="{{ $name }}" accept="{{ $accept }}" class="image-input"
                    id="{{ $id }}" @if ($required) required @endif />
                <input type="hidden" name="{{ $nameRemove }}" />
            </label>
            <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-bs-dismiss="click"
                title="Cancel image">
                <i class="bi bi-x fs-2"></i>
            </span>

            @if ($hasImage && !empty($removeUrl))
                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="remove_image" data-bs-toggle="tooltip" title="Remove image"
                    data-remove-url="{{ $removeUrl }}" style="right:  0;">
                    <i class="bi bi-trash text-danger"></i>
                </span>
            @endif

            <span {{ $buttonAction }}
                class="d-none btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                data-kt-image-input-action="upload" data-bs-toggle="tooltip" data-bs-dismiss="click"
                title="Upload image">
                <i class="bi bi-upload fs-2"></i>
            </span>
        @endif
    </div>

    @if ($action)
        </form>
    @else
</div>
@endif

@error($name)
    <x-form-error id="{{ $id }}-error">{{ $message }}</x-form-error>
@enderror

@if ($help)
    <x-form-help id="{{ $id }}-help">{!! $help !!}</x-form-help>
@endif
</div>

@push('scripts')
    <script>
        @php
            if ($action && $onlyShow === false) {
                echo <<<JS
                KTImageInput.createInstances();

                var {$imageInput}Element = document.querySelector("#{$id}");
                var {$imageInput} = KTImageInput.getInstance({$imageInput}Element);
                {$imageInput}.on("kt.imageinput.changed", function() {
                    $("#{$id}").find("[{$buttonAction}]").removeClass("d-none");
                });

                {$imageInput}.on("kt.imageinput.canceled", function() {
                    $("#{$id}").find("[{$buttonAction}]").addClass("d-none");
                });

                $(document).on("click", "[{$buttonAction}]", function(e) {
                    $("#form-image-{$id}").submit();
                });

                $("#form-image-{$id}").submit(function(e) {
                    e.preventDefault();
                    let url = $(this).attr('action');

                    globalSimpanMaster(this, url, 'POST').then((succ) => {
                        $("#{$id}").find("[{$buttonAction}]").addClass("d-none");
                        $("#{$id}").removeClass('image-input-changed').addClass('image-input-empty');
                    });
                })
                JS;
            }
        @endphp
    </script>

    <script>
        $(document).on("click", "#{{ $id }} [data-kt-image-input-action='remove_image']", function(e) {
            e.preventDefault();

            let removeUrl = $(this).data("remove-url");

            globalHapusData(removeUrl, {}, false, "force")
                .then((success) => {
                    $("#{{ $id }}").find("[data-kt-image-input-action='remove_image']").addClass(
                        "d-none");
                    $("#{{ $id }}").removeClass('image-input-changed').addClass('image-input-empty');

                    $("#{{ $id }}").find(".image-input-wrapper").css({
                        "background-image": "url('{{ asset('app/assets/media/no-image.jpg') }}')",
                    });
                })
                .catch((error) => {
                    reject(error);
                });
        });
    </script>
@endpush

@push('styles')
    <style>
        .w-{{ $width }} {
            width: {{ $width }} !important;
        }

        .h-{{ $height }} {
            height: {{ $height }} !important;
        }
    </style>
@endpush
