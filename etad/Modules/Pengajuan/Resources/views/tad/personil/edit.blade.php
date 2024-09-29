@php
    $user = auth()->user();
    $user->load('vendor.categories.jabatanTad');
@endphp
@extends('layouts.app')
@section('title', $title)
@section('buttons-after') @endsection
@section('content')
    <form action="{{ route($route . '.update', $record->id) }}" autocomplete="off" method="post">
        @method('PUT')
        @csrf
        <input type="hidden" name="id" value="{{ $record->id }}">
        @if ($record->lastEmployment)
            <input type="hidden" name="cek_kepegawaian" value="yes">
            <!-- mengecek apakah status resign atau berhenti kerja -->
        @else
            <input type="hidden" name="cek_kepegawaian" value="no">
        @endif

        <div class="card card-custom">
            <div class="card-header">
                <h5 class="card-title">
                    Informasi Data Diri
                </h5>
                <button aria-label="Close" class="close" data-dismiss="card"
                    onclick="location.href='{{ route($route . '.index') }}'" type="button">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Vendor</label>
                            <div class="col parent-group">
                                <select name="vendor_id" id="vendorCtrl" class="form-control base-plugin--select2"
                                    @if (isset($record->lastEmployment) && !isset($record->lastEmployment->out_at)) disabled @endif>
                                    <option value=""></option>
                                    @if (auth()->user()->isEhc)
                                        @foreach (\Modules\Master\Entities\Vendor::with('categories.jabatanTad')->orderBy('nama', 'ASC')->get() as $item)
                                            <option data-categories='{{ json_encode($item->categories) }}'
                                                value="{{ $item->id }}"
                                                @if ($record->vendor_id == $item->id) selected @endif>
                                                {{ $item->nama }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option data-categories='{{ json_encode($user->vendor->categories) }}'
                                            value="{{ auth()->user()->vendor_id }}" selected>
                                            {{ auth()->user()->vendor->nama }}
                                        </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Nama Lengkap</label>
                            <div class="col parent-group">
                                <input class="form-control" name="nama" value="{{ $record->nama }}"
                                    placeholder="Nama Lengkap">
                            </div>
                        </div>
                        @if ($record->lastEmployment)
                            <div class="form-group row">
                                <label for="" class="col-3 text-bold">NIO</label>
                                <div class="col parent-group">
                                    <input type="hidden" name="nio" value="{{ $record->lastEmployment->nio }}">
                                    <input class="form-control masking-nik" disabled name="nio"
                                        value="{{ $record->lastEmployment->nio }}" placeholder="NIO">
                                </div>
                            </div>
                        @endif
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">NIK</label>
                            <div class="col parent-group">
                                @if ($record->nik)
                                    <input type="hidden" name="nik" value="{{ $record->nik }}">
                                @endif
                                <input class="form-control masking-nik" name="nik"
                                    value="{{ $record->nik }}" placeholder="NIK" {{ $record->nik ? 'disabled' : '' }}>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">NPWP</label>
                            <div class="col parent-group">
                                <input class="form-control masking-npwp" name="npwp"
                                    value="{{ $record->npwp }}" placeholder="NPWP">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Nomor BPJS</label>
                            <div class="col parent-group">
                                <input class="form-control masking-bpjs" name="bpjs"
                                    value="{{ $record->bpjs }}" placeholder="Nomor BPJS">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Rekening Bank Jatim</label>
                            <div class="col parent-group">
                                <input class="form-control masking-norek" max="10" maxlength="10" name="rekening_bjtm"
                                    value="{{ $record->rekening_bjtm }}" placeholder="Nomor Rekening BJTM">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Agama</label>
                            <div class="col parent-group">
                                <select name="agama_id" id="" class="form-control base-plugin--select2">
                                    <option value=""></option>
                                    @foreach (\App\Entities\EHC\Agama::get() as $item)
                                        <option {{ $record->agama_id == $item->Sandi ? 'selected' : '' }}
                                            value="{{ $item->Sandi }}">{{ $item->Lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Jenis Kelamin</label>
                            <div class="col parent-group">
                                <select name="jenis_kelamin" id="" class="form-control base-plugin--select2">
                                    <option value=""></option>
                                    <option {{ $record->jenis_kelamin == 'L' ? 'selected' : '' }} value="L">Laki -
                                        Laki
                                    </option>
                                    <option {{ $record->jenis_kelamin == 'P' ? 'selected' : '' }} value="P">
                                        Perempuan
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Tempat & Tgl Lahir</label>
                            <div class="col parent-group">
                                <select name="tempat_lahir" id="tempat_lahir" class="form-control base-plugin--select2">
                                    <option value=""></option>
                                    @foreach (\Modules\Master\Entities\Geografis\City::orderBy('name', 'ASC')->get() as $item)
                                        <option value="{{ $item->name }}"
                                            {{ $record->tempat_lahir == $item->name ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col parent-group">
                                <input class="form-control base-plugin--datepicker" data-language="en"
                                    data-format="dd/mm/yyyy" data-options='@json(['endDate' => now()->format('d/m/Y')])'
                                    name="tanggal_lahir" value="{{ $record->tanggal_lahir ? $record->tanggal_lahir->format('d/m/Y') : '' }}"
                                    placeholder="Tanggal Lahir">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Status Perkawinan</label>
                            <div class="col parent-group">
                                <select name="status_perkawinan" id=""
                                    class="form-control base-plugin--select2">
                                    <option value=""></option>
                                    <option {{ $record->status_perkawinan === 'Lajang' ? 'selected' : '' }}
                                        value="Lajang">
                                        Lajang
                                    </option>
                                    <option {{ $record->status_perkawinan === 'Menikah' ? 'selected' : '' }}
                                        value="Menikah">
                                        Menikah
                                    </option>
                                    <option {{ $record->status_perkawinan === 'Cerai' ? 'selected' : '' }} value="Cerai">
                                        Cerai
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Nomor Handphone</label>
                            <div class="col parent-group">
                                <input class="form-control base-plugin--inputmask_phone" name="telepon"
                                    value="{{ $record->telepon }}" placeholder="Nomor Handphone">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Email</label>
                            <div class="col parent-group">
                                @if ($record->email)
                                <input type="hidden" name="email" value="{{ $record->email }}">
                                <input class="form-control" {{ $record->email ? 'disabled' : '' }}
                                        value="{{ $record->email }}">
                                @else
                                    <input class="form-control" {{ $record->email ? 'disabled' : '' }}
                                        name="email" value="{{ $record->email }}">
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Alamat Lengkap</label>
                            <div class="col parent-group">
                                <textarea name="alamat_lengkap" placeholder="Alamat Lengkap" class="form-control" id="" cols="30"
                                    rows="5">{{ $record->alamat_lengkap }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Provinsi</label>
                            <div class="col parent-group">
                                <select name="province_id" id="provinsi" class="form-control base-plugin--select2">
                                    <option value=""></option>
                                    @foreach (\Modules\Master\Entities\Geografis\Province::orderBy('name', 'ASC')->get() as $item)
                                        <option {{ ($record->city->province_id ?? null) == $item->id ? 'selected' : '' }}
                                            value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Kota / Kab</label>
                            <div class="col parent-group">
                                <select name="city_id" id="kota" class="form-control base-plugin--select2">
                                    <option value=""></option>
                                    @if (isset($record->city->province_id))
                                        @foreach (\Modules\Master\Entities\Geografis\City::where('province_id', $record->city->province_id)->orderBy('name', 'ASC')->get() as $item)
                                            <option {{ $record->city_id == $item->id ? 'selected' : '' }}
                                                value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Foto 3 x 4</label>
                            <div class="col parent-group">
                                <div class="custom-file">
                                    <input type="hidden" name="uploads_foto3x4[uploaded]" class="uploaded"
                                        value="">
                                    <input type="file" multiple data-name="uploads_foto3x4"
                                        class="custom-file-input base-form--save-temp-files" data-container="parent-group"
                                        data-max-size="2048" data-max-file="100" accept=".jpg,.jpeg,.png">
                                    <label class="custom-file-label" for="file" style="color:#B5B5C3;font-weight:400;">{{ 'Pilih file' }}</label>
                                </div>
                                <div class="form-text text-muted">*Maksimal 2MB</div>
                                @foreach ($record->files->where('flag', 'foto3x4') as $file)
                                    <div class="progress-container w-100" data-uid="{{ $file->id }}">
                                        <div class="alert alert-custom alert-light fade show success-uploaded mb-0 mt-2 px-3 py-2"
                                            role="alert">
                                            <div class="alert-icon">
                                                <i class="{{ $file->file_icon }}"></i>
                                            </div>
                                            <div class="alert-text text-left">
                                                <input type="hidden" name="uploads_foto3x4[files_ids][]"
                                                    value="{{ $file->id }}">
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
                            <label for="" class="col-3 text-bold">Foto Full Body</label>
                            <div class="col parent-group">
                                <div class="custom-file">
                                    <input type="hidden" name="uploads_foto_fullbody[uploaded]" class="uploaded"
                                        value="">
                                    <input type="file" multiple data-name="uploads_foto_fullbody"
                                        class="custom-file-input base-form--save-temp-files" data-container="parent-group"
                                        data-max-size="2048" data-max-file="100" accept=".jpg,.jpeg,.png">
                                    <label class="custom-file-label" for="file" style="color:#B5B5C3;font-weight:400;">{{ 'Pilih file' }}</label>
                                </div>
                                <div class="form-text text-muted">*Maksimal 2MB</div>
                                @foreach ($record->files->where('flag', 'foto_fullbody') as $file)
                                    <div class="progress-container w-100" data-uid="{{ $file->id }}">
                                        <div class="alert alert-custom alert-light fade show success-uploaded mb-0 mt-2 px-3 py-2"
                                            role="alert">
                                            <div class="alert-icon">
                                                <i class="{{ $file->file_icon }}"></i>
                                            </div>
                                            <div class="alert-text text-left">
                                                <input type="hidden" name="uploads_foto_fullbody[files_ids][]"
                                                    value="{{ $file->id }}">
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
                            <label for="" class="col-3 text-bold">Curiculum Vitae</label>
                            <div class="col parent-group">
                                <div class="custom-file">
                                    <input type="hidden" name="uploads_cv[uploaded]" class="uploaded" value="">
                                    <input type="file" multiple data-name="uploads_cv"
                                        class="custom-file-input base-form--save-temp-files" data-container="parent-group"
                                        data-max-size="2048" data-max-file="100" accept=".pdf">
                                    <label class="custom-file-label" for="file" style="color:#B5B5C3;font-weight:400;">{{ 'Pilih file' }}</label>
                                </div>
                                <div class="form-text text-muted">*Maksimal 2MB</div>
                                @foreach ($record->files->where('flag', 'cv') as $file)
                                    <div class="progress-container w-100" data-uid="{{ $file->id }}">
                                        <div class="alert alert-custom alert-light fade show success-uploaded mb-0 mt-2 px-3 py-2"
                                            role="alert">
                                            <div class="alert-icon">
                                                <i class="{{ $file->file_icon }}"></i>
                                            </div>
                                            <div class="alert-text text-left">
                                                <input type="hidden" name="uploads_cv[files_ids][]"
                                                    value="{{ $file->id }}">
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
                            <label for="" class="col-3 text-bold">Hasil Psikotest</label>
                            <div class="col parent-group">
                                <div class="custom-file">
                                    <input type="hidden" name="uploads_psikotest[uploaded]" class="uploaded"
                                        value="">
                                    <input type="file" multiple data-name="uploads_psikotest"
                                        class="custom-file-input base-form--save-temp-files" data-container="parent-group"
                                        data-max-size="2048" data-max-file="100" accept=".pdf">
                                    <label class="custom-file-label" for="file" style="color:#B5B5C3;font-weight:400;">{{ 'Pilih file' }}</label>
                                </div>
                                <div class="form-text text-muted">*Maksimal 2MB</div>
                                @foreach ($record->files->where('flag', 'psikotest') as $file)
                                    <div class="progress-container w-100" data-uid="{{ $file->id }}">
                                        <div class="alert alert-custom alert-light fade show success-uploaded mb-0 mt-2 px-3 py-2"
                                            role="alert">
                                            <div class="alert-icon">
                                                <i class="{{ $file->file_icon }}"></i>
                                            </div>
                                            <div class="alert-text text-left">
                                                <input type="hidden" name="uploads_psikotest[files_ids][]"
                                                    value="{{ $file->id }}">
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
                </div>
                <h4 class="card-tile my-5">Informasi Pendidikan</h4>
                <hr>
                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Pendidikan</label>
                            <div class="col parent-group">
                                <select name="pendidikan_id" id="pendidikan" class="form-control base-plugin--select2">
                                    <option value=""></option>
                                    @foreach (\Modules\Master\Entities\Pendidikan::orderBy('name', 'ASC')->get() as $item)
                                        <option {{ ($record->pendidikan_id ?? null) == $item->id ? 'selected' : '' }}
                                            value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Jurusan</label>
                            <div class="col parent-group">
                                <select name="jurusan_id" id="jurusan" class="form-control base-plugin--select2">
                                    <option value=""></option>
                                    @if (isset($record->jurusan->pendidikan_id))
                                        @foreach (\Modules\Master\Entities\Jurusan::where('pendidikan_id', $record->jurusan->pendidikan_id)->orderBy('name', 'ASC')->get() as $item)
                                            <option {{ $record->jurusan_id == $item->id ? 'selected' : '' }}
                                                value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Gelar</label>
                            <div class="col parent-group">
                                <input name="gelar" value="{{ $record->gelar }}" class="form-control"
                                    placeholder="Gelar">
                            </div>
                        </div>
                    </div>
                </div>
                <h4 class="card-tile my-5">Informasi Lainnya</h4>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Rekomendasi</label>
                            <div class="col parent-group">
                                <input name="rekomendasi" value="{{ $record->rekomendasi }}"
                                    class="form-control" placeholder="Rekomendasi">
                            </div>
                        </div>
                    </div>
                    @if ($record->lastEmployment)
                        <div class="col">
                            <div class="form-group row">
                                <label for="" class="col-3 text-bold">Tgl Kontrak Lama</label>
                                <div class="col parent-group">
                                    <input type="text" class="form-control base-plugin--datepicker" data-language="en"
                                        data-format="dd/mm/yyyy"
                                        data-options='@json(['endDate' => now()->format('d/m/Y')])' name="date_old_contract"
                                        placeholder="Tgl Kontrak Lama" value="{{ isset($record->date_old_contract) ? $record->date_old_contract->format('d/m/Y') : '' }}">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <hr>
                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Posisi TAD</label>
                            <div class="col parent-group">
                                @if (!empty($record->vendor_id))
                                    @php
                                        $POSISI_TAD = \App\Entities\EHC\Jabatan::orderBy('NM_UNIT', 'ASC')
                                            ->get();
                                    @endphp
                                    <select class="form-control base-plugin--select2" id="jabatanCtrl" name="jabatan_id"
                                    @if(isset($record->kepegawaian->status) && $record->kepegawaian->status == 2)

                                    @elseif(isset($record->lastEmployment) && !isset($record->lastEmployment->out_at))
                                        disabled
                                    @endif
                                    {{-- @if(isset($record->status)) --}}
                                        {{-- @if (isset($record->lastEmployment) && !isset($record->lastEmployment->out_at)) disabled @endif --}}
                                        >
                                        @foreach ($POSISI_TAD as $item)
                                            <option {{ $record->jabatan_id == $item->idunit ? 'selected' : '' }}
                                                value="{{ $item->idunit }}">
                                                {{ $item->NM_UNIT }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col">
                    </div>
                </div>
            </div>
            <div class="card-footer p-5">
                <div class="d-flex float-right flex-row">
                    <x-btn-back class="mr-2" url="{{ route($route . '.index') }}" />
                    <x-btn-save via="base-form--submit-page" confirm='0' />
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $(document)
                .on('change', '#pendidikan', function() {
                    $("#jurusan").html('<option value=""></option>');
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('personil.belum-bekerja.jurusan') }}',
                        data: {
                            '_token': csrf,
                            pendidikan_id: $('#pendidikan').val()
                        },
                        success: function(res) {
                            var html = '<option value=""></option>';
                            $.each(res, function(idx, val) {
                                html += '<option value="' + val.id + '">' + val.name +
                                    '</option>';
                            });
                            $("#jurusan").html(html);
                        },
                        error: function(res) {
                            console.log(res);
                        }
                    });
                })
                .on('change', '#vendorCtrl', function() {
                    let categories = $('#vendorCtrl option:selected').data('categories');
                    console.log(285, categories);
                    let POSISI_TAD = {};
                    for (let category of categories) {
                        for (let jabatan of category.jabatan_tad) {
                            if (POSISI_TAD[jabatan.idunit]) {} else {
                                POSISI_TAD[jabatan.idunit] = jabatan.NM_UNIT;
                            }
                        }
                    }
                    let options = '';
                    for (let idunit in POSISI_TAD) {
                        options += `<option value='${idunit}'>${POSISI_TAD[idunit]}</option>`;
                    }
                    $('#jabatanCtrl').select2('destroy');
                    $('#jabatanCtrl').html(options);
                    $('#jabatanCtrl').select2();
                });
            $("#provinsi").change(function() {
                $("#kota").html('<option value=""></option>');
                $.ajax({
                    type: 'POST',
                    url: '{{ route('personil.belum-bekerja.kota') }}',
                    data: {
                        '_token': csrf,
                        province_id: $('#provinsi').val()
                    },
                    success: function(res) {
                        var html = '<option value=""></option>';
                        $.each(res, function(idx, val) {
                            html += '<option value="' + val.id + '">' + val.name +
                                '</option>';
                        });

                        $("#kota").html(html);
                    },
                    error: function(res) {
                        console.log(res);
                    }
                });
            });
            $(".masking-nik").inputmask({
                "mask": "9",
                "repeat": 16,
                "greedy": false
            });
            $(".masking-npwp").inputmask({
                "mask": "9",
                "repeat": 15,
                "greedy": false
            });
            $(".masking-bpjs").inputmask({
                "mask": "9",
                "repeat": 13,
                "greedy": false
            });
            $(".masking-norek").inputmask({
                "mask": "9",
                "repeat": 30,
                "greedy": false
            });
        });
    </script>
@endpush
