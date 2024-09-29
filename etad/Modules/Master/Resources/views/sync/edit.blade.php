@extends('layouts.app')
@section('title', $title)
@section('buttons') @endsection
@section('content')
    <form action="{{ route($route . '.update', $record->id) }}" autocomplete="off" method="post">
        @method('PUT')
        @csrf
        <div class="card card-custom">
            <div class="card-body">
                <h4 class="card-tile mb-5">Informasi Data Diri</h4>
                <hr>
                <div class="row mb-3">
                    <div class="col text-center">
                        <div class="symbol symbol-150 mr-3">
                            <img alt="pic" src="{{ url('storage/' . $record->file->foto_filepath) }}" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Nama Lengkap</label>
                            <div class="col parent-group">
                                <input type="text" class="form-control" name="nama" value="{{ $record->nama }}"
                                    placeholder="Nama Lengkap">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">NIK</label>
                            <div class="col parent-group">
                                <input type="text" class="form-control masking-nik" name="nik" value="{{ $record->nik }}"
                                    placeholder="NIK">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">NPWP</label>
                            <div class="col parent-group">
                                <input type="text" class="form-control masking-npwp" name="npwp"
                                    value="{{ $record->npwp }}" placeholder="NPWP">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Nomor BPJS</label>
                            <div class="col parent-group">
                                <input type="text" class="form-control masking-bpjs" name="bpjs"
                                    value="{{ $record->bpjs }}" placeholder="Nomor BPJS">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Rekening Bank Jatim</label>
                            <div class="col parent-group">
                                <input type="text" class="form-control masking-norek" name="rekening_bjtm"
                                    value="{{ $record->rekening_bjtm }}" placeholder="Nomor Rekening BJTM">
                                <span class="form-text text-muted">Abaikan form ini jika belum memiliki rekening bank
                                    jatim</span>
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
                                    <option {{ $record->jenis_kelamin == 'L' ? 'selected' : '' }} value="L">Laki - Laki
                                    </option>
                                    <option {{ $record->jenis_kelamin == 'P' ? 'selected' : '' }} value="P">Perempuan
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Tempat & Tgl Lahir</label>
                            <div class="col parent-group">
                                <select name="tempat_lahir" id="tempat_lahir" class="form-control base-plugin--select2">
                                    <option value=""></option>
                                    @foreach (\Modules\Master\Entities\Kota::get() as $item)
                                        <option value="{{ $item->nama }}"
                                            {{ $record->tempat_lahir == $item->nama ? 'selected' : '' }}>
                                            {{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col parent-group">
                                <input type="text" class="form-control base-plugin--datepicker"
                                    data-format="dd/mm/yyyy"
                                    data-options='@json(['endDate' => ''])' name="tanggal_lahir"
                                    value="{{ $record->tanggal_lahir->format('d/m/Y') }}" placeholder="Tanggal Lahir">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Status Perkawinan</label>
                            <div class="col parent-group">
                                <select name="status_perkawinan" id="" class="form-control base-plugin--select2">
                                    <option value=""></option>
                                    <option {{ $record->status_perkawinan == '1' ? 'selected' : '' }} value="1">Lajang
                                    </option>
                                    <option {{ $record->status_perkawinan == '2' ? 'selected' : '' }} value="2">Menikah
                                    </option>
                                    <option {{ $record->status_perkawinan == '3' ? 'selected' : '' }} value="3">Cerai
                                    </option>
                                    {{-- <option value="">Janda</option>
                <option value="">Duda</option> --}}
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Nomor Handphone</label>
                            <div class="col parent-group">
                                <input type="text" class="form-control base-plugin--inputmask_phone" name="telepon"
                                    value="{{ $record->telepon }}" placeholder="Nomor Handphone">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Email</label>
                            <div class="col parent-group">
                                <input type="text" class="form-control" name="email" value="{{ $record->email }}"
                                    placeholder="Email">
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Alamat Lengkap</label>
                            <div class="col parent-group">
                                <textarea name="alamat_lengkap" placeholder="Alamat Lengkap" class="form-control" id="" cols="30"
                                    rows="3">{{ $record->alamat_lengkap }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Provinsi</label>
                            <div class="col parent-group">
                                <select name="provinsi_id" id="provinsi" class="form-control base-plugin--select2">
                                    <option value=""></option>
                                    @foreach (\Modules\Master\Entities\Provinsi::get() as $item)
                                        <option {{ $record->provinsi_id == $item->id ? 'selected' : '' }}
                                            value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Kota / Kab</label>
                            <div class="col parent-group">
                                <select name="kota_id" id="kota" class="form-control base-plugin--select2">
                                    <option value=""></option>
                                    @foreach (\Modules\Master\Entities\Kota::where('provinsi_id', $record->provinsi_id)->get() as $item)
                                        <option {{ $record->kota_id == $item->id ? 'selected' : '' }}
                                            value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Foto 3 x 4</label>
                            <div class="col parent-group">
                                <div class="custom-file">
                                    <input type="file" name="foto" accept=".jpg,.jpeg,.png" class="custom-file-input" />
                                    <label class="custom-file-label" for="file" style="color:#B5B5C3;font-weight:400;">Pilih file</label>
                                    <span class="form-text text-muted">Lampirkan File dengan format .jpg / .png / .jpeg,
                                        Ukuruan Maks : 500kb</span>
                                    <span class="form-text text-info">Kosongkan jika foto tidak diganti</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Foto Full Body</label>
                            <div class="col parent-group">
                                <div class="custom-file">
                                    <input type="file" name="foto_full" accept=".jpg,.jpeg,.png"
                                        class="custom-file-input" />
                                    <label class="custom-file-label" for="file" style="color:#B5B5C3;font-weight:400;">Pilih file</label>
                                    <span class="form-text text-muted">Lampirkan File dengan format .jpg / .png / .jpeg,
                                        Ukuruan Maks : 500kb</span>
                                    <span class="form-text text-info">Kosongkan jika foto tidak diganti</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Curiculum Vitae</label>
                            <div class="col parent-group">
                                <div class="custom-file">
                                    <input type="file" name="cv" accept=".pdf" class="custom-file-input" />
                                    <label class="custom-file-label" for="file" style="color:#B5B5C3;font-weight:400;">Pilih file</label>
                                    <span class="form-text text-muted">Lampirkan File dengan format .pdf, Ukuruan Maks :
                                        10MB</span>
                                    <span class="form-text text-info">Kosongkan jika foto tidak diganti</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Hasil Psikotest</label>
                            <div class="col parent-group">
                                <div class="custom-file">
                                    <input type="file" name="psikotest" accept=".pdf" class="custom-file-input" />
                                    <label class="custom-file-label" for="file" style="color:#B5B5C3;font-weight:400;">Pilih file</label>
                                    <span class="form-text text-muted">Lampirkan File dengan format .pdf, Ukuruan Maks :
                                        10MB</span>
                                    <span class="form-text text-info">Kosongkan jika foto tidak diganti</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h4 class="card-tile my-5">Informasi Pendidikan</h4>
                <hr>
                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Pendidikan Terakhir</label>
                            <div class="col parent-group">
                                <select name="pendidikan_id" id="" class="form-control base-plugin--select2">
                                    <option value=""></option>
                                    @foreach (\App\Entities\EHC\Pendidikan::get() as $item)
                                        <option {{ $record->pendidikan_id == $item->sandi ? 'selected' : '' }}
                                            value="{{ $item->sandi }}">{{ $item->lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group row">
                            <label for="" class="col-3 text-bold">Gelar</label>
                            <div class="col parent-group">
                                <input type="text" name="gelar" value="{{ $record->gelar }}" class="form-control"
                                    placeholder="Gelar">
                                <span class="form-text text-muted">Kosongkan jika tidak ada</span>
                            </div>
                        </div>
                    </div>
                </div>
                @if (auth()->user()->isVendor)
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="form-group row">
                                <label for="" class="col-3 text-bold">Posisi TAD</label>
                                <div class="col parent-group">
                                    <select name="jabatan_id" id="" class="form-control base-plugin--select2">
                                        <option value=""></option>
                                        @foreach (\App\Entities\EHC\Jabatan::get() as $item)
                                            <option {{ $record->jabatan_id == $item->idunit ? 'selected' : '' }}
                                                value="{{ $item->idunit }}">{{ $item->NM_UNIT }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col">

                        </div>
                    </div>
                @endif
            </div>
            <div class="card-footer p-5">
                <div class="float-right d-flex flex-row">
                    <x-btn-back class="mr-2" url="{{ route($route . '.index') }}" />
                    <x-btn-save via="base-form--submit-page" />
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $("#provinsi").change(function() {
                $("#kota").html('<option value=""></option>');
                $.ajax({
                    type: 'POST',
                    url: '{{ route('master.tad.kota') }}',
                    data: {
                        '_token': csrf,
                        provinsi_id: $('#provinsi').val()
                    },
                    success: function(res) {
                        var html = '<option value=""></option>';
                        $.each(res, function(idx, val) {
                            html += '<option value="' + val.id + '">' + val.nama +
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
