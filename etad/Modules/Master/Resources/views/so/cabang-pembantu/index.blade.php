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
@section('buttons-before')
    {{-- <div class="btn-group dropdown">
  <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Export
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item" href="#">Excel</a>
    <a class="dropdown-item" href="#">PDF</a>
  </div>
</div> --}}
@endsection
