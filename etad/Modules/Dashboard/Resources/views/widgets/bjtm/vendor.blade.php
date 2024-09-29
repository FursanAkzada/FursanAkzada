
<div class="card card-custom gutter-b">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5">
        <div class="card-title font-weight-bolder">
            <div class="card-label">Total TAD di Bank Jatim
            </div>
        </div>
        <div class="card-toolbar">
            <div class="font-size-sm text-muted mt-2">
                <strong>{{ $vendors->sum('tad_count') }} TAD</strong> dari <strong>{{ $vendors->count() }} Vendor</strong>
            </div>
        </div>
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body p-0 d-flex flex-column" style="position: relative;">
        <!--begin::Items-->
        <div class="flex-grow-1 card-spacer">
            <div class="row">
            @foreach ($vendors->take(9) as $key => $item)
                <!--begin::Item-->
                <div class="col-4 my-5">
                    <div class="d-flex align-items-center mr-2">
                        <!--begin::Symbol-->
                        <div class="symbol symbol-45 symbol-light-danger mr-4 flex-shrink-0">
                            <span class="symbol-label font-size-h5">{{ $item->acronym }}</span>
                        </div>
                        <!--end::Symbol-->
                        <!--begin::Title-->
                        <div>
                            <div class="font-size-h4 text-dark-75 font-weight-bolder">{{ $item->tad_count }} <small>TAD</small></div>
                            <div class="font-size-sm text-muted font-weight-bold mt-1">{{ $item->nama }}</div>
                        </div>
                        <!--end::Title-->
                    </div>
                </div>
                <!--end::Widget Item-->
            @endforeach
            </div>
        </div>
        <!--end::Items-->
    </div>
    <!--end::Body-->
</div>
