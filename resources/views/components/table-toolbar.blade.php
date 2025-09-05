<div class="d-flex flex-stack flex-column flex-md-row gap-3 {{ $class }}">
    <div class="btn-group flex-wrap order-1 order-md-0">
        @if ($useReset)
            <button type="button" class="btn btn-light dt-button-reset"
                onclick="customResetDataTable($('#{{ $tableId }}'))">
                <i class="fas fa-undo"></i>
                Reset
            </button>
        @endif

        @if ($useRefresh)
            <button type="button" class="btn btn-light dt-button-refresh"
                onclick="reloadDataTable('{{ $tableId }}')">
                <i class="fas fa-refresh"></i>
                Reload
            </button>
        @endif
    </div>

    @if ($useSearch)
        <div class="d-flex align-items-center position-relative">
            <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6"><span class="path1"></span><span
                    class="path2"></span></i>
            <input id="dt-search" type="search" class="form-control form-control-solid w-250px ps-15"
                placeholder="Cari" autocomplete="off" datatable-filter />
        </div>
    @endif
</div>
