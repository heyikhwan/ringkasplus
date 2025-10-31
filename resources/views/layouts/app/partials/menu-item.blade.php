@props(['item', 'current', 'userRoles', 'level' => 1])

@php
    $hasChild = !empty($item['child']);
    $isActive = isMenuActive($item, $current);

    $viewPermission = getViewPermission($item);

    $canView = true;
    if (!empty($item['role_only'])) {
        $canView = count(array_intersect($userRoles, $item['role_only'])) > 0;
    } elseif ($viewPermission) {
        $canView = auth()->user()->can($viewPermission);
    }

    $visibleChildren = [];
    if ($hasChild) {
        foreach ($item['child'] as $child) {
            $childPermission = getViewPermission($child);
            $canChild = true;

            if (!empty($child['role_only'])) {
                $canChild = count(array_intersect($userRoles, $child['role_only'])) > 0;
            } elseif ($childPermission) {
                $canChild = auth()->user()->can($childPermission);
            }

            if ($canChild) {
                $visibleChildren[] = $child;
            }
        }
        $hasChild = count($visibleChildren) > 0;
    }
@endphp

@if ($canView)
    @if ($hasChild && count($visibleChildren) > 0)
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
                @foreach ($visibleChildren as $child)
                    @include('layouts.app.partials.menu-item', [
                        'item' => $child,
                        'current' => $current,
                        'userRoles' => $userRoles,
                        'level' => $level + 1,
                    ])
                @endforeach
            </div>
        </div>
    @elseif(!$hasChild)
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
@endif

