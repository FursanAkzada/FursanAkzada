<div class="card card-custom mb-5">
    <div class="card-body padding-20">
    @section('dataFilters')
    <div class="form-inline align-items-center" id="dataFilters">
        <div class="reset-filter mr-sm-2 hide">
            <button class="btn btn-info btn-pill btn-icon btn-sm reset button" data-toggle="tooltip" data-original-title="Reset Filter"><i class="icon-md la la-refresh"></i></button>
        </div>
        <div class="label-filter mr-sm-2">
            <button class="btn btn-primary btn-pill btn-icon btn-sm filter button" data-toggle="tooltip" data-original-title="Filter"><i class="icon-md text-white la la-filter"></i></button>
        </div>
        @section('filters')
        {!! $filters ?? '' !!}
        @show
    </div>
    @show
    <div class="mt-5">
        <ul class="nav nav-bold nav-pills nav-light-primary" role="tablist">
            @if ($tableStruct['tabs'] === 'default')
                @php
                    $tableStruct['tabs'] = [
                        [
                            'title' => 'TO DO',
                            'table_id' => 'datatable_1',
                            'table_url' => route($route . '.grid'),
                            'badge' => false,
                            'active' => !in_array(request()->tab, [2, 3]) ? true : false,
                        ],
                        [
                            'title' => 'IN PROGRESS',
                            'table_id' => 'datatable_2',
                            'table_url' => route($route . '.grid', ['tab' => 2]),
                            'badge' => false,
                            'active' => in_array(request()->tab, [2]) ? true : false,
                        ],
                        [
                            'title' => 'COMPLETE',
                            'table_id' => 'datatable_3',
                            'table_url' => route($route . '.grid', ['tab' => 3]),
                            'badge' => false,
                            'active' => in_array(request()->tab, [3]) ? true : false,
                        ],
                    ];
                @endphp
            @endif
            @foreach ($tableStruct['tabs'] as $tab)
                <li class="nav-item nav-light-danger mx-0">
                    <a href="#tab-{{ $loop->iteration }}" data-toggle="tab"
                        data-badge="{{ $tab['badge'] ? 'true' : 'false' }}"
                        class="nav-link {{ $tab['active'] ? 'active' : '' }}">
                        {{-- class="nav-link {{ $loop->first ? 'nav-light-danger' : ($loop->last ? 'nav-light-success' : 'nav-light-info') }} {{ $tab['active'] ? 'active' : '' }}"> --}}
                        <span>{{ $tab['title'] }}</span>
                        @if ($tab['badge'])
                            <span class="label label-info tab-badge ml-2 label-inline">0</span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="tab-content">
        @foreach ($tableStruct['tabs'] as $tab)
            <div class="tab-pane fade {{ $tab['active'] ? 'active show' : '' }}"
                id="tab-{{ $loop->iteration }}">
                {{-- <div class="table-responsive"> --}}
                <div class="">
                    @if (isset($tableStruct[$tab['table_id']]))
                        <table id="{{ $tab['table_id'] }}"
                            class="table table-bordered table-hover is-datatable hide"
                            data-url="{{ $tab['table_url'] }}"
                            data-badge="{{ $tab['badge'] ? 'true' : 'false' }}"
                            data-tab-list="#tab-{{ $loop->iteration }}" style="width: 100%">
                            <thead>
                                <tr>
                                    @foreach ($tableStruct[$tab['table_id']] as $struct)
                                        <th class="text-center v-middle"
                                            data-columns-name="{{ $struct['name'] ?? '' }}"
                                            data-columns-data="{{ $struct['data'] ?? '' }}"
                                            data-columns-label="{{ $struct['label'] ?? '' }}"
                                            data-columns-sortable="{{ $struct['sortable'] === true ? 'true' : 'false' }}"
                                            data-columns-width="{{ $struct['width'] ?? '' }}"
                                            data-columns-class-name="{{ $struct['className'] ?? '' }}"
                                            style="{{ isset($struct['width']) ? 'width: ' . $struct['width'] : '' }}">
                                            {{ $struct['label'] }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @yield('tableBody')
                            </tbody>
                        </table>
                    @else
                        <div class="p-7 text-center">
                            <strong>Empty Tab Content</strong>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
</div>
