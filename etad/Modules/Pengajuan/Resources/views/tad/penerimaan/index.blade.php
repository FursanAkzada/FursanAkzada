@php
    $user = auth()->user();
    $ORG_STRUCTS = \Modules\Master\Entities\SO\OrgStruct::groupByLevel([], true);
    $GROUPS = \App\Entities\Group::orderBy('name', 'ASC')
    ->get();
@endphp
@extends('layouts.grid.list')
@section('filters')
    <div class="mr-2">
        <input type="text" class="form-control filter-control" data-post="keyword" placeholder="No Tiket" style="width: 150px">
    </div>
    <div class="mr-2">
        <input type="text" class="form-control filter-control" data-post="personil" placeholder="Personil TAD" style="width: 150px">
    </div>
    <div class="mr-2" style="width: 250px">
        <select class="form-control base-plugin--select2 show-tick filter-control" data-post="location_id" data-placeholder="Unit Kerja" id="tipeStrukturCtrl">
            <option value="">Unit Kerja</option>
            @if (
                ($user->position_id &&
                    ($user->position->struct->code === 'A35' ||
                        (isset($user->position->struct->parent->code) && $user->position->struct->parent->code === 'A35'))) ||
                    $user->is_vendor)
                @foreach ($ORG_STRUCTS as $level => $group)
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
            @else
                <optgroup label="{{ ucfirst($user->position->struct->level) }}">
                    <option value="{{ $user->position->struct->id }}">{{ $user->position->struct->name }}</option>
                </optgroup>
            @endif
        </select>
    </div>
    <div class="mr-2" style="width: 150px">
        <select class="filter-control form-control base-plugin--select2" data-post="vendor_id" title="Vendor">
            <option selected disabled></option>
             @foreach ($VENDOR as $item)
                @if ($user->isVendor)
                    @if ($user->vendor_id == $item->id)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endif
                @else
                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                @endif
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
@section('buttons-after')
@endsection
@section('buttons-before')

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
