<form action="{{ route($route . '.store') }}" method="POST">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="">Kompetensi</label>
            <input type="text" class="form-control" name="kompetensi" placeholder="Kompetensi">
        </div>
        <div class="form-group">
            <label for="">Uraian</label>
            <textarea class="form-control" name="uraian" id="" cols="30" placeholder="Uraian"></textarea>
        </div>
    </div>
    <div class="pt-0 border-0 modal-footer">
        {{-- <x-btn-save via="base-form--submit-page" /> --}}
        <button type="submit" data-swal-confirm="false" data-rusmen="true"
            class="btn btn-info d-flex align-items-center base-form--submit-page">
            <i class="fas fa-save mr-2"></i>Simpan
        </button>
    </div>
</form>
<script>
    $('#modal .modal-lg').removeClass('modal-lg');
</script>
