<div class="d-flex flex-column flex-lg-row-auto w-100 w-lg-275px" data-kt-drawer="true" data-kt-drawer-name="inbox-aside"
    data-kt-drawer-activate="false" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_inbox_aside_toggle">
    <div class="card card-flush mb-0" data-kt-sticky="true" data-kt-sticky-name="inbox-aside-sticky"
        data-kt-sticky-offset="{default: false, xl: '100px'}" data-kt-sticky-width="{lg: '275px'}"
        data-kt-sticky-left="auto" data-kt-sticky-top="100px" data-kt-sticky-animation="false"
        data-kt-sticky-zindex="95">
        <div class="card-body">
            <div
                class="menu menu-column menu-rounded menu-state-bg menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary">
                <a href="{{ route('application-setting.general') }}" class="menu-item text-dark mb-3">
                    <span class="menu-link {{ request()->routeIs('application-setting.general') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="fas fa-cogs text-muted fs-2 me-3"></i>
                        </span>
                        <span class="menu-title fw-bold">Pengaturan Umum</span>
                    </span>
                </a>

                <a href="{{ route('application-setting.social-media') }}" class="menu-item text-dark">
                    <span class="menu-link {{ request()->routeIs('application-setting.social-media') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="fas fa-share text-muted fs-2 me-3"></i>
                        </span>
                        <span class="menu-title fw-bold">Pengaturan Sosial Media</span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>
