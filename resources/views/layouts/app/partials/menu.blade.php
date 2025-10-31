@php
    $current = \Request::route()->getName();
    $menus = menuUser();

    $userRoles = [];
    if (method_exists(auth()->user(), 'getRoleNames')) {
        $userRoles = auth()->user()->getRoleNames()->toArray();
    } elseif (!empty(auth()->user()->role->name)) {
        $userRoles[] = auth()->user()->role->name;
    }
@endphp

<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
    <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
        <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true" data-kt-scroll-activate="true"
            data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
            data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">

            <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" data-kt-menu="true"
                data-kt-menu-expand="false">

                {{-- Dashboard --}}
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-element-11 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </div>

                @foreach ($menus as $menuGroup)
                    @php
                        $visibleItems = [];

                        foreach ($menuGroup['items'] as $item) {
                            $viewPermission = getViewPermission($item);
                            $canView = false;

                            if (!empty($item['role_only'])) {
                                $canView = count(array_intersect($userRoles, $item['role_only'])) > 0;
                            } elseif ($viewPermission) {
                                $canView = auth()->user()->can($viewPermission);
                            }

                            $visibleChildren = [];
                            if (!empty($item['child'])) {
                                foreach ($item['child'] as $child) {
                                    $childPermission = getViewPermission($child);
                                    $canChild = false;

                                    if (!empty($child['role_only'])) {
                                        $canChild = count(array_intersect($userRoles, $child['role_only'])) > 0;
                                    } elseif ($childPermission) {
                                        $canChild = auth()->user()->can($childPermission);
                                    }

                                    if ($canChild) {
                                        if (empty($child['icon'] ?? null) && !empty($item['icon'] ?? null)) {
                                            $child['icon'] = $item['icon'];
                                        }

                                        $visibleChildren[] = $child;
                                    }
                                }

                                if (count($visibleChildren) > 0) {
                                    $canView = true;
                                }

                                $item['child'] = $visibleChildren;
                            }

                            if ($canView || count($item['child'] ?? []) > 0) {
                                $visibleItems[] = $item;
                            }
                        }
                    @endphp

                    @if (count($visibleItems))
                        <div class="menu-item pt-5">
                            <div class="menu-content">
                                <span class="menu-heading fw-bold text-uppercase fs-7">
                                    {{ $menuGroup['heading'] }}
                                </span>
                            </div>
                        </div>

                        @foreach ($visibleItems as $item)
                            @include('layouts.app.partials.menu-item', [
                                'item' => $item,
                                'current' => $current,
                                'userRoles' => $userRoles,
                                'level' => 1,
                            ])
                        @endforeach
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
