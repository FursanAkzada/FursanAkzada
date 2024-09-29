@extends('layouts.grid.list')
@section('filters')
  <div class="row">
    <div class="col-12 col-sm-6 col-xl-3 pb-2 ">
      <input type="text" class="form-control filter-control" data-post="name" placeholder="Nama">
    </div>
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
