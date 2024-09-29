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
    <form action="{{ route($route . '.update', $record->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card card-custom">

            <div class="card-header">
                <h4 class="card-title">Form Penilaian TAD</h4>
                <button aria-label="Close" class="close" data-dismiss="card" onclick="location.href='{{ url()->previous() }}'"
                    type="button">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="col-12">
                    <div id="accordion">
                        <div id="headingOne">
                            <h4 class="mb-0">
                                <button class="btn btn-success mb-2" data-toggle="collapse" data-target="#collapseOne"
                                    aria-expanded="true" aria-controls="collapseOne" type="button">
                                    Klasifikasi Nilai
                                </button>
                            </h4>
                        </div>
                        <div id="collapseOne" class="show collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="alert alert-success mb-5 p-5" role="alert">
                                <h4 class="alert-heading"> Memberikan Centang Pada Lingkaran Skor Penilaian</h4>
                                <div class="row">
                                    <div class="col">
                                        <p>Nilai Mutu</p>
                                        <hr>
                                        <p>5 : A</p>
                                        <p>4 : B</p>
                                        <p>3 : C</p>
                                        <p>2 : D</p>
                                        <p class="mr-6">1 : E</p>
                                    </div>
                                    <div class="col">
                                        <p>Bobot</p>
                                        <hr>
                                        <p>110 - 120</p>
                                        <p>90 - 109</p>
                                        <p>61 - 89</p>
                                        <p>31 - 60</p>
                                        <p>0 - 30</p>
                                    </div>
                                    <div class="col">
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
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="separator separator-dashed separator-border-3 mb-5 mt-3"></div>
                    <div class="row mb-5">
                        <div class="col-6">
                            <div class="form-group row">
                                <label for="" class="col-4 col-form-label font-weight-bold">Nomor Surat</label>
                                <div class="col-8 parent-group">
                                    <input class="form-control" name="no_surat" placeholder="{{ __('Nomor Surat') }}"
                                        value="{{ $record->no_surat }}" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-4 col-form-label text-bold">Unit Kerja</label>
                                <div class="col-8 parent-group">
                                    <select name="unit_kerja_id" id="unitKerjaCtrl"
                                        class="form-control base-plugin--select2" title="Unit Kerja">
                                        <option></option>
                                        @if ($user->cekDivisiHC())
                                            @foreach ($ORG_STRUCT as $key => $group)
                                                <optgroup
                                                    label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($key) }}">
                                                    @foreach ($group as $val)
                                                        <option value="{{ $val->id }}"
                                                            @if ($record->kepegawaian->unitKerja->id == $val->id) selected @endif>
                                                            {{ $val->name }}</option>
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
                                        data-url="{{ route('master.vendor.ajax') }}" title="Vendor">
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
                                        title="Posisi TAD">
                                        @if ($record->tad->jabatan->idunit)
                                            <option value="{{ $record->tad->jabatan->idunit }}" selected>
                                                {{ $record->tad->jabatan->NM_UNIT }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group row">
                                <label class="col-4 col-form-label text-bold">Personil TAD</label>
                                <div class="col-8 parent-group">
                                    <select name="personil" id="personilCtrl" class="form-control base-plugin--select2-ajax"
                                        data-url="{{ route('personil.migrasi.getAjaxPenilaianTADCekPosisi') }}"
                                        title="Personil TAD">
                                        @if ($record->tad->id)
                                            <option value="{{ $record->tad_id }}" selected>{{ $record->tad->nama }}
                                            </option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-4 col-form-label font-weight-bold">Kepada</label>
                                <div class="col-8 parent-group">
                                    <select name="to" class="form-control base-plugin--select2-ajax"
                                        title="Kepada"
                                        data-url="{{ route('settings.user.ajax', ['with' => $record->vendor_id]) }}"
                                        data-url-origin="{{ route('settings.user.ajax') }}" id="toCtrl">
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
                                <div class="col-4 parent-group">
                                    <input class="form-control base-plugin--datepicker-3" data-language="en"
                                        id="yearCtrl" name="tahun" placeholder="Periode Tahun"
                                        value="{{ $record->tahun }}">
                                </div>
                                <div class="col-4 parent-group">
                                    <select class="form-control base-plugin--select2" id="semesterCtrl" name="semester"
                                        title="Semester">
                                        <option value="Satu" @if ($record->semester == 'Satu') selected @endif>Satu
                                        </option>
                                        <option value="Dua" @if ($record->semester == 'Dua') selected @endif>Dua
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                 <label for="" class="col-4 col-form-label font-weight-bold">Tanggal Penilaian</label>
                                 <div class="col-8">
                                     <input type="hidden" name="tanggal_sekarang" value="{{ now()->format('d/m/Y') }}">
                                     <input type="text" name="periode_penilaian"
                                         class="form-control base-plugin--datepicker tgl-penilaian"
                                         data-orientation="bottom" data-format="dd/mm/yyyy"
                                         data-options='@json(['endDate' => now()->format('d/m/Y'), 'format' => 'dd/mm/yyyy'])'
                                         placeholder="{{ __('Tgl Penilaian') }}" @if ($record->periode_penilaian)
                                      value="{{ $record->periode_penilaian->format('d/m/Y') }}"
                                      @endif>
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
                                            <span class="text-bold">{{ $loop->iteration }}.
                                                {{ $child->judul }}</span><br>
                                            <span>{{ $child->pertanyaan }}</span>
                                        </label>
                                        <div class="col-4 col-form-label parent-group"
                                            style="justify-content:center;align-items:center;">
                                            <div class="radio-inline" style="justify-content:center;">
                                                <label class="radio radio-success">
                                                    <input type="radio"
                                                        {{ !empty($jawaban) && $jawaban->value == '1' ? 'checked' : '' }}
                                                        name="question[0][{{ $child->id }}]" value="1" />
                                                    <span></span>
                                                    1
                                                </label>
                                                <label class="radio radio-success">
                                                    <input type="radio"
                                                        {{ !empty($jawaban) && $jawaban->value == '2' ? 'checked' : '' }}
                                                        name="question[0][{{ $child->id }}]" value="2" />
                                                    <span></span>
                                                    2
                                                </label>
                                                <label class="radio radio-success">
                                                    <input type="radio"
                                                        {{ !empty($jawaban) && $jawaban->value == '3' ? 'checked' : '' }}
                                                        name="question[0][{{ $child->id }}]" value="3" />
                                                    <span></span>
                                                    3
                                                </label>
                                                <label class="radio radio-success">
                                                    <input type="radio"
                                                        {{ !empty($jawaban) && $jawaban->value == '4' ? 'checked' : '' }}
                                                        name="question[0][{{ $child->id }}]" value="4" />
                                                    <span></span>
                                                    4
                                                </label>
                                                <label class="radio radio-success">
                                                    <input type="radio"
                                                        {{ !empty($jawaban) && $jawaban->value == '5' ? 'checked' : '' }}
                                                        name="question[0][{{ $child->id }}]" value="5" />
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
                        <label class="text-bold">Prestasi Lain Yang Perlu Dicatat</label>
                        <textarea name="prestasi" id="" cols="30" rows="3" class="form-control">{{ $record->prestasi }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="text-bold">Indisipliner Tertentu Yang Perlu Dicatat</label>
                        <textarea name="indisipliner" id="" cols="30" rows="3" class="form-control">{{ $record->indisipliner }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="text-bold">Saran dan Perbaikan</label>
                        <textarea name="saran" id="" cols="30" rows="3" class="form-control">{{ $record->saran }}</textarea>
                    </div>
                    {{-- <div class="form-group">
                        <label class="text-bold">Status Kontrak Kerja</label>
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
        </div>
        <br>
        <div class="row">
            @php
                $tipe = $module;
            @endphp
            <div class="col-6">
                @include('penilaian::tad.form.flow')
            </div>
            <div class="col">
                <div class="card card-custom gutter-b" style="margin-bottom:0; height:100%;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-lg-wrap flex-xl-nowrap p-4">
                            <div class="d-flex flex-column mr-5">
                                <a href="#" class="h4 text-dark text-hover-primary mb-5">
                                    Informasi
                                </a>
                                <p class="text-dark-50">
                                    Sebelum submit pastikan data Penilaian TAD tersebut sudah sesuai.
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
                                <x-btn-draft via="base-form--submit-page" submit="{{ $submit }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <script>
        const PERIODE_QUOTA = @json($QUOTA_PERIODE);
        $(document).ready(function() {
            $('.collapse').collapse();
            $(document)
                .on('change', '#unitKerjaCtrl', function(e) {
                    console.log('oke');
                    var me = $(this);
                    if (me.val()) {
                        $.ajax({
                            method: 'POST',
                            url: "{{ route('master.vendor.ajax') }}",
                            data: {
                                unit_kerja_id: $(this).val(),
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response, state, xhr) {
				console.log(response);
                                // let options = `<option value='' selected disabled></option>`;
                                let options =
                                    `<option disabled selected value=''>Pilih Vendor</option>`;
                                for (let item of response) {
                                    options += `<option value='${item.id}'>${item.nama}</option>`;
                                }
                                $('#vendorCtrl').select2('destroy');
                                $('#vendorCtrl').html(options);
                                $('#vendorCtrl').select2();
                                console.log(54, response, options);
                            },
                            error: function(a, b, c) {
                                console.log(a, b, c);
                            }
                        });
                    }
                    BasePlugin.initSelect2();
                })
                .on('change', '#vendorCtrl', function() {
                    console.log('oke');
                    var me = $(this);
                    if (me.val()) {
                        $.ajax({
                            method: 'POST',
                            url: '{{ route('personil.migrasi.getAjaxPenilaianTADCekJabatan') }}',
                            data: {
                                vendor_id: $('#vendorCtrl').val(),
                                unit_kerja_id: $('#unitKerjaCtrl').val(),
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response, state, xhr) {
                                console.log(response);
                                // let options = `<option disabled selected value=''>Pilih Personil</option>`;
                                // for (let item of response) {
                                //     options += `<option value='${item.id}' data-jabatan="${item.jabatan.NM_UNIT}" data-unit-kerja="${item.kepegawaian.unit_kerja.name}">${item.nama} (${item.kepegawaian.unit_kerja.name})</option>`;
                                // }
                                // $('#personilCtrl').select2('destroy');
                                // $('#personilCtrl').html(options);
                                // $('#personilCtrl').select2();
                                // console.log(54, response, options);
                                let options =
                                    `<option disabled selected value=''>Pilih Posisi</option>`;
                                for (let item of response) {
                                    options +=
                                        `<option value='${item.idunit}'>${item.NM_UNIT}</option>`;
                                }
                                $('#posisiCtrl').select2('destroy');
                                $('#posisiCtrl').html(options);
                                $('#posisiCtrl').select2();
                                console.log(54, response, options);
                            },
                            error: function(a, b, c) {
                                console.log(a, b, c);
                            }
                        });
                        var kepada = $('#toCtrl');
                        var urlOrigin = kepada.data('url-origin');
                        var urlParam = $.param({
                            with: $('#vendorCtrl').val(),
                        });
                        kepada.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' + urlParam)));
                        kepada.val(null).prop('disabled', false);
                        BasePlugin.initSelect2();
                    }
                })
                .on('change', '#posisiCtrl', function() {
                    let data = $(this).find('option:selected').data();
                    console.log(270, data);
                    var me = $(this);
                    if (me.val()) {
                        $.ajax({
                            method: 'POST',
                            url: '{{ route('personil.migrasi.getAjaxPenilaianTADCekPosisi') }}',
                            data: {
                                vendor_id: $('#vendorCtrl').val(),
                                unit_kerja_id: $('#unitKerjaCtrl').val(),
                                jabatan_id: $('#posisiCtrl').val(),
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response, state, xhr) {
                                console.log(response);
                                let options =
                                    `<option disabled selected value=''>Pilih Personil</option>`;
                                for (let item of response) {
                                    options +=
                                        `<option value='${item.id}'>${item.nama}</option>`;
                                }
                                $('#personilCtrl').select2('destroy');
                                $('#personilCtrl').html(options);
                                $('#personilCtrl').select2();
                                console.log(54, response, options);
                            },
                            error: function(a, b, c) {
                                console.log(a, b, c);
                            }
                        });
                    }
                })
                .on('change', '#yearCtrl, #semesterCtrl', function() {
                    let year = $('#yearCtrl').val();
                    let semester = $('#semesterCtrl').val();
                    let periodeExists = false;
                    if (year != '' && semester != '' && year && semester) {
                        for (let periode of PERIODE_QUOTA) {
                            if (['approved', 'completed', 'new-struct', 'new-position'].includes(periode
                                    .status) && periode.year == year && periode.semester == semester) {
                                periodeExists = true;
                            }
                        }
                        if (periodeExists) {} else {
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
        });
    </script>
@endpush
