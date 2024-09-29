<div class="modal-header">
    <h4 class="modal-title"><i class="fas fa-users mr-2 text-primary"></i> Kandidat</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
            class="ki ki-close"></i></button>
</div>
<div class="modal-body">
    <div class="form-group row">
        <label for="" class="col-3 text-bold">Posisi TAD</label>
        <label for="" class="col">: {{ $record->jabatan->NM_UNIT }}</label>
    </div>
    <div class="form-group row">
        <label for="" class="col-3 text-bold">Jumlah Tenaga yg Dibutuhkan</label>
        <label for="" class="col">: {{ $record->jumlah }}</label>
    </div>
    <div class="form-group row">
        <label for="" class="col-3 text-bold">Kandidat Dari Vendor</label>
        <label for="" class="col">: {{ $record->vendor->nama }}</label>
    </div>
    @if (isset($tableStruct['datatable_2']))
        <table id="datatable_2" class="table table-bordered table-hover is-datatable hide" style="width: 100%;"
            data-url="{{ isset($tableStruct['url']) ? $tableStruct['url'] : route($route . '.grid') }}"
            data-paging="{{ $paging ?? true }}" data-info="{{ $info ?? true }}">
            <thead>
                <tr>
                    @foreach ($tableStruct['datatable_2'] as $struct)
                        <th class="text-center v-middle" data-columns-name="{{ $struct['name'] ?? '' }}"
                            data-columns-data="{{ $struct['data'] ?? '' }}"
                            data-columns-label="{{ $struct['label'] ?? '' }}"
                            data-columns-sortable="{{ $struct['sortable'] === true ? 'true' : 'false' }}"
                            data-columns-width="{{ $struct['width'] ?? '' }}"
                            data-columns-class-name="{{ $struct['className'] ?? '' }}"
                            style="{{ isset($struct['width']) ? 'width: ' . $struct['width'] . '; ' : '' }}">
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
{{-- <div class="modal-footer pt-0 border-0">

</div> --}}
