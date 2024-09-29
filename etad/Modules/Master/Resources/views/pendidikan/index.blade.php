@extends('layouts.grid.list')
@section('filters')
    <div class="mr-2">
        <input type="text" class="form-control filter-control" data-post="keyword" placeholder="Nama">
    </div>
@endsection
@section('buttons-after')
    @if (\Route::has($route . '.create'))
        <a href="{{ route($route . '.create') }}" class="ml-2 btn btn-info base-modal--render" data-modal-size="modal-lg">
            <i class="mr-1 fa fa-plus"></i> {{ __('Data') }}
        </a>
    @endif
@endsection
