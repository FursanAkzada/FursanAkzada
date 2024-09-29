@php
    $user = auth()->user();
    $user->load('vendor.categories.jabatanTad');
@endphp
@extends('layouts.app')
@section('title', $title)
@section('buttons-after') @endsection
@section('content')
    <form action="{{ route($route . '.store') }}" autocomplete="off" method="post">
        @csrf
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
                                <select name="vendor_id" id="vendorCtrl" class="form-control base-plugin--select2">
                                    <option value=""></option>
                                    @if (auth()->user()->isEhc)
                                        @foreach (\Modules\Master\Entities\Vendor::with('categories.jabatanTad')->orderBy('nama', 'ASC')->get() as $item)
                                            @if ($item->nama === 'PLACEHOLDER')
                                                @continue
                                            @endif
                                            <option data-categories='{{ json_encode($item->categories) }}'
                                                value="{{ $item->id }}">
                                                {{ $item->nama }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option data-categories='{{ json_encode($user->vendor->categories) }}'
                                            value="{{ $user->vendor_id }}" selected>
                                            {{ $user->vendor->nama }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Nama Lengkap</label>
                            <div class="col parent-group">
                                <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">NIK</label>
                            <div class="col parent-group">
                                <input type="text" class="form-control masking-nik" name="nik" placeholder="NIK">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">NPWP</label>
                            <div class="col parent-group">
                                <input type="text" class="form-control masking-npwp" name="npwp" placeholder="NPWP">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Nomor BPJS</label>
                            <div class="col parent-group">
                                <input type="text" class="form-control masking-bpjs" name="bpjs"
                                    placeholder="Nomor BPJS">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Rekening Bank Jatim</label>
                            <div class="col parent-group">
                                <input type="text" class="form-control masking-norek" name="rekening_bjtm"
                                    placeholder="Nomor Rekening BJTM" maxlength="10">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Agama</label>
                            <div class="col parent-group">
                                <select name="agama_id" id="" class="form-control base-plugin--select2">
                                    <option value=""></option>
                                    @foreach (\App\Entities\EHC\Agama::get() as $item)
                                        <option value="{{ $item->Sandi }}">{{ $item->Lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Jenis Kelamin</label>
                            <div class="col parent-group">
                                <select name="jenis_kelamin" id="" class="form-control base-plugin--select2">
                                    <option value=""></option>
                                    <option value="L">Laki - Laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Tempat & Tgl Lahir</label>
                            <div class="col parent-group">
                                <select name="tempat_lahir" id="tempat_lahir" class="form-control base-plugin--select2">
                                    <option value=""></option>
                                    @foreach (\Modules\Master\Entities\Geografis\City::orderBy('name', 'ASC')->get() as $item)
                                        <option value="{{ $item->name }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col parent-group">
                                <input type="text" class="form-control base-plugin--datepicker" data-language="en"
                                    data-format="dd/mm/yyyy" data-options='@json(['endDate' => now()->format('d/m/Y')])'
                                    name="tanggal_lahir" placeholder="Tanggal Lahir">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Status Perkawinan</label>
                            <div class="col parent-group">
                                <select name="status_perkawinan" id=""
                                    class="form-control base-plugin--select2">
                                    <option value="" disabled></option>
                                    <option value="Lajang">Lajang</option>
                                    <option value="Menikah">Menikah</option>
                                    <option value="Cerai">Cerai</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Nomor Handphone</label>
                            <div class="col parent-group">
                                <input type="text" class="form-control base-plugin--inputmask_phone" name="telepon"
                                    placeholder="Nomor Handphone">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Email</label>
                            <div class="col parent-group">
                                <input type="text" class="form-control" name="email" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Alamat Lengkap</label>
                            <div class="col parent-group">
                                <textarea class="form-control" cols="30" name="alamat_lengkap" placeholder="Alamat Lengkap" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Provinsi</label>
                            <div class="col parent-group">
                                <select name="province_id" id="provinsi" class="form-control base-plugin--select2">
                                    <option value=""></option>
                                    @foreach (\Modules\Master\Entities\Geografis\Province::orderBy('name', 'ASC')->get() as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Kota / Kab</label>
                            <div class="col parent-group">
                                <select name="city_id" id="kota" class="form-control base-plugin--select2">
                                    <option value=""></option>
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
                                        data-max-size="2048" data-max-file="100" accept="image/*">
                                    <label class="custom-file-label" for="file"
                                        style="color:#B5B5C3;font-weight:400;">{{ 'Pilih file' }}</label>
                                </div>
                                <div class="form-text text-muted">*Maksimal 2MB</div>
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
                                        data-max-size="2048" data-max-file="100" accept="image/*">
                                    <label class="custom-file-label" for="file"
                                        style="color:#B5B5C3;font-weight:400;">{{ 'Pilih file' }}</label>
                                </div>
                                <div class="form-text text-muted">*Maksimal 2MB</div>
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
                                    <label class="custom-file-label" for="file"
                                        style="color:#B5B5C3;font-weight:400;">{{ 'Pilih file' }}</label>
                                </div>
                                <div class="form-text text-muted">*Maksimal 2MB</div>
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
                                    <label class="custom-file-label" for="file"
                                        style="color:#B5B5C3;font-weight:400;">{{ 'Pilih file' }}</label>
                                </div>
                                <div class="form-text text-muted">*Maksimal 2MB</div>
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
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Jurusan</label>
                            <div class="col parent-group">
                                <select name="jurusan_id" id="jurusan" class="form-control base-plugin--select2">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Gelar</label>
                            <div class="col parent-group">
                                <input type="text" name="gelar" class="form-control" placeholder="Gelar">
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
                                <input class="form-control" name="rekomendasi" placeholder="Rekomendasi">
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col">
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Tgl Kontrak Lama</label>
                            <div class="col parent-group">
                                <input type="text" class="form-control base-plugin--datepicker" data-language="en"
                                    data-format="dd/mm/yyyy"
                                    data-options='@json(['endDate' => now()->format('d/m/Y')])' name="date_old_contract"
                                    placeholder="Tgl Kontrak Lama">
                            </div>
                        </div>
                    </div> --}}
                </div>
                <hr>
                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Posisi TAD</label>
                            <div class="col parent-group">
                                @php
                                    $POSISI_TAD = \App\Entities\EHC\Jabatan::orderBy('NM_UNIT', 'ASC')->get();
                                @endphp
                                <select class="form-control base-plugin--select2" id="jabatanCtrl" name="jabatan_id">
                                    <option value=""></option>
                                    @foreach ($POSISI_TAD as $jabatan)
                                        <option value="{{ $jabatan->idunit }}">{{ $jabatan->NM_UNIT }}</option>
                                    @endforeach
                                </select>
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
                    <x-btn-save via="base-form--submit-page" class="skip-confirm" confirm='false' />
                    {{-- <button type="submit" data-swal-confirm="true"
                        class="btn btn-info d-flex align-items-center base-form--submit-page">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button> --}}
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
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
            $(document)
                .on('change', '#provinsi', function() {
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
                })
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
                });
        });
    </script>
@endpush
