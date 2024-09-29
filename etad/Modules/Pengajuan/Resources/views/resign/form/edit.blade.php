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
    // dd(22, json_decode($ORG_STRUCT['bod']));
@endphp
@extends('layouts.app')
@section('title', $title)
@section('buttons-after') @endsection
@section('content')
    <form action="{{ route($route . '.update', $pengajuan->id) }}" method="post">
        <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">
        @if ($user->isEhc)
            <input type="hidden" id="unitKerjaType" name="unit_kerja_type"
                value="{{ \Modules\Master\Entities\SO\OrgStruct::class }}">
        @else
            <input type="hidden" id="unitKerjaType" name="unit_kerja_type"
                value="{{ \Modules\Master\Entities\Vendor::class }}">
        @endif
        @method('put')
        @csrf
        <div class="card card-custom">
            <div class="card-header">
                <h5 class="card-title">
                    Pengajuan Resign
                </h5>
                <button aria-label="Close" class="close" data-dismiss="card"
                    onclick="location.href='{{ route($route . '.index') }}'" type="button">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group row">
                            <label for="" class="col-4 col-form-label text-bold">Unit Kerja</label>
                            <div class="col-8 parent-group">
                                <select class="form-control base-plugin--select2" id="unitKerjaCtrl" name="unit_kerja_id"
                                    title="Pilih Unit Kerja">
                                    @if($user->isVendor)
                                        @foreach ($ORG_STRUCT as $level => $group)
                                            <optgroup
                                                label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($level) }}">
                                                @foreach ($group as $val)
                                                    <option value="{{ $pengajuan->so->id }}">
                                                        {{ $pengajuan->so->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    @else
                                        @if ($user->cekDivisiHC()){
                                            @foreach ($ORG_STRUCT as $level => $group)
                                                @foreach ($group as $val)
                                                    @if ($loop->first)
                                                        <optgroup
                                                            label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($level) }}">
                                                    @endif
                                                    <option value="{{ $val->id }}"
                                                        @if ($val->id == $pengajuan->unit_kerja_id) selected @endif>{{ $val->name }}
                                                    </option>
                                                    @if ($loop->last)
                                                        </optgroup>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @else
                                            <option value="{{ $pengajuan->so->id }}"  selected>
                                                {{ $pengajuan->so->name }}
                                            </option>
                                        @endif
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group row">

                            <label for="" class="col-4 col-form-label text-bold">No. Surat</label>
                            <div class="col-md-8 parent-group">
                                <input class="form-control" value="{{ $pengajuan->no_tiket }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group row">
                            <label for="" class="col-4 col-form-label text-bold">Tgl Pengajuan</label>
                            <div class="col-8">
                                <input type="text" name="tgl_pengajuan" class="form-control base-plugin--datepicker"
                                    data-format="dd/mm/yyyy" data-options='@json(['endDate' => now()->format('d/m/Y'), 'format' => 'dd/mm/yyyy'])'
                                    placeholder="{{ __('Tgl Pengajuan') }}" @if ($pengajuan->tgl_pengajuan)
                                value="{{ $pengajuan->tgl_pengajuan->format('d/m/Y') }}"
                                @endif>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group row">
                            <label for="" class="col-4 col-form-label text-bold">Perihal</label>
                            <div class="col-8 parent-group">
                                <input type="text" name="perihal" class="form-control"
                                    value="{{ $pengajuan->perihal }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group row">
                            <label for="" class="col-2 col-form-label text-bold">Kepada</label>
                            <div class="col-10 parent-group">
                                <select name="to[]" id="kepada" class="form-control base-plugin--select2-ajax"
                                    title="Pilih Kepada"
                                    data-url="{{ route('settings.user.ajax', ['select' => 'hc_with_vendor']) }}">
                                    <option value=""></option>
				    @foreach ($pengajuan->to as $to)
                                        <option selected value="{{ $to->id }}">
                                            {{ $to->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label text-bold">Kalimat Pembuka</label>
                    <div class="col-10 parent-group">
                        <textarea name="pembukaan" class="base-plugin--summernote-2" data-height="200"
                            placeholder="{{ __('Kalimat Pembuka') }}">{!! $pengajuan->pembukaan !!}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label text-bold">Isi Surat</label>
                    <div class="col-10 parent-group">
                        <textarea name="isi_surat" class="form-control base-plugin--summernote-2" data-height="200"
                            placeholder="{{ __('Isi Surat') }}">{!! $pengajuan->isi_surat !!}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label text-bold">Surat Permohonan</label>
                    <div class="col-10 parent-group">
                        <div class="custom-file">
                            <input type="hidden" name="uploads[]" class="uploaded" value="">
                            <input type="file" multiple data-name="uploads"
                                class="custom-file-input base-form--save-temp-files" data-container="parent-group"
                                data-max-size="2048" data-max-file="100" accept="*">
                            <label class="custom-file-label" for="file" style="color:#B5B5C3;font-weight:400;">{{ 'Pilih file' }}</label>
                        </div>
                        <div class="form-text text-muted">*Maksimal 2MB</div>
                        @foreach ($pengajuan->files as $file)
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
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label text-bold">Tembusan</label>
                    <div class="col-10 parent-group">
                        <select name="user_id[]" id="" multiple class="form-control base-plugin--select2-ajax"
                            title="Pilih User"
                            data-url="{{ route('settings.user.ajax.selectAjaxUserDivisiHC') }}">
                            <option value=""></option>
                            @foreach ($pengajuan->cc as $item)
                                <option value="{{ $item->id }}" selected>
                                    {{ $item->name }} 
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr class="my-8">
                <div id="requirement-row">
                    @forelse ($pengajuan->pegawai as $key => $pegawai)
                        <div class="form-row justify-content-left">
                            <div class="form-group col-2">
                                <label for="">Posisi TAD</label>
                                <select name="pegawai[{{ $key }}][jabatan_id]"
                                    class="form-control base-plugin--select2 posisi-ctrl"
                                    data-placeholder="Posisi TAD">
                                    <option value=""></option>
                                    @foreach ($POSISI_TAD as $item)
                                        <option value="{{ $item->idunit }}"
                                            @if ($pegawai->jabatan_id == $item->idunit) selected @endif>
                                            {{ $item->NM_UNIT }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-2">
                                <label for="">Personil TAD</label>
                                <select name="pegawai[{{ $key }}][id]" onchange="selectNio(this)"
                                    class="form-control base-plugin--select2 personil-ctrl"
                                    data-placeholder="Personil TAD">
                                    <option value=""></option>
                                    @foreach (\Modules\Master\Entities\Tad\Tad::where('jabatan_id', $pegawai->jabatan_id)->isPegawai()->get() as $item)
                                        <option value="{{ $item->id }}"
                                            @if ($pegawai->id == $item->id) selected @endif>
                                            {{ $item->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-3">
                                <label for="">Alasan</label>
                                <select name="pegawai[{{ $key }}][alasan]" onchange="selectNio(this)"
                                    class="form-control base-plugin--select2-ajax"
                                    data-url="{{ route('master.reason-resign.ajax') }}" data-placeholder="Alasan">
                                    @if ($pegawai->pivot->alasan)
                                        @foreach ($reason as $dd)
                                            @if ($dd->id == $pegawai->pivot->alasan)
                                                <option value="{{ $pegawai->pivot->alasan }}" selected>
                                                    {!! $dd->alasan !!}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-2">
                                <label for="">Tanggal Resign</label>
                                <input type="text" name="pegawai[{{ $key }}][tanggal_resign]"
                                    class="form-control base-plugin--datepicker date_start" data-language="en"
                                    data-format="dd/mm/yyyy" data-options='@json(['startDate' => now(), 'endDate' => ''])'
                                    placeholder="Tanggal Resign"
                                    value="{{ \Carbon\Carbon::createFromFormat('Y-m-d', $pegawai->pivot->tanggal_resign)->format('d/m/Y') }}">
                            </div>
                            <div class="form-group col-2">
                                <label for="">Tanggal Efektif</label>
                                <input type="text" name="pegawai[{{ $key }}][tanggal_efektif]"
                                    class="form-control base-plugin--datepicker date_end" data-language="en"
                                    data-format="dd/mm/yyyy"
                                    data-options='@json(['startDate' => $pegawai->pivot->tanggal_resign, 'endDate' => ''])'
                                    placeholder="Tanggal Efektif"
                                    value="{{ \Carbon\Carbon::createFromFormat('Y-m-d', $pegawai->pivot->tanggal_efektif)->format('d/m/Y') }}">
                            </div>
                            <div class="form-group col-1">
                                <label for="">&nbsp;</label>
                                @if ($key == 0)
                                    <a href="javascript:;" onclick="addRow(this)"
                                        class="btn btn-icon btn-primary btn-circle"><i class="fas fa-plus"></i></a>
                                @else
                                    <a href="javascript:;"
                                        data-href="{{ route($route . '.pegawai.delete', $pegawai->pivot->id) }}"
                                        onclick="deleteRow(this)" class="btn btn-circle btn-icon btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="form-row justify-content-left">
                            <div class="form-group col-3">
                                <label for="">Posisi TAD</label>
                                <select name="pegawai[0][jabatan_id]"
                                    class="form-control base-plugin--select2 posisi-ctrl"
                                    data-placeholder="Posisi TAD">
                                    <option value=""></option>
                                    @foreach ($POSISI_TAD as $item)
                                        <option value="{{ $item->idunit }}">{{ $item->NM_UNIT }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-3">
                                <label for="">Personil TAD</label>
                                <select name="pegawai[0][id]" onchange="selectNio(this)"
                                    class="form-control base-plugin--select2 personil-ctrl"
                                    data-placeholder="Personil TAD">
                                    <option value=""></option>
                                    @foreach (\Modules\Master\Entities\Tad\Tad::isPegawai()->get() as $item)
                                        <option value="{{ $item->id }}">{{ $item->kepegawaian->nio }} -
                                            {{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-3">
                                <select name="pegawai[0][alasan]" onchange="selectNio(this)"
                                    class="form-control base-plugin--select2 alasan-ctrl" data-placeholder="Alasan">
                                    <option value="1">Keinginan Sendiri</option>
                                    <option value="2">Pihak Vendor/Unit Kerja</option>
                                </select>
                            </div>
                            <div class="form-group col-2">
                                <label for="">Tanggal Resign</label>
                                <input type="text" name="pegawai[0][tanggal_resign]"
                                    class="form-control base-plugin--datepicker date_start" data-language="en"
                                    data-format="dd/mm/yyyy" data-options='@json(['endDate' => ''])'
                                    placeholder="Tanggal Resign">
                            </div>
                            <div class="form-group col-1">
                                <label for="">&nbsp;</label>
                                <a href="javascript:;" onclick="addRow(this)"
                                    class="btn btn-icon btn-primary btn-circle"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <!-- <div class="col-container"> -->
            <div class="col-6">
                @include('pengajuan::resign.partials.flow')
            </div>
            <div class="col-6">
                <div class="card card-custom" style="height:100%;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-lg-wrap flex-xl-nowrap p-4">
                            <div class="d-flex flex-column mr-5">
                                <a href="#" class="h4 text-dark text-hover-primary mb-5">
                                    Informasi
                                </a>
                                <p class="text-dark-50">
                                    Sebelum submit pastikan data Pengajuan Resign tersebut sudah sesuai.
                                </p>
                            </div>
                            <div class="ml-lg-0 ml-xxl-6 ml-6 flex-shrink-0">
                                @php
                                    $menu = \Modules\Settings\Entities\Menu::where('code', $module)->first();
                                    $count = $menu->flows()->count();
                                    $submit = $count == 0 ? 'disabled' : 'enabled';
                                @endphp
                                <div style="display: none">
                                    <x-btn-back class="mr-2" url="{{ route($route . '.index') }}" />
                                </div>
                                <x-btn-draft id="myForm" via="base-form--submit-page" submit="{{ $submit }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- </div> -->
        </div>
    </form>

    <template id="requirement-form">
        <div class="form-row justify-content-left">
            <div class="form-group col-2">
                <select name="pegawai[@{{ : index }}][jabatan_id]"
                    class="form-control base-plugin--select2 posisi-ctrl" data-placeholder="Posisi TAD">
                    <option value=""></option>
                    @foreach ($POSISI_TAD as $item)
                        <option value="{{ $item->idunit }}">{{ $item->NM_UNIT }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-2">
                <select name="pegawai[@{{ : index }}][id]" onchange="selectNio(this)"
                    class="form-control base-plugin--select2 personil-ctrl" data-placeholder="Personil TAD">
                    <option value=""></option>
                </select>
            </div>
            <div class="form-group col-3">
                <select name="pegawai[@{{ : index }}][alasan]" onchange="selectNio(this)"
                    class="form-control base-plugin--select2-ajax alasan-ctrl"
                    data-url="{{ route('master.reason-resign.ajax') }}" data-placeholder="Alasan">
                </select>
            </div>
            <div class="form-group col-2">
                <input name="pegawai[@{{ : index }}][tanggal_resign]" class="form-control base-plugin--datepicker date_start"
                    data-language="en" data-format="dd/mm/yyyy" data-options='@json(['startDate' => now(), 'endDate' => ''])'
                    placeholder="Tanggal Resign">
            </div>
            <div class="form-group col-2">
                <input name="pegawai[@{{ : index }}][tanggal_efektif]"
                    data-options='@json(['startDate' => now(), 'endDate' => ''])'
                    class="form-control base-plugin--datepicker date_end" data-language="en" data-format="dd/mm/yyyy"
                    placeholder="Tanggal Efektif">
            </div>
            <div class="form-group col-1">
                <a href="javascript:;" onclick="deleteRow(this)" class="btn btn-icon btn-danger btn-circle"><i
                        class="fas fa-trash"></i></a>
            </div>
        </div>
    </template>
@endsection

@push('scripts')
    <script>
        $(function () {
            // initLetterDate();
            initDateRange();
            initDateResignRange();
        });

        var initDateRange = function () {
            $('form').on('changeDate', 'input.date_start', function (value) {
                var me = $(this);
                if (me.val()) {
                    var startDate = new Date(value.date.valueOf());
                    var date_end = me.closest('.form-row').find('input.date_end');
                    date_end.prop('disabled', false)
                            .val(me.val())
                            .datepicker('setStartDate', startDate)
                            .focus();
                }
            });
        }

        var initDateResignRange = function () {
            $('form').on('changeDate', 'input.tgl-pengajuan', function (value) {
                console.log(1);
                var me = $(this);
                if (me.val()) {
                    var startDate = new Date(value.date.valueOf());
                    var date_end = $('input.date_start');
                    date_end.prop('disabled', false)
                            .val(me.val())
                            .datepicker('setStartDate', startDate);
                }
            });
        }
    </script>
    <script>
        addRow = (elem) => {
            var row = $('#requirement-form').html();
            var index = $('.form-row:last').index();
            var tmpl = jsrender.templates(row);
            var resp = {
                'index': index + 1
            };
            var dom = tmpl.render(resp);
            $('#requirement-row').append(dom);
            console.log(resp);

            BasePlugin.initSelect2();
            BasePlugin.initDatepicker();
        }
        deleteRow = (elem) => {
            $(elem).parent().parent().remove();
            BasePlugin.initSelect2();
        }

        $(document).ready(function() {
            $(document).on('change', '.unitKerjaCtrl', function() {
                let so_id = $('#unitKerjaCtrl').val();
                let jabatan_id = $('.posisi-ctrl').val();
                let personilCtrl = $('.posisi-ctrl').parent().parent().find('.personil-ctrl');
                console.log(176, personilCtrl);
                $.ajax({
                    method: 'GET',
                    url: '{{ route('personil.migrasi.getAjaxResignMutasi') }}',
                    data: {
                        jabatan_id: jabatan_id,
                        form_type: 'resign',
                        so_id: so_id,
                    },
                    success: function(response, state, xhr) {
                        let options = `<option value='' selected disabled></option>`;
                        // let options = ``;
                        for (let item of response) {
                            options += `<option value='${item.id}'>${item.nama} (${item.kepegawaian.nio})</option>`;
                        }
                        personilCtrl.select2('destroy');
                        personilCtrl.html(options);
                        personilCtrl.select2();
                    },
                    error: function(a, b, c) {
                        console.log(a, b, c);
                    }
                });
            });
        });

        $(document).ready(function() {
            $(document).on('change', '.posisi-ctrl', function() {
                let so_id = $('#unitKerjaCtrl').val();
                let jabatan_id = $(this).val();
                let personilCtrl = $(this).parent().parent().find('.personil-ctrl');
                console.log(176, personilCtrl);
                $.ajax({
                    method: 'GET',
                    url: '{{ route('personil.migrasi.getAjaxResignMutasi') }}',
                    data: {
                        jabatan_id: jabatan_id,
                        form_type: 'resign',
                        so_id: so_id,
                    },
                    success: function(response, state, xhr) {
                        let options = `<option value='' selected disabled></option>`;
                        // let options = ``;
                        for (let item of response) {
                            options += `<option value='${item.id}'>${item.nama} (${item.kepegawaian.nio})</option>`;
                        }
                        personilCtrl.select2('destroy');
                        personilCtrl.html(options);
                        personilCtrl.select2();
                    },
                    error: function(a, b, c) {
                        console.log(a, b, c);
                    }
                });
            });
        });

        function selectNio(nio) {
            var totalNioSelected = $('select.personil-ctrl option:selected[value="' + nio.value + '"]').length;
            if (nio.value != '' && totalNioSelected > 1) {
                $.gritter.add({
                    title: 'Failed!',
                    text: 'Personil sudah di pilih!',
                    image: baseurl + '/assets/images/icon/ui/cross.png',
                    sticky: false,
                    time: '3000'
                });
                console.log($(nio).val());
                $(nio).val('').trigger('change');
            }
        }

	$('#kepada').on('change', function() {
    	    var selectedValue = $(this).val();
    	    console.log('Pilihan "kepada" telah berubah menjadi: ' + selectedValue);
    	    // Lakukan tindakan lain sesuai kebutuhan
	});
    </script>
@endpush
