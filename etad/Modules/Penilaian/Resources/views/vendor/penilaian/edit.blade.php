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
    <form action="{{ route('penilaian.vendor.update', $record->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="unit_kerja_id" value="{{ $record->unit_kerja_id }}">
        <input type="hidden" name="unit_kerja_type" value="{{ $record->unit_kerja_type }}">
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
                                    <label for="" class="col-md-4 col-form-label font-weight-bold">Unit
                                        Kerja</label>
                                    <div class="col-md-8">
                                        <select class="form-control base-plugin--select2" id="unitKerjaAsalCtrl"
                                            name="unit_kerja_id" title="Unit Kerja">
                                            <option></option>
                                            @if($user->cekDivisiHC())
                                                @foreach ($ORG_STRUCT as $key => $group)
                                                    <optgroup
                                                        label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($key) }}">
                                                        @foreach ($group as $val)
                                                            <option value="{{ $val->id }}" @if ($record->unitKerja->id == $val->id) selected @endif>
                                                                {{ $val->name }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            @else
                                                @foreach ($ORG_STRUCT as $key => $group)
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
                                    <label for="" class="col-md-4 col-form-label font-weight-bold">Vendor</label>
                                    <div class="col-md-8">
                                        <select name="vendor_id" id="vendorCtrl" class="form-control base-plugin--select2"
                                            title="Pilih Vendor" data-url="{{ route('master.vendor.ajaxGetByIdUnitKerja', ['unit_kerja_id' => $record->unitKerja->id]) }}">
                                            <option selected disabled></option>
                                            @foreach ($VENDOR as $item)
                                                <option value="{{ $item->id }}"
                                                    @if ($record->vendor_id == $item->id) selected @endif>{{ $item->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-4 col-form-label font-weight-bold">Nomor Surat</label>
                                    <div class="col-8 parent-group">
                                        <input class="form-control" name="no_surat" placeholder="{{ __('Nomor Surat') }}"
                                            value="{{ $record->no_surat }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="" class="col-md-4 col-form-label font-weight-bold">Periode</label>
                                    <div class="col-md-4">
                                        <input class="form-control base-plugin--datepicker-3" id="yearCtrl" name="tahun"
                                            data-options="{&quot;startDate&quot;:&quot;+0Y&quot;}"
                                            data-orientation="bottom" placeholder="Tahun"
                                            value="{{ $record->tahun }}">
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control base-plugin--select2" id="semesterCtrl" name="semester"
                                            title="Semester">
                                            <option disabled selected></option>
                                            <option value="Satu" @if ($record->semester == 'Satu') selected @endif>Satu
                                            </option>
                                            <option value="Dua" @if ($record->semester == 'Dua') selected @endif>Dua
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-4 col-form-label font-weight-bold">Tgl
                                        Penilaian</label>
                                    <div class="col-8">
                                        <input type="hidden" name="tanggal_sekarang" value="{{ now()->format('d/m/Y') }}">
                                        <input type="text" name="tgl_penilaian"
                                            class="form-control base-plugin--datepicker tgl-penilaian"
                                            data-orientation="bottom" data-format="dd/mm/yyyy"
                                            data-options='@json(['endDate' => now()->format('d/m/Y'), 'format' => 'dd/mm/yyyy'])'
                                            placeholder="{{ __('Tgl Penilaian') }}" @if ($record->tgl_penilaian)
                                        value="{{ $record->tgl_penilaian->format('d/m/Y') }}"
                                        @endif>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-4 col-form-label font-weight-bold">Kepada</label>
                                    <div class="col-8 parent-group">
                                        <select name="to" id="toCtrl" class="form-control base-plugin--select2-ajax"
                                            title="Kepada"
                                            data-url="{{ route('settings.user.ajax', ['with' => $record->vendor_id]) }}"
                                            data-url-origin="{{ route('settings.user.ajax') }}" id="toCtrl">
                                            <option value=""></option>
                                            @if ($record->toUser)
                                                <option value="{{ $record->to }}" selected>
                                                    {{ $record->toUser->name }}
                                                    ({{ $record->toUser->position->name ?? 'Vendor ' . $record->toUser->vendor->nama }})
                                                </option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed separator-border-3 mb-5 mt-3"></div>
                        @foreach ($pertanyaan as $item)
                            <div class="form-group row">
                                <label for="" class="col-8">{{ $item->pertanyaan }}</label>
                                <div class="col col-form-label parent-group"
                                    style="display: flex; flex-direction: row-reverse">
                                    <div class="radio-inline">
                                        <label class="radio radio-success">
                                            <input type="radio"
                                                {{ !empty($item->jawaban->value) && $item->jawaban->value == '1' ? 'checked' : '' }}
                                                name="question[{{ $item->id }}]" value="1" />
                                            <span></span>
                                            1
                                        </label>
                                        <label class="radio radio-success">
                                            <input type="radio"
                                                {{ !empty($item->jawaban->value) && $item->jawaban->value == '2' ? 'checked' : '' }}
                                                name="question[{{ $item->id }}]" value="2" />
                                            <span></span>
                                            2
                                        </label>
                                        <label class="radio radio-success">
                                            <input type="radio"
                                                {{ !empty($item->jawaban->value) && $item->jawaban->value == '3' ? 'checked' : '' }}
                                                name="question[{{ $item->id }}]" value="3" />
                                            <span></span>
                                            3
                                        </label>
                                        <label class="radio radio-success">
                                            <input type="radio"
                                                {{ !empty($item->jawaban->value) && $item->jawaban->value == '4' ? 'checked' : '' }}
                                                name="question[{{ $item->id }}]" value="4" />
                                            <span></span>
                                            4
                                        </label>
                                        <label class="radio radio-success">
                                            <input type="radio"
                                                {{ !empty($item->jawaban->value) && $item->jawaban->value == '5' ? 'checked' : '' }}
                                                name="question[{{ $item->id }}]" value="5" />
                                            <span></span>
                                            5
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="separator separator-dashed separator-border-3 mb-5 mt-3"></div>
                        <div class="form-group">
                            <label for="" class="text-bold">Kesimpulan</label>
                            <div class="row">
                                <div class="col-5 parent-group">
                                    <select name="kesimpulan" id="" class="form-control base-plugin--select2"
                                        title="Pilih Kesimpulan">
                                        <option selected disabled></option>
                                        <option value="Dapat Diperpanjang"
                                            {{ $record->kesimpulan == 'Dapat Diperpanjang' ? 'selected' : '' }}>Dapat
                                            Diperpanjang
                                        </option>
                                        <option value="Dipertimbangkan"
                                            {{ $record->kesimpulan == 'Dipertimbangkan' ? 'selected' : '' }}>
                                            Dipertimbangkan</option>
                                        <option value="Tidak Diperpanjang"
                                            {{ $record->kesimpulan == 'Tidak Diperpanjang' ? 'selected' : '' }}>Tidak
                                            Diperpanjang
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="text-bold">Kritik Untuk Vendor Tenaga Alih Daya</label>
                            <textarea name="kritik" id="" cols="30" rows="3" class="form-control" placeholder="Kritik">{{ $record->kritik }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="" class="text-bold">Saran Untuk Vendor Tenaga Alih Daya</label>
                            <textarea name="saran" id="" cols="30" rows="3" class="form-control" placeholder="Saran">{{ $record->saran }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                @php
                    $tipe = $module;
                @endphp
                <div class="col-6">
                    @include('penilaian::vendor.penilaian.flow')
                </div>
                <div class="col-6">
                    <div class="card card-custom gutter-b" style="margin-bottom:0; height:100%;">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between flex-lg-wrap flex-xl-nowrap p-4">
                                <div class="d-flex flex-column mr-5">
                                    <a href="#" class="h4 text-dark text-hover-primary mb-5">
                                        Informasi
                                    </a>
                                    <p class="text-dark-50">
                                        Sebelum submit pastikan data Penilaian Vendor tersebut sudah sesuai.
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
        </div>
    </form>
@endsection
@push('scripts')
    <script>
        const PERIODE_QUOTA = @json($QUOTA_PERIODE);
        $('#modal .modal-dialog-right-bottom')
            .addClass('modal-xl')
            .removeClass('modal-dialog-right-bottom');
        $(document).ready(function() {
            $('.collapse').collapse();
        });
        $('.card-body')
            .on('change', '#unitKerjaAsalCtrl', function(e) {
                var me = $(this);
                if (me.val()) {
                    $.ajax({
                        method: 'POST',
                        // headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content'), '_method': 'post'},
                        url: '{{ route('master.vendor.ajax') }}',
                        data: {
                            unit_kerja_id: $(this).val(),
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response, state, xhr) {
                            console.log(response);
                            // let options = `<option value='' selected disabled></option>`;
                            let options = `<option disabled selected value=''>Pilih Vendor</option>`;
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
            .on('change', '#vendorCtrl', function(e) {
                var me = $(this);
                if (me.val()) {
                    var kepada = $('#toCtrl');
                    var urlOrigin = kepada.data('url-origin');
                    var urlParam = $.param({
                        with: me.val(),
                    });
                    kepada.data('url', decodeURIComponent(decodeURIComponent(urlOrigin + '?' + urlParam)));
                    kepada.val(null).prop('disabled', false);
                }
                BasePlugin.initSelect2();
            })
            .on('change', '#yearCtrl, #semesterCtrl', function() {
                    let year = $('#yearCtrl').val();
                    let semester = $('#semesterCtrl').val();
                    let periodeExists = false;
                    if (year != '' && semester != '' && year && semester) {
                        for (let periode of PERIODE_QUOTA) {
                            if (['approved', 'completed', 'new-struct', 'new-position'].includes(periode
                                    .status) && periode
                                .year == year && periode.semester == semester) {
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
                });;
        // $('#vendorCtrl').trigger('change');
    </script>
@endpush
