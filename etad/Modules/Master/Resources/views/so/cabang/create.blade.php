<form action="{{ route($route . '.store') }}" method="POST">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="parent_id">Parent</label>
            <div class="col-sm-8 parent-group">
                <select name="parent_id" class="form-control base-plugin--select2 show-tick" data-size="7"
                    data-live-search="true" title="Pilih parent">
                    <option value=""></option>
                    @foreach ($parents as $item)
                        <option value="{{ $item->id }}" data-parent='{{ $item->parent }}'
                            data-provinsi-id='{{ $item->province_id }}' data-city-id='{{ $item->city_id }}'>
                            {{ $item->name }}</option>
                    @endforeach
                </select>
                <span class="form-text text-muted">*Parent berupa Direksi</span>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Kode Cabang</label>
            <div class="col-sm-8 parent-group">
                <input type="text" class="form-control" name="code" value="" placeholder="Kode">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Nama Cabang</label>
            <div class="col-sm-8 parent-group">
                <input type="text" class="form-control" name="name" value="" placeholder="Nama">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Alamat</label>
            <div class="col-sm-8 parent-group">
                <textarea name="address" id="" cols="30" rows="2" placeholder="Alamat" class="form-control"></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="province">Provinsi</label>
            <div class="col-sm-8 parent-group">
                <select name="province_id" class="form-control base-plugin--select2 show-tick" data-size="7"
                    data-live-search="true" title="Pilih provinsi" id="provinsi">
                    @foreach ($province as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="city">Kota / Kabupaten</label>
            <div class="col-sm-8 parent-group">
                <select name="city_id" class="form-control base-plugin--select2 show-tick" data-size="7"
                    data-live-search="true" title="Pilih kota / kabupaten" id="kabupaten">
                    @foreach ($city as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Telepon</label>
            <div class="col-sm-8 parent-group">
                <input type="text" class="form-control base-plugin--inputmask_int" name="phone" value=""
                    placeholder="Telepon">
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
        $('#provinsi').val('{{ $root->province_id ?? '' }}');
        $('#provinsi').change();
        setTimeout(() => {
            $('#kabupaten').val('{{ $root->city_id ?? '' }}');
            $('#kabupaten').change();
        }, 1000);
    });
</script>
