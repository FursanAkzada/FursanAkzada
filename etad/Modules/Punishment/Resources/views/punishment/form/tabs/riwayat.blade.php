@if(isset($tableStruct['datatable_2']))
{{-- {{ dd($tableStruct['datatable_2']) }} --}}
<table id="datatable_2" class="table table-bordered table-hover is-datatable hide" style="width: 100%;" data-url="{{ isset($tableStruct['table_url_2']) ? $tableStruct['table_url_2'] : route($route.'.grid') }}" data-paging="{{ $paging ?? true }}" data-info="{{ $info ?? true }}">
  <thead>
    <tr>
      @foreach ($tableStruct['datatable_2'] as $struct)
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