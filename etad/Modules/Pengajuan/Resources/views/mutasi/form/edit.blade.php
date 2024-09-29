@php
    $user = auth()->user();
    $soLevelMap = [''];
    $so_id = $user->position->org_struct_id ?? null;
    // dd(json_decode($record));
@endphp
@extends('layouts.app')
@section('title', $title)
@section('buttons-after') @endsection
@push('styles')
    <style>
        .select2-selection__rendered#select2-unitKerjaAsalCtrl-container {
            color: #3F4254 !important;
        }
    </style>
@endpush
@section('content')
    <form action="{{ route($route . '.update', $record->id) }}" method="post">
        @csrf
        @method('put')
        <input type="hidden" name="is_update" value="1">
        <div class="card card-custom">
            <div class="card-header">
                <h5 class="card-title">
                    Pengajuan Mutasi
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
                            <label for="" class="col-4 text-bold">Unit Kerja Asal</label>
                            <div class="col-8 parent-group">
                                <select class="form-control base-plugin--select2" id="unitKerjaAsalCtrl"
                                    name="unit_kerja_asal" title="Unit Kerja Asal">
                                    @foreach ($struct_tujuan as $key => $group)
                                        <optgroup label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($key) }}">
                                            @foreach ($group as $val)
                                                <option value="{{ $val->id }}"
                                                    @if ($record->unit_kerja_asal == $val->id) selected @endif>{{ $val->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-4 text-bold">Unit Kerja Tujuan</label>
                            <div class="col-8 parent-group">
                                <select class="form-control base-plugin--select2" id="unitKerjaTujuanCtrl"
                                    name="unit_kerja_tujuan" title="Unit Kerja Tujuan">
                                    <option></option>
                                    @foreach ($struct_tujuan as $key => $group)
                                        <optgroup label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($key) }}">
                                            @foreach ($group as $val)
                                                <option value="{{ $val->id }}"
                                                    @if ($record->unit_kerja_tujuan == $val->id) selected @endif>{{ $val->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group row">
                            <label for="" class="col-4 text-bold">Tgl Pengajuan</label>
                            <div class="col-8">
                                <input type="text" name="tgl_pengajuan" class="form-control base-plugin--datepicker"
                                    data-language="en" data-format="dd/mm/yyyy" data-options='@json(['endDate' => now()->format('d/m/Y'), 'format' => 'dd/mm/yyyy'])'
                                    placeholder="{{ __('Tgl Pengajuan') }}"
                                    value="{{ $record->tgl_pengajuan->format('d/m/Y') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-4 text-bold">No. Surat</label>
                            <div class="col-8">
                                <input class="form-control" disabled value="{{ $record->no_tiket }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group row">
                            <label for="" class="col-2 col-form-label text-bold">Perihal</label>
                            <div class="col-10 parent-group">
                                <input type="text" name="perihal" class="form-control" placeholder="{{ __('Perihal') }}"
                                    value="{{ $record->perihal }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label text-bold">Kepada</label>
                    <div class="col-10 parent-group">
                        <select name="to[]" id="" class="form-control base-plugin--select2-ajax" title="Kepada"
                            data-url="{{ route('settings.user.ajax.selectAjaxUserDivisiHC') }}">
                            @foreach ($record->to as $to)
                                <option selected value="{{ $to->id }}">
                                    {{ $to->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 text-bold">Surat Permohonan</label>
                    <div class="col-10 parent-group">
                        <div class="custom-file">
                            <input type="hidden" name="uploads[]" class="uploaded" value="">
                            <input type="file" multiple data-name="uploads"
                                class="custom-file-input base-form--save-temp-files" data-container="parent-group"
                                data-max-size="2048" data-max-file="100" accept="*">
                            <label class="custom-file-label" for="file" style="color:#B5B5C3;font-weight:400;">{{ 'Pilih file' }}</label>
                        </div>
                        <div class="form-text text-muted">*Maksimal 2MB</div>
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
                <div class="form-group row">
                    <label for="" class="col-2 text-bold">Tembusan</label>
                    <div class="col-10 parent-group">
                        <select name="user_id[]" id="" multiple class="form-control base-plugin--select2-ajax"
                            title="Jabatan" data-url="{{ route('settings.user.ajax.selectAjaxUserDivisiHC') }}">
                            <option value=""></option>
                            @foreach ($record->cc as $item)
                                <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label text-bold">Menunjuk</label>
                    <div class="col-10 parent-group">
                        <textarea name="menunjuk" class="base-plugin--summernote-2" data-height="200" placeholder="{{ __('Menunjuk') }}">{!! $record->menunjuk !!}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label text-bold">Menindaklanjuti</label>
                    <div class="col-10 parent-group">
                        <textarea name="menindaklanjuti" class="form-control base-plugin--summernote-2" data-height="200"
                            placeholder="{{ __('Menindaklanjuti') }}">{!! $record->menindaklanjuti !!}</textarea>
                    </div>
                </div>
                <hr class="my-8">
                <div id="requirement-row">
                    @foreach ($record->pegawai as $pegawai)
                        <div class="form-row justify-content-left">
                            <div class="form-group col-3">
                                <label for="">Posisi TAD</label>
                                <select name="pegawai[{{ $loop->index }}][jabatan_id]"
                                    class="form-control base-plugin--select2 jabatan-ctrl" id="jabatan0Ctrl"
                                    data-placeholder="Pilih Posisi TAD">
                                    <option value=""></option>
                                    @foreach ($POSISI_TAD as $item)
                                        <option value="{{ $item->idunit }}"
                                            @if ($pegawai->jabatan->idunit == $item->idunit) selected @endif>{{ $item->NM_UNIT }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-3">
                                <label for="">Personil TAD</label>
                                <select class="form-control base-plugin--select2 personil-ctrl"
                                    data-placeholder="Pilih Personil TAD" id="personil0Ctrl"
                                    name="pegawai[{{ $loop->index }}][id]" onchange="selectNio(this)">
                                    <option value=""></option>
                                    @foreach (\Modules\Master\Entities\Tad\Tad::isPegawai()->where('jabatan_id', $pegawai->jabatan_id)->get() as $item)
                                        <option value="{{ $item->id }}"
                                            @if ($pegawai->id == $item->id) selected @endif>
                                            {{ $item->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-2">
                                <label for="">Tgl SK Mutasi</label>
                                <input class="form-control base-plugin--datepicker tgl-mutasi-ctrl" id="tglMutasi0Ctrl"
                                    data-language="en" data-format="dd/mm/yyyy"
                                    data-options='@json(['startDate' => now()])'
                                    name="pegawai[{{ $loop->index }}][tanggal_mutasi]" placeholder="Tgl SK Mutasi"
                                    value="{{ \Carbon\Carbon::createFromFormat('Y-m-d', $pegawai->pivot->tgl_mutasi)->format('d/m/Y') }}">
                            </div>
                            <div class="form-group col-2">
                                <label for="">Tgl Efektif</label>
                                <input class="form-control base-plugin--datepicker tgl-efektif-ctrl" id="tglEfektif0Ctrl"
                                    data-language="en" data-format="dd/mm/yyyy"
                                    data-options='@json(['startDate' => now()])'
                                    name="pegawai[{{ $loop->index }}][tanggal_efektif]" placeholder="Tgl Efektif"
                                    value="{{ \Carbon\Carbon::createFromFormat('Y-m-d', $pegawai->pivot->tgl_efektif)->format('d/m/Y') }}">
                            </div>
                            <div class="form-group col-1">
                                <label for="">&nbsp;</label>
                                @if ($loop->first)
                                    <button class="btn btn-icon btn-primary btn-circle btn-add-requirement"
                                        onclick="addRow(this)" type="button">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                @else
                                    <a href="javascript:;" onclick="deleteRow(this)"
                                        class="btn btn-icon btn-danger btn-circle"><i class="fas fa-trash"></i></a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-6">
                @include('pengajuan::mutasi.partials.flow')
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
                                    Sebelum submit pastikan data Pengajuan Mutasi tersebut sudah sesuai.
                                </p>
                            </div>
                            <div class="ml-lg-0 ml-xxl-6 ml-6 flex-shrink-0">
                                @php
                                    $menu = \Modules\Settings\Entities\Menu::where('code', $module)->first();
                                    $count = $menu->flows()->count();
                                    $submit = $count == 0 ? 'disabled' : 'enabled';
                                @endphp
                                <x-btn-draft via="base-form--submit-page" submit="{{ $submit }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <template id="requirement-form">
        <div class="form-row justify-content-left">
            <div class="form-group col-3">
                <select name="pegawai[@{{ : index }}][jabatan_id]"
                    class="form-control base-plugin--select2 jabatan-ctrl" data-placeholder="Posisi TAD">
                    <option value=""></option>
                    @foreach ($POSISI_TAD as $item)
                        <option value="{{ $item->idunit }}">{{ $item->NM_UNIT }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-3">
                <select name="pegawai[@{{ : index }}][id]" onchange="selectNio(this)"
                    class="form-control base-plugin--select2 personil-ctrl" data-placeholder="Personil TAD">
                    <option value=""></option>
                </select>
            </div>
            <div class="form-group col-2">
                <input class="form-control base-plugin--datepicker tgl-mutasi-ctrl" data-language="en"
                    data-format="dd/mm/yyyy" data-options='@json(['startDate' => now()])'
                    name="pegawai[@{{ : index }}][tanggal_mutasi]" placeholder="Tanggal Mutasi">
            </div>
            <div class="form-group col-2">
                <input class="form-control base-plugin--datepicker tgl-efektif-ctrl" data-language="en"
                    data-format="dd/mm/yyyy" data-options='@json(['startDate' => now()])'
                    name="pegawai[@{{ : index }}][tanggal_efektif]" placeholder="Tanggal Efektif">
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

        function countRow() {
            return $('.form-row:last').index() + 1;
        }

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

        function unitKerjaAsalChanged(exists = false) {
            if (exists) {
                $('select.jabatan-ctrl').prop('disabled', false);
                $('select.personil-ctrl').prop('disabled', false);
                $('.tgl-mutasi-ctrl').prop('disabled', false);
                $('.btn-add-requirement').prop('disabled', false);
            } else {
                // $('select.jabatan-ctrl').prop('disabled', true);
                // $('select.personil-ctrl').prop('disabled', true);
                // $('.tgl-mutasi-ctrl').prop('disabled', true);
                // $('.btn-add-requirement').prop('disabled', true);
            }
        }

        let unitKerjaAsal;
        let unitKerjaAsalText;
        $(document).ready(function() {
            unitKerjaAsalChanged();
            $(document)
                .on('change', '#unitKerjaAsalCtrl', function() {
                    let unitKerjaAsalCtrl = $('#unitKerjaAsalCtrl');
                    unitKerjaAsal = unitKerjaAsalCtrl.val();
                    unitKerjaAsalText = unitKerjaAsalCtrl.find('option:selected').text();
                    unitKerjaAsalChanged(Boolean(unitKerjaAsal));
                    $('#jabatan0Ctrl, .jabatan-ctrl').val('').change();
                    $('#personil0Ctrl, .personil-ctrl').val('').change();
                    $('#tglMutasi0Ctrl, .tgl-mutasi-ctrl').val('').change();

                    // ajax
                    let jabatan_id = $('.jabatan-ctrl').val();
                    let personilCtrl = $('.jabatan-ctrl').parent().parent().find('.personil-ctrl');
                    $.ajax({
                        method: 'GET',
                        url: '{{ route('personil.migrasi.getAjaxResignMutasi') }}',
                        data: {
                            jabatan_id: jabatan_id,
                            so_id: unitKerjaAsal,
                            form_type: 'mutasi',
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
                })
                .on('change', '.jabatan-ctrl', function() {
                    let jabatan_id = $(this).val();
                    let personilCtrl = $(this).parent().parent().find('.personil-ctrl');
                    console.log(176, personilCtrl);
                    $.ajax({
                        method: 'GET',
                        url: '{{ route('personil.migrasi.getAjaxResignMutasi') }}',
                        data: {
                            jabatan_id: jabatan_id,
                            so_id: unitKerjaAsal,
                            form_type: 'mutasi',
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
    </script>
@endpush
