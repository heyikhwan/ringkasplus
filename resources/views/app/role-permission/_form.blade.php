<div class="row g-5">
    <div class="card">
        <div class="card-body">
            <div class="row g-5">
                <div class="col-12">
                    @if (isset($isDefaultRole) && $isDefaultRole)
                        <div class="fv-row">
                            <label for="name" class="form-label">Nama Peran</label>
                            <input type="text" id="name" class="form-control" value="{{ $result->name }}"
                                disabled />
                        </div>
                    @else
                        <div class="fv-row">
                            <label for="name" class="required form-label">Nama Peran</label>
                            <input type="text" id="name" name="name" class="form-control"
                                value="{{ old('name', $result->name ?? '') }}" placeholder="Input Nama Peran" required
                                autocomplete="off" />
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex flex-column flex-md-row align-items-center align-items-md-center gap-5">
            <h3 class="card-title">Daftar Hak Akses</h3>

            <div class="fv-row my-auto">
                <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="1" id="check-all-global"
                        @checked(false) />
                    <label for="check-all-global" class="form-check-label">
                        Aktifkan Semua
                    </label>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="d-grid gap-8">
                @foreach ($permissions as $group_permission)
                    <div class="card permission-card" style="border-left: 4px solid #1E90FF">
                        <div class="card-header cursor-pointer bg-hover-light-secondary">
                            <div
                                class="w-100 d-flex flex-column flex-md-row align-items-center align-items-md-center justify-content-center justify-content-md-between gap-5">
                                <div class="flex-grow-1 h-100 d-flex align-items-center gap-2" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-{{ $group_permission->id }}" area-expanded="true">
                                    @if (!empty($group_permission->icon))
                                        {!! $group_permission->icon !!}
                                    @endif
                                    <h3 class="card-title m-0">{{ $group_permission->name }}</h3>
                                    <div
                                        class="badge badge-light-secondary badge-pill d-flex align-items-center gap-1 px-3 py-2">
                                        <span data-count="checked">0</span> / <span data-count="total">0</span>
                                    </div>
                                </div>
                                <div class="fv-row">
                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="1"
                                            id="check-all-{{ $group_permission->id }}" data-role="all"
                                            @checked(false) />
                                        <label for="check-all-{{ $group_permission->id }}" class="form-check-label">
                                            Aktifkan Semua
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="collapse-{{ $group_permission->id }}" class="collapse show">
                            <div class="card-body">
                                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-5">
                                    @foreach ($group_permission->permissions as $item)
                                        <div class="col">
                                            <div
                                                class="bg-light rounded overflow-hidden px-5 d-flex align-items-center justify-content-between">
                                                <label for="permission-{{ $item->id }}"
                                                    class="fw-bold fs-5 flex-grow-1 py-3">{{ $item->description }}</label>
                                                <div class="fv-row">
                                                    <div
                                                        class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input" data-role="child"
                                                            type="checkbox" value="1"
                                                            name="permissions[{{ $item->id }}]"
                                                            id="permission-{{ $item->id }}"
                                                            @checked(in_array($item->id, old('permissions', $selectedPermissions ?? []))) />
                                                    </div>
                                                </div>
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
                <a href="{{ route("$permission_name.index") }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            function updateGroup($card) {
                let $children = $card.find(".form-check-input[data-role=child]");
                let checkedCount = $children.filter(":checked").length;
                let total = $children.length;

                $card.find("[data-count=checked]").text(checkedCount);
                $card.find("[data-count=total]").text(total);

                let $checkAll = $card.find(".form-check-input[data-role=all]");
                $checkAll.prop("checked", checkedCount === total && total > 0);

                let $badge = $card.find(".badge");
                if (checkedCount === total && total > 0) {
                    $badge.removeClass("badge-light-secondary").addClass("badge-light-primary");
                } else {
                    $badge.removeClass("badge-light-primary").addClass("badge-light-secondary");
                }
            }

            function updateGlobalCheckAll() {
                let allGroups = $(".permission-card");
                let allChecked = true;

                allGroups.each(function() {
                    let $children = $(this).find(".form-check-input[data-role=child]");
                    if ($children.length && $children.filter(":checked").length !== $children.length) {
                        allChecked = false;
                        return false;
                    }
                });

                $("#check-all-global").prop("checked", allChecked);
            }

            // check-all per group
            $(document).on("change", ".form-check-input[data-role=all]", function() {
                let $card = $(this).closest(".permission-card");
                let isChecked = $(this).is(":checked");
                $card.find(".form-check-input[data-role=child]").prop("checked", isChecked);
                updateGroup($card);
                updateGlobalCheckAll();
            });

            // child checkbox
            $(document).on("change", ".form-check-input[data-role=child]", function() {
                let $card = $(this).closest(".permission-card");
                updateGroup($card);
                updateGlobalCheckAll();
            });

            // global check-all
            $(document).on("change", "#check-all-global", function() {
                let isChecked = $(this).is(":checked");
                $(".permission-card").each(function() {
                    $(this).find(".form-check-input[data-role=child]").prop("checked", isChecked);
                    updateGroup($(this));
                });
            });

            $(".permission-card").each(function() {
                updateGroup($(this));
            });
            updateGlobalCheckAll();
        });
    </script>
@endpush
