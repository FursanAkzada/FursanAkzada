<form action="{{ route($route . '.update', $record->id) }}" method="POST">
    @csrf
    @method('PATCH')
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="year">Tahun</label>
            <input class="form-control base-plugin--datepicker-3" id="year" name="year" placeholder="Tahun"
                value="{{ $record->year }}">
        </div>
        <div class="form-group">
            <label for="">Semester</label>
            <select class="form-control base-plugin--select2 show-tick" name="semester" placeholder="Pilih Semester">
                <option value="">Pilih Semester</option>
                <option value="Satu" @if ($record->semester == 'Satu') selected @endif>Satu</option>
                <option value="Dua" @if ($record->semester == 'Dua') selected @endif>Dua</option>
            </select>
        </div>
        <div class="form-group">
            <label for="">Tipe Struktur</label>
            <input type="hidden" name="level" value="{{ $record->level }}">
            <select class="form-control base-plugin--select2 show-tick" disabled name="level"
                placeholder="Pilih Tipe Struktur">
                <option value="">Pilih Tipe Struktur</option>
                <option {{ $record->level === 'bod' ? 'selected' : '' }} value="bod">Direksi</option>
                <option {{ $record->level === 'vice' ? 'selected' : '' }} value="vice">SEVP</option>
                <option {{ $record->level === 'division' ? 'selected' : '' }} value="division">Divisi</option>
                <option {{ $record->level === 'departemen' ? 'selected' : '' }} value="departemen">Sub Divisi</option>
                <option {{ $record->level === 'cabang' ? 'selected' : '' }} value="cabang">Cabang</option>
                <option {{ $record->level === 'capem' ? 'selected' : '' }} value="capem">Cabang Pembantu</option>
                <option {{ $record->level === 'kas' ? 'selected' : '' }} value="kas">Kantor Kas</option>
            </select>
        </div>
    </div>
    <div class="modal-footer border-0 pt-0">
        {{-- <x-btn-save via="base-form--submit-page" /> --}}
        <button type="submit" data-swal-confirm="false" data-rusmen="true"
            class="btn btn-info d-flex align-items-center base-form--submit-page">
            <i class="fas fa-save mr-2"></i>Simpan
        </button>
    </div>
</form>
