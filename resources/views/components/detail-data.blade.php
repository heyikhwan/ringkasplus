@if ($vertical)
    @php
        $column1 = [];
        $column2 = [];
        foreach ($details as $key => $detail) {
            $no = 0;
            foreach ($detail as $k => $d) {
                $no++;
                if ($no == 1) {
                    $column1[$k] = $d;
                } else {
                    $column2[$k] = $d;
                }
            }
        }
    @endphp
    <div class="row">
        @if (!empty($column1))
            <div class="col-lg-6">
                @foreach ($column1 as $k => $d)
                    <div class="row">
                        <div class="col-lg-6 text-gray-900"><b>{!! $k !!}</b></div>
                        <div class="col-lg-6">{!! $d !!}</div>
                    </div>
                    <div class="separator my-3"></div>
                @endforeach
            </div>
        @endif
        @if (!empty($column2))
            <div class="col-lg-6">
                @foreach ($column2 as $k => $d)
                    <div class="row">
                        <div class="col-lg-6 text-gray-900"><b>{!! $k !!}</b></div>
                        <div class="col-lg-6">{!! $d !!}</div>
                    </div>
                    <div class="separator my-3"></div>
                @endforeach
            </div>
        @endif
    </div>
@else
    @foreach ($details as $key => $detail)
        <div class="row">
            @php
                $total_detail = count($detail);
            @endphp
            @if ($total_detail > 1)
                @foreach ($detail as $k => $d)
                    <div class="col-lg-{{ 12 / $total_detail / 2 }} text-gray-900"><b>{!! $k !!}</b></div>
                    <div class="col-lg-{{ 12 / $total_detail / 2 }}">{!! $d !!}</div>
                    <div class="separator my-4 d-lg-none"></div>
                @endforeach
            @else
                @foreach ($detail as $k => $d)
                    <div class="col-lg-3 text-gray-900"><b>{!! $k !!}</b></div>
                    <div class="col-lg-9">{!! $d !!}</div>
                    <div class="separator my-4 d-lg-none"></div>
                @endforeach
            @endif
            <div class="separator my-4 d-none d-lg-block"></div>
        </div>
    @endforeach
@endif
