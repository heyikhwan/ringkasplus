<x-app-layout title="Daftar {{ $title }}">
    <div class="d-flex align-items-center justify-content-end gap-2 mb-3">
        @can("$permission_name.create")
            <button type="button" class="btn btn-primary btn-sm" data-title="Tambah {{ $title }}"
                data-url="{{ route("$permission_name.create") }}" onclick="actionModalData(this)">
                <i class="fas fa-plus"></i> Tambah {{ $title }}
            </button>
        @endcan
    </div>
    <div class="card">
        <div class="card-body">
            <x-table-toolbar class="mb-5" />
            <div class="table-responsive">
                <table id="{{ DATATABLE_ID }}"
                    class="table align-middle table-row-dashed table-border table-row-gray-300 fs-6 gy-5">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th>Nama</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                setFilterDataTable(['#dt-search'], `#${ DATATABLE_ID }`);

                const datatable = initAjaxDataTable(`#${ DATATABLE_ID }`, {
                    ajax: {
                        url: "{{ route($permission_name . '.datatable') }}",
                    },
                    columns: [{
                            data: "name",
                            name: "name",
                        },
                        {
                            data: "status",
                            name: "status",
                            render: function(data, type, row, meta) {
                                const status = row.status ? 'Aktif' : 'Tidak Aktif';

                                const html =
                                    `<span class="badge badge-light-${row.status ? 'success' : 'danger'}">${status}</span>`;
                                return html;
                            }
                        },
                        {
                            data: "action",
                            name: "action",
                            orderable: false,
                            searchable: false,
                            className: "text-end"
                        }
                    ],
                });
            })
        </script>
    @endpush
</x-app-layout>
