
<form action="{{ route($route . '.keluarga.store', $record->id) }}" method="POST">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">Informasi Keluarga</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col">
                <div class="form-group row">
                    <label for="" class="col-md-4 col-form-label text-bold">Keluarga Sebagai</label>
                    <div class="col-md-8 parent-group">
                        <select name="parents[0][tipe_id]" id="" class="form-control base-plugin--select2">
                            <option value=""></option>
                            @foreach (\Modules\Master\Entities\Tad\TipeKeluarga::orderBy('tipe', 'ASC')->get() as $item)
                                <option value="{{ $item->id }}">{{ $item->tipe }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-md-4 col-form-label text-bold">Nama</label>
                    <div class="col-md-8 parent-group">
                        <input type="text" class="form-control" name="parents[0][nama]" placeholder="Nama">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-md-4 col-form-label text-bold">Tempat & Tgl Lahir</label>
                    <div class="col-md-4 parent-group">
                        <select name="parents[0][tempat_lahir]" id="parents[0][tempat_lahir]"
                            class="form-control base-plugin--select2">
                            <option value=""></option>
                            @foreach (\Modules\Master\Entities\Geografis\City::orderBy('name', 'ASC')->get() as $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 parent-group">
                        <input type="text" class="form-control base-plugin--datepicker" data-language="in"
                            data-format="dd/mm/yyyy"
                            data-options='@json(['endDate' => now()->format('d/m/Y')])' name="parents[0][tanggal_lahir]"
                            placeholder="Tanggal Lahir">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-md-4 col-form-label text-bold">Anak Ke</label>
                    <div class="col-md-8 parent-group">
                        <input type="text" class="form-control masking-anak" name="parents[0][urutan_anak]"
                            placeholder="Anak Ke">
                        <span class="form-text text-muted">*Kosongkan jika keluarga bukan sebagai anak</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-md-4 col-form-label text-bold">Alamat Lengkap</label>
                    <div class="col-md-8 parent-group">
                        <textarea name="parents[0][alamat]" class="form-control" id="" cols="30" rows="3"
                            placeholder="Alamat Lengkap"></textarea>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group row">
                    <label for="" class="col-md-4 col-form-label text-bold">Jenis Kelamin</label>
                    <div class="col-md-8 parent-group">
                        <select name="parents[0][jenis_kelamin]" id="" class="form-control base-plugin--select2">
                            <option value=""></option>
                            <option value="L">Laki - Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-md-4 col-form-label text-bold">Agama</label>
                    <div class="col-md-8 parent-group">
                        <select name="parents[0][agama_id]" id="" class="form-control base-plugin--select2">
                            <option value=""></option>
                            @foreach (\App\Entities\EHC\Agama::get() as $item)
                                <option value="{{ $item->Sandi }}">{{ $item->Lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-md-4 col-form-label text-bold">Nomor Telepon / HP</label>
                    <div class="col-md-8 parent-group">
                        <input type="text" class="form-control base-plugin--inputmask_phone" maxlength="12"
                            name="parents[0][telepon]" placeholder="Nomor Telepon / HP">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-md-4 col-form-label text-bold">Kewarganegaraan</label>
                    <div class="col-md-8 parent-group">
                        <select class="form-control base-plugin--select2" name="parents[0][kewarganegaraan]" data-placeholder="Pilih Kewarganegaraan">
                            <option value="WNI">WNI</option>
                            <option value="WNA">WNA</option>
                        </select>
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

