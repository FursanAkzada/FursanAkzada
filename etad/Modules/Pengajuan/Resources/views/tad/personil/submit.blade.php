<form action="{{ route('personil.migrasi.import-save', ['tipe_import' => $tipe])  }}" method="GET">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            @if($tipe == 'quota-aktif')
            <h4 class="modal-title text-center" style="font-weight: 500;font-size: 1.3rem;color: #181C32;">{{ __('Migrasi quota telah dilakukan. Yakin untuk melakukan migrasi kembali?') }}</h4>
            @else
            <h4 class="modal-title text-center" style="font-weight: 500;font-size: 1.3rem;color: #181C32;">{{ __('Migrasi personil telah dilakukan. Yakin untuk melakukan migrasi kembali?') }}</h4>
            @endif
        </div>
    </div>
    <div class="modal-footer pt-0 border-0">
        <div class="d-flex justify-content-between w-100">
            <button type="button" class="btn btn-secondary btn-back" data-dismiss="modal">
                <i class="fa fa-chevron-left mr-1"></i>
                {{ __('Kembali') }}
            </button>
            <button type="submit" class="btn btn-primary base-form--submit-modal">
                <i class="fa fa-sync mr-1"></i>
                {{ __('Migrasi') }}
            </button>
        </div>
    </div>
</form>

<script>
	$('.modal-dialog').removeClass('modal-dialog-right-bottom');
</script>
