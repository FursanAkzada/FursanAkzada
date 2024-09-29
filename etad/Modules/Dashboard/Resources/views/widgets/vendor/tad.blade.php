<div class="col">
    <div class="card card-custom bgi-no-repeat card-stretch gutter-b" style="background-position: right top; background-size: 30% auto; background-image: url(assets/media/svg/shapes/abstract-1.svg)">
        <!--begin::Body-->
        <div class="card-body">
            <span class="svg-icon svg-icon-3x svg-icon-success">
                <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Mail-opened.svg-->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24" />
                        <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                        <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                    </g>
                </svg>
                <!--end::Svg Icon-->
            </span>
            <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-6 d-block">{{ $tad['exists'] }}</span>
            <span class="font-weight-bold text-muted font-size-sm">TAD Sudah Bekerja</span>
        </div>
        <!--end::Body-->
    </div>
</div>
<div class="col">
    <div class="card card-custom bgi-no-repeat card-stretch gutter-b" style="background-position: right top; background-size: 30% auto; background-image: url(assets/media/svg/shapes/abstract-1.svg)">
        <!--begin::Body-->
        <div class="card-body">
            <span class="svg-icon svg-icon-3x svg-icon-danger">
                <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Mail-opened.svg-->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24" />
                        <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                        <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                    </g>
                </svg>
                <!--end::Svg Icon-->
            </span>
            <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-6 d-block">{{ $tad['available'] }}</span>
            <span class="font-weight-bold text-muted font-size-sm">TAD Belum Bekerja</span>
        </div>
        <!--end::Body-->
    </div>
</div>