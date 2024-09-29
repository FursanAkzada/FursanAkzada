<div class="form-inline align-items-center" id="dataFilters">
  <div class="reset-filter mr-sm-2 hide">
    <button class="btn btn-info btn-pill btn-icon btn-sm reset button" data-toggle="tooltip" data-original-title="Reset Filter"><i class="icon-md la la-refresh"></i></button>
  </div>
  <div class="label-filter mr-sm-2">
    <button class="btn btn-primary btn-pill btn-icon btn-sm filter button" data-toggle="tooltip" data-original-title="Filter"><i class="icon-md text-white la la-refresh"></i></button>
  </div>
</div>
@if(isset($tableStruct['datatable_1']))
<table id="datatable_1" class="table table-bordered table-hover is-datatable hide" style="width: 100%;" data-url="{{ isset($tableStruct['table_url_1']) ? $tableStruct['table_url_1'] : route($route.'.grid') }}" data-paging="{{ $paging ?? true }}" data-info="{{ $info ?? true }}">
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