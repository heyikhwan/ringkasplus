<div class="alert alert-dismissible bg-light-{{ $alertType }} border border-{{ $alertType }} border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-10 {{ $attributes->get('class') }}">
    <span class="me-4 mb-5 mb-sm-0"><i class="{{ $icon }} text-{{ $alertType }} fs-1"></i></span>
    <div class="d-flex flex-column pe-0 pe-sm-10">
        @if ($title)
            <h5 class="mb-1">{{ $title }}</h5>
        @endif
        <span>{!! $slot !!}</span>
    </div>
    @if ($dismiss)
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="bi bi-x fs-1 text-{{ $alertType }}"></i>
        </button>
    @endif
</div>
