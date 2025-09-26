<x-app-layout title="Daftar {{ $title }}">
    <div class="d-flex align-items-center justify-content-end gap-2 mb-3">
        @can("$permission_name.create")
            <a href="{{ route("$permission_name.create") }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah {!! $title !!}
            </a>
        @endcan
    </div>
    <div class="card">
        <div class="card-body">
            <x-table-filter class="mb-8">
                <div class="row g-5 align-items-center">
                    <div class="col-md-6 col-lg-3">
                        <x-form-select2 id="category" name="category" url="{{ route('select2.categories') }}"
                            :allowClear=true :options="['key' => 'id', 'value' => 'name']" placeholder="Filter Kategori" datatable-filter />
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <x-form-select name="status" :options=$statusOptions placeholder="Filter Status"
                            :disableSearch=true :allowClear=true datatable-filter />
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <x-form-input name="published_at" placeholder="Filter Tanggal Publikasi" datatable-filter />
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <x-form-select name="is_featured" :options="[1 => 'Tampilkan Artikel Unggulan']" placeholder="Filter Artikel Unggulan"
                            defaultValue="" :disableSearch=true :allowClear=true datatable-filter />
                    </div>
                </div>
            </x-table-filter>

            <x-table-toolbar class="mb-5" />

            <div class="table-responsive">
                <table id="{{ DATATABLE_ID }}"
                    class="table align-middle table-row-dashed table-border table-row-gray-300 fs-6 gy-5">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Penulis</th>
                            <th>Status</th>
                            <th>Dipublikasikan</th>
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
                $('#published_at').flatpickr({
                    altInput: true
                });

                setFilterDataTable(['#dt-search', '#category', '#status', '#published_at', '#is_featured'],
                    `#${ DATATABLE_ID }`);

                const datatable = initAjaxDataTable(`#${ DATATABLE_ID }`, {
                    ajax: {
                        url: "{{ route($permission_name . '.datatable') }}",
                    },
                    columns: [{
                            data: "title",
                            name: "title",
                            render: function(data, type, row) {
                                let html = `<div class="d-flex align-items-center gap-3">`;

                                html +=
                                    `<i class="fas fa-star ${row.is_featured ? 'text-warning' : 'text-secondary'} " title="${row.is_featured ? 'Artikel Unggulan' : ''}"></i>`;

                                html += `<div>`;
                                html += `<div class="fw-semibold">${data}</div>`;
                                html +=
                                    `<code class="shadow-none bg-transparent m-0 p-0">${row.slug}</code>`;
                                html += `</div>`;
                                html += `</div>`;

                                return html;

                                // TODO: Saat diklik, muncul modal untuk ubah status artikel unggulan
                            }
                        },
                        {
                            data: "categories",
                            name: "categories",
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                if (data && data.length > 0) {
                                    let badges = data.map(cat =>
                                        `<span class="badge badge-info me-1 mb-1">${cat.name}</span>`
                                    );
                                    return badges.join(' ');
                                }
                                return '<span class="text-muted">N/A</span>';
                            }
                        },
                        {
                            data: "author",
                            name: "author",
                            render: function(data, type, row) {
                                return data ? data.name : 'N/A';
                            }
                        },
                        {
                            data: "status",
                            name: "status",
                            render: function(data, type, row) {
                                let badgeClass = 'badge-secondary';
                                if (data === 'Draft') {
                                    badgeClass = 'badge-secondary';
                                } else if (data === 'Publikasi') {
                                    badgeClass = 'badge-success';
                                } else if (data === 'Arsip') {
                                    badgeClass = 'badge-warning';
                                }

                                return `<span class="badge ${badgeClass} text-capitalize">${data}</span>`;

                                // TODO: Saat diklik, muncul modal untuk ubah status artikel
                            }
                        },
                        {
                            data: "published_at",
                            name: "published_at",
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
                        [4, 'desc']
                    ]
                });
            })
        </script>
    @endpush
</x-app-layout>
