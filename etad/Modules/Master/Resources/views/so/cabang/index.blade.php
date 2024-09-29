@extends('layouts.grid.list')
@section('filters')
    <div class="mr-2">
        <input type="text" class="form-control filter-control" data-post="name" placeholder="Kode & Nama">
    </div>
    <div class="mr-2" style="width: 350px">
        <select class="form-control base-plugin--select2 show-tick filter-control" data-post="parent_id"
            data-placeholder="Parent">
            <option value="">Parent</option>
            @foreach ($parents as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
@endsection
