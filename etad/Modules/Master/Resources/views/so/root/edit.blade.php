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
            <label class="col-sm-4 col-form-label" for="name">Nama</label>
            <div class="col-sm-8 parent-group">
                <input type="text" id="name" name="name" class="form-control" placeholder="Nama"
                    value="{{ $record->name }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="name">Email</label>
            <div class="col-sm-8 parent-group">
                <input type="text" id="name" name="email" class="form-control" placeholder="Email"
                    value="{{ $record->email }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="name">Alamat</label>
            <div class="col-sm-8 parent-group">
                <input type="text" id="name" name="address" class="form-control" placeholder="Alamat"
                    value="{{ $record->address }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="province">Provinsi</label>
            <div class="col-sm-8 parent-group">
                <select name="province_id" class="form-control base-plugin--select2 show-tick" data-size="7"
                    data-live-search="true" title="Pilih provinsi" id="provinsi">
                    <option value="">(Pilih Salah Satu)</option>
                    @foreach ($province as $item)
                        <option value="{{ $item->id }}" {{ $record->province_id == $item->id ? 'selected' : '' }}>
                            {{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="city">Kota / Kabupaten</label>
            <div class="col-sm-8 parent-group">
                <select name="city_id" class="form-control base-plugin--select2 show-tick" data-size="7"
                    data-live-search="true" title="Pilih kota / kabupaten" id="kabupaten">
                    <option value="">(Pilih Salah Satu)</option>
                    @foreach ($city as $item)
                        <option value="{{ $item->id }}" {{ $record->city_id == $item->id ? 'selected' : '' }}>
                            {{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="name">Telepon</label>
            <div class="col-sm-8 parent-group">
                <input type="text" id="name" name="phone" class="form-control" placeholder="Telepon"
                    value="{{ $record->phone }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="name">Fax</label>
            <div class="col-sm-8 parent-group">
                <input type="text" id="name" name="fax" class="form-control" placeholder="Fax"
                    value="{{ $record->fax }}">
            </div>
        </div>
    </div>
    <div class="modal-footer pt-0 border-0">
        {{-- <x-btn-save via="base-form--submit-modal" /> --}}
        <button type="submit" data-swal-confirm="false" data-rusmen="true"
            class="btn btn-info d-flex align-items-center base-form--submit-modal">
            <i class="fas fa-save mr-2"></i>Simpan
        </button>
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
