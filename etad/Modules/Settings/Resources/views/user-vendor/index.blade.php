@php
    $VENDOR = \Modules\Master\Entities\Vendor::orderBy('nama', 'ASC')->get();
@endphp

@extends('layouts.grid.list')
@section('filters')
    <div class="mr-2">
        <input type="text" class="form-control filter-control" data-post="keyword" placeholder="Nama & Username">
    </div>
    <div class="mr-2" style="width: 350px">
        <select class="form-control filter-control base-plugin--select2" data-post="vendor_id" title="Vendor"
            data-live-search="true">
            <option selected disabled></option>
            @foreach ($VENDOR as $item)
                <option value="{{ $item->id }}">{{ $item->nama }}</option>
            @endforeach
        </select>
    </div>
@endsection
{{-- @section('buttons')

@endsection --}}
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
