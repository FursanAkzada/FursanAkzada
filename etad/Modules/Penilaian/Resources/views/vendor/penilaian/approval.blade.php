@php
    $user = auth()->user();
    $unit_kerja;
    $unit_kerja_id;
    $unit_kerja_type;
    if (isset($user->position->org_struct_id)) {
        $unit_kerja = $user->org_name;
        $unit_kerja_id = $user->position->org_struct_id;
        $unit_kerja_type = \Modules\Master\Entities\SO\OrgStruct::class;
    } else {
        $unit_kerja = $user->name;
        $unit_kerja_id = $user->id;
        $unit_kerja_type = \App\Entities\User::class;
    }

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
@endphp

@extends('layouts.app')
@section('title', $title)
@section('buttons-after') @endsection
@section('content')

    <div class="card card-custom">
        <div class="card-header">
            <h5 class="card-title">
                Penilaian Vendor
                <button class="btn btn-success ml-2" data-toggle="collapse" data-target="#collapseOne"
                    aria-expanded="true" aria-controls="collapseOne" type="button">
                    Klasifikasi Nilai
                </button>
            </h5>
            <button aria-label="Close" class="close" data-dismiss="card"
                onclick="location.href='{{ route($route . '.index') }}'" type="button">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="" class="col-md-4 col-form-label font-weight-bold">Unit Kerja</label>
                                <div class="col-md-8">
                                    <select class="form-control base-plugin--select2" id="unitKerjaAsalCtrl"
                                        name="unit_kerja_id" title="Pilih Unit Kerja Asal" disabled>
                                        <option></option>
                                        @if ($user->cekDivisiHC())
                                            @foreach ($ORG_STRUCT as $key => $group)
                                                <optgroup
                                                    label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($key) }}">
                                                    @foreach ($group as $val)
                                                        <option value="{{ $val->id }}"
                                                            @if ($record->unitKerja->id == $val->id) selected @endif>
                                                            {{ $val->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-md-4 col-form-label font-weight-bold">Vendor</label>
                                <div class="col-md-8">
                                    <select name="vendor_id" id="" class="form-control base-plugin--select2"
                                        title="Pilih Vendor" disabled>
                                        <option selected disabled></option>
                                        <option value="{{ $record->vendor->id }}" selected>{{ $record->vendor->nama }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-4 col-form-label">Kepada</label>
                                <div class="col-8 parent-group">
                                    <select name="to" id="" class="form-control base-plugin--select2-ajax"
                                        title="Pilih Kepada" data-url="{{ route('settings.user.ajax.selectAjaxUserDivisiHC') }}" disabled>
                                        <option value=""></option>
                                        @if ($record->toUser)
                                            <option value="{{ $record->to }}" selected>{{ $record->toUser->name }}
                                                ({{ $record->toUser->position->name ?? 'Vendor ' . $record->toUser->vendor->nama }})</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="" class="col-md-4 col-form-label font-weight-bold">Periode</label>
                                <div class="col-md-4">
                                    <select name="tahun" class="base-plugin--select2 form-control" id="yearCtrl"
                                        title="Pilih Tahun" disabled>
                                        <option disabled></option>
                                        @for ($year = 2015; $year <= date('Y'); $year++)
                                            <option value="{{ $year }}"
                                                @if ($year == $record->tahun) selected @endif>
                                                {{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control base-plugin--select2" id="semesterCtrl" name="semester"
                                        title="Pilih Semester" disabled>
                                        <option disabled selected></option>
                                        <option value="Satu" @if ($record->semester == 'Satu') selected @endif>Satu
                                        </option>
                                        <option value="Dua" @if ($record->semester == 'Dua') selected @endif>Dua
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-4 col-form-label font-weight-bold">Tgl Penilaian</label>
                                <div class="col-8">
                                    <input type="text" name="tgl_penilaian"
                                        class="form-control base-plugin--datepicker tgl-penilaian" data-orientation="top"
                                        data-format="dd/mm/yyyy" data-options='@json(['endDate' => '', 'format' => 'dd/mm/yyyy'])'
                                        placeholder="{{ __('Tgl Penilaian') }}" @if ($record->tgl_penilaian)
                                    value="{{ $record->tgl_penilaian->format('d/m/Y') }}"
                                    @endif disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="separator separator-dashed separator-border-3 mb-5 mt-3"></div>
                    @foreach ($pertanyaan as $item)
                        <div class="form-group row">
                            <label for="" class="col-8">{{ $item->pertanyaan }}</label>
                            <div class="col col-form-label parent-group" style="display: flex; flex-direction: row-reverse">
                                <div class="radio-inline">
                                    <label class="radio radio-success">
                                        <input type="radio"
                                            {{ !empty($item->jawaban->value) && $item->jawaban->value == '1' ? 'checked' : '' }}
                                            name="question[{{ $item->id }}]" value="1" disabled />
                                        <span></span>
                                        1
                                    </label>
                                    <label class="radio radio-success">
                                        <input type="radio"
                                            {{ !empty($item->jawaban->value) && $item->jawaban->value == '2' ? 'checked' : '' }}
                                            name="question[{{ $item->id }}]" value="2" disabled />
                                        <span></span>
                                        2
                                    </label>
                                    <label class="radio radio-success">
                                        <input type="radio"
                                            {{ !empty($item->jawaban->value) && $item->jawaban->value == '3' ? 'checked' : '' }}
                                            name="question[{{ $item->id }}]" value="3" disabled />
                                        <span></span>
                                        3
                                    </label>
                                    <label class="radio radio-success">
                                        <input type="radio"
                                            {{ !empty($item->jawaban->value) && $item->jawaban->value == '4' ? 'checked' : '' }}
                                            name="question[{{ $item->id }}]" value="4" disabled />
                                        <span></span>
                                        4
                                    </label>
                                    <label class="radio radio-success">
                                        <input type="radio"
                                            {{ !empty($item->jawaban->value) && $item->jawaban->value == '5' ? 'checked' : '' }}
                                            name="question[{{ $item->id }}]" value="5" disabled />
                                        <span></span>
                                        5
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="separator separator-dashed separator-border-3 mb-5 mt-3"></div>
                    <div class="form-group">
                        <label for="" class="text-bold">Kesimpulan :</label>
                        <div class="row">
                            <div class="col-5 parent-group">
                                <p>{{ $record->kesimpulan }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="text-bold">Kritik Untuk Vendor Tenaga Alih Daya :</label>
                        <p>{{ $record->kritik }}</p>
                    </div>
                    <div class="form-group">
                        <label for="" class="text-bold">Saran Untuk Vendor Tenaga Alih Daya :</label>
                        <p>{{ $record->saran }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <form action="{{ route($route . '.approvalSave', $record->id) }}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="id" value="{{ $record->id }}">
                <div class="d-flex float-right flex-row">
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
    <br>
    <div class="row">
        @php
            $tipe = 'penilaian.vendor';
        @endphp
        <div class="col-6">
            @include('penilaian::vendor.penilaian.flow')
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#modal .modal-dialog-right-bottom')
            .addClass('modal-xl')
            .removeClass('modal-dialog-right-bottom');
        $(document).ready(function() {
            $('.collapse').collapse();
        });
    </script>
@endpush
