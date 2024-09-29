@extends('layouts.grid.list')
@section('filters')
    <div class="mr-2">
        <input type="text" class="form-control filter-control" data-post="keyword" placeholder="Nama">
    </div>
    <div class="mr-2" style="width: 300px">
        <select class="form-control base-plugin--select2 show-tick filter-control" data-post="pendidikan_id"
            data-placeholder="Pendidikan" id="categoryCtrl">
            <option value="">Pilih Pendidikan</option>
            @foreach (\Modules\Master\Entities\Pendidikan::orderBy('name', 'ASC')->get() as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
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
