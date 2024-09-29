@extends('layouts.grid.list', ['paging' => 'false', 'info' => 'false'])
@section('filters')
    <div class="mr-2">
      <input type="text" class="form-control filter-control" data-post="keyword" placeholder="Pencarian">
    </div>
@endsection
