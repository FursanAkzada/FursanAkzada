@php
    $ORG_STRUCTS = \Modules\Master\Entities\SO\OrgStruct::groupByLevel([], true);
    $GROUPS = \App\Entities\Group::orderBy('name', 'ASC')
    ->get();
    $user = auth()->user();
    $so_id = $user->position->org_struct_id ?? null;
@endphp
@extends('layouts.grid.list')
@push('styles')
    <style>
        .select2-selection__rendered[title="Pilih Personil TAD"] {
            color: #B5B5C3 !important;
        }
    </style>
@endpush
@section('filters')
    <div class="mr-2 mt-2">
        <input type="text"  data-post="tahun" class="form-control filter-control base-plugin--datepicker-3" placeholder="{{ __('Tahun') }}" style="width: 100px">
    </div>
    <div class="mr-2 mt-2">
        <input type="text"  data-post="personil" class="form-control filter-control" placeholder="{{ __('Personil TAD') }}" style="width: 150px">
    </div>
    <div class="mr-2 mt-2" style="width: 250px">
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
    <div class="mr-2 mt-2" style="width: 150px">
        <select class="filter-control form-control base-plugin--select2"
            id="vendorCtrl" data-post="vendor_id" title="Vendor">
            <option selected disabled></option>
            @foreach ($VENDOR as $item)
                @if ($user->isVendor)
                    @if ($user->vendor_id == $item->id)
                        <option value="{{ $item->id }}" data-kategori-id='{{ $item->categories->pluck('id') }}' data-tads='{{ json_encode($item->tad) }}'>{{ $item->nama }}</option>
                    @endif
                @else
                    <option value="{{ $item->id }}" data-kategori-id='{{ $item->categories->pluck('id') }}' data-tads='{{ json_encode($item->tad) }}'>{{ $item->nama }}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="d-none mr-2 mt-2">
        <select class="filter-control form-control base-plugin--select2"
            id="personilCtrl" data-post="tad_id" title="Pilih Personil TAD">
            <option selected disabled></option>
        </select>
    </div>
    <div class="mr-2 mt-2 content-filter-date" style="width: 300px">
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
        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            Export
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="#">Excel</a>
            <a class="dropdown-item" href="#">PDF</a>
        </div>
    </div> --}}
@endsection
@section('buttons-after')
    @if (\Route::has($route . '.create') && auth()->user()->checkPerms('penilaian.tad.add'))
        <a href="{{ route($route . '.create') }}" class="btn btn-info ml-2">
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

        $(document).ready(function(){
            $(document).on('change', '#vendorCtrl', function(){
                let tads = $('#vendorCtrl option:selected').data('tads');
                let category_ids = $('#vendorCtrl option:selected').data('kategori-id');
                console.log(71, category_ids, tads);
                let options = '<option disabled selected value="">Pilih Personil TAD</option>';
                // let options = '';
                for (let item of tads) {
                    if (category_ids.includes(item.jabatan.kategori_id)) {
                        options += `<option value="${item.id}">${item.nama}</option>`;
                    }
                }
                $('#personilCtrl').select2('destroy');
                $('#personilCtrl').html(options);
                $('#personilCtrl').select2();
            });
        });
    </script>
@endpush
