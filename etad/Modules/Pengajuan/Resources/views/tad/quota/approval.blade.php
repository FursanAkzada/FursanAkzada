{{-- {{ dd(json_decode($periode)) }} --}}
@extends('layouts.app')
@section('title', $title)
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

        .cmp {
            margin: 0 !important;
            padding: 0 !important;
        }

        .sticky-left {
            position: sticky;
            left: 0;
            background-color: #3F4254;
            color: white;
            padding: 1em .5em !important;
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
    </style>
@endpush

@section('buttons-after')
@endsection

@section('content')
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
                                @foreach (\Modules\Master\Entities\SO\OrgStruct::groupByLevel(['bod', 'division']) as $level => $group)
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
            <div class="table-container" style="overflow-x:auto; overflow-y:auto; height:80vh;">
                <table class="table-bordered w-100 table" id="quotaTable">
                    <thead style="position:sticky;top:0;z-index:1;">
                        @php
                            $jabatan_count = 0;
                        @endphp
                        <tr class="sticky-header-1" style="position: sticky;z-index: 1;top:0px;">
                            <td class="kosong" rowspan="2" style="background-color:#3F4254;"></td>
                            @foreach ($KATEGORI_VENDOR as $kategori_vendor)
                                @if ($kategori_vendor->nama === 'PLACEHOLDER')
                                    @continue
                                @endif
                                <td colspan="{{ $kategori_vendor->jabatanTad->count() * 2 }}" class="text-center text-white"
                                    style="background-color:#3F4254;">
                                    <b>{{ $kategori_vendor->nama }}</b>
                                </td>
                            @endforeach
                        </tr>
                        <tr class="sticky-header-2" style="position: sticky;z-index: 1;top:0px;">
                            @foreach ($KATEGORI_VENDOR as $kategori_vendor)
                                @if ($kategori_vendor->nama === 'PLACEHOLDER')
                                    @continue
                                @endif
                                @foreach ($kategori_vendor->jabatanTad as $jabatan_tad)
                                    <td class="text-center text-white" colspan="2"
                                        style="width: 200px !important;background-color:#3F4254;">
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
                                <td class="p-4 text-left" colspan="{{ $jabatan_count * 2 + 1 }}">
                                    <b>{{ $org_struct_map[$org_struct] }}</b>
                                </td>
                            </tr>
                            @foreach ($structs as $struct)
                                <tr class="quota-row parent-id__{{ $struct->parent_id }}">
                                    <td class="p-2 sticky-left" style="width: 200px !important">
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
                                                <span class="badge" style="background-color: #FF0;"
                                                    title="Quota">{{ number_format($quota->quota ?? '0', 0, ',', '.') }}</span>
                                            </td>
                                            <td class="text-center">
                                                {{-- @isset($quota->used)
                                                    <b>Draft</b> : {{ $quota->used['draft']??0 }}<br>
                                                    <b>Waiting Approval</b> : {{ $quota->used['submit']??0 }}<br>
                                                    <b>Ditolak</b> : {{ $quota->used['rejected']??0 }}<br>
                                                    <b>Diproses Vendor</b> : {{ $quota->used['vendor.submit']??0 }}<br>
                                                    <b>Kandidat Diverifikasi</b> : {{ $quota->used['hc.verified']??0 }}<br>
                                                    <b>Total</b> : {{ ($quota->used['total']??0) - ($quota->used['rejected']??0) }}<br> --}}
                                                <span class="badge" style="background-color: #0BB; color: #FFF;"
                                                    title="Pemenuhan">
                                                    {{ number_format($quota->fulfillment ?? '0', 0, ',', '.') }}
                                                </span>
                                                {{-- @endisset --}}
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
        @if ($periode->status == 'submit')
            <div class="card-footer p-5">
                <form action="{{ route($route . '.approvalSave', $periode->id) }}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="id" value="{{ $periode->id }}">
                    <div class="d-flex float-right flex-row">
                        @if ($approval = $periode->checkApproval())
                            <input type="hidden" name="approval_id" value="{{ $approval->id }}">
                            <div class="btn-group dropup d-flex align-items-center">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="far fa-save mr-2"></i>Approval
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <button type="button" class="dropdown-item" data-toggle="modal"
                                        data-target="#rejectModal">
                                        <i class="far fa-save text-danger mr-3"></i> Reject
                                    </button>
                                    <button type="button" class="dropdown-item base-form--submit-page"
                                        data-swal-confirm="true" data-submit="approved">
                                        <i class="far fa-save text-primary mr-2"></i> Approve
                                    </button>
                                </div>
                            </div>
                            @include('pengajuan::tad.quota.modal-reject')
                        @endif
                    </div>
                </form>
            </div>
        @endif
    </div>
    @if ($periode->status == 'waiting.approval.upgrade')
        <div class="card card-custom mt-3">
            <div class="card-body">
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label text-bold">Alasan Revisi</label>
                    <div class="col-10">
                        <textarea class="base-plugin--summernote-2" data-height="200" placeholder="{{ __('Alasan Pengajuan Revisi') }}"
                            disabled>{!! $periode->upgrade_reason !!}</textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer p-5">
                <form action="{{ route($route . '.approvalSave', $periode->id) }}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="id" value="{{ $periode->id }}">
                    <div class="d-flex float-right flex-row">
                        @if ($approval = $periode->checkApproval())
                            <input type="hidden" name="approval_id" value="{{ $approval->id }}">
                            <div class="btn-group dropup d-flex align-items-center">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="far fa-save mr-2"></i>Approval
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <button type="button" class="dropdown-item" data-toggle="modal"
                                        data-target="#rejectModal">
                                        <i class="far fa-save text-danger mr-3"></i> Reject
                                    </button>
                                    <button type="button" class="dropdown-item base-form--submit-page"
                                        data-swal-confirm="true" data-submit="approved">
                                        <i class="far fa-save text-primary mr-2"></i> Approve
                                    </button>
                                </div>
                            </div>
                            @include('pengajuan::tad.quota.modal-reject')
                        @endif
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
            $(document)
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
        })
    </script>
@endpush
