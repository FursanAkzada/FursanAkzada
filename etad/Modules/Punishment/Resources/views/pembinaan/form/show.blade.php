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
@section('buttons') @endsection
@section('buttons-after') @endsection
@section('content')
    @csrf
    @method('PUT')
    <div class="card card-custom">
        <div class="card-header">
            <h4 class="card-title">{{ $title }}</h4>
            <button aria-label="Close" class="close" data-dismiss="modal" onclick="location.href='{{ url()->previous() }}'"
                type="button">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    {{-- <div class="form-group row">
                        <label for="" class="col-4 col-form-label text-bold">Nomor Surat</label>
                        <div class="col-8 parent-group">
                            <input class="form-control" name="no_surat" placeholder="{{ __('Nomor Surat') }}"
                                value="{{ $record->no_surat }}" disabled>
                        </div>
                    </div> --}}
                    <div class="form-group row">
                        <label class="col-4 col-form-label text-bold">Unit Kerja</label>
                        <div class="col-8 parent-group">
                            <select name="unit_kerja_id" id="unitKerjaCtrl" class="form-control base-plugin--select2"
                                title="Pilih Unit Kerja" disabled>
                                <option></option>
                                @if ($user->cekDivisiHC())
                                    @foreach ($ORG_STRUCT as $key => $group)
                                        <optgroup label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($key) }}">
                                            @foreach ($group as $val)
                                                <option value="{{ $val->id }}"
                                                    @if ($record->kepegawaian->unitKerja->id == $val->id) selected @endif>{{ $val->name }}
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
                                                        @if ($record->kepegawaian->unitKerja->id == $val->id) selected @endif>
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
                        <div class="col-8 parent-group">
                            <select name="vendor_id" id="vendorCtrl" class="form-control base-plugin--select2-ajax"
                                data-url="{{ route('master.vendor.ajax') }}" title="Pilih Vendor" disabled>
                                <option value=""></option>
                                @if ($record->tad->vendor->id)
                                    <option value="{{ $record->tad->vendor->id }}" selected>
                                        {{ $record->tad->vendor->nama }}</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-4 col-form-label text-bold">Posisi TAD</label>
                        <div class="col-8 parent-group">
                            <select name="posisi_id" id="posisiCtrl" class="form-control base-plugin--select2-ajax"
                                data-url="{{ route('personil.migrasi.getAjaxPenilaianTADCekJabatan') }}"
                                title="Pilih Posisi" disabled>
                                @if ($record->tad->jabatan->idunit)
                                    <option value="{{ $record->tad->jabatan->idunit }}" selected>
                                        {{ $record->tad->jabatan->NM_UNIT }}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-4 col-form-label text-bold">Personil TAD</label>
                        <div class="col-8 parent-group">
                            <select name="tad_id" id="personilCtrl" class="form-control base-plugin--select2-ajax"
                                data-url="{{ route('personil.migrasi.getAjaxPenilaianTADCekPosisi') }}"
                                title="Pilih Personil TAD" disabled>
                                @if ($record->tad->id)
                                    <option value="{{ $record->tad->id }}" selected>
                                        {{ $record->tad->nama }}
                                        ({{ $record->tad->kepegawaian->nio }})
                                    </option>
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label text-bold">Kepada</label>
                        <div class="col-9 parent-group">
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
                        <label for="" class="col-3 col-form-label text-bold">Jenis Pembinaan</label>
                        <div class="col parent-group">
                            <select class="form-control base-plugin--select2" name="jenis_id" title="Pilih Salah Satu"
                                disabled>
                                <option></option>
                                @foreach (\App\Entities\EHC\JenisPunishment::pembinaan()->get() as $item)
                                    <option value="{{ $item->sandi }}" @if ($record->jenis_id == $item->sandi) selected @endif>
                                        {{ $item->Lengkap }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row content-filter-date">
                        <label class="col-3 col-form-label text-bold">Tanggal</label>
                        <div class="col-9">
                            <div class="row">
                                <div class="col-6 parent-group">
                                    <input type="text" name="tanggal_mulai" data-post="date_start"
                                        class="form-control filter-control base-plugin--datepicker date_start"
                                        data-format="dd/mm/yyyy" value="{{ $record->tanggal_mulai->format('d/m/Y') }}"
                                        data-options='@json([
                                            'format' => 'dd/mm/yyyy',
                                            'startDate' => '',
                                            'endDate' => now()->format('d/m/Y')
                                        ])' placeholder="Mulai" disabled>
                                </div>
                                <div class="col-6 parent-group">
                                    <input type="text" data-post="date_end"
                                        class="form-control filter-control base-plugin--datepicker date_end"
                                        name="tanggal_selesai" value="{{ $record->tanggal_selesai->format('d/m/Y') }}"
                                        placeholder="Selesai" data-format="dd/mm/yyyy"
                                        data-options='@json([
                                            'format' => 'dd/mm/yyyy',
                                            'startDate' => '',
                                        ])' disabled>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 col-form-label text-bold">No / Tanggal SK </label>
                        <div class="col-9 parent-group">
                            <div class="row">
                                <div class="parent-group col-md-6">
                                    <input class="form-control" type="text" name="sk" placeholder="No SK" value="{{ $record->sk }}" disabled>
                                </div>
                                <div class="parent-group col-md-6">
                                    <input class="form-control base-plugin--datepicker-1" data-language="en"
                                        data-format="dd/mm/yyyy" data-options='@json(['endDate' => now()->format('d/m/Y'), 'format' => 'dd/mm/yyyy'])' id="tglSkCtrl"
                                        name="tanggal_sk" placeholder="Tanggal SK" value="{{ $record->tanggal_sk->format('d/m/Y') }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-2 col-form-label text-bold">Tembusan</label>
                <div class="col-10 parent-group">
                    <select name="cc[]" id="" multiple class="form-control base-plugin--select2-ajax"
                        title="Pilih User"
                        data-url="{{ route('settings.user.ajax.selectAjaxUserDivisiHC') }}" disabled>
                        <option value=""></option>
                        @foreach ($record->cc as $item)
                            <option value="{{ $item->id }}" selected>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-2 col-form-label text-bold">Deskripsi</label>
                <div class="col parent-group">
                    <textarea class="form-control" name="eviden" placeholder="Deskripsi" disabled>{{ $record->eviden }}</textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-2 col-form-label text-bold" for="berkas">Lampiran</label>
                <div class="col-10 parent-group">
                    @foreach ($record->files as $file)
                        <div class="progress-container w-100" data-uid="{{ $file->id }}">
                            <div class="alert alert-custom alert-light fade show success-uploaded mb-0 mt-2 px-3 py-2"
                                role="alert">
                                <div class="alert-icon">
                                    <i class="{{ $file->file_icon }}"></i>
                                </div>
                                <div class="alert-text text-left">
                                    <input type="hidden" name="uploads[files_ids][]" value="{{ $file->id }}">
                                    <div>Uploaded File:</div>
                                    <a href="{{ $file->file_url }}" target="_blank" class="text-primary">
                                        {{ $file->file_name }}
                                    </a>
                                </div>
                                <div class="alert-close">
                                    <button type="button" class="close base-form--remove-temp-files"
                                        data-toggle="tooltip" data-original-title="Remove">
                                        <span aria-hidden="true">
                                            <i class="ki ki-close"></i>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card-footer">
            <form action="{{ route($route . '.approvalSave', $record->id) }}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="id" value="{{ $record->id }}">
                <div class="d-flex justify-content-between">
                    {{-- <x-btn-draft via="base-form--submit-page" /> --}}
                    {{-- <x-btn-save via="base-form--submit-page" /> --}}
                    <div style="display: hidden">
                        <x-btn-back class="mr-2" url="{{ route($route . '.index') }}" />
                    </div>
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
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $(document)
                .on('change', '#vendorCtrl', function() {
                    let tads = $('#vendorCtrl option:selected').data('tads');
                    console.log(71, tads);
                    let options = '<option disabled selected value="">Pilih Personil TAD</option>';
                    // let options = '';
                    for (let item of tads) {
                        options += `<option value="${item.id}" data-jabatan="${item.jabatan.NM_UNIT}" data-unit-kerja="${item.kepegawaian.unit_kerja.name}">
                            ${item.nama}</option>`;
                    }
                    $('#personilCtrl').select2('destroy');
                    $('#personilCtrl').html(options);
                    $('#personilCtrl').select2();
                    $('#jabatanValue').text('');
                    $('#unitKerjaValue').text('');
                })
                .on('change', '#personilCtrl', function() {
                    let data = $(this).find('option:selected').data();
                    console.log(270, data);
                    $('#jabatanValue').text(data.jabatan);
                    $('#unitKerjaValue').text(data.unitKerja);
                });
            $('#vendorCtrl').val('{{ $record->tad->vendor_id }}').change();
            setTimeout(() => {
                $('#personilCtrl').val('{{ $record->tad->id }}').change();
            }, 500);
        });
    </script>
@endpush
