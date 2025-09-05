<div tabindex="-1" class="modal fade " id="{{ $id }}" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog {{ $msize }}" id="modal-dialog">
        <div class="modal-content">
            @if ($title)
                <div class="modal-header">
                    <h3 class="modal-title">{{ $title }}</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
            @endif


            <div class="modal-body">
                {!! $slot !!}
            </div>

            @if ($modalFooter)
                <div class="modal-footer">
                    @if ($modalFooter === true)
                        @if ($btnclose)
                            <button type="button" class="btn btn-light" btnModalClose
                                data-bs-dismiss="modal">{!! $btnclose === true ? 'Tutup' : trim($btnclose) !!}</button>
                        @endif
                        @if ($btndone)
                            <button type="button" class="btn btn-primary" btnModalDone>{!! $btndone === true ? 'Simpan' : trim($btndone) !!}</button>
                        @endif
                    @else
                        {!! $modalFooter !!}
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
