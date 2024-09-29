@extends('layouts.grid.list')
@section('filters')
    <div class="mr-2">
        <input type="text" class="form-control filter-control" data-post="keyword" placeholder="Nama">
    </div>
    <div class="mr-2" style="width: 300px">
        <select class="form-control base-plugin--select2 show-tick filter-control" data-post="category_id"
            data-placeholder="Kategori" id="categoryCtrl">
            <option value="">Kategori</option>
            @foreach ($CATEGORIES as $category)
                <option value="{{ $category->id }}">
                    {{ $category->judul }}
                </option>
            @endforeach
        </select>
    </div>
@endsection
@section('buttons-after')
    @if (\Route::has($route . '.create'))
        <a href="{{ route($route . '.create') }}" class="ml-2 btn btn-info base-modal--render">
            <i class="mr-1 fa fa-plus"></i> {{ __('Data') }}
        </a>
    @endif
@endsection
