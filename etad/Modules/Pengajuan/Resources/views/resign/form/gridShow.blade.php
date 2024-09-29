@inject('carbon', '\Carbon\Carbon')

<div class="modal-header">
    <h4 class="modal-title">Resign</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
            class="ki ki-close"></i></button>
</div>
<div class="modal-body">
    {{-- <td class="align-middle">
        <div class="d-flex align-items-center">
            <div class="symbol symbol-30 symbol-circle mr-2" data-toggle="tooltip"
                title="{{ $item->nama }}">
                @if (isset($item->file->foto_filepath) && \Storage::exists($item->file->foto_filepath))
                    <img alt="Pic" src="{{ url('storage/' . $item->file->foto_filepath) }}" />
                @else
                    <span class="symbol-label font-size-h5">{{ $item->nama[0] }}</span>
                @endif
            </div>
            {{ $pengajuan->nama }}
        </div>
    </td> --}}
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for="name">@lang('Nama')</label>
        <div class="col-sm-8 parent-group">
            <input class="form-control" disabled placeholder="@lang('Nama')" value="{{ $pengajuan->nama }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for="name">@lang('NIO')</label>
        <div class="col-sm-8 parent-group">
            <input class="form-control" disabled placeholder="@lang('Nama')" value="{{ $pengajuan->kepegawaian->nio }}">
        </div>
    </div>
    
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for="name">@lang('Vendor')</label>
        <div class="col-sm-8 parent-group">
            <input class="form-control" disabled placeholder="@lang('Nama')" value="{{ $pengajuan->vendor->nama }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for="name">@lang('Jabatan')</label>
        <div class="col-sm-8 parent-group">
            <input class="form-control" disabled placeholder="@lang('Nama')" value="{{ $pengajuan->jabatan->NM_UNIT }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for="name">@lang('Tgl Resign')</label>
        <div class="col-sm-8 parent-group">
            <input class="form-control" disabled placeholder="@lang('Nama')" value="{{ $pengajuan->pengajuanResignPegawai[0]->tanggal_resign->format('d/m/Y') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for="name">@lang('Tgl Efektif')</label>
        <div class="col-sm-8 parent-group">
            <input class="form-control" disabled placeholder="@lang('Nama')" value="{{ $pengajuan->pengajuanResignPegawai[0]->tanggal_efektif->format('d/m/Y') }}">
        </div>
    </div>
</div>
