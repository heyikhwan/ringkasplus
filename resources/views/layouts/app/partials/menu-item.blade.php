@props(['item', 'current', 'userRoles', 'level' => 1])

@php
    $hasChild = !empty($item['child']);
    $isActive = isMenuActive($item, $current);
@endphp

@if ($hasChild)
    <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $isActive ? 'here show' : '' }}">
        <span class="menu-link">
            @if ($level == 1)
                <span class="menu-icon">{!! $item['icon'] ?? '' !!}</span>
            @else
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
            @endif
            <span class="menu-title">{{ $item['name'] }}</span>
            <span class="menu-arrow"></span>
        </span>
        <div class="menu-sub menu-sub-accordion">
            @foreach ($item['child'] as $child)
                @include('layouts.app.partials.menu-item', [
                    'item' => $child,
                    'current' => $current,
                    'userRoles' => $userRoles,
                    'level' => $level + 1,
                ])
            @endforeach
        </div>
    </div>
@else
    <div class="menu-item">
        <a class="menu-link {{ $isActive ? 'active' : '' }}"
            href="{{ Route::has($item['url'] ?? '') ? route($item['url']) : '#' }}">
            @if ($level == 1)
                <span class="menu-icon">{!! $item['icon'] ?? '' !!}</span>
            @else
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
            @endif
            <span class="menu-title">{{ $item['name'] }}</span>
        </a>
    </div>
@endif
