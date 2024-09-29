<div class="form-group row">
    <label for="" class="col-2 text-bold">Unit Kerja</label>
    <label for="" class="col">: {{ $record->so->name ?? '' }}</label>
</div>
<div class="form-group row">
    <label for="" class="col-2 text-bold">Pengajuan Untuk</label>
    <label for="" class="col">: Tahun {{ $record->year }} Semester {{ $record->semester }}</label>
</div>
<div class="form-group row">
    <label for="" class="col-2 text-bold">Tanggal Pengajuan</label>
    <label for="" class="col">: {{ $record->tgl_pengajuan->format('d/m/Y') }}</label>
</div>
<div class="form-group row">
    <label for="" class="col-2 text-bold">Nomor Tiket</label>
    <label for="" class="col font-italic">: {{ $record->no_tiket }}</label>
</div>

<div class="form-group row">
    <div class="col-2">
        <a href="{{ url('storage/'.$record->so_filepath) }}" class="btn btn-block btn-primary btn-sm" download="{{ $record->so_filename }}"><i
                class="mr-3 fa fa-download"></i>Download SO</a>
    </div>
    <div class="col-3">
        <a href="{{ url('storage/'.$record->surat_filepath) }}" class="btn btn-block btn-warning btn-sm" download="{{ $record->surat_filename }}"><i
                class="mr-3 fa fa-download"></i>Download Surat Permohonan</a>
    </div>
    @foreach ($record->sp as $sp)
        <div class="col-3">
            <a href="{{ url('storage/'.$sp->surat_filepath) }}" class="btn btn-block btn-danger btn-sm" download="{{ $sp->surat_filename }}"><i
                    class="mr-3 fa fa-download"></i>Download {{ $sp->no_surat }}</a>
        </div>
    @endforeach
</div>
<div class="form-group row">
    <label for="" class="col-2 text-bold">Tembusan</label>
    <label for="" class="col">
        :
        @foreach ($record->cc as $cc)
        {!! !$loop->first?'&nbsp;':'' !!} {{ $cc->name }} <br>
        @endforeach
    </label>
</div>
<hr class="my-8">
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
