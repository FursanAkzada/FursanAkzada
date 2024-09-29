<form action="{{ route($route . '.store') }}" method="POST">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="year">Tahun</label>
            <input class="form-control base-plugin--datepicker-3" id="year" name="year" placeholder="Tahun">
        </div>
        <div class="form-group">
            <label for="">Semester</label>
            <select class="form-control base-plugin--select2 show-tick" name="semester" placeholder="Pilih Semester">
                <option value="">Pilih Semester</option>
                <option value="Satu">Satu</option>
                <option value="Dua">Dua</option>
            </select>
        </div>
        <div class="form-group">
            <label for="">Tipe Struktur</label>
            <select class="form-control base-plugin--select2 show-tick" name="level"
                placeholder="Pilih Tipe Struktur">
                <option value="">Pilih Tipe Struktur</option>
                <option value="bod">Direksi</option>
                <option value="vice">SEVP</option>
                <option value="division">Divisi</option>
                <option value="departemen">Sub Divisi</option>
                <option value="cabang">Cabang</option>
                <option value="capem">Cabang Pembantu</option>
                <option value="kas">Kantor Kas</option>
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
<script>
    $(".masking-quota").inputmask({
        "mask": "9",
        "repeat": 3,
        "greedy": false
    });
</script>
