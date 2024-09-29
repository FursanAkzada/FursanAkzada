@extends('layouts.grid.list')
@section('filters')
    <div class="mr-2">
        <input type="text" class="form-control filter-control" data-post="keyword" placeholder="Pencarian">
    </div>
    <div class="mr-2">
        <select data-post="status" id="" class="form-control filter-control base-plugin--select2"
            title="Filter Berdasarkan Status">
            <option value=""></option>
            <option value="waiting.hc">Waiting Approval</option>
            <option value="approved.hc">Completed</option>
            <option value="rejected.hc">Ditolak</option>
            <option value="closed">Pengajuan Berakhir</option>
        </select>
    </div>
@endsection
@section('buttons')
    @if (\Route::has($route . '.create'))
        <a href="{{ route($route . '.create') }}" class="btn btn-info ml-2" data-toggle="tooltip"
            data-original-title="{{ __('Data') }}" data-placement="bottom">
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
