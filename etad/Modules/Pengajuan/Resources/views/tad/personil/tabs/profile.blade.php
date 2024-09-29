{{-- {{ dd(json_decode($pengajuan)) }} --}}
@if (isset($pengajuan))
    <h4 class="card-title mb-0">
        Informasi Pengajuan</h4>
    <hr>
    <div class="row">
        <div class="col">
            <div class="form-group row">
                <label for="" class="col-4 text-bold">Penempatan Unit Kerja</label>
                <label for="" class="col">: {{ $pengajuan->so->name ?? '' }}</label>
            </div>
            <div class="form-group row">
                <label for="" class="col-4 text-bold">Penempatan Jabatan</label>
                <label for="" class="col">:
                    {{ $pengajuan->requirement[0]->jabatan->NM_UNIT ?? '' }}</label>
            </div>9
            {{-- <div class="form-group row">
                <label for="" class="col-4 text-bold">Tanggal Pengajuan</label>
                <label for="" class="col">: {{ $pengajuan->created_at->format('d/m/Y') }}</label>
            </div> --}}
            <div class="form-group row">
                <label for="" class="col-4 text-bold">Pengajuan Untuk</label>
                <label for="" class="col">: Tahun {{ $pengajuan->year }} Semester
                    {{ $pengajuan->semester }}</label>
            </div>
            <div class="form-group row">
                <label for="" class="col-4 text-bold">Nomor Tiket</label>
                <label for="" class="col font-italic">: {{ $pengajuan->no_tiket }}</label>
            </div>
        </div>
        <div class="col">
            <div class="form-group row mb-5">
                <div class="col">
                    <a class="btn btn-block btn-primary btn-sm" download="{{ $pengajuan->so_filename }}"
                        href="{{ url('storage/' . $pengajuan->so_filepath) }}">
                        <i class="fa fa-download mr-3"></i>Download SO</a>
                </div>
                <div class="col">
                    <a class="btn btn-block btn-warning btn-sm" download="{{ $pengajuan->surat_filename }}"
                        href="{{ url('storage/' . $pengajuan->surat_filepath) }}">
                        <i class="fa fa-download mr-3"></i>Download Surat Permohonan</a>
                </div>
            </div>
        </div>
    </div>
    <h4 class="card-title mb-0">Informasi Data Diri</h4>
    <hr>
@endif
<div class="row">
    <table class="table-bordered w-100 table">
        <tr>
            <td style="width: 200px"><b>Pas Foto</b></td>
            <td style="width: 2em">:</td>
            <td style="width: 30%">
                @if ($record->files->where('flag', 'foto3x4')->count() > 0)
                    @php
                        $temp = $record->files->where('flag', 'foto3x4');
                    @endphp
                    <span class="foto cursor-pointer" data-path="{{ $temp->first()->file_path }}" data-toggle="modal"
                        data-target="#exampleModal">{{ $temp->first()->file_name }}</span>
                @endif
            </td>
            <td style="width: 200px"><b>Foto Full Body</b></td>
            <td style="width: 2em">:</td>
            <td>
                @if ($record->files->where('flag', 'foto_fullbody')->count() > 0)
                    @php
                        $temp = $record->files->where('flag', 'foto_fullbody');
                    @endphp
                    <span class="foto cursor-pointer" data-path="{{ $temp->first()->file_path }}" data-toggle="modal"
                        data-target="#exampleModal">{{ $temp->first()->file_name }}</span>
                @endif
            </td>
        </tr>
        <tr>
            <td><b>Nama / NIO</b></td>
            <td>:</td>
            <td>{{ $record->nama }} @if (isset($record->kepegawaian->nio))
                    {{ ' / ' . $record->kepegawaian->nio }}
                @endif
            </td>
            <td><b>Telepon</b></td>
            <td>:</td>
            <td>{{ $record->telepon }}</td>
        </tr>
        <tr>
            <td><b>NIK</b></td>
            <td>:</td>
            <td>{{ $record->nik }}</td>
            <td><b>Email</b></td>
            <td>:</td>
            <td>{{ $record->email }}</td>
        </tr>
        <tr>
            <td><b>NPWP</b></td>
            <td>:</td>
            <td>{{ $record->npwp }}</td>
            <td><b>Alamat</b></td>
            <td>:</td>
            <td>{{ $record->alamat_lengkap }}</td>
        </tr>
        <tr>
            <td><b>Nomor BPJS</b></td>
            <td>:</td>
            <td>{{ $record->bpjs }}</td>
            <td><b>Provinsi</b></td>
            <td>:</td>
            <td>{{ $record->city->province->name ?? '' }}</td>
        </tr>
        <tr>
            <td><b>Rekening Bank Jatim</b></td>
            <td>:</td>
            <td>{{ $record->rekening_bjtm }}</td>
            <td><b>Kota</b></td>
            <td>:</td>
            <td>{{ $record->city->name ?? '' }}</td>
        </tr>
        <tr>
            <td><b>Agama</b></td>
            <td>:</td>
            <td>{{ $record->agama_raw }}</td>
            <td><b>Pendidikan Terakhir</b></td>
            <td>:</td>
            <td>
                {{ $record->pendidikan->name ?? '' }}
                @if ($record->jurusan)
                    {{ ', ' . $record->jurusan->name }}
                @endif
                @if ($record->gelar)
                    {{ ', ' . $record->gelar }}
                @endif
            </td>
        </tr>
        <tr>
            <td><b>Jenis Kelamin</b></td>
            <td>:</td>
            <td>{{ $record->attr_jenis_kelamin }}</td>
            <td><b>Rekomendasi</b></td>
            <td>:</td>
            <td>{{ $record->rekomendasi }}</td>
        </tr>
        <tr>
            <td><b>Tempat & Tgl Lahir</b></td>
            <td>:</td>
            <td>
                {{ $record->tempat_lahir }},
                {{ $record->tanggal_lahir ? $record->tanggal_lahir->format('d/m/Y') : '' }}
            </td>
            <td><b>Vendor</b></td>
            <td>:</td>
            <td>{{ $record->vendor->nama ?? ''}}</td>
        </tr>
        <tr>
            <td><b>Status Perkawinan</b></td>
            <td>:</td>
            <td>{{ $record->status_perkawinan }}</td>
            <td><b>Posisi TAD</b></td>
            <td>:</td>
            <td>{{ $record->jabatan->NM_UNIT ?? '' }}</td>
        </tr>
    </table>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <img id="imgEl" style="max-height: 500px; max-width: 100%">
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', 'span.foto', function() {
                let url = '{{ url('storage') }}/' + $(this).data('path');
                $('#imgEl').attr('src', url)
                    .prop('src', url);
            })
        });
    </script>
@endpush
