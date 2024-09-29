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
            <label class="col-4" for="parent_id">Parent</label>
            <div class="parent-group col-8">
                <select name="parent_id" class="form-control base-plugin--select2 show-tick" data-size="7"
                    data-live-search="true" title="Pilih parent">
                    @foreach ($parents as $item)
                        <option value="{{ $item->id }}" {{ $record->parent_id == $item->id ? 'selected' : '' }}>
                            {{ $item->name }}</option>
                    @endforeach
                </select>
                <span class="form-text text-muted">*Parent berupa Cabang</span>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-4" for="">Kode</label>
            <div class="parent-group col-8">
                <input type="text" class="form-control" name="code" value="{{ $record->code }}"
                    placeholder="Kode">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-4" for="">Nama</label>
            <div class="parent-group col-8">
                <input type="text" class="form-control" name="name" value="{{ $record->name }}"
                    placeholder="Nama">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-4" for="">Alamat</label>
            <div class="parent-group col-8">
                <textarea name="address" id="" cols="30" rows="4" placeholder="Alamat" class="form-control">{{ $record->address }}</textarea>
            </div>
        </div>
        {{-- <div class="form-group">
            <label for="province">Provinsi</label>
            <select name="province_id" class="form-control base-plugin--select2 show-tick" data-size="7" data-live-search="true" title="Pilih provinsi" id="provinsi">
                @foreach ($province as $item)
                <option value="{{ $item->id }}" {{ $record->province_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="city">Kota / Kabupaten</label>
            <select name="city_id" class="form-control base-plugin--select2 show-tick" data-size="7" data-live-search="true" title="Pilih kota / kabupaten" id="kabupaten">
                @foreach ($city as $item)
                <option value="{{ $item->id }}" {{ $record->city_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                @endforeach
            </select>
        </div> --}}
        <div class="form-group row">
            <label class="col-4" for="">Telepon</label>
            <div class="parent-group col-8">
                <input type="text" class="form-control base-plugin--inputmask_int" name="phone"
                    value="{{ $record->phone }}" placeholder="Telepon">
            </div>
        </div>
    </div>
    <div class="modal-footer border-0 pt-0">
        <button type="submit" class="btn btn-info base-form--submit-modal">
            <i class="fa fa-save mr-1"></i>
            {{ __('Simpan') }}
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
