@extends('layouts.grid.list')
@section('filters')
    <div class="mr-2">
        <input type="text" class="form-control filter-control" data-post="keyword" placeholder="Keyword">
    </div>
    <div class="mr-2">
        <input type="text" class="form-control filter-control base-plugin--datepicker-3" data-language="en" data-post="tahun"
            placeholder="Tahun">
    </div>
@endsection
@section('buttons')
    @if (\Route::has($route . '.create'))
        <a href="{{ route($route . '.create') }}" class="btn btn-info ml-2 base-modal--render" data-modal-size="modal-xl"
            data-toggle="tooltip" data-original-title="{{ __('Create') }} Data" data-placement="bottom">
            <i class="fa fa-plus mr-1"></i> {{ __('Data') }}
        </a>
    @endif
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
