@if ($slot->isNotEmpty())
    <div {{ $attributes->merge(['class' => 'card card-px-0 shadow-none']) }} styele="box-shadow: none">
        <div class="card-header p-1 bg-primary collapsible cursor-pointer rotate" style="min-height: 0px" overflow="hidden"
            data-bs-toggle="collapse" data-bs-target="#filter-table-collapsible">
            <div class="card-title p-3 text-white">{!! $attributes->get('title') ?? '<i class="fas fa-filter me-3 align-middle"></i> Filter' !!}</div>
            <div class="card-toolbar rotate-180 p-3">
                <i class="ki-duotone ki-arrow-down text-white fs-2x">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </div>
        </div>
        <div id="filter-table-collapsible" class="collapse show ">
            <div class="card-body p-1">
                <div class="my-4 mx-2">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
@endif
