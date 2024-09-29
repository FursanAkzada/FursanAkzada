<form action="{{ route($route . '.update', $record->id) }}" method="POST">
    @method('put')
    @csrf
    <input type="hidden" name="id" value="{{ $record->id }}">
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="parent_id">Parent</label>
            <div class="col-sm-8 parent-group">
                <input type="text" class="form-control" placeholder="Parent"
                    value="{{ $record->parent->name ?? '' }}" disabled>
                <span class="form-text text-muted">*Parent berupa Direksi</span>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Kode Cabang</label>
            <div class="col-sm-8 parent-group">
                <input type="text" class="form-control" name="code" placeholder="Kode"
                    value="{{ $record->code }}" disabled>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Nama Cabang</label>
            <div class="col-sm-8 parent-group">
                <input type="text" id="name" name="name" class="form-control" placeholder="Nama"
                    value="{{ $record->name }}" disabled>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Alamat</label>
            <div class="col-sm-8 parent-group">
                <textarea name="address" id="" cols="30" rows="4" placeholder="Alamat" class="form-control" disabled>{{ $record->address }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="province">Provinsi</label>
            <div class="col-sm-8 parent-group">
                <input type="text" class="form-control" placeholder="Provinsi"
                    value="{{ $record->province->name ?? '' }}" disabled>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="city">Kota / Kabupaten</label>
            <div class="col-sm-8 parent-group">
                <input type="text" class="form-control" placeholder="Kota / Kabupaten"
                    value="{{ $record->city->name ?? '' }}" disabled>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Telepon</label>
            <div class="col-sm-8 parent-group">
                <input type="text" class="form-control base-plugin--inputmask_int" name="phone"
                    value="{{ $record->phone }}" placeholder="Telepon" disabled>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        //Get data provinsi
        $('#provinsi').on('change', function() {
            $.ajax({
                    url: '{{ route($route . '.city') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        province: this.value
                    },
                })
                .done(function(response) {
                    $('#kabupaten').html(response)
                    BasePlugin.initSelectpicker();
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    console.log("complete");
                });
        });
    });
</script>
