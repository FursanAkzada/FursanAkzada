<form action="{{ route($route.'.kandidat.tl.update',$requirement->id) }}" method="POST">
  @csrf
  @method('put')
  <div class="modal-header">
    <h4 class="modal-title">{{ $title }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close"></i></button>
  </div>
  <div class="modal-body">
    {{-- \Modules\Pengajuan\Entities\Tad\Wawancara\Wawancara::ongoing($r->pivot->id)->first(); --}}
    <div class="form-group row">
      <label for="" class="text-bold col-4">Kandidat</label>
      <div class="col">
        @foreach ($requirement->kandidat as $item)
          <li>
            {{ $item->nama }}
            {{-- @switch($item->pivot->accepted)
                @case(1)
                    <span class="badge badge-success" data-toggle="">Diterima</span>
                    @break
                @case(2)
                    <span class="badge badge-danger" data-toggle="">Ditolak</span>
                    @break
                @default
                    <span class="badge badge-light">Penentuan</span>
                    @break
            @endswitch --}}
          </li>
        @endforeach
      </div>
    </div>
    <div class="form-group row">
      <label for="" class="text-bold col-4">Nomor Surat Persetujuan</label>
      <div class="col parent-group">
        <input type="text" class="form-control" name="no_surat" value="{{ $requirement->tl->no_surat }}" placeholder="Nomor Surat Persetujuan">
      </div>
    </div>
    <div class="form-group row">
      <label for="" class="col-4 text-bold">Lampiran Surat Persetujuan</label>
      <div class="col parent-group">
        <div class="custom-file">
          <input type="file" name="surat" accept=".pdf" class="custom-file-input" />
          <label class="custom-file-label" for="file" style="color:#B5B5C3;font-weight:400;">Pilih file</label>
          <span class="form-text text-muted">Lampirkan File Surat Persetujuan dengan format .pdf</span>
          <span class="form-text text-info">Kosongkan jika tidak ganti</span>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer pt-0 border-0">
    <x-btn-save via="base-form--submit-modal" />
  </div>
</form>
