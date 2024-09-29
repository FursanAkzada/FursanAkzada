@inject('carbon', 'Carbon\Carbon')
@php
    $user = auth()->user();

    $so_id = $user->position->org_struct_id ?? null;

    $org_struct_map = [
        'bod' => 'Direksi',
        'vice' => 'SEVP',
        'division' => 'Divisi',
        'departemen' => 'Sub Divisi',
        'cabang' => 'Cabang',
        'capem' => 'Cabang Pembantu',
        'kas' => 'Kantor Kas',
    ];
    $ORG_STRUCT = \Modules\Master\Entities\SO\OrgStruct::groupByLevel();
    $ORG_STRUCT_2 = \Modules\Master\Entities\SO\OrgStruct::groupByLevelWhereId($so_id);
    // dd(22, json_decode($ORG_STRUCT['bod']));
@endphp
@push('styles')
    <style>
        .select2-selection__rendered[title="Pilih Personil TAD"] {
            color: #B5B5C3 !important;
        }
    </style>
@endpush
@extends('layouts.app')
@section('title', $title)
@section('buttons-after') @endsection
@section('content')
    <div class="card card-custom">

        <div class="card-header">
            <h4 class="card-title">{{ $title }}</h4>
            <button aria-label="Close" class="close" data-dismiss="card" onclick="location.href='{{ url()->previous() }}'"
                type="button">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row mb-5">
                <div class="col-6">
                    <div class="form-group row">
                        <label for="" class="col-4 col-form-label font-weight-bold">No Perpanjangan Kontrak</label>
                        <div class="col-8 parent-group">
                            <input class="form-control" value="{{ $record->no_pengajuan }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-4 col-form-label text-bold">Unit Kerja</label>
                        <div class="col-8">
                            <select name="unit_kerja_id" id="unitKerjaCtrl" class="form-control base-plugin--select2"
                                title="Pilih Unit Kerja" disabled>
                                <option></option>
                                @if ($user->cekDivisiHC())
                                    @foreach ($ORG_STRUCT as $key => $group)
                                        <optgroup label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($key) }}">
                                            @foreach ($group as $val)
                                                <option value="{{ $val->id }}"
                                                    @if ($record->unitKerja->id == $val->id) selected @endif>{{ $val->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                @else
                                    @foreach ($ORG_STRUCT_2 as $key => $group)
                                        @if ($group->count() != 0)
                                            <optgroup
                                                label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($key) }}">
                                                @foreach ($group as $val)
                                                    <option value="{{ $val->id }}"
                                                        @if ($record->unitKerja->id == $val->id) selected @endif>
                                                        {{ $val->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-4 col-form-label text-bold">Vendor</label>
                        <div class="col-8">
                            <select id="vendorCtrl" class="form-control base-plugin--select2-ajax"
                                data-url="{{ route('master.vendor.ajaxAll') }}" title="Pilih Vendor" disabled>
                                <option value=""></option>
                                @if ($record->kepegawaian->vendor->id)
                                    <option value="{{ $record->kepegawaian->vendor->id }}" selected>
                                        {{ $record->kepegawaian->vendor->nama }}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-4 col-form-label text-bold">Posisi TAD</label>
                        <div class="col-8">
                            <select id="posisiCtrl" class="form-control base-plugin--select2-ajax"
                                data-url="{{ route('personil.migrasi.getAjaxPenilaianTADCekJabatan') }}"
                                title="Pilih Posisi" disabled>
                                @if ($record->kepegawaian->jabatan->idunit)
                                    <option value="{{ $record->kepegawaian->jabatan->idunit }}" selected>
                                        {{ $record->kepegawaian->jabatan->NM_UNIT }}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-4 col-form-label text-bold">Personil TAD</label>
                        <div class="col-8">
                            <select name="tad_id" id="personilCtrl" class="form-control base-plugin--select2-ajax"
                                data-url="{{ route('personil.migrasi.getAjaxPenilaianTADCekPosisi') }}"
                                title="Pilih Personil TAD" disabled>
                                @if ($record->tad->id)
                                    <option value="{{ $record->tad_id }}" selected>{{ $record->tad->nama }}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group row">
                        <label class="col-4 col-form-label font-weight-bold">Tgl Perpanjangan Kontrak</label>
                        <div class="col-8 parent-group">
                            <input class="form-control base-plugin--datepicker-1" data-language="en"
                                data-format="dd/mm/yyyy" data-options='@json(['endDate' => now()])' name="tgl_pengajuan"
                                placeholder="Tgl Perpanjangan Kontrak"
                                value="{{ $record->tgl_pengajuan->format('d/m/Y') }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-4 col-form-label font-weight-bold">NIO</label>
                        <div class="col-8 parent-group">
                            <input class="form-control" id="tempNIO" placeholder="{{ __('NIO') }}"
                                value="{{ $record->kepegawaian->nio }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-4 col-form-label font-weight-bold">Tgl Akhir Kontrak Lama</label>
                        <div class="col-8 parent-group">
                            <input class="form-control base-plugin--datepicker-1" data-language="en"
                                data-format="dd/mm/yyyy" id="tempContractDue" data-options='@json(['startDate' => now()])'
                                name="tgl_awal_kontrak_lama" placeholder="Tgl Akhir Kontrak Lama"
                                value="{{ $record->tgl_akhir_kontrak_lama ? $record->tgl_akhir_kontrak_lama->format('d/m/Y') : '' }}"
                                disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-4 col-form-label font-weight-bold">Tgl Awal Kontrak Baru</label>
                        <div class="col-8 parent-group">
                            <input class="form-control base-plugin--datepicker-1" data-language="en"
                                data-format="dd/mm/yyyy" data-options='@json(['endDate' => ''])'
                                name="tgl_awal_kontrak_baru" placeholder="Tgl Awal Kontrak Baru"
                                value="{{ $record->tgl_awal_kontrak_baru->format('d/m/Y') }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-4 col-form-label font-weight-bold">Tgl Akhir Kontrak Baru</label>
                        <div class="col-8 parent-group">
                            <input class="form-control base-plugin--datepicker-1" data-language="en"
                                data-format="dd/mm/yyyy" data-options='@json(['startDate' => now()])'
                                name="tgl_akhir_kontrak_baru" placeholder="Tgl Akhir Kontrak Baru"
                                value="{{ $record->tgl_akhir_kontrak_baru->format('d/m/Y') }}" disabled>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label font-weight-bold">Kepada</label>
                        <div class="col-4 parent-group">
                            <select name="to" id="" class="form-control base-plugin--select2-ajax"
                                title="Pilih Kepada" data-url="{{ route('settings.user.ajax.selectAjaxUserDivisiHC') }}" disabled>
                                <option value=""></option>
                                @if ($record->toUser)
                                    <option value="{{ $record->to }}" selected>{{ $record->toUser->name }}
                                        ({{ $record->toUser->position->name ?? 'Vendor ' . $record->toUser->vendor->nama }})
                                    </option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label text-bold">Uraian</label>
                        <div class="col-10">
                            <textarea name="keterangan" id="" cols="30" rows="3" class="form-control" disabled>{!! $record->keterangan !!}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 text-bold">Tembusan</label>
                        <div class="col-10 parent-group">
                            <select name="cc[]" id="" multiple
                                class="form-control base-plugin--select2-ajax" title="Jabatan"
                                data-url="{{ route('master.so.jabatan.select-cc') }}" disabled>
                                @foreach ($record->cc as $item)
                                    <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label text-bold">Lampiran</label>
                        <div class="col-10">
                            @foreach ($record->files as $file)
                                <div class="progress-container w-100" data-uid="{{ $file->id }}">
                                    <div class="alert alert-custom alert-light fade show success-uploaded mb-0 mt-2 px-3 py-2"
                                        role="alert">
                                        <div class="alert-icon">
                                            <i class="{{ $file->file_icon }}"></i>
                                        </div>
                                        <div class="alert-text text-left">
                                            <input type="hidden" name="uploads_cv[files_ids][]"
                                                value="{{ $file->id }}">
                                            <div>Uploaded File:</div>
                                            <a href="{{ $file->file_url }}" target="_blank" class="text-primary">
                                                {{ $file->file_name }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label text-bold">Kalimat Pembuka</label>
                        <div class="col-10 parent-group">
                            <textarea name="pembukaan" class="base-plugin--summernote-2" data-height="200"
                                placeholder="{{ __('Kalimat Pembuka') }}" disabled>{!! $record->pembukaan !!}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label text-bold">Kalimat Penutup</label>
                        <div class="col-10 parent-group">
                            <textarea name="penutup" class="form-control base-plugin--summernote-2" data-height="200"
                                placeholder="{{ __('Kalimat Penutup') }}" disabled>{!! $record->penutup !!}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (in_array($record->status, [1, 'submit', 'waiting', 'waiting.approval']))
            <div class="card-footer p-5">
                <form action="{{ route($route . '.approvalSave', $record->id) }}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="id" value="{{ $record->id }}">
                    <div class="d-flex float-right flex-row">
                        {{-- <x-btn-back class="mr-2" url="{{ route($route . '.index') }}" />
                        <x-btn-draft style="float:right;" via="base-form--submit-page" /> --}}
                        {{-- <x-btn-save via="base-form--submit-page" /> --}}
                        @if ($approval = $record->checkApproval())
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
                            @include('pengajuan::tad.partials.modal-reject')
                        @endif
                    </div>
                </form>
            </div>
        @endif
    </div>
    <br>
    <div class="row">
        @php
            $tipe = 'penilaian.perpanjangan';
        @endphp

        @if (in_array($record->status, [1, 'submit', 'waiting.approval']))
        @else
            <div class="col-12">
                @include('penerimaan::perpanjangan.flow')
            </div>
        @endif
    </div>

@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.collapse').collapse();
        });
    </script>
@endpush
