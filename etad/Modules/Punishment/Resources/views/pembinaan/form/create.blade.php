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
    <form action="{{ route($route . '.store') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="card card-custom">
            <div class="card-header">
                <h4 class="card-title">{{ $title }}</h4>
                <button aria-label="Close" class="close" data-dismiss="modal"
                    onclick="location.href='{{ url()->previous() }}'" type="button">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        {{-- <div class="form-group row">
                            <label for="" class="col-4 col-form-label text-bold">Nomor Surat</label>
                            <div class="col-8 parent-group">
                                <input class="form-control" name="no_surat" placeholder="{{ __('Nomor Surat') }}">
                            </div>
                        </div> --}}
                        <div class="form-group row">
                            <label class="col-4 col-form-label text-bold">Unit Kerja</label>
                            <div class="col-8 parent-group">
                                <select name="unit_kerja_id" id="unitKerjaCtrl" class="form-control base-plugin--select2"
                                    title="Unit Kerja">
                                    <option></option>
                                    @if ($user->cekDivisiHC())
                                        @foreach ($ORG_STRUCT as $key => $group)
                                            <optgroup
                                                label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($key) }}">
                                                @foreach ($group as $val)
                                                    <option {{ ($user->position->org_struct_id ?? '') === $val->id ? 'selected' : '' }}
                                                        value="{{ $val->id }}">{{ $val->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    @else
                                        @foreach ($ORG_STRUCT_2 as $key => $group)
                                            @if ($group->count() != 0)
                                                <optgroup
                                                    label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($key) }}">
                                                    @foreach ($group as $val)
                                                        <option value="{{ $val->id }}">{{ $val->name }}</option>
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
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-4 col-form-label text-bold">Posisi TAD</label>
                            <div class="col-8 parent-group">
                                <select name="posisi_id" id="posisiCtrl" class="form-control base-plugin--select2-ajax"
                                    data-url="{{ route('personil.migrasi.getAjaxPenilaianTADCekJabatan') }}"
                                    title="Posisi TAD">
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label text-bold">Personil TAD</label>
                            <div class="col-8 parent-group">
                                <select name="tad_id" id="personilCtrl" class="form-control base-plugin--select2-ajax"
                                    data-url="{{ route('personil.migrasi.getAjaxPenilaianTADCekPosisi') }}"
                                    title="Personil TAD">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group row">
                            <label for="" class="col-3 col-form-label text-bold">Kepada</label>
                            <div class="col-9 parent-group">
                                <select name="to" id="toCtrl" class="form-control base-plugin--select2-ajax" data-url="{{ route('settings.user.ajax.selectAjaxUserDivisiHC') }}"
                                    data-url-origin="{{ route('settings.user.ajax.selectAjaxUserDivisiHC') }}" title="Kepada">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-3 col-form-label text-bold">Jenis Pembinaan</label>
                            <div class="col parent-group">
                                <select class="form-control base-plugin--select2" name="jenis_id" id=""
                                    title="Jenis Pembinaan">
                                    <option></option>
                                    @foreach (\App\Entities\EHC\JenisPunishment::pembinaan()->get() as $item)
                                        <option value="{{ $item->sandi }}">{{ $item->Lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row content-filter-date">
                            <label class="col-3 col-form-label text-bold">Tanggal</label>
                            <div class="col-9">
                                <div class="row">
                                    <div class="parent-group col-6">
                                        <input type="text" name="tanggal_mulai" data-post="date_start"
                                            class="form-control filter-control base-plugin--datepicker date_start"
                                            data-format="dd/mm/yyyy" data-options='@json([
                                                'format' => 'dd/mm/yyyy',
                                                'startDate' => '',
                                                'endDate' => now()->format('d/m/Y')
                                            ])'
                                            placeholder="Mulai">
                                    </div>
                                    <div class="parent-group col-6">
                                        <input type="text" data-post="date_end"
                                            class="form-control filter-control base-plugin--datepicker date_end"
                                            name="tanggal_selesai" placeholder="Selesai" data-format="dd/mm/yyyy"
                                            data-options='@json([
                                                'format' => 'dd/mm/yyyy',
                                                'startDate' => '',
                                            ])'>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-3 col-form-label text-bold">No / Tanggal SK </label>
                            <div class="col-9 parent-group">
                                <div class="row">
                                    <div class="parent-group col-md-6">
                                        <input class="form-control" type="text" name="sk" placeholder="No SK">
                                    </div>
                                    <div class="parent-group col-md-6">
                                        <input class="form-control base-plugin--datepicker-1" data-language="en"
                                            data-format="dd/mm/yyyy" data-options='@json(['endDate' => now()->format('d/m/Y'), 'format' => 'dd/mm/yyyy'])' id="tglSkCtrl"
                                            name="tanggal_sk" placeholder="Tanggal SK">
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
                            title="Pilih Jabatan"
                            data-url="{{ route('settings.user.ajax.selectAjaxUserDivisiHC') }}">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label text-bold">Deskripsi</label>
                    <div class="col parent-group">
                        <textarea class="form-control" name="eviden" placeholder="Deskripsi" id="" cols="30"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label text-bold" for="berkas">Lampiran</label>
                    <div class="col parent-group">
                        <div class="custom-file">
                            <input type="hidden" name="uploads[uploaded]" class="uploaded" value="">
                            <input type="file" multiple data-name="uploads"
                                class="custom-file-input base-form--save-temp-files" data-container="parent-group"
                                data-max-size="2048" data-max-file="100" accept="*">
                            <label class="custom-file-label" for="file" style="color:#B5B5C3;font-weight:400;">{{ 'Pilih file' }}</label>
                        </div>
                        <div class="form-text text-muted">*Maksimal 2MB</div>
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
                                    Sebelum submit pastikan data Reward tersebut sudah sesuai.
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
        $(function() {
            initDateStart();
        });
        var initDateStart = function() {
            $('.content-filter-date').on('changeDate', 'input.date_start', function(value) {
                var me = $(this);
                if (me.val()) {
                    var startDate = new Date(value.date.valueOf());
                    var date_end = $('input.date_end');
                    date_end.prop('disabled', false)
                        .val(me.val())
                        .datepicker('setStartDate', startDate)
                        .focus();
                }
            });
        }

        $(document).ready(function() {
            const MONTH_BY_NAME = {
                'January': '01',
                'February': '02',
                'March': '03',
                'April': '04',
                'May': '05',
                'June': '06',
                'July': '07',
                'August': '08',
                'September': '09',
                'Oktober': '10',
                'November': '11',
                'Desember': '12',
            };
            $(document)
                .on('change', '#unitKerjaCtrl', function(e) {
                    console.log('oke');
                    var me = $(this);
                    if (me.val()) {
                        $.ajax({
                            method: 'POST',
                            url: '{{ route('master.vendor.ajax') }}',
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
                                vendor_id: $(this).val(),
                                unit_kerja_id: $('#unitKerjaCtrl').val(),
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response, state, xhr) {
                                console.log(response);
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
                                form: 'pembinaan',
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
                                    `<option disabled selected value=''>Pilih Personil</option>`;
                                for (let item of response) {
                                    options +=
                                        `<option value='${item.id}'>${item.nama} (${item.kepegawaian.nio})</option>`;
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
                });
            $('#unitKerjaCtrl').trigger('change');
        });
    </script>
@endpush
