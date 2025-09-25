<div class="row g-5">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row g-5">
                    <div class="col-12">
                        @if (isset($isDefaultRole) && $isDefaultRole)
                            <x-form-input label="Nama Peran" name="name" :value="$result->name ?? ''" disabled />
                        @else
                            <x-form-input label="Nama Peran" name="name" :value="$result->name ?? ''" :required=true />
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex flex-column flex-md-row align-items-center align-items-md-center gap-5">
                <h3 class="card-title">Daftar Hak Akses</h3>
                <x-form-switch id="check-all-global" labelOn="Aktifkan Semua" :checked=false />
            </div>
            <div class="card-body">
                <div class="d-grid gap-8">
                    @foreach ($permissions as $group_permission)
                        <div class="card permission-card" style="border-left: 4px solid #1E90FF">
                            <div class="card-header cursor-pointer bg-hover-light-secondary">
                                <div
                                    class="w-100 d-flex flex-column flex-md-row align-items-center align-items-md-center justify-content-center justify-content-md-between gap-5">
                                    <div class="flex-grow-1 h-100 d-flex align-items-center gap-2"
                                        data-bs-toggle="collapse" data-bs-target="#collapse-{{ $group_permission->id }}"
                                        area-expanded="true">
                                        @if (!empty($group_permission->icon))
                                            {!! $group_permission->icon !!}
                                        @endif
                                        <h3 class="card-title m-0">{{ $group_permission->name }}</h3>
                                        <div
                                            class="badge badge-light-secondary badge-pill d-flex align-items-center gap-1 px-3 py-2">
                                            <span data-count="checked">0</span> / <span data-count="total">0</span>
                                        </div>
                                    </div>

                                    <x-form-switch id="check-all-{{ $group_permission->id }}" data-role="all"
                                        labelOn="Aktifkan Semua" :checked=false />
                                </div>
                            </div>
                            <div id="collapse-{{ $group_permission->id }}" class="collapse show">
                                <div class="card-body">
                                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-5">
                                        @foreach ($group_permission->permissions as $item)
                                            <div class="col">
                                                <div
                                                    class="bg-light rounded overflow-hidden px-5 d-flex align-items-center justify-content-between">
                                                    <x-form-label for="permission-{{ $item->id }}"
                                                        class="fw-bold fs-5 flex-grow-1 py-3">{{ $item->description }}</x-form-label>
                                                    <x-form-switch id="permission-{{ $item->id }}"
                                                        name="permissions[{{ $item->id }}]" data-role="child"
                                                        checked="{{ in_array($item->id, old('permissions', $selectedPermissions ?? [])) }}" />
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer position-sticky bottom-0 bg-body">
                <div class="d-flex justify-content-end align-items-center gap-2">
                    <a href="{{ route("$permission_name.index") }}" class="btn btn-light">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('app/assets/js/permission.js') }}"></script>
@endpush
