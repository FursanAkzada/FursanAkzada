@extends('layouts.grid.list')
@section('filters')
    <div class="mr-2">
      <input type="text" class="form-control filter-control" data-post="name" placeholder="Nama">
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
