<form action="{{ route($route . '.store') }}" method="POST">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Nama</label>
            <div class="col-sm-8 parent-group">
                <input type="text" class="form-control" name="name" value="" placeholder="Nama">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Alamat</label>
            <div class="col-sm-8 parent-group">
                <textarea name="address" id="" cols="30" rows="4" placeholder="Alamat" class="form-control"></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Provinsi</label>
            <div class="col-sm-8 parent-group">
                <input type="text" class="form-control" name="province" value="" placeholder="Provinsi">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Kota / Kabupaten</label>
            <div class="col-sm-8 parent-group">
                <input type="text" class="form-control" name="city" value="" placeholder="Kota / Kabupaten">
            </div>
        </div>
    </div>
    <div class="modal-footer pt-0 border-0">
        <button type="submit" 
            class="btn btn-info base-form--submit-modal">
            <i class="fa fa-save mr-1"></i>
            {{ __('Simpan') }}
        </button>
    </div>
</form>
