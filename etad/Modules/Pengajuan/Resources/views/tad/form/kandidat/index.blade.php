<form action="{{ route($route . '.kandidat.store', $record->id) }}" autocomplete="off" method="POST">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title"><i class="fas fa-users mr-2 text-primary"></i>
            @if ($record->pengajuan->status == 'approved')
                Pilih
            @endif Kandidat
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label for="" class="col-4 text-bold">Posisi TAD</label>
            <label for="" class="col">: <span class="badge badge-primary">{{ $record->jabatan->NM_UNIT }}</span></label>
        </div>
        <div class="form-group row">
            <label for="" class="col-4 text-bold">Jumlah Tenaga yg Dibutuhkan</label>
            <label for="" class="col">: <span class="badge" style="background-color: #FF0;">{{ $record->jumlah }}</span></label>
        </div>
        <div class="form-group row">
            <label for="" class="col-4 text-bold">Kandidat Dari Vendor</label>
            <label for="" class="col">: <span class="badge badge-info">{{ $record->vendor->nama }}</span></label>
        </div>
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
    <div class="modal-footer pt-0 border-0">
        @if (auth()->user()->isVendor && $record->flag == 'open')
            <x-btn-save via="base-form--submit-modal" />
        @endif
    </div>
</form>

@push('scripts')
    <script>
        $(document).ready(function(){
            $(document).on('click', 'span.foto', function(){
                let url = '{{ url('storage') }}/'+$(this).data('path');
                $('#imgEl').attr('src', url)
                    .prop('src', url);
            })
        });
    </script>
@endpush

