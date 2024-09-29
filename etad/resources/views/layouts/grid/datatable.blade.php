<div class="card card-custom">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            @section('dataFilters')
                <div class="form-inline align-items-center" id="dataFilters">
                    <div class="reset-filter mr-sm-2 hide">
                        <button class="btn btn-info btn-pill btn-icon btn-sm reset button" data-toggle="tooltip"
                            data-original-title="Reset Filter"><i class="icon-md la la-refresh"></i></button>
                    </div>
                    <div class="label-filter mr-sm-2">
                        <button class="btn btn-primary btn-pill btn-icon btn-sm button filter" data-toggle="tooltip"
                            data-original-title="Filter"><i class="icon-md la la-filter text-white"></i></button>
                    </div>
                @section('filters')
                    {!! $filters ?? '' !!}
                @show
            </div>
        @show
        <div class="card-toolbar my-0">
            @section('buttons')

            @show
        </div>
    </div>
    <div class="table-responsive" style="min-height: 100px;">
        <br>
        @yield('card-top-table')
        <div class="" style="min-height: 100px;">
            @if (isset($tableStruct['datatable_1']))
                <table id="datatable_1" class="table-bordered table-hover is-datatable hide table"
                    style="width: 100%;"
                    data-url="{{ isset($tableStruct['url']) ? $tableStruct['url'] : route($route . '.grid') }}"
                    data-paging="{{ $paging ?? true }}" data-info="{{ $info ?? true }}">
                    <thead>
                        <tr>
                            @foreach ($tableStruct['datatable_1'] as $struct)
                                <th class="v-middle text-center" data-columns-name="{{ $struct['name'] ?? '' }}"
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
        @yield('card-bottom-table')
    </div>
</div>

@section('dataPanelFilters')
    <div id="panel-filter" class="offcanvas offcanvas-right p-10">
        <div id="dataFilters">
            <div class="offcanvas-header d-flex align-items-center justify-content-between pb-7">
                <div class="d-flex align-items-center">
                    <div class="reset-filter mr-sm-2 hide">
                        <button class="btn btn-info btn-pill btn-icon btn-sm reset button" data-toggle="tooltip"
                            data-original-title="Reset Filter"><i class="icon-md la la-refresh"></i></button>
                    </div>
                    <div class="label-filter mr-sm-2">
                        <button class="btn btn-primary btn-pill btn-icon btn-sm button filter" data-toggle="tooltip"
                            data-original-title="Filter"><i class="icon-md la la-filter text-white"></i></button>
                    </div>
                    <h4 class="font-weight-bold m-0">
                        <span>Pencarian </span>
                    </h4>
                </div>
                <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="panel-filter-close">
                    <i class="ki ki-close icon-xs text-muted"></i>
                </a>
            </div>
            <div class="offcanvas-content">
                <div class="offcanvas-wrapper scroll-pull mb-5">
                    @section('panelFilters')

                    @show
                </div>
                <div class="offcanvas-footer">
                    {{-- <a href="https://1.envato.market/EA4JP" target="_blank" class="btn btn-block btn-danger btn-shadow font-weight-bolder text-uppercase">Buy Metronic Now!</a> --}}
                </div>
            </div>
        </div>
    </div>
@show
