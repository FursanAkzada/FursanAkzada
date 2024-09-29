@php
    $ORG_STRUCTS = \Modules\Master\Entities\SO\OrgStruct::groupByLevel([], true);
    $GROUPS = \App\Entities\Group::orderBy('name', 'ASC')
    ->get();
    $user = auth()->user();
    $so_id = $user->position->org_struct_id ?? null;
    // dd(json_decode($struct));
@endphp
@extends('layouts.grid.list')
@section('filters')
    <div class="mr-2 mt-2" >
        <input type="text" data-post="tahun" class="form-control base-plugin--datepicker-3 filter-control " placeholder="{{ __('Tahun') }}" style="width: 100px">
    </div>
    <div class="mr-2 mt-2" style="width:250px;">
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
@section('buttons-after')
    @if (
        \Route::has($route . '.create') &&
            auth()->user()->checkPerms('penilaian.vendor.add'))
        <a href="{{ route($route . '.create') }}" class="btn btn-info ml-2"
            data-toggle="tooltip" data-original-title="{{ __('Data') }}" data-placement="bottom">
            <i class="fa fa-plus mr-1"></i> {{ __('Data') }}
        </a>
    @endif
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

        const PERIODE_QUOTA = @json($QUOTA_PERIODE);

        function periodeChanged(exists = false) {
            if (exists) {
                // $('select.kategori-ctrl').prop('disabled', false);
                // $('select.jabatan-ctrl').prop('disabled', false);
                // $('select.gender-ctrl').prop('disabled', false);
                // $('.jumlah-ctrl').prop('disabled', false);
                // $('select.vendor-ctrl').prop('disabled', false);
                // $('.btn-add-requirement').prop('disabled', false);
            } else {
                // $('select.kategori-ctrl').prop('disabled', true);
                // $('select.jabatan-ctrl').prop('disabled', true);
                // $('select.gender-ctrl').prop('disabled', true);
                // $('.jumlah-ctrl').prop('disabled', true);
                // $('select.vendor-ctrl').prop('disabled', true);
                // $('.btn-add-requirement').prop('disabled', true);
            }
        }
        $(document)
            .on('change', '#yearCtrl, #semesterCtrl', function() {
                let year = $('#yearCtrl').val();
                let semester = $('#semesterCtrl').val();
                let periodeExists = false;
                if (year != '' && semester != '' && year && semester) {
                    for (let periode of PERIODE_QUOTA) {
                        if (['approved', 'completed', 'new-struct', 'new-position'].includes(periode.status) && periode.year ==
                            year && periode.semester == semester) {
                            periodeExists = true;
                        }
                    }
                    if (periodeExists) {
                        periodeChanged(true);
                    } else {
                        periodeChanged();
                        $.gritter.add({
                            title: 'Failed!',
                            text: 'Periode Quota Tidak Tersedia.',
                            image: baseurl + '/assets/images/icon/ui/cross.png',
                            sticky: false,
                            time: '3000'
                        });
                        console.log(116, year, semester);
                        // $('#yearCtrl').val('').trigger('change');
                        // $('#semesterCtrl').val('').trigger('change');
                        // $(this).val('').trigger('change');
                        $(this).val('').change();
                    }
                }
            });
    </script>
@endpush
