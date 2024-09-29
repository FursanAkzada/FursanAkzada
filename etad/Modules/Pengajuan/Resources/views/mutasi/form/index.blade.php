@php
    $ORG_STRUCTS = \Modules\Master\Entities\SO\OrgStruct::groupByLevel([], true);
    $GROUPS = \App\Entities\Group::orderBy('name', 'ASC')
    ->get();
    $user = auth()->user();
    $so_id = $user->position->org_struct_id ?? null;
@endphp
@extends('layouts.grid.list')
@section('filters')
    <div class="mr-2">
        <input type="text" class="form-control filter-control" data-post="keyword" placeholder="No Surat" style="width: 150px">
    </div>
    <div class="mr-2">
        <input type="text" class="form-control filter-control" data-post="personil" placeholder="Personil TAD" style="width: 150px">
    </div>
    <div class="mr-2" style="width: 250px">
        <select class="form-control base-plugin--select2 show-tick filter-control" data-post="location_id" data-placeholder="Unit Kerja" id="tipeStrukturCtrl">
            <option value="">Unit Kerja</option>
                @foreach ($ORG_STRUCTS  as $level => $group)
                    @foreach ($group as $val)
                        @if ($loop->first)
                            <optgroup label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($level) }}">
                        @endif
                        <option value="{{ $val->id }}">{{ $val->name }}</option>
                        @if ($loop->last)
                            </optgroup>
                        @endif
                    @endforeach
                @endforeach
        </select>
    </div>
    <div class="mr-2 content-filter-date" style="width: 300px">
        <div class="input-group">
            <input type="text"  name="tgl_pengajuan" data-post="date_start"
            class="form-control filter-control base-plugin--datepicker date_start"
            data-format="dd/mm/yyyy"
            data-options='@json([
                "format" => "dd/mm/yyyy",
                "startDate" => "",
                "endDate" => now()->format('d/m/Y')
                ])' placeholder="{{ __('Mulai') }}">
            <div class="input-group-append">
                <span class="input-group-text">
                    <i class="la la-ellipsis-h"></i>
                </span>
            </div>
            <input type="text" data-post="date_end"
                class="form-control filter-control base-plugin--datepicker date_end"
                placeholder="{{ __('Selesai') }}"
                data-format="dd/mm/yyyy"
                data-options='@json([
                    "format" => "dd/mm/yyyy",
                    "startDate" => "",
                    "endDate" => now()->format('d/m/Y')
                ])' disabled>
        </div>
    </div>
@endsection
@section('buttons-before')
    {{-- <div class="btn-group dropdown">
  <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Export
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item" href="#">Excel</a>
    <a class="dropdown-item" href="#">PDF</a>
  </div>
</div> --}}
@endsection
@section('buttons-after')
    @if (
        \Route::has($route . '.create') &&
            auth()->user()->checkPerms($perms . '.add'))
        <a href="{{ route($route . '.create') }}" class="btn btn-info ml-2" data-toggle="tooltip"
            data-original-title="{{ __('Data') }}" data-placement="bottom">
            <i class="fa fa-plus mr-1"></i> {{ __('Data') }}
        </a>
    @endif
@endsection

@push('scripts')
    <script>
        $(function () {
            initDateStart();
        });
        var initDateStart = function () {
            $('.content-filter-date').on('changeDate', 'input.date_start', function (value) {
                var me = $(this);
                if (me.val()) {
                    var startDate = new Date(value.date.valueOf());
                    var date_end = me.closest('.input-group').find('input.date_end');
                    date_end.prop('disabled', false)
                            .val(me.val())
                            .datepicker('setStartDate', startDate)
                            .focus();
                }
            });
        }
    </script>
@endpush
