{{-- {{ dd('edit-quota', json_decode($quota)) }} --}}
<form action="{{ route($route . '.detail.update-quota', $quota->id) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="pengajuan_tad_quota_periode_id" value="{{ $quota->periode->id }}">
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="org_struct_id">Struktur</label>
            <select name="org_struct_id" class="form-control base-plugin--select2 lokasi" data-size="7"
                data-live-search="true" title="Pilih Struktur">
                <option value=""></option>
                @foreach ($ORG_STRUCT as $group)
                    @foreach ($group as $val)
                        @if ($loop->first)
                            <optgroup label="{{ $val->show_level }}">
                        @endif
                            <option value="{{ $val->id }}" @if ($quota->org_struct_id == $val->id) selected @endif>
                                {{ $val->name }}</option>
                        @if ($loop->last)
                            </optgroup>
                        @endif
                    @endforeach
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="">Posisi TAD</label>
            <select class="form-control base-plugin--select2 show-tick" name="posisi_tad_id"
                data-placeholder="Pilih Posisi TAD">
                <option value=""></option>
                @foreach ($KATEGORI_VENDOR as $kategori_vendor)
                    <optgroup label="{{ $kategori_vendor->nama }}">
                        @foreach ($kategori_vendor->jabatanTad as $item)
                            <option value="{{ $item->idunit }}" @if ($quota->posisi_tad_id == $item->idunit) selected @endif>
                                {{ $item->NM_UNIT }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="quota">Quota</label>
            <input class="form-control masking-quota" id="quota" inputmode="numeric" min="0" max="1000" maxlength="1000"
                name="quota" placeholder="Quota" type="number" value="{{ $quota->quota }}">
        </div>
    </div>
    <div class="pt-0 border-0 modal-footer">
        {{-- <x-btn-save via="base-form--submit-page" /> --}}
        <button type="submit" data-swal-confirm="false" data-rusmen="true"
            class="btn btn-info d-flex align-items-center base-form--submit-modal">
            <i class="fas fa-save mr-2"></i>Simpan
        </button>
    </div>
</form>
