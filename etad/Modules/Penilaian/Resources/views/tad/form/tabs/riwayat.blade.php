@if(isset($tableStruct2['datatable_2']))
{{-- {{ dd($tableStruct2['datatable_2']) }} --}}
<table id="datatable_2" class="table table-bordered table-hover is-datatable hide" style="width: 100%;" data-url="{{ isset($tableStruct2['url']) ? $tableStruct2['url'] : route($route.'.grid') }}" data-paging="{{ $paging ?? true }}" data-info="{{ $info ?? true }}">
  <thead>
    <tr>
      @foreach ($tableStruct2['datatable_2'] as $struct)
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