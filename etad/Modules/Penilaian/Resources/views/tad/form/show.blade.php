@inject('carbon', 'Carbon\Carbon')
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
            <h5 class="card-title">
                Penilaian TAD
                <button class="btn btn-success mb-2" data-toggle="collapse" data-target="#collapseOne"
                    aria-expanded="true" aria-controls="collapseOne" type="button">
                    Klasifikasi Nilai
                </button>
            </h5>
            <button aria-label="Close" class="close" data-dismiss="card" onclick="location.href='{{ url()->previous() }}'"
                type="button">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        <div id="accordion">
                            <div id="collapseOne" class="show collapse" aria-labelledby="headingOne"
                                data-parent="#accordion">
                                <div class="alert alert-success mb-5 p-5" role="alert">
                                    <h4 class="alert-heading"> Memberikan Centang Pada Lingkaran Skor Penilaian</h4>
                                    <div class="row">
                                        <div class="col text-center">
                                            <p>Nilai Mutu</p>
                                            <hr>
                                            <p>5 : A</p>
                                            <p>4 : B</p>
                                            <p>3 : C</p>
                                            <p>2 : D</p>
                                            <p>1 : E</p>
                                        </div>
                                        <div class="col text-center">
                                            <p>Bobot</p>
                                            <hr>
                                            <p>110 - 120</p>
                                            <p>90 - 109</p>
                                            <p>61 - 89</p>
                                            <p>31 - 60</p>
                                            <p>0 - 30</p>
                                        </div>
                                        <div class="col text-center">
                                            <p>Kualitas</p>
                                            <hr>
                                            <p>Sangat Baik</p>
                                            <p>Baik</p>
                                            <p>Cukup</p>
                                            <p>Kurang</p>
                                            <p>Sangat Kurang</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="separator separator-dashed separator-border-3 mb-5 mt-3"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-6">
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label font-weight-bold">Nomor Surat</label>
                            <div class="col-9 parent-group">
                                <input class="form-control" name="no_surat" placeholder="{{ __('Nomor Surat') }}"
                                    value="{{ $record->no_surat }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-3 col-form-label text-bold">Unit Kerja</label>
                            <div class="col-9">
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
                            <label class="col-3 col-form-label text-bold">Vendor</label>
                            <div class="col-9">
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
                            <label class="col-3 col-form-label text-bold">Posisi TAD</label>
                            <div class="col-9">
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
                            <label class="col-3 col-form-label text-bold">Personil TAD</label>
                            <div class="col-9">
                                <select name="personil" id="personilCtrl" class="form-control base-plugin--select2-ajax"
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
                            <label for="" class="col-4 col-form-label font-weight-bold">Kepada</label>
                            <div class="col-8 parent-group">
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
                            <label class="col-4 font-weight-bold">Tahun</label>
                            <div class="col-8">
                                <input class="form-control base-plugin--datepicker-3" data-language="en" id="yearCtrl"
                                    name="tahun" placeholder="Periode Tahun" value="{{ $record->tahun }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 font-weight-bold">Semester</label>
                            <div class="col-8">
                                <select class="form-control base-plugin--select2" id="semesterCtrl" name="semester"
                                    title="Pilih Semester" disabled>
                                    <option value="Satu" @if ($record->semester == 'Satu') selected @endif>Satu</option>
                                    <option value="Dua" @if ($record->semester == 'Dua') selected @endif>Dua</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                                <label for="" class="col-4 col-form-label font-weight-bold">Tanggal Penilaian</label>
                                <div class="col-8">
                                    <input type="text" name="periode_penilaian"
                                        class="form-control base-plugin--datepicker tgl-penilaian" data-orientation="top"
                                        data-format="dd/mm/yyyy" data-options='@json(['endDate' => '', 'format' => 'dd/mm/yyyy'])'
                                        placeholder="{{ __('Tgl Penilaian') }}" @if ($record->periode_penilaian)
                                    value="{{ $record->periode_penilaian->format('d/m/Y') }}"
                                    @endif disabled>
                                </div>
                         </div>
                    </div>
                </div>

                <ul class="nav nav-light-success nav-bold nav-pills">
                    @foreach ($pertanyaan as $index => $item)
                        <li class="nav-item">
                            <a class="nav-link {{ $index == 0 ? 'active' : '' }}" data-toggle="tab"
                                href="#penilai-{{ $index }}">
                                <span class="nav-text">{{ $item->judul }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <hr><br>

                <div class="tab-content">
                    @foreach ($pertanyaan as $index => $item)
                        <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"
                            id="penilai-{{ $index }}" role="tabpanel" aria-labelledby="{{ $index }}">
                            @foreach ($item->child as $child)
                                @php
                                    $jawaban = $child
                                        ->jawaban()
                                        ->where('penilai', 0)
                                        ->where('penilaian_id', $record->id)
                                        ->first();
                                @endphp
                                <div class="form-group row">
                                    <label class="col-8" style="text-align:justify;">
                                        <span class="text-bold">{{ $loop->iteration }}. {{ $child->judul }}</span><br>
                                        <span>{{ $child->pertanyaan }}</span>
                                    </label>
                                    <div class="col-4 col-form-label parent-group"
                                        style="justify-content:center;align-items:center;">
                                        <div class="radio-inline" style="justify-content:center;">
                                            <label class="radio radio-success">
                                                <input type="radio"
                                                    {{ !empty($jawaban) && $jawaban->value == '1' ? 'checked' : '' }}
                                                    disabled name="question[0][{{ $child->id }}]" value="1" />
                                                <span></span>
                                                1
                                            </label>
                                            <label class="radio radio-success">
                                                <input type="radio"
                                                    {{ !empty($jawaban) && $jawaban->value == '2' ? 'checked' : '' }}
                                                    disabled name="question[0][{{ $child->id }}]" value="2" />
                                                <span></span>
                                                2
                                            </label>
                                            <label class="radio radio-success">
                                                <input type="radio"
                                                    {{ !empty($jawaban) && $jawaban->value == '3' ? 'checked' : '' }}
                                                    disabled name="question[0][{{ $child->id }}]" value="3" />
                                                <span></span>
                                                3
                                            </label>
                                            <label class="radio radio-success">
                                                <input type="radio"
                                                    {{ !empty($jawaban) && $jawaban->value == '4' ? 'checked' : '' }}
                                                    disabled name="question[0][{{ $child->id }}]" value="4" />
                                                <span></span>
                                                4
                                            </label>
                                            <label class="radio radio-success">
                                                <input type="radio"
                                                    {{ !empty($jawaban) && $jawaban->value == '5' ? 'checked' : '' }}
                                                    disabled name="question[0][{{ $child->id }}]" value="5" />
                                                <span></span>
                                                5
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <div class="separator separator-dashed separator-border-3 mb-5 mt-3"></div>
                <div class="form-group">
                    <label for="" class="text-bold">Prestasi Lain Yang Perlu Dicatat :</label>
                    <div>{{ $record->prestasi }}</div>
                </div>
                <div class="form-group">
                    <label for="" class="text-bold">Indisipliner Tertentu Yang Perlu Dicatat :</label>
                    <div>{{ $record->indisipliner }}</div>
                </div>
                <div class="form-group">
                    <label for="" class="text-bold">Saran dan Perbaikan :</label>
                    <div>{{ $record->saran }}</div>
                </div>
                {{-- <div class="form-group">
                        <label for="" class="text-bold">Status Kontrak Kerja</label>
                        <div class="row">
                            <div class="col-5 parent-group">
                                <select name="status_perpanjangan" id=""
                                    class="form-control base-plugin--select2" title="Pilih Kontrak Kerja">
                                    <option selected disabled></option>
                                    <option value="1">Tidak Diperpanjang</option>
                                    <option value="2">Diperpanjang</option>
                                </select>
                            </div>
                        </div>
                    </div> --}}
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
            $('.collapse').collapse();
        });
    </script>
@endpush
