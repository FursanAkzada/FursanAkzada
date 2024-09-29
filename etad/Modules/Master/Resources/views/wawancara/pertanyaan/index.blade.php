@extends('layouts.grid.list')
@section('filters')
    <div class="mr-2">
        <input type="text" class="form-control filter-control" data-post="keyword" placeholder="Nama">
    </div>

    <div class="mr-2">
        <select class="form-control filter-control base-plugin--select2" data-post="kompetensi_id" title="Kompetensi">
            <option selected disabled></option>
            @foreach (\Modules\Master\Entities\Wawancara\Kompetensi::get() as $item)
                <option value="{{ $item->id }}">{{ $item->kompetensi }}</option>
            @endforeach
        </select>
    </div>
@endsection
@section('buttons-after')
    @if (\Route::has($route . '.create'))
        <a href="{{ route($route . '.create') }}" class="ml-2 btn btn-info base-modal--render" data-modal-size="modal-lg">
            <i class="mr-1 fa fa-plus"></i> {{ __('Data') }}
        </a>
    @endif
@endsection
