@php
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
    // dd(22, json_decode($ORG_STRUCT['bod']));
    $user = auth()->user();
@endphp
@extends('layouts.app')
@section('title', $title)
@section('buttons') @endsection
@section('buttons-after') @endsection
@section('content')
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
                        <label for="" class="col-4 col-form-label font-weight-bold">Nomor Surat</label>
                        <div class="col-8 parent-group">
                            <input class="form-control" name="no_surat" placeholder="{{ __('Nomor Surat') }}" value="{{ $record->no_surat}}" disabled>
                        </div>
                    </div> --}}
                    <div class="form-group row">
                        <label class="col-4 col-form-label text-bold">Unit Kerja</label>
                        <div class="col-8 parent-group">
                            <select name="unit_kerja_id" id="unitKerjaCtrl" class="form-control base-plugin--select2"
                                title="Pilih Unit Kerja" disabled>
                                <option></option>
                                @foreach ($ORG_STRUCT as $key => $group)
                                    <optgroup label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($key) }}">
                                        @foreach ($group as $val)
                                            <option value="{{ $val->id }}"
                                                @if ($record->kepegawaian->unitKerja->id == $val->id) selected @endif>{{ $val->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-4 col-form-label text-bold">Vendor</label>
                        <div class="col-8 parent-group">
                            <select name="vendor_id" id="vendorCtrl" class="form-control base-plugin--select2-ajax"
                                data-url="{{ route('master.vendor.ajax') }}" title="Pilih Vendor" disabled>
                                @if ($record->tad->vendor->id)
                                    <option value="{{ $record->tad->vendor->id }}" selected>{{ $record->tad->vendor->nama }}
                                    </option>
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
                        <label for="" class="col-4 col-form-label font-weight-bold">Kepada</label>
                        <div class="col-8 parent-group">
                            <select name="to" id="" class="form-control base-plugin--select2-ajax"
                                title="Pilih Kepada" data-url="{{ route('settings.user.ajax.selectAjaxUserDivisiHC') }}"
                                disabled>
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
                        <label class="col-4 col-form-label text-bold">No. SK</label>
                        <div class="col parent-group">
                            <input class="form-control" type="text" name="sk" placeholder="No. SK"
                                value="{{ $record->sk }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-4 col-form-label text-bold">Tanggal SK</label>
                        <div class="col parent-group">
                            <input name="tanggal_reward" class="form-control base-plugin--datepicker-1" data-language="en"
                                data-format="dd/mm/yyyy" data-options='@json(['endDate' => ''])' type="text"
                                placeholder="Pilih Tanggal SK" value="{{ $record->tanggal_reward->format('d/m/Y') }}"
                                disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-4 col-form-label text-bold">Jenis Penghargaan</label>
                        <div class="col parent-group">
                            <select class="form-control base-plugin--select2" name="jenis_id" id=""
                                title="Pilih Salah Satu" disabled>
                                <option></option>
                                @foreach (\App\Entities\EHC\JenisPunishment::reward()->orderBy('Lengkap', 'ASC')->get() as $item)
                                    <option value="{{ $item->sandi }}" @if ($item->sandi == $record->jenis_id) selected @endif>
                                        {{ $item->Lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-2 col-form-label text-bold">Tembusan</label>
                <div class="col-10 parent-group">
                    <select name="cc[]" id="" multiple class="form-control base-plugin--select2-ajax"
                        title="Pilih User" data-url="{{ route('settings.user.ajax.selectAjaxUserDivisiHC') }}" disabled>
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
                <label class="col-2 col-form-label text-bold">Deskripsi</label>
                <div class="col parent-group">
                    <textarea class="form-control" name="keterangan" placeholder="Deskripsi" id="" cols="30" disabled>{!! $record->keterangan !!}</textarea>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-2 col-form-label text-bold">Lampiran</label>
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
            $(document).on('change', '#personilCtrl', function() {
                let data = $(this).find('option:selected').data();
                console.log(270, data);
                $('#nioValue').text(data.nio);
                $('#jabatanValue').text(data.jabatan);
                $('#unitKerjaValue').text(data.unitKerja);
                $('#masaKerjaCtrl').val(data.masaKerja);
            });
            $('#personilCtrl').change();
        });
    </script>
@endpush
