@php
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
@section('buttons-after') @endsection
@section('content')
    <style>
        .disabled.day {
            color: #7E8299 !important;
        }

        .day {
            color: #3F4254 !important;
        }
    </style>
    <form action="{{ route($route . '.store') }}" method="post">
        @csrf
        <input class="status" id="status" name="status" type="hidden" value="0">
        <input type="hidden" id="levelCtrl" name="level">
        <div class="card card-custom">
            <div class="card-header">
                <h5 class="card-title">
                    Pengajuan TAD
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
                                <select class="form-control base-plugin--select2 show-tick filter-control" name="so_id"
                                    data-placeholder="Unit Kerja" id="tipeStrukturCtrl">
                                    <option value="">Unit Kerja</option>
                                    @if ($user->cekDivisiHC() || !$user->position_id)
                                        @foreach ($ORG_STRUCT as $level => $group)
                                            @foreach ($group as $val)
                                                @if ($loop->first)
                                                    <optgroup
                                                        label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($level) }}">
                                                @endif
                                                <option data-level="{{ $level }}" value="{{ $val->id }}"
                                                    @if ($val->id == $user->position->struct->id) selected @endif>
                                                    {{ $val->name }}</option>
                                                @if ($loop->last)
                                                    </optgroup>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @elseif($user->position_id)
                                        <option data-level='{{ $user->position->struct->level }}'
                                            value="{{ $user->position->struct->id }}" selected>
                                            {{ $user->position->struct->name }}
                                        </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group row">
                            <label for="" class="col-4 col-form-label text-bold">Pengajuan Untuk</label>
                            <div class="col-4 parent-group">
                                <input class="form-control base-plugin--datepicker-3" id="yearCtrl" name="year"
                                    placeholder="Tahun" data-orientation="bottom">
                            </div>
                            <div class="col-4 parent-group">
                                <select class="form-control base-plugin--select2 show-tick" id="semesterCtrl"
                                    name="semester" data-placeholder="Semester">
                                    <option value="">Pilih Semester</option>
                                    <option value="Satu">Satu</option>
                                    <option value="Dua">Dua</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group row">
                            <label for="" class="col-4 col-form-label text-bold">Nomor Surat</label>
                            <div class="col-8 parent-group">
                                <input class="form-control" disabled value="Generated By System" style="font-style: italic">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group row">
                            <label for="" class="col-4 col-form-label text-bold">Tanggal Pengajuan</label>
                            <div class="col-8 parent-group">
                                <input type="text" name="tgl_pengajuan"
                                    class="form-control base-plugin--datepicker tgl-pengajuan" data-orientation="bottom"
                                    data-format="dd/mm/yyyy" data-options='@json(['endDate' => now()->format('d/m/Y'), 'format' => 'dd/mm/yyyy'])'
                                    placeholder="{{ __('Tgl Pengajuan') }}">
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
                                    value="Pengajuan Tenaga Alih Daya">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label text-bold">Kepada</label>
                    <div class="col-10 parent-group">
                        <select name="to[]" id="" class="form-control base-plugin--select2-ajax" title="Jabatan"
                            data-url="{{ route('settings.user.ajax.selectAjaxUserDivisiHC') }}">
                            <option value=""></option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-2 col-form-label text-bold">Surat Permohonan</label>
                    <div class="col-10 parent-group">
                        <div class="custom-file parent-group">
                            <input type="hidden" name="uploads_sp[uploaded]" class="uploaded" value="">
                            <input type="file" multiple data-name="uploads_sp"
                                class="custom-file-input base-form--save-temp-files form-control"
                                data-container="parent-group" data-max-size="2048" data-max-file="100" accept="*">
                            <label class="custom-file-label" for="file"
                                style="color:#B5B5C3;font-weight:400;">{{ 'Pilih file' }}</label>
                        </div>
                        <div class="form-text text-muted">*Maksimal 2MB</div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label text-bold">SO (Struktur Organisasi)</label>
                    <div class="col-10 parent-group">
                        <div class="custom-file parent-group">
                            <input type="hidden" name="uploads_so[uploaded]" class="uploaded" value="">
                            <input type="file" multiple data-name="uploads_so"
                                class="custom-file-input base-form--save-temp-files" data-container="parent-group"
                                data-max-size="2048" data-max-file="100" accept="*" required>
                            <label class="custom-file-label" for="file"
                                style="color:#B5B5C3;font-weight:400;">{{ 'Pilih file' }}</label>
                        </div>
                        <div class="form-text text-muted">*Maksimal 2MB</div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label text-bold">Kalimat Pembuka</label>
                    <div class="col-10 parent-group">
                        <textarea name="pembukaan" class="base-plugin--summernote-2" data-height="200"
                            placeholder="{{ __('Kalimat Pembuka') }}"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label text-bold">Kalimat Penutup</label>
                    <div class="col-10 parent-group">
                        <textarea name="penutupan" class="form-control base-plugin--summernote-2" data-height="200"
                            placeholder="{{ __('Kalimat Penutup') }}"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label text-bold">Tembusan</label>
                    <div class="col-10 parent-group">
                        <select name="cc[]" id="" multiple class="form-control base-plugin--select2-ajax"
                            title="User HC" data-url="{{ route('settings.user.ajax.selectAjaxUserDivisiHC') }}">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                <hr class="my-8">
                <div id="requirement-row">
                    <div class="form-row justify-content-center">
                        <div class="form-group col-3">
                            <label for="">Kategori TAD</label>
                            <select name="kategori_id[0]" row="0" onchange="select(0, this, 'kategori')"
                                class="kategori-ctrl form-control base-plugin--select2" data-placeholder="Kategori TAD">
                                <option value=""></option>
                                @foreach ($KATEGORI_VENDOR as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-3">
                            <label for="">Posisi TAD</label>
                            <select name="jabatan_id[0]" row="0" onchange="select(0, this)"
                                class="jabatan-ctrl form-control base-plugin--select2" data-placeholder="Posisi TAD">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="form-group col-1">
                            <label for="">JK</label>
                            <select name="jenis_kelamin[0]" row="0" onchange="select(0, this)"
                                class="form-control base-plugin--select2 gender-ctrl" data-placeholder="JK">
                                <option value=""></option>
                                <option value="LP">ALL</option>
                                <option value="L">L</option>
                                <option value="P">P</option>
                            </select>
                        </div>
                        <div class="form-group col-1">
                            <label for="">Jumlah</label>
                            <input type="text" class="form-control masking-jumlah jumlah-ctrl" name="jumlah[0]"
                                row="0" onchange="select(0, this)" placeholder="Jumlah">
                        </div>
                        <div class="form-group col-3">
                            <label for="">Vendor</label>
                            <select name="vendor_id[0]" row="0" onchange="select(0, this)"
                                class="form-control base-plugin--select2 vendor-ctrl" data-placeholder="Vendor">
                                <option value=""></option>
                                @foreach ($VENDOR as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-1">
                            <label for="">&nbsp;</label>
                            <button onclick="addRow(this)" class="btn btn-icon btn-primary btn-circle btn-add-requirement"
                                type="button">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-6">
                @include('pengajuan::tad.form.flow')
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
                                    Sebelum submit pastikan data Pengajuan TAD tersebut sudah sesuai.
                                </p>
                            </div>
                            <div class="ml-lg-0 ml-xxl-6 ml-6 flex-shrink-0">
                                {{-- @php
                                    $menu = \Modules\Settings\Entities\Menu::where('module', $module)->first();
                                    $count = $menu->flows()->count();
                                    $submit = $count == 0 ? 'disabled' : 'enabled';
                                @endphp --}}
                                <div style="display: none">
                                    <x-btn-back class="mr-2" url="{{ route($route . '.index') }}" />
                                </div>
                                <x-btn-draft via="base-form--submit-page" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <template id="requirement-form">
        <div class="form-row justify-content-center">
            <div class="form-group col-3">
                <select name="kategori_id[@{{ : index }}]" row="@{{ : index }}"
                    onchange="select(@{{ : index }}, this, 'kategori')"
                    class="kategori-ctrl form-control base-plugin--select2" data-placeholder="Kategori TAD">
                    <option value=""></option>
                    @foreach ($KATEGORI_VENDOR as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-3">
                <select name="jabatan_id[@{{ : index }}]" row="@{{ : index }}"
                    onchange="select(@{{ : index }}, this)" class="jabatan-ctrl form-control base-plugin--select2"
                    data-placeholder="Posisi TAD">
                    <option value=""></option>
                </select>
            </div>
            <div class="form-group col-1">
                <select name="jenis_kelamin[@{{ : index }}]" row="@{{ : index }}"
                    onchange="select(@{{ : index }}, this)" class="form-control base-plugin--select2 gender-ctrl"
                    data-placeholder="JK">
                    <option value=""></option>
                    <option value="LP">All</option>
                    <option value="L">L</option>
                    <option value="P">P</option>
                </select>
            </div>
            <div class="form-group col-1">
                <input type="text" class="form-control masking-jumlah jumlah-ctrl"
                    name="jumlah[@{{ : index }}]" row="@{{ : index }}"
                    onchange="select(@{{ : index }}, this)" data-placeholder="Jumlah">
            </div>
            <div class="form-group col-3">
                <select name="vendor_id[@{{ : index }}]" id="" row="@{{ : index }}"
                    onchange="select(@{{ : index }}, this)" class="form-control base-plugin--select2 vendor-ctrl"
                    data-placeholder="Vendor">
                    <option value=""></option>
                    @foreach ($VENDOR as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
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
        const PERIODE_QUOTA = @json($QUOTA_PERIODE);
        addRow = (elem) => {
            // var row = $('#requirement-form').html();
            var index = $('.form-row:last').index();
            var resp = {
                'index': index + 1
            };
            var key = index + 1;
            var row = `
                <div class="form-row justify-content-center">
                    <div class="form-group col-3">
                        <select name="kategori_id[` + key + `]" row="` + key + `"
                            onchange="select(` + key + `, this, 'kategori')"
                            class="kategori-ctrl form-control base-plugin--select2" data-placeholder="Kategori TAD">
                            <option value=""></option>
                            @foreach ($KATEGORI_VENDOR as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-3">
                        <select name="jabatan_id[` + key + `]" row="` + key + `"
                            onchange="select(` + key + `, this)" class="jabatan-ctrl form-control base-plugin--select2"
                            data-placeholder="Posisi TAD">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="form-group col-1">
                        <select name="jenis_kelamin[` + key + `]" row="` + key + `"
                            onchange="select(` + key + `, this)" class="form-control base-plugin--select2 gender-ctrl"
                            data-placeholder="JK">
                            <option value=""></option>
                            <option value="LP">All</option>
                            <option value="L">L</option>
                            <option value="P">P</option>
                        </select>
                    </div>
                    <div class="form-group col-1">
                        <input type="text" class="form-control masking-jumlah jumlah-ctrl"
                            name="jumlah[` + key + `]" row="` + key + `"
                            onchange="select(` + key + `, this)" placeholder="Jumlah">
                    </div>
                    <div class="form-group col-3">
                        <select name="vendor_id[` + key + `]" id="" row="` + key + `"
                            onchange="select(` + key + `, this)" class="form-control base-plugin--select2 vendor-ctrl"
                            data-placeholder="Vendor">
                            <option value=""></option>
			    @foreach ($VENDOR as $item)
                               <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="form-group col-1">
                        <a href="javascript:;" onclick="deleteRow(this)" class="btn btn-icon btn-danger btn-circle"><i
                                class="fas fa-trash"></i></a>
                    </div>
                </div>
			`;
            var tmpl = jsrender.templates(row);

            var dom = tmpl.render(resp);
            // console.log(163, dom);
            $('#requirement-row').append(dom);
            BasePlugin.initSelect2();
            $(".masking-jumlah").inputmask({
                "mask": "9",
                "repeat": 3,
                "greedy": false
            });
        }
        deleteRow = (elem) => {
            $(elem).parent().parent().remove();
        }

        $(".masking-jumlah").inputmask({
            "mask": "9",
            "repeat": 3,
            "greedy": false
        });

        function select(row, input, ctrl) {
            kategori_id = $('select[name^="kategori_id"][row="' + row + '"]').val();
            jabatanCtrl = $('select[name^="jabatan_id"][row="' + row + '"]');
            jabatan_id = jabatanCtrl.val();
            vendorCtrl = $('select[name^="vendor_id"][row="' + row + '"]');
            vendor_id = vendorCtrl.val();
            jenis_kelamin = $('select[name^="jenis_kelamin"][row="' + row + '"]').val();

            if (ctrl == 'kategori') {
                $.ajax({
                    method: 'GET',
                    url: '{{ url('master/jabatan-tad/ajax') }}',
                    data: {
                        kategori_id: kategori_id
                    },
                    success: function(response, state, xhr) {
                        // let options = `<option value='' selected disabled></option>`;
                        let options = ``;
                        for (let item of response) {
                            options += `<option value='${item.idunit}'>${item.NM_UNIT}</option>`;
                        }
                        jabatanCtrl.select2('destroy');
                        jabatanCtrl.html(options);
                        jabatanCtrl.select2();
                    },
                    error: function(a, b, c) {
                        console.log(a, b, c);
                    }
                });
            }

            var jabatanSelected_total = $('select:not([row="' + row + '"])[name^="jabatan_id"] option:selected[value="' +
                jabatan_id + '"]').length;
            var vendorSelected_total = $('select:not([row="' + row + '"])[name^="vendor_id"] option:selected[value="' +
                vendor_id + '"]').length;
            var jenisKelaminSelected_total = $('select:not([row="' + row +
                '"])[name^="jenis_kelamin"] option:selected[value="' + jenis_kelamin + '"]').length;

            var validateEmpty = kategori_id != '' && jabatan_id != '' && vendor_id != '' && jenis_kelamin != '' ? true :
                false;
            var validateTotal = jabatanSelected_total && jenisKelaminSelected_total && vendorSelected_total ? true : false;
            console.log(300, jabatanSelected_total, jenisKelaminSelected_total, vendorSelected_total);

            if (validateEmpty && validateTotal) {
                $.gritter.add({
                    title: 'Failed!',
                    text: 'Jabatan dan jenis kelamin di vendor tersebut sudah di pilih!',
                    image: baseurl + '/assets/images/icon/ui/cross.png',
                    sticky: false,
                    time: '3000'
                });
                $(input).val('').trigger('change');
            }
        }

        function periodeChanged(exists = false) {
            // if (exists) {
            //     $('select.kategori-ctrl').prop('disabled', false);
            //     $('select.jabatan-ctrl').prop('disabled', false);
            //     $('select.gender-ctrl').prop('disabled', false);
            //     $('.jumlah-ctrl').prop('disabled', false);
            //     $('select.vendor-ctrl').prop('disabled', false);
            //     $('.btn-add-requirement').prop('disabled', false);
            //     $('#submitBtn').removeClass('disabled');
            //     $('#submitBtn').prop('disabled', false);
            // } else {
            //     $('select.kategori-ctrl').prop('disabled', true);
            //     $('select.jabatan-ctrl').prop('disabled', true);
            //     $('select.gender-ctrl').prop('disabled', true);
            //     $('.jumlah-ctrl').prop('disabled', true);
            //     $('select.vendor-ctrl').prop('disabled', true);
            //     $('.btn-add-requirement').prop('disabled', true);
            //     $('#submitBtn').addClass('disabled');
            //     $('#submitBtn').prop('disabled', true);
            // }
        }

        $(document).ready(function() {
            periodeChanged();
            $(document)
                .on('change', '#tipeStrukturCtrl, #yearCtrl, #semesterCtrl', function() {
                    let year = $('#yearCtrl').val();
                    let semester = $('#semesterCtrl').val();
                    let level = $('#tipeStrukturCtrl option:selected').data('level');
                    $('#levelCtrl').val(level);
                    let periodeExists = false;
                    if (year != '' && semester != '' && year && semester) {
                        for (let periode of PERIODE_QUOTA) {
                            if (['approved', 'completed', 'new-struct', 'new-position'].includes(periode
                                    .status) && periode.year == year && periode.semester == semester && periode
                                .level == level) {
                                periodeExists = true;
                            }
                        }
                        if (periodeExists) {
                            periodeChanged(true);
                        } else {
                            periodeChanged();
                            $.gritter.add({
                                title: 'Failed!',
                                text: 'Periode Quota Tidak Tersedia.',
                                image: baseurl + '/assets/images/icon/ui/cross.png',
                                sticky: false,
                                time: '3000'
                            });
                            // $('#yearCtrl').val('').trigger('change');
                            $('#semesterCtrl').val('').trigger('change');
                        }
                    }
                });
        });

        $('#bodContainer').hide();
        $('#viceContainer').hide();
        $('#divisionContainer').hide();
        $('#departemenContainer').hide();
        $('#cabangContainer').hide();
        $('#Container').hide();
        $('#capemContainer').hide();
        $('#kasContainer').hide();

        $('#tipeStrukturCtrl').on('change', function() {
            let so_id = $('#tipeStrukturCtrl').val();
            $.ajax({
                method: 'GET',
                url: '{{ route('master.so.cek-level-tipe-struktur') }}',
                data: {
                    so_id: so_id,
                },
                success: function(response, state, xhr) {
                    console.log(535, response.level);

                    $('#flowContainer').html($('#' + response.level + 'Container').html());
                },
                error: function(a, b, c) {
                    console.log(a, b, c);
                }
            });
            // $("#tingkatTemuanCtrl").prop("disabled", false);
            // if(this.value == "non-finding"){
            //     // $('#tingkatTemuanCtrl').val('1'); // Select the option with a value of '1'
            //     $('#tingkatTemuanCtrl').val('');
            //     $('#tingkatTemuanCtrl').trigger('change');
            //     $("#tingkatTemuanCtrl").prop("disabled", true);
            // }else{
            //     $('#tingkatTemuanCtrl').val('Major'); // Select the option with a value of '1'
            //     $('#tingkatTemuanCtrl').trigger('change');
            // }
        });
        $('#tipeStrukturCtrl').trigger('change');
    </script>
@endpush
