@extends('layouts.grid.list')
@section('filters')
    <div class="mr-2">
        <input type="text" class="form-control filter-control" data-post="keyword" placeholder="Pencarian">
    </div>
@endsection
@section('buttons')
    @if (
        \Route::has($route . '.create') &&
            auth()->user()->checkPerms('pu.pembinaan.add'))
        <a href="{{ route($route . '.create') }}" class="btn btn-info ml-2">
            <i class="fa fa-plus mr-1"></i> {{ __('Data') }}
        </a>
    @endif
@endsection
