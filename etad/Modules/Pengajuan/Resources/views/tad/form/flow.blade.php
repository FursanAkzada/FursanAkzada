<div class="card card-custom gutter-b" style="margin-bottom:0; height:100%;">
    <div class="card-header">
        <div class="card-title">
            <h3 class="card-label">
                Alur Persetujuan
            </h3>
        </div>
    </div>
    <div class="card-body" id="flowContainer">
    </div>
</div>

@php
    $colors = [
        1 => 'primary',
        2 => 'info',
    ];
@endphp
@foreach (['bod', 'vice', 'division', 'departemen', 'cabang', 'capem', 'kas'] as $level)
    <div id="{{ $level }}Container" style="display: none">
        <div class="d-flex align-items-center justify-content-center">
            @if ($menu = \Modules\Settings\Entities\Menu::where('module', 'pengajuan.tad.form')->where('code', 'tad-' . $level)->first())
                @foreach ($orders = $menu->flows()->get()->groupBy('order') as $i => $flows)
                    @foreach ($flows as $j => $flow)
                        <span class="label label-light-{{ $colors[$flow->type] }} font-weight-bold label-inline"
                            data-toggle="tooltip" title="{{ $flow->show_type }}">{{ $flow->group->name }}</span>
                        @if (!($i === $orders->keys()->last() && $j === $flows->keys()->last()))
                            <i class="fas fa-angle-double-right text-muted mx-2"></i>
                        @endif
                    @endforeach
                @endforeach
                @if ($orders->count() == 0)
                    <span class="label label-light-danger font-weight-bold label-inline"
                        data-toggle="tooltip">{{ __('Data tidak tersedia!') }}</span>
                @endif
            @endif
        </div>
    </div>
@endforeach
