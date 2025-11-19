<x-app-layout title="Daftar {{ $title }}">
    <div class="d-flex align-items-center justify-content-end gap-2 mb-3">
        @can("$permission_name.create")
            <button type="button" class="btn btn-primary btn-sm" data-title="Tambah {{ $title }}"
                data-url="{{ route("$permission_name.create") }}" onclick="actionModalData(this)">
                <i class="fas fa-user-plus"></i> Tambah {{ $title }}
            </button>
        @endcan
    </div>
    <div class="card">
        <div class="card-body">
            <x-table-toolbar class="mb-5">
                <x-form-select name="status" id="status" class="form-select-solid w-200px ps-15"
                    placeholder="Semua Status" :options="[1 => 'Aktif', 0 => 'Tidak Aktif']" defaultValue="" :disableSearch=true :allowClear=true
                    datatable-filter>
                    <x-slot:prepend>
                        <i class="ki-duotone ki-filter fs-1 position-absolute ms-6 z-index-2"><span
                                class="path1"></span><span class="path2"></span></i>
                    </x-slot:prepend>
                </x-form-select>
            </x-table-toolbar>

            <div class="table-responsive">
                <table id="{{ DATATABLE_ID }}"
                    class="table align-middle table-row-dashed table-border table-row-gray-300 fs-6 gy-5">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Peran</th>
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
            const resetPassword = (url, id) => {
                return new Promise((resolve, reject) => {
                    konfirmasiSweet(`Yakin reset password untuk user ini?`, 'Ya, reset!', 'Batalkan', 'warning')
                        .then(() => {
                            ajaxMaster(url, 'POST', {
                                    id: id
                                })
                                .then((ress) => {
                                    alertSweet(
                                        ress.message,
                                        ress.success ? "success" : "error"
                                    ).then(() => {
                                        resolve(ress);
                                    });
                                }).catch((error) => {
                                    handleError(error);
                                    reject(error);
                                })
                        }).catch(() => {})
                })
            }

            $(document).ready(function() {
                setFilterDataTable(['#dt-search', '#status'], `#${ DATATABLE_ID }`);

                const datatable = initAjaxDataTable(`#${ DATATABLE_ID }`, {
                    ajax: {
                        url: "{{ route($permission_name . '.datatable') }}",
                    },
                    columns: [{
                            data: "name",
                            name: "name",
                            render: function(data, type, row, meta) {
                                let html = `<div class="d-flex align-items-center">`;

                                html += `<div class="symbol symbol-35px me-5">`;
                                html += `<img alt="${row.name}" src="${row.photo}" />`;
                                html += `</div>`;
                                html += `<div class="d-flex justify-content-start flex-column">`;
                                html += `<div class="fw-semibold">${row.name}</div>`;
                                html += `<small class="text-muted">${row.username}</small>`;
                                html += `</div>`;
                                html += `</div>`;

                                return html;
                            },
                        },
                        {
                            data: "email",
                            name: "email",
                            render: function(data, type, row, meta) {
                                let html = `-`;
                                if (row.email) {
                                    html = `<span>${row.email}</span>`;

                                    if (row.email_verified_at) {
                                        html +=
                                            `<i class="ki-solid ki-verify ms-1 fs-5 align-middle" style="color: #2c7be5" data-bs-toggle="tooltip" title="Terverifikasi"><span class="path1"></span><span class="path2"></span></i>`;
                                    }
                                }

                                return html;
                            }
                        },
                        {
                            data: "roles",
                            name: "roles",
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row, meta) {
                                if (!Array.isArray(row.roles) || row.roles.length === 0) {
                                    return '<span class="text-muted">-</span>';
                                }

                                return row.roles.map(role => {
                                    if (role.name === 'Super Admin') {
                                        return `<span class="badge bg-superadmin">Super Admin</span>`;
                                    }

                                    return `<span class="badge badge-light me-1">${$('<div>').text(role.name).html()}</span>`;
                                }).join('');
                            }
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
