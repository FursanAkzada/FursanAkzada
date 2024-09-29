<!--begin::Aside-->
<div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto page-with-light-sidebar" id="kt_aside">
    <!--begin::Brand-->
    <div class="brand flex-column-auto" id="kt_brand">
        <!--begin::Logo-->
        <a href="{{ route('dashboard.index') }}" class="brand-logo">
            <img alt="Logo" style="width: 12em;" src="/assets/media/logos/logo.png" />
        </a>
        <!--end::Logo-->
        <!--begin::Toggle-->
        <button class="custom-brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
            <span class="svg-icon svg-icon svg-icon-xl mr-0">
                <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Angle-double-left.svg-->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                    height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24"></polygon>
                        <path
                            d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z"
                            fill="#000000" fill-rule="nonzero"
                            transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)">
                        </path>
                        <path
                            d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z"
                            fill="#000000" fill-rule="nonzero" opacity="0.3"
                            transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)">
                        </path>
                    </g>
                </svg>
                <!--end::Svg Icon-->
            </span>
        </button>
        <!--end::Toolbar-->
    </div>
    <!--end::Brand-->

    <div class="custom-sidebar sidebar-menu" id="sidebar">
        <div class="py-0" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0;">
            <div class="card card-aside m-0" style="height: 100%; background: transparent; box-shadow: unset;">
                <div class="card-body p-0">
                    <div class="sidebar-menu-scroll scroll scroll-pull ps" data-scroll="true"
                        data-wheel-propagation="true" style="height: 100%; background: transparent; overflow: hidden;">
                        <ul class="menu-nav nav overflow-y-auto active">
                            @include('layouts.partials.menu', ['items' => config('backendmenu')])
                        </ul>
                        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                        </div>
                        <div class="ps__rail-y" style="top: 0px; right: -2px;">
                            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Aside-->
