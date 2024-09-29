{{-- <form action="{{ route($route . '.importSave') }}" method="POST">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">Personil TAD</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-md-4 col-form-label">{{ __('Import Excel') }}</label>
            <div class="col-md-8 parent-group">
                <div class="custom-file">
                    <input type="hidden" name="uploads[uploaded]" class="uploaded" value="">
                    <input type="file" accept=".xlsx,.xls" class="custom-file-input base-form--save-temp-files" data-name="uploads"
                        data-container="parent-group" data-max-size="20024" data-max-file="1">
                    <label class="custom-file-label" for="file">Choose File</label>
                </div>
                <div class="form-text text-muted">*Pastikan file sesuai dengan template terbaru</div>
            </div>
        </div>
    </div>
    <div class="modal-footer pt-0 border-0">
        <x-btn-save via="base-form--submit-modal" confirm="0"/>
    </div>
</form> --}}

<form action="{{ route($route . '.importSave') }}" method="POST">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">Personil TAD</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-md-4 col-form-label">{{ __('Pilih Data') }}</label>
            <div class="col-md-8 parent-group">
                <select name="tipe_import" id="pendidikan" class="form-control base-plugin--select2">
                    <option value=""></option>
                        <option value="aktif">TAD AKTIF</option>
                        <option value="nonaktif">TAD NON AKTIF</option>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer pt-0 border-0">
        <x-btn-save via="base-form--submit-modal" confirm="0"/>
    </div>
</form>
