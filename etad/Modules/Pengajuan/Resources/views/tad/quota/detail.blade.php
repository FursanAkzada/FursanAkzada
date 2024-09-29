{{-- {{ dd(json_decode(\Modules\Master\Entities\SO\OrgStruct::groupByLevel(['division', 'bod']))) }} --}}
@php
    $org_struct_map = [
        'bod' => 'Direksi',
        'vice' => 'SEVP',
        'division' => 'Divisi',
        'departemen' => 'Sub Divisi',
        'cabang' => 'Cabang',
        'capem' => 'Cabang Pembantu',
        'kas' => 'Kantor Kas',
    ];
@endphp
@extends('layouts.app')
@section('title', $title)
@section('filters')
    <div class="mr-2">
        <input type="text" class="form-control filter-control" data-post="keyword" placeholder="Pencarian">
    </div>
@endsection
@section('buttons-after') @endsection
@push('styles')
    <style>
        .sticky-header-1 {
            position: sticky;
            top: 0px;
        }

        .sticky-header-2 {
            position: sticky;
            top: 0px;
        }

        .sticky-left {
            position: sticky;
            left: 0;
            background-color: #3F4254;
            color: white;
            width: 220px;
            padding: 1em .5em !important;
        }

        .cmp {
            margin: 0 !important;
            padding: 0 !important;
        }

        .sticky-header-1 td:after,
        .sticky-header-1 td:before,
        .sticky-header-2 td:after,
        .sticky-header-2 td:before {
            content: '';
            position: absolute;
            left: 0;
            width: 100%;
        }

        .sticky-header-1 td:before,
        .sticky-header-2 td:before {
            top: -1px;
            border-top: 1px solid rgb(235, 237, 243);
            border-left: 1px solid rgb(235, 237, 243);
        }

        .sticky-header-1 td:after,
        .sticky-header-2 td:after {
            bottom: -1px;
            border-bottom: 1px solid rgb(235, 237, 243);
            border-right: 1px solid rgb(235, 237, 243);
        }

        .table-container {
            position: relative;
        }

        .table-container::-webkit-scrollbar {
            width: 25px; /* Increase the width of the scrollbar */
            height: 25px; /* Increase the height of the scrollbar */
            /* Lebar scrollbar */
        }

        .table-container::-webkit-scrollbar-track {
            background-color: #f1f1f1;
            /* Warna latar belakang track */
        }

        .table-container::-webkit-scrollbar-thumb {
            background-color: #C3C3C3 !important;
            /* Warna thumb scrollbar */
            border-radius: 5px;
            /* Sudut melengkung pada thumb */
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background-color: #555;
            /* Warna thumb saat dihover */
        }

        #quotaTable tbody tr:nth-child(even) {
            background-color: #EEE;
        }
        #quotaTable {
            border-collapse: separate;
            border-spacing: 0;
        }

        #quotaTable tbody{
            white-space: nowrap;
        }

        #loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.339) url('{{ URL::asset('assets/media/ui/spinner.gif') }}') no-repeat center center;
            z-index: 10000;
        }
    </style>
@endpush

@section('buttons-after')
    {{-- @if (\Route::has($route . '.create') &&
    auth()->user()->checkPerms($perms . '.add'))
        <a href="{{ route($route . '.detail.create-quota', $periode->id) }}" class="ml-2 btn btn-info base-modal--render">
            <i class="mr-1 fa fa-plus"></i> {{ __('Data') }}
        </a>
    @endif --}}
@endsection
@section('content')
    <form id="formSpinner" action="{{ route($route . '.update', $periode->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <input type="hidden" name="year" value="{{ $periode->year }}">
        <input type="hidden" name="semester" value="{{ $periode->semester }}">
        <input type="hidden" name="detail" value="1">
        <input type="hidden" id="saveSubmitCtrl" name="save_submit" value="0">
        <input type="hidden" name="module" value="{{ $module }}">
        <div class="card card-custom">
            <div class="card-header">
                <h5 class="card-title">
                    @if (in_array($periode->level, ['capem', 'departemen']))
                        <div class="d-flex justify-content-between" style="width: 400px">
                            <div class="reset-filter mr-sm-2">
                                <button class="btn btn-info btn-pill btn-icon btn-sm mt-2" data-toggle="tooltip"
                                    data-original-title="Reset Filter" id="resetFilter" type="button"><i
                                        class="icon-md la la-refresh"></i></button>
                            </div>
                            <select class="form-control base-plugin--select2" name="" id="parentFilterCtrl"
                                style="width: 400px" title="Pilih Parent">
                                <option disabled selected value="">Pilih Parent</option>
                                @if ($periode->level === 'capem')
                                    @foreach (\Modules\Master\Entities\SO\OrgStruct::groupByLevel(['cabang']) as $level => $group)
                                        @foreach ($group as $val)
                                            @if ($loop->first)
                                                <optgroup
                                                    label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($level) }}">
                                            @endif
                                            <option value="{{ $val->id }}">{{ $val->name }}</option>
                                            @if ($loop->last)
                                                </optgroup>
                                            @endif
                                        @endforeach
                                    @endforeach
                                @elseif ($periode->level === 'departemen')
                                    @foreach (\Modules\Master\Entities\SO\OrgStruct::groupByLevel(['division']) as $level => $group)
                                        @foreach ($group as $val)
                                            @if ($loop->first)
                                                <optgroup
                                                    label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($level) }}">
                                            @endif
                                            <option value="{{ $val->id }}">{{ $val->name }}</option>
                                            @if ($loop->last)
                                                </optgroup>
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @endif
                </h5>
                <button aria-label="Close" class="close" data-dismiss="card"
                    onclick="location.href='{{ route($route . '.index') }}'" type="button">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="table-container inner" style="overflow-x:auto; overflow-y:auto; height:80vh;">
                    <table class="table-bordered w-100 table" id="quotaTable">
                        @php
                            $jabatan_count = 0;
                        @endphp
                        <thead style="position:sticky;top:0;z-index:1;">
                            <tr class="sticky-header-1">
                                <td class="kosong" style="background-color:#3F4254;"></td>
                                @foreach ($KATEGORI_VENDOR as $kategori_vendor)
                                    @if ($kategori_vendor->nama === 'PLACEHOLDER')
                                        @continue
                                    @endif
                                    <td colspan="{{ $kategori_vendor->jabatanTad->count() }}"
                                        class="text-center text-white" style="background-color:#3F4254;">
                                        <b>{{ $kategori_vendor->nama }}</b>
                                    </td>
                                @endforeach
                            </tr>
                            <tr class="sticky-header-2">
                                <td class="kosong" style="background-color:#3F4254;"></td>
                                @foreach ($KATEGORI_VENDOR as $kategori_vendor)
                                    @if ($kategori_vendor->nama === 'PLACEHOLDER')
                                        @continue
                                    @endif
                                    @foreach ($kategori_vendor->jabatanTad as $jabatan_tad)
                                        <td class="text-center text-white"
                                            style="background-color:#3F4254; padding: 1em .5em !important;">
                                            <b>{{ $jabatan_tad->NM_UNIT }}</b>
                                        </td>
                                        @php
                                            $jabatan_count++;
                                        @endphp
                                    @endforeach
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ORG_STRUCT as $org_struct => $structs)
                                <tr>
                                    <td class="p-4 text-left">
                                        <b>{{ $org_struct_map[$org_struct] }}</b>
                                    </td>
                                    <td colspan="{{ $jabatan_count }}"></td>
                                </tr>
                                @foreach ($structs as $struct)
                                    <tr class="quota-row parent-id__{{ $struct->parent_id }}">
                                        <td class="p-2 sticky-left" style="width: 220px">
                                            <span class="pl-4">↳</span>
                                            {{ $struct->name }}
                                        </td>
                                        @foreach ($KATEGORI_VENDOR as $kategori_vendor)
                                            @if ($kategori_vendor->nama === 'PLACEHOLDER')
                                                @continue
                                            @endif
                                            @foreach ($kategori_vendor->jabatanTad as $jabatan_tad)
                                                @php
                                                    $quota = Modules\Pengajuan\Entities\Tad\Quota::where('pengajuan_tad_quota_periode_id', $periode->id)
                                                        ->where('org_struct_id', $struct->id)
                                                        ->where('posisi_tad_id', $jabatan_tad->idunit)
                                                        ->first();
                                                @endphp
                                                <td class="text-center">
                                                    {{-- @if (($quota->used['total'] ?? 0) !== 0)
                                                            <input type="hidden" inputmode="numeric" min="0"
                                                                name="quota[{{ $struct->id }}][{{ $jabatan_tad->idunit }}][quota]"
                                                                placeholder="Quota" value="{{ $quota->quota ?? '0' }}">
                                                            <input class="form-control masking-quota" disabled
                                                                inputmode="numeric" min="0"
                                                                value="{{ $quota->quota ?? '0' }}">
                                                        @else --}}
                                                    <input class="form-control masking-quota input_{{ $loop->iteration }}" inputmode="numeric"
                                                        min="0" onfocus="storeOriginalValue(this, 'input_{{ $loop->iteration }}')" onblur="resetIfChanged(this)"
                                                        name="quota[{{ $struct->id }}][{{ $jabatan_tad->idunit }}][quota]"
                                                        placeholder="Quota" value="{{ $quota->quota ?? '0' }}" data-value-original="{{ $quota->quota ?? 0 }}"
                                                        style="width: 50px !important">
                                                    {{-- @endif --}}
                                                </td>
                                            @endforeach
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            @php
                $tipe = $module;
            @endphp
            <div class="col-6">
                @include('pengajuan::tad.quota.flow')
            </div>
            <div class="col-6">
                <div class="card card-custom" style="height:100%;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-lg-wrap flex-xl-nowrap p-4">
                            <div class="d-flex flex-column mr-5">
                                <span class="h4 text-dark text-hover-primary mb-5">
                                    Informasi
                                </span>
                                <p class="text-dark-50">
                                    Sebelum submit pastikan data Quota Pengajuan TAD tersebut sudah sesuai.
                                </p>
                            </div>
                            <div class="ml-lg-0 ml-xxl-6 ml-6 flex-shrink-0">
                                @php
                                    $menu = \Modules\Settings\Entities\Menu::where('code', $tipe)->first();
                                    $count = $menu->flows()->count();
                                    $submit = $count == 0 ? 'disabled' : 'enabled';
                                @endphp
                                <div style="display: none">
                                    <x-btn-back class="mr-2" url="{{ route($route . '.index') }}" />
                                </div>
                                <x-btn-draft via="base-form--submit-page" confirm="true" submit="{{ $submit }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- <div id="loader"></div> --}}
@endsection

@push('scripts')
    <script>
        function storeOriginalValue(input, identifier) {
            // Store the original value and identifier as data attributes
            $(input).data('original-value', $(input).val());
            $(input).data('identifier', identifier);
        }

        function resetIfChanged(input) {
            // Get the original value and identifier from data attributes
            var originalValue = $(input).data('original-value');
            var valueDb = $(input).attr("data-value-original");
            // Check if the value has changed
            if ($(input).val() !== originalValue && $(input).val() == '') {
                console.log($(input).val() == '');
                // Reset the input value to the original value
                $(input).val(valueDb);
            }
        }
        $(".masking-quota").inputmask({
            "mask": "9",
            "repeat": 3,
            "greedy": false
        });
        $('#kt_aside_toggle').click();
        $('#kt_aside_toggle').trigger('click');
        $(document)
            .on('click', '.base-form--submit-page', function() {
                $('#saveSubmitCtrl').val($(this).data('submit'));
            })
            .on('click', '#resetFilter', function() {
                $('#parentFilterCtrl').val('').trigger('change');
            })
            .on('change', '#parentFilterCtrl', function() {
                let val = $(this).val();
                let selector = '.parent-id__' + val;
                if (val) {
                    $('.quota-row').hide();
                    $(selector).show();
                } else {
                    $('.quota-row').show();
                }
                console.log(selector);
            });
    </script>
@endpush
