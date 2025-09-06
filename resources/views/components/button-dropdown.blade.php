@php
    $visibleItems = collect($items)->filter(function ($item) {
        return isset($item['permission']) ? auth()->user()->can($item['permission']) : true;
    });
@endphp

@if ($visibleItems->isNotEmpty())
    <div>
        <button type="button"
            class="btn btn-sm {{ !empty($class) ? $class : 'btn-bg-light btn-active-color-primary' }} {{ empty($label) ? 'btn-icon' : '' }}"
            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
            @if (!empty($label))
                <div class="d-flex align-items-center gap-2">
                    {!! $label !!} <i class="fa-solid fa-caret-down"></i>
                </div>
            @else
                <i class="fa-solid fa-ellipsis-h text-muted fs-5"></i>
            @endif
        </button>

        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded 
            menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-175px py-4 text-start"
            data-kt-menu="true">

            @foreach ($visibleItems as $item)
                <div class="menu-item px-3">
                    <a href="{{ $item['url'] ?? 'javascript:void(0)' }}" class="menu-link px-3 {{ $item['class'] ?? '' }}"
                        @if (!empty($item['attributes'])) 
                            @foreach ($item['attributes'] as $attr => $val) 
                                {{ $attr }}="{{ $val }}" 
                            @endforeach 
                        @endif>
                        <div class="d-flex align-items-center gap-2">
                            {!! $item['icon'] ?? '' !!}
                            {{ $item['title'] ?? '' }}
                        </div>
                    </a>
                </div>
            @endforeach

        </div>
    </div>
@endif
