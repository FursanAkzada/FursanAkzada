@inject('carbon', '\Carbon\Carbon')

<div class="form-group row">
    <label for="" class="col-2 text-bold">Unit Kerja</label>
    <label for="" class="col">: {{ $record->cabang->CAPEM }}</label>
</div>
<div class="form-group row">
    <label for="" class="col-2 text-bold">Tanggal Pengajuan</label>
    <label for="" class="col">: {{ $record->created_at->format('d/m/Y') }}</label>
</div>
<div class="form-group row">
    <label for="" class="col-2 text-bold">Nomor Tiket</label>
    <label for="" class="col font-italic">: {{ $record->no_tiket }}</label>
</div>

<div class="form-group row">
    <div class="col-3">
        <a href="{{ url('storage/'.$record->surat_filepath) }}" class="btn btn-block btn-warning btn-sm"><i class="fa fa-download mr-3"></i>Download Surat Permohonan</a>
    </div>
</div>
<hr class="my-8">
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama</th>
            <th>NIO</th>
            <th>Vendor</th>
            <th>Jabatan</th>
            <th>Tgl. Pengajuan Resign</th>
            @if($record->active_logs->status == 'approved.hc')
            <th>Tgl. Completed Resign</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($record->pegawai as $item)
        <tr>
            <td class="align-middle">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-30 symbol-circle mr-2" data-toggle="tooltip" title="{{ $item->nama }}">
                        @if (\Storage::exists($item->file->foto_filepath))
                            <img alt="Pic" src="{{ url('storage/'.$item->file->foto_filepath) }}" />
                        @else
                            <span class="symbol-label font-size-h5">{{ $item->nama[0] }}</span>
                        @endif
                    </div>
                    {{ $item->nama }}
                </div>
            </td>
            <td class="text-center">{{ $item->kepegawaian->nio ?? '-'}}</td>
            <td class="align-middle">{{ $item->vendor->nama }}</td>
            <td class="align-middle">{{ $item->jabatan->NM_UNIT }}</td>
            <td class="align-middle">{{ $carbon->parse($item->pivot->tanggal_resign)->format('d/m/Y') }}</td>
            @if($record->active_logs->status == 'approved.hc')
            <td class="align-middle">
                {{ $carbon->parse($item->pivot->tanggal_efektif)->format('d/m/Y') }}
            </td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
