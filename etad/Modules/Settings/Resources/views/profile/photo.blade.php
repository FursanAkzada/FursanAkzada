<form action="{{ route($route . '.photo.store') }}" autocomplete="off" method="POST">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">Foto Profil</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="img-result">
            <img src="{{ auth()->user()->photo ? '/storage/profile/' . auth()->user()->photo : '' }}"
                id="previewHolder" class="img-fluid mb-2 rounded" alt="">
        </div>
        <div class="form-group">
            <div class="custom-file">
                <input type="file" name="foto" id="filePhoto" accept=".jpg,.jpeg,.png"
                    class="custom-file-input" />
                <label class="custom-file-label" for="file" style="color:#B5B5C3;font-weight:400;">Pilih file</label>
                <span class="form-text text-muted">Lampirkan File dengan format .jpg / .png / .jpeg, Ukuruan Maks :
                    500kb</span>
            </div>
        </div>
    </div>
    <div class="modal-footer border-0 pt-0">
        {{-- <div class="mr-2 d-none btn-back" url="{{ route('settings.profile.index') }}"></div> --}}
        <x-btn-save via="base-form--submit-modal" />
    </div>
</form>

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#previewHolder').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            alert('select a file to see preview');
            $('#previewHolder').attr('src', '');
        }
    }

    $("#filePhoto").change(function() {
        readURL(this);
    });
</script>
