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
            <th class="text-center">Nama</th>
            <th class="text-center">NIO</th>
            <th>Vendor</th>
            <th>Jabatan</th>
            <th>Tgl. Pengajuan Resign</th>
            <th>Tgl. Completed Resign</th>
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
            <td class="align-middle">
                @if($record->active_logs->status == 'approved.hc')
                    {{ $carbon->parse($item->pivot->tanggal_efektif)->format('d/m/Y') }}
                @else
                    <input type="text" name="pegawai[{{ $item->id }}][tanggal_efektif]"
                    class="form-control base-plugin--datepicker"
                    data-format="dd/mm/yyyy"
                    data-language="en" value="{{ $carbon->parse($item->pivot->tanggal_resign)->format('d/m/Y') }}" placeholder="Tanggal Resign Completed">
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
