@extends('layouts.grid.list', ['paging' => 'false', 'info' => 'false'])
@inject('menu', 'Modules\Settings\Entities\Menu')

@section('filters')
    <div class="mr-2" style="width:350px;">
        @php
            $records = $menu->get();
        @endphp
        <select class="form-control base-plugin--select2 filter-control" data-post="module_name"
            data-placeholder="{{ __('Modul') }}">
            <option value="" selected disabled>{{ __('Modul') }}</option>
            @foreach ($records as $menu)
                @if (!$menu->parent_id)
                    <option value="{{ $menu->module }}">{{ $menu->name }}</option>
                @endif
            @endforeach
        </select>
    </div>
@endsection

@section('buttons-after')
@endsection
