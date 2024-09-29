<form action="{{ route($route.'.store') }}" method="POST">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="">Provinsi</label>
            <select name="province_id" class="form-control base-plugin--select2 show-tick">
                @foreach ($province as $a)
                    <option value="{{$a->id}}">{{$a->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
        <label for="">Kota/Kabupaten</label>
            <input class="form-control" type="text" placeholder="Kota/Kabupaten" name="name">
        </div>
    </div>
    <div class="pt-0 border-0 modal-footer">
        {{-- <x-btn-save via="base-form--submit-modal" /> --}}
        <button type="submit" data-swal-confirm="false" data-rusmen="true"
            class="btn btn-info d-flex align-items-center base-form--submit-modal">
            <i class="fas fa-save mr-2"></i>Simpan
        </button>
    </div>
</form>
