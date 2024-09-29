<div class="form-group row">
    <label for="" class="col-2 text-bold">Unit Kerja</label>
    <label for="" class="col">: {{ $record->so->name }}</label>
</div>
<div class="form-group row">
    <label for="" class="col-2 text-bold">Tanggal Pengajuan</label>
    <label for="" class="col">: {{ $record->created_at->format('d/m/Y') }}</label>
</div>
<div class="form-group row">
    <label for="" class="col-2 text-bold">Nomor Tiket</label>
    <label for="" class="col font-italic">: {{ $record->no_tiket }}</label>
</div>

<div class="form-group row">
    <div class="col-2">
        <a href="{{ url('storage/' . $record->so_filepath) }}" class="btn btn-block btn-primary btn-sm"><i
                class="fa fa-download mr-3"></i>Download SO</a>
    </div>
    <div class="col-3">
        <a href="{{ url('storage/' . $record->surat_filepath) }}" class="btn btn-block btn-warning btn-sm"><i
                class="fa fa-download mr-3"></i>Download Surat Permohonan</a>
    </div>
</div>
<hr class="my-8">
<div class="form-inline align-items-center" id="dataFilters">
    <div class="reset-filter mr-sm-2 hide">
        <button class="btn btn-info btn-pill btn-icon btn-sm reset button" data-toggle="tooltip"
            data-original-title="Reset Filter"><i class="icon-md la la-refresh"></i></button>
    </div>
    <div class="label-filter mr-sm-2">
        <button class="btn btn-primary btn-pill btn-icon btn-sm filter button" data-toggle="tooltip"
            data-original-title="Filter"><i class="icon-md text-white la la-refresh"></i></button>
    </div>
</div>
@if (isset($tableStruct['datatable_1']))
    <table id="datatable_1" class="table table-bordered table-hover is-datatable hide" style="width: 100%;"
        data-url="{{ isset($tableStruct['url']) ? $tableStruct['url'] : route($route . '.grid') }}"
        data-paging="{{ $paging ?? true }}" data-info="{{ $info ?? true }}">
        <thead>
            <tr>
                @foreach ($tableStruct['datatable_1'] as $struct)
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
