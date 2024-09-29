@php
    $status = false;
    if (
        \Modules\Pengajuan\Entities\Tad\QuotaPeriode::where('status', 'completed')
            ->orWhere('version', '>', 0)
            ->get()
            ->first()
    ) {
        $status = true;
    }

    $statusPersonil = false;
    if (\Modules\Master\Entities\Tad\KepegawaianMigrasi::whereIn('status', [\Modules\Master\Entities\Tad\KepegawaianMigrasi::WORK, \Modules\Master\Entities\Tad\KepegawaianMigrasi::MIGRATE])->count() > 0) {
        $statusPersonil = true;
    }
@endphp

@push('styles')
    <style>
        #loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.339) url('{{ URL::asset('assets/media/ui/spinner.gif') }}') no-repeat center center;
            z-index: 10000;
        }
    </style>
@endpush

@extends('layouts.grid.list')
@section('filters')
    <div class="mr-2">
        <input class="form-control filter-control base-plugin--datepicker-3" id="yearFilterCtrl" data-post="year"
            placeholder="Tahun" style="width: 100px" value="">
    </div>
    <div class="mr-2" style="width: 150px">
        <select class="form-control base-plugin--select2 show-tick filter-control" data-post="semester"
            data-placeholder="Semester" id="semesterFilterCtrl">
            <option disabled selected value="">Semester</option>
            <option value="semua">Semua</option>
            <option value="Satu">Satu</option>
            <option value="Dua">Dua</option>
        </select>
    </div>
    <div id="loader">
    </div>
@endsection

@section('buttons')
    <div class="d-flex">
        <label class="col-form-label mr-2">Migrasi Personil: </label>
        <div class="mr-4">
            <input class="form-control" disabled style="width: 70px" value="{{ $statusPersonil ? 'Sudah' : 'Belum' }}">
        </div>
        <label class="col-form-label mr-2">Migrasi Quota: </label>
        <div class="mr-4">
            <input class="form-control" disabled style="width: 70px" value="{{ $status ? 'Sudah' : 'Belum' }}">
        </div>
        <label class="col-form-label mr-2">Quota: </label>
        <div class="mr-4">
            <input class="form-control" disabled id="quotaCount" style="width: 100px">
        </div>
        <label class="col-form-label mr-2">Pemenuhan: </label>
        <div class="mr-2">
            <input class="form-control" disabled id="fulfillmentCount" style="width: 100px">
        </div>
    </div>
@endsection

@section('buttons-after')
    @if (
        \Route::has($route . '.create') &&
            (isset($perms) &&
                auth()->user()->checkPerms($perms . '.add')))
        @php
            $statusPersonil = false;
            if (\Modules\Master\Entities\Tad\KepegawaianMigrasi::whereIn('status', [\Modules\Master\Entities\Tad\KepegawaianMigrasi::WORK, \Modules\Master\Entities\Tad\KepegawaianMigrasi::MIGRATE])->count() < 2) {
                $statusPersonil = true;
            }

            $status = false;
            if (
                \Modules\Pengajuan\Entities\Tad\QuotaPeriode::where('status', 'completed')
                    ->orWhere('version', '>', 0)
                    ->get()
                    ->first()
            ) {
                $status = true;
            }
        @endphp
        @if ($statusPersonil)
            <a href="javascript:void(0)" id="linkButton" class="btn btn-primary ml-2">
                <i class="fa fa-sync mr-1"></i> {{ __('Migrasi Quota') }}
            </a>
            <a href="javascript:quotaDisabled()" class="btn btn-info ml-2">
                <i class="fa fa-plus mr-1"></i> {{ __('Data') }}
            </a>
        @elseif($status)
            <a href="javascript:void(0)" id="linkButton2" class="btn btn-primary ml-2">
                <i class="fa fa-sync mr-1"></i> {{ __('Migrasi Quota') }}
            </a>
            <a href="{{ route($route . '.create') }}"
                class="btn btn-info {{ empty($baseContentReplace) ? 'base-modal--render' : 'base-content--replace' }} ml-2"
                data-modal-backdrop="false" data-modal-v-middle="false" data-toggle="tooltip"
                data-original-title="{{ __('Data') }}" data-placement="bottom">
                <i class="fa fa-plus mr-1"></i> {{ __('Data') }}
            </a>
        @elseif($route === 'personil.quota')
            <a href="{{ route('personil.migrasi.import-save', ['tipe_import' => 'quota-aktif']) }}"
                class="btn btn-primary loader-button ml-2">
                <i class="fa fa-sync mr-1"></i> {{ __('Migrasi Quota') }}
            </a>
            <a href="javascript:quotaDisabled()" class="btn btn-info ml-2">
                <i class="fa fa-plus mr-1"></i> {{ __('Data') }}
            </a>
        @else
            <a href="{{ route($route . '.create') }}"
                class="btn btn-info {{ empty($baseContentReplace) ? 'base-modal--render' : 'base-content--replace' }} ml-2"
                data-modal-backdrop="false" data-modal-v-middle="false" data-toggle="tooltip"
                data-original-title="{{ __('Data') }}" data-placement="bottom">
                <i class="fa fa-plus mr-1"></i> {{ __('Data') }}
            </a>
        @endif
    @endif
@endsection

@push('scripts')
    <script>
        const rupiah = (number) => {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
            }).format(number);
        }
        $(document).on('change', '#yearFilterCtrl, #semesterFilterCtrl', function() {
            $.ajax({
                method: 'GET',
                url: '{{ route('ajax.getQuotaCount') }}',
                data: {
                    year: $('#yearFilterCtrl').val(),
                    semester: $('#semesterFilterCtrl').val(),
                },
                success: function(res) {
                    $('#quotaCount').val(rupiah(res.quota_count ?? 0).replace('Rp', '').replace(',00',
                        ''));
                    $('#fulfillmentCount').val(rupiah(res.fulfillment_count ?? 0).replace('Rp', '')
                        .replace(',00', ''));
                }
            });
        });
        $('#yearFilterCtrl').trigger('change');
    </script>
@endpush

@push('scripts')
    <script>
        $('.loader-button').on('click', function() {
            var spinner = $('#loader');
            spinner.show();
        });
        $('#linkButton').click(function() {
            $.gritter.add({
                title: 'Failed!',
                text: 'Migrasi Quota tidak bisa dilakukan jika belum dilakukan migrasi personil!',
                image: baseurl + '/assets/images/icon/ui/cross.png',
                sticky: false,
                time: '3000'
            });
        });
        $('#linkButton2').click(function() {
            $.gritter.add({
                title: 'Failed!',
                text: 'Migrasi quota hanya bisa dilakukan satu kali!',
                image: baseurl + '/assets/images/icon/ui/cross.png',
                sticky: false,
                time: '3000'
            });
        });

        function quotaDisabled() {
            $.gritter.add({
                title: 'Failed!',
                text: 'Tidak bisa memanipulasi Quota sebelum migrasi quota!',
                image: baseurl + '/assets/images/icon/ui/cross.png',
                sticky: false,
                time: '3000'
            });
        }
        $(window).on('click', '.disableQuota', function() {
            quotaDisabled();
        });
    </script>
@endpush
