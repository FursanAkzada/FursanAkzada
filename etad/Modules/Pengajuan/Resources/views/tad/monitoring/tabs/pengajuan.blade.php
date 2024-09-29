<div class="form-group row">
    <label for="" class="col-2 text-bold">Unit Kerja</label>
    <label for="" class="col">: {{ $record->cabang->CAPEM }}</label>
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
                class="mr-3 fa fa-download"></i>Download SO</a>
    </div>
    <div class="col-3">
        <a href="{{ url('storage/' . $record->surat_filepath) }}" class="btn btn-block btn-warning btn-sm"><i
                class="mr-3 fa fa-download"></i>Download Surat Permohonan</a>
    </div>
</div>
<hr class="my-8">
{{-- <div class="alert alert-custom alert-danger" role="alert">
  <div class="alert-icon">
    <i class="fas fa-exclamation-circle"></i>
  </div>
  <div class="alert-text">Kandidat TAD belum terpenuhi!</div>
  <div class="alert-close">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true"><i class="ki ki-close"></i></span>
    </button>
  </div>
</div> --}}
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
