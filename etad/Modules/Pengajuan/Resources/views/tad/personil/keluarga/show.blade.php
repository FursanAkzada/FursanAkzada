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
                <div class="col">
                    <div class="form-group row">
                        <label for="" class="col-4 text-bold">Keluarga Sebagai</label>
                        <div class="col parent-group">
                            <input type="text" class="form-control" value="{{ $keluarga->tipeKeluarga->tipe }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-4 text-bold">Nama</label>
                        <div class="col parent-group">
                            <input type="text" class="form-control" value="{{ $keluarga->nama }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-4 text-bold">Anak Ke</label>
                        <div class="col parent-group">
                            <input type="text" class="form-control" value="{{ $keluarga->urutan_anak ?? '-' }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-4 text-bold">Tempat & Tgl Lahir</label>
                        <div class="col parent-group">
                            <input type="text" class="form-control" value="{{ $keluarga->tempat_lahir }}" disabled>
                        </div>
                        <div class="col parent-group">
                            <input type="text" class="form-control" value="{{ $keluarga->tanggal_lahir->format('d/m/Y') }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-4 text-bold">Alamat Lengkap</label>
                        <div class="col parent-group">
                            <input type="text" class="form-control" cols="30" rows="3" value="{{ $keluarga->alamat }}" disabled>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="form-group row">
                        <label for="" class="col-6 text-bold">Jenis Kelamin</label>
                        <div class="col-6 parent-group">
                            <input type="text" class="form-control" value="{{ $keluarga->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-6 text-bold">Agama</label>
                        <div class="col-6 parent-group">
                            <input type="text" class="form-control" value="{{ $keluarga->agama->Lengkap }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-6 text-bold">Nomor HP</label>
                        <div class="col-6 parent-group">
                            <input type="text" class="form-control" value="{{ $keluarga->telepon }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-6 text-bold">Kewarganegaraan</label>
                        <div class="col-6 parent-group">
                            <input type="text" class="form-control" value="{{ $keluarga->kewarganegaraan }}" disabled>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

<script>
    $('#modal .modal-md').removeClass('modal-md').addClass('modal-xl');
</script>
