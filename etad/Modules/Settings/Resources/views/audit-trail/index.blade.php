@extends('layouts.grid.list')
@section('filters')
    <div class="mr-2" style="width: 350px">
        <select class="form-control filter-control base-plugin--select2" data-post="module_name" title="Modul">
            <option value="" selected disabled>{{ __('Modul') }}</option>
            @foreach (\Base::getMainModules() as $key => $val)
                <option value="{{ $key }}">{{ $val }}</option>
            @endforeach
        </select>
    </div>
@endsection
@section('buttons')
@endsection
@section('buttons-before')
    {{-- <div class="btn-group dropdown">
        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            Export
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="#">Excel</a>
            <a class="dropdown-item" href="#">PDF</a>
        </div>
    </div> --}}
@endsection
