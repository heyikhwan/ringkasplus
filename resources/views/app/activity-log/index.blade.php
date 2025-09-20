<x-app-layout title="Daftar {!! $title !!}">
    <div class="card">
        <div class="card-body">
            <x-table-toolbar class="mb-5" />
            <div class="table-responsive">
                <table id="{{ DATATABLE_ID }}"
                    class="table align-middle table-row-dashed table-border table-row-gray-300 fs-6 gy-5">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th>Event</th>
                            <th>Deskripsi</th>
                            <th>User</th>
                            <th>Waktu</th>
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
                            data: "event",
                            name: "event",
                            orderable: false,
                            render: function(data, type, row) {
                                let badge = '';

                                switch (data) {
                                    case 'Created':
                                        badge =
                                            `<span class="badge badge-light-success"><i class="me-1 fas fa-plus-circle"></i> Created</span>`;
                                        break;

                                    case 'Updated':
                                        badge =
                                            `<span class="badge badge-light-warning"><i class="me-1 fas fa-edit"></i> Updated</span>`;
                                        break;

                                    case 'Deleted':
                                        badge =
                                            `<span class="badge badge-light-danger"><i class="me-1 fas fa-trash-alt"></i> Deleted</span>`;
                                        break;

                                    case 'Login':
                                        badge =
                                            `<span class="badge badge-light-primary"><i class="me-1 fas fa-sign-in-alt"></i> Login</span>`;
                                        break;

                                    case 'Logout':
                                        badge =
                                            `<span class="badge badge-light-secondary"><i class="me-1 fas fa-sign-out-alt"></i> Logout</span>`;
                                        break;

                                    case 'Generate Massal':
                                        badge =
                                            `<span class="badge badge-light-info"><i class="me-1 fas fa-cogs"></i> Generate Massal</span>`;
                                        break;

                                    case 'Importer':
                                        badge =
                                            `<span class="badge badge-light-info"><i class="me-1 fas fa-file-import"></i> Importer</span>`;
                                        break;

                                    default:
                                        badge =
                                            `<span class="badge badge-light-light text-dark">${data}</span>`;
                                        break;
                                }

                                return badge;
                            }
                        },
                        {
                            data: "description",
                            name: "description",
                            className: "text-wrap"
                        },
                        {
                            data: "user",
                            name: "user",
                            orderable: false,
                            render: function(data, type, row) {
                                return `<div><i class="ki-duotone ki-user-tick fs-5 me-2 align-middle"><span class="path1"></span> <span class="path2"></span><span class="path3"></span></i>${data}</div>`
                            }
                        },
                        {
                            data: "created_at",
                            name: "created_at",
                        },
                        {
                            data: "action",
                            name: "action",
                            orderable: false,
                            searchable: false,
                            className: "text-end"
                        }
                    ],
                    order: [
                        [3, 'desc']
                    ]
                });
            })
        </script>
    @endpush
</x-app-layout>
