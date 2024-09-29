@php
    $ORG_STRUCTS = \Modules\Master\Entities\SO\OrgStruct::groupByLevel([], true);
    $GROUPS = \App\Entities\Group::orderBy('name', 'ASC')
    ->get();
@endphp

@extends('layouts.grid.list')
@section('filters')
    <div class="mr-2">
        <input type="text" class="form-control filter-control" data-post="keyword" placeholder="Nama & Username">
    </div>
    <div class="mr-2" style="width: 250px">
        <select class="form-control filter-control base-plugin--select2" data-live-search="true" data-post="location_id" title="Unit Kerja">
            <option value=""></option>
            @foreach ($ORG_STRUCTS as $level => $group)
                @foreach ($group as $val)
                    @if ($loop->first)
                        <optgroup label="{{ \Modules\Master\Entities\SO\OrgStruct::getLevelLabel($level) }}">
                    @endif
                    <option value="{{ $val->id }}" data-positions='@json($val->positions)'>
                        {{ $val->name }}</option>
                    @if ($loop->last)
                        </optgroup>
                    @endif
                @endforeach
            @endforeach
        </select>
    </div>
    <div class="mr-2" style="width: 250px">
      <select class="form-control filter-control  base-plugin--select2" data-post="group_id" title="Hak Akses" data-live-search="true">
            <option selected disabled></option>
            @foreach ($GROUPS as $item)
              <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
@endsection
{{-- @section('buttons')

@endsection --}}
@section('buttons-before')
@endsection

@push('scripts')
    <script>
        $(document)
            .ready(function() {
                $(document)
                    .on('change', '#structCtrl', function() {
                        let val = $(this).val();
                        let data = $(this).find('option:selected').data();
                        $('#positionCtrl').html('');
                        let options = "<option disabled selected value=''>Pilih Jabatan</option>";
                        for (let position of data.positions) {
                            options += `<option value='${position.id}'>${position.name}</option>`;
                        }
                        $('#positionCtrl').html(options);
                        BasePlugin.initSelect2();
                    });
            });
    </script>
@endpush
