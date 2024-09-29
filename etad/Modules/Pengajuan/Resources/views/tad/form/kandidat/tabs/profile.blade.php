<h4 class="card-title mb-0">Informasi Pengajuan</h4>
<hr>
<div class="row">
    <div class="col">
        <div class="form-group row">
            <label for="" class="col-4 text-bold">Penempatan Unit Kerja</label>
            <label for="" class="col">: {{ $pengajuan->cabang->CAPEM }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-4 text-bold">Penempatan Jabatan</label>
            <label for="" class="col">: {{ $kandidat->requirement->jabatan->NM_UNIT }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-4 text-bold">Tanggal Pengajuan</label>
            <label for="" class="col">: {{ $pengajuan->created_at->format('d/m/Y') }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-4 text-bold">Nomor Tiket</label>
            <label for="" class="col font-italic">: {{ $pengajuan->no_tiket }}</label>
        </div>
    </div>
    <div class="col">
        <div class="form-group row mb-5">
            <div class="col">
                <a href="{{ url('storage/'.$pengajuan->so_filepath) }}" class="btn btn-block btn-primary btn-sm"><i
                        class="fa fa-download mr-3"></i>Download SO</a>
            </div>
            <div class="col">
                <a href="{{ url('storage/'.$pengajuan->surat_filepath) }}" class="btn btn-block btn-warning btn-sm"><i
                        class="fa fa-download mr-3"></i>Download Surat Permohonan</a>
            </div>
        </div>
    </div>
</div>
<h4 class="card-title mb-0">Informasi Data Diri</h4>
<hr>
<div class="d-flex mb-9">
    <!--begin: Pic-->
    <div class="flex-shrink-0 mr-7 mt-lg-0 mt-3">
        <div class="symbol symbol-50 symbol-lg-120">
            @if (\Storage::exists($tad->file->foto_filepath))
                <img alt="Pic" src="{{ url('storage/' . $tad->file->foto_filepath) }}" />
            @else
                <span class="font-size-h3 symbol-label font-weight-boldest">{{ $tad->nama[0] }}</span>
            @endif
        </div>
    </div>
    <!--end::Pic-->
    <!--begin::Info-->
    <div class="flex-grow-1">
        <!--begin::Title-->
        <div class="d-flex justify-content-between flex-wrap mt-1">
            <div class="d-flex mr-3">
                <a href="#"
                    class="text-dark-75 text-hover-primary font-size-h5 font-weight-bold mr-3">{{ $tad->nama }}</a>
            </div>
            {{-- <div class="my-lg-0 my-3">
              <a href="#" class="btn btn-sm btn-primary font-weight-bolder text-uppercase"><i class="fas fa-edit mr-1"></i>Ubah</a>
            </div> --}}
        </div>
        <!--end::Title-->
        <!--begin::Content-->
        <div class="d-flex flex-wrap justify-content-between mt-1">
            <div class="d-flex flex-column flex-grow-1 pr-8">
                <div class="d-flex flex-wrap mb-4">
                    <a href="#" class="text-dark-50 text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                        <i class="flaticon2-new-email mr-2 font-size-lg"></i>{{ $tad->email }}</a>
                    <a href="#" class="text-dark-50 text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                        <i class="flaticon2-calendar-3 mr-2 font-size-lg"></i>{{ $tad->telepon }}</a>
                    <a href="#" class="text-dark-50 text-hover-primary font-weight-bold">
                        <i class="flaticon2-placeholder mr-2 font-size-lg"></i>{{ $tad->kota_raw }}</a>
                </div>
                <span class="font-weight-bold mb-4 text-dark-50">{{ $tad->alamat_lengkap }}, {{ $tad->kota_raw }},
                    {{ $tad->provinsi_raw }}</span>
                <span class="font-weight-bold text-dark-50"><span class="text-bold">Vendor Penyedia :
                        {{ ($vendor = $tad->vendor) ? $vendor->nama : '-' }}</span></span>
            </div>
        </div>
        <!--end::Content-->
    </div>
    <!--end::Info-->
</div>
<div class="row">
    <div class="col">
        <div class="form-group row">
            <label for="" class="col-3 text-bold">NIK</label>
            <label for="" class="col">: {{ $tad->nik }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-3 text-bold">Nomor BPJS</label>
            <label for="" class="col">: {{ $tad->bpjs }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-3 text-bold">NPWP</label>
            <label for="" class="col">: {{ $tad->npwp }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-3 text-bold">Tempat & Tgl Lahir</label>
            <label for="" class="col">: {{ $tad->tempat_lahir }},
                {{ $tad->tanggal_lahir->format('d/m/Y') }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-3 text-bold">Jenis Kelamin</label>
            <label for="" class="col">: {{ $tad->attr_jenis_kelamin }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-3 text-bold">Status Perkawinan</label>
            <label for="" class="col">: {{ $tad->status_perkawinan }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-3 text-bold">Agama</label>
            <label for="" class="col">: {{ $tad->agama_raw }}</label>
        </div>
        <div class="form-group row">
            <label for="" class="col-3 text-bold">Pendidikan Terakhir</label>
            <label for="" class="col">: {{ $tad->pendidikan_raw }}, {{ $tad->gelar }}</label>
        </div>
    </div>
    <div class="col">
        <div class="d-flex justify-content-center">
            @if (\Storage::exists($tad->file->foto_full_filepath))
                <img src="{{ url('storage/'.$tad->file->foto_full_filepath) }}" alt="{{ $tad->nama }}"
                    class="img-responsive w-50">
            @endif
        </div>
    </div>
</div>
