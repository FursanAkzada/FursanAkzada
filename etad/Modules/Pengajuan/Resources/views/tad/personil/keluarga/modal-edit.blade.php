
<form action="{{ route($route . '.keluarga.detailUpdate', $record->id) }}" method="POST">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">Informasi Keluarga</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div id="families">
            @php
                $keluarga = $record;
            @endphp
                <div class="row families-form">
                    <div class="col-6">
                        <div class="form-group row">
                            <label for="" class="col-4 text-bold">Keluarga Sebagai</label>
                            <div class="col parent-group">
                                <select name="tipe_id" id="" class="form-control base-plugin--select2">
                                    <option value=""></option>
                                    @foreach (\Modules\Master\Entities\Tad\TipeKeluarga::orderBy('tipe', 'ASC')->get() as $item)
                                        <option value="{{ $item->id }}" @if($keluarga->tipe_id==$item->id) selected @endif>{{ $item->tipe }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-4 text-bold">Nama</label>
                            <div class="col parent-group">
                                <input type="text" name="nama" class="form-control" value="{{ $keluarga->nama }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-4 text-bold">Anak Ke</label>
                            <div class="col parent-group">
                                <input type="text" name="urutan_anak" class="form-control" value="{{ $keluarga->urutan_anak ?? '-' }}">
                                <span class="form-text text-muted">*Kosongkan jika keluarga bukan sebagai anak</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-4 text-bold">Tempat & Tgl Lahir</label>
                            <div class="col-4 parent-group">
                                <select name="tempat_lahir" id="tempat_lahir"
                                    class="form-control base-plugin--select2">
                                    <option value=""></option>
                                    @foreach (\Modules\Master\Entities\Geografis\City::orderBy('name', 'ASC')->get() as $item)
                                        <option value="{{ $item->name }}" @if($keluarga->tempat_lahir == $item->name) selected @endif>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4 parent-group">
                                <input type="text" name="tanggal_lahir"
                                    data-format="dd/mm/yyyy" data-options='@json(['endDate' => now()->format('d/m/Y')])'
                                    class="form-control base-plugin--datepicker"
                                    value="{{ $keluarga->tanggal_lahir->format('d/m/Y') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group row">
                            <label for="" class="col-4 text-bold">Jenis Kelamin</label>
                            <div class="col-8 parent-group">
                                <select name="jenis_kelamin" id="" class="form-control base-plugin--select2">
                                    <option value=""></option>
                                    <option value="L" @if($record->jenis_kelamin == 'L') selected @endif>Laki - Laki</option>
                                    <option value="P" @if($record->jenis_kelamin == 'P') selected @endif>Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-4 text-bold">Agama</label>
                            <div class="col-8 parent-group">
                                <select name="agama_id" class="form-control base-plugin--select2">
                                    <option value=""></option>
                                    @foreach (\App\Entities\EHC\Agama::get() as $item)
                                        <option value="{{ $item->Sandi }}" @if($item->Sandi == $keluarga->agama_id) selected @endif>{{ $item->Lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-4 text-bold">Nomor HP</label>
                            <div class="col-8 parent-group">
                                <input type="text" name="telepon" class="form-control" value="{{ $keluarga->telepon }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-4 text-bold">Kewarganegaraan</label>
                            <div class="col-8 parent-group">
                                <select class="form-control base-plugin--select2" name="kewarganegaraan" data-placeholder="Pilih Kewarganegaraan">
                                    <option value="WNI" @if($keluarga->kewarganegaraan == 'WNI') selected @endif>WNI</option>
                                    <option value="WNA" @if($keluarga->kewarganegaraan == 'WNA') selected @endif>WNA</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group row">
                            <label for="" class="col-2 text-bold">Alamat Lengkap</label>
                            <div class="col-10 parent-group">
                                <textarea name="alamat" class="form-control" id="" cols="30" rows="3"
                                    placeholder="Alamat Lengkap">{!! $keluarga->alamat !!}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <div class="pt-0 border-0 modal-footer">
        <button type="submit" data-swal-confirm="false" data-rusmen="true"
            class="btn btn-info d-flex align-items-center base-form--submit-page">
            <i class="fas fa-save mr-2"></i>Simpan
        </button>
    </div>
</form>

<script>
    $('#modal .modal-md').removeClass('modal-md').addClass('modal-xl');
</script>
