<div class="row">
    <div class="col">
        <span class="font-size-h3 mb-0">Informasi Data Diri</span>
        <div class="separator separator-dashed m-3 mb-5"></div>
        <div class="form-group row">
            <label for="" class="col-3 text-bold">NIK</label>
            <label for="" class="col">: {{ $record->nik }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-3 text-bold">Nama</label>
            <label for="" class="col">: {{ $record->nama }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-3 text-bold">Alamat</label>
            <label for="" class="col">: {{ $record->alamat_lengkap }}, {{ $record->kota_raw }},
                {{ $record->provinsi_raw }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-3 text-bold">Nomor Telepon / HP</label>
            <label for="" class="col">: {{ $record->telepon }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-3 text-bold">Email</label>
            <label for="" class="col">: {{ $record->email }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-3 text-bold">Nomor BPJS</label>
            <label for="" class="col">: {{ $record->bpjs }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-3 text-bold">NPWP</label>
            <label for="" class="col">: {{ $record->npwp }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-3 text-bold">Tempat & Tgl Lahir</label>
            <label for="" class="col">: {{ $record->tempat_lahir }},
                {{ $record->tanggal_lahir->format('d/m/Y') }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-3 text-bold">Jenis Kelamin</label>
            <label for="" class="col">: {{ $record->attr_jenis_kelamin }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-3 text-bold">Status Perkawinan</label>
            <label for="" class="col">: {{ $record->status_perkawinan }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-3 text-bold">Agama</label>
            <label for="" class="col">: {{ $record->agama_raw }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-3 text-bold">Pendidikan Terakhir</label>
            <label for="" class="col">: {{ $record->pendidikan_raw }}, {{ $record->gelar }}</label>
        </div>
        <span class="font-size-h3 mb-0">Informasi Kepegawaian</span>
        <div class="separator separator-dashed m-3 mb-5"></div>
        <div class="form-group row">
            <label for="" class="col-3 text-bold">NIO</label>
            <label for="" class="col">: {{ $record->kepegawaian->nio ?? '' }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-3 text-bold">Cabang</label>
            <label for="" class="col">: {{ $record->kepegawaian->unitKerja->name ?? '' }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-3 text-bold">Jabatan</label>
            <label for="" class="col">: {{ $record->jabatan->NM_UNIT ?? '' }}</label>
        </div>
    </div>
    <div class="col">
        <div class="text-center mb-5">
            <div class="symbol symbol-50 symbol-lg-120">
                @if (isset($record->file->foto_filepath) && \Storage::exists($record->file->foto_filepath))
                    <img alt="Pic" src="{{ url('storage/' . $record->file->foto_filepath) }}" />
                @else
                    <span class="font-size-h3 symbol-label font-weight-boldest">{{ $record->nama[0] ?? '' }}</span>
                @endif
            </div><br>
            <span class="text-bold font-italic">Foto 3x4</span>
        </div>
        <div class="text-center">
            <div class="d-block mb-2">
                @if (isset($record->file->foto_full_filepath) && \Storage::exists($record->file->foto_full_filepath))
                    <img src="{{ url('storage/' . $record->file->foto_full_filepath) }}" alt="{{ $record->nama }}"
                        class="img-responsive rounded w-50">
                @endif
            </div>
            <span class="text-bold font-italic">Foto Full Body</span>
        </div>
    </div>
</div>
