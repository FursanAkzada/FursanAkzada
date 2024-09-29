@extends('settings::layouts.profile.layout')
@inject('menu', 'Modules\Settings\Entities\Menu')

@section('title',$title)

@section('content-profile')
<div class="card card-custom">
  {{-- <div class="card-header">
    <div class="card-title">
      <h3 class="card-label font-weight-bolder text-dark">Notifikasi</h3>
    </div>
  </div> --}}
  <div class="card-body">
    <div class="d-flex justify-content-between">
      <div class="form-inline align-items-center" id="dataFilters">
        <div class="reset-filter mr-sm-2 hide">
          <button class="btn btn-info btn-pill btn-icon btn-sm reset button" data-toggle="tooltip" data-original-title="Reset Filter"><i class="icon-md la la-refresh"></i></button>
        </div>
        <div class="label-filter mr-sm-2">
          <button class="btn btn-primary btn-pill btn-icon btn-sm filter button" data-toggle="tooltip" data-original-title="Filter"><i class="icon-md text-white la la-filter"></i></button>
        </div>
        <div class="mr-2" style="width:350px;">
            @php
                $records = $menu->get();
            @endphp
            <select class="form-control base-plugin--select2 filter-control" data-post="module_name"
                data-placeholder="{{ __('Modul') }}">
                <option value="" selected disabled>{{ __('Modul') }}</option>
                @foreach (\Base::getMainModules() as $key => $val)
                  <option value="{{ $key }}">{{ $val }}</option>
                @endforeach
            </select>
        </div>
      </div>
    </div>
    <div class="" style="min-height: 100px;">
      @if(isset($tableStruct['datatable_1']))
      <table id="datatable_1" class="table table-bordered table-hover is-datatable hide" style="width: 100%;" data-url="{{ isset($tableStruct['url']) ? $tableStruct['url'] : route($route.'.grid') }}" data-paging="{{ $paging ?? true }}" data-info="{{ $info ?? true }}">
        <thead>
          <tr>
            @foreach ($tableStruct['datatable_1'] as $struct)
            <th class="text-center v-middle" data-columns-name="{{ $struct['name'] ?? '' }}" data-columns-data="{{ $struct['data'] ?? '' }}" data-columns-label="{{ $struct['label'] ?? '' }}" data-columns-sortable="{{ $struct['sortable'] === true ? 'true' : 'false' }}" data-columns-width="{{ $struct['width'] ?? '' }}" data-columns-class-name="{{ $struct['className'] ?? '' }}" style="{{ isset($struct['width']) ? 'width: '.$struct['width'].'; ' : '' }}">
              {{ $struct['label'] }}
            </th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @yield('tableBody')
        </tbody>
      </table>
      @endif
    </div>
  </div>
</div>
@endsection