@php
    $time = time() + rand(0, 9999);
    $buttonAction = 'btnUploadImage' . $time;
    $id = $attributes->has('id') ? $attributes->get('id') : '';
    $imageInput = 'imageInput' . $time;
@endphp
@if ($action)
    <form action="{{ $action }}" method="POST" id="form-image-{{ $id }}">
        @csrf
    @else
        <div class="image-input-pembungkus">
@endif
<div class="image-input image-input-empty" data-kt-image-input="true" id="{{ $id }}"
    style="background-image: url({{ $background }}); background-size: cover;  background-repeat: no-repeat;">
    <div class="image-input-wrapper w-{{ $width }} h-{{ $height }}"></div>
    @if ($onlyShow === false)
        <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
            data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Change image">
            <i class="bi bi-pencil-fill fs-7"></i>
            <input type="file" name="{{ $name }}" accept="{{ $accept }}" class="image-input" />
            <input type="hidden" name="{{ $nameRemove }}" />
        </label>
        <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
            data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Cancel image">
            <i class="bi bi-x fs-2"></i>
        </span>
        {{-- <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Remove image">
            <i class="bi bi-x fs-2"></i>
        </span> --}}
        <span {{ $buttonAction }}
            class="d-none btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
            data-kt-image-input-action="upload" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Upload image">
            <i class="bi bi-upload fs-2"></i>
        </span>
    @endif

</div>
<div class="image-input-messsage"></div>
@if ($action)
    </form>
@else
    </div>
@endif

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
