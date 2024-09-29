{{-- {{ dd(json_decode($record), json_decode($TAD)) }} --}}
<form action="{{ route($route.'.kandidat.update',$record->id) }}" method="POST">
    @method('put')
    @csrf
    <input type="hidden" name="id" value="{{ $record->id }}">
    <div class="modal-header">
        <h4 class="modal-title">Ubah Kandidat TAD</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="">Posisi TAD</label>
            <input class="form-control" readonly value="{{ $record->requirement->jabatan->NM_UNIT }}">
        </div>
        <div class="form-group">
            <label for="">Kandidat ditolak</label>
            <input class="form-control" readonly value="{{ $record->tad->nama }}">
        </div>
        <div class="form-group">
            <label for="kandidat_baru">Kandidat Baru</label>
            <select name="kandidat_baru" class="form-control base-plugin--select2 show-tick" data-placeholder="Pilih Kandidat">
                <option></option>
                @foreach ($TAD as $item)
                    <option value="{{ $item->id }}" >{{ $item->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer pt-0 border-0">
        {{-- <x-btn-save via="base-form--submit-modal" /> --}}
        <button type="submit" data-swal-confirm="true" class="btn btn-info d-flex align-items-center base-form--submit-modal">
            <i class="fas fa-save mr-2"></i>Simpan
        </button>
    </div>
</form>
