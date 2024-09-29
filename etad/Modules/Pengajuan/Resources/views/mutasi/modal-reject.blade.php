<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="keterangan" class="form-control" placeholder="Deskripsi"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <x-btn-save via="base-form--submit-page" class="btn-danger" label="Reject" />
            </div>
        </div>
    </div>
</div>
