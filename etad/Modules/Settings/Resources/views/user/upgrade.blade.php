
<form action="{{ route($route . '.saveUpgrade', $record->id) }}" method="POST">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">Reset Password {{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <div class="parent-group">
                Reset password akan mengubah password menjadi: qwerty123456
            </div>
        </div>
    </div>
    <div class="pt-0 border-0 modal-footer">
        <button type="submit" data-swal-confirm="false" data-rusmen="true"
            class="btn btn-info d-flex align-items-center base-form--submit-page">
            <i class="fas fa-key mr-2"></i>Reset
        </button>
    </div>
</form>

<script>
	$('.modal-dialog').removeClass('modal-dialog-right-bottom');
</script>

