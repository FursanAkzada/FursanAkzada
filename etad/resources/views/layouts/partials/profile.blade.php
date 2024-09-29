<div class="dropdown">
    <!--begin::Toggle-->
    <div class="topbar-item" data-toggle="dropdown" data-offset="0px,0px" aria-expanded="false">
        <div class="btn btn-icon btn-hover-transparent-white d-flex align-items-center btn-lg px-md-2 w-md-auto">
            <span class="text-muted font-weight-bolder font-size-base d-none d-md-inline mr-4 opacity-90">
                Hi, {{ auth()->user()->name ?? '' }}
                <br> {{ auth()->user()->groups()->first()->name }}
            </span>
            @if (auth()->user()->photo ?? null)
                <div class="thumb-avatar"
                    style="background-image: url({{ '/storage/profile/' . auth()->user()->photo }})"></div>
            @else
                <span class="symbol symbol-lg-35 symbol-25 symbol-light-danger">
                    <span class="symbol-label font-size-h5 font-weight-bold">{{ auth()->user()->name[0] ?? '' }}</span>
                </span>
            @endif
        </div>
    </div>
    <!--end::Toggle-->
    <!--begin::Dropdown-->
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg m-0 p-0 p-0" style="">
        <!--begin::Header-->
        <div class="d-flex align-items-center rounded-top p-3">
            <!--begin::Symbol-->
            @if (auth()->user()->photo ?? null)
                <div class="thumb-avatar mr-2"
                    style="background-image: url({{ '/storage/profile/' . auth()->user()->photo }})"></div>
            @else
                <span class="symbol symbol-lg-35 symbol-25 symbol-light-danger mr-2">
                    <span class="symbol-label font-size-h5 font-weight-bold">{{ auth()->user()->name[0] ?? '' }}</span>
                </span>
            @endif
            <!--end::Symbol-->
            <!--begin::Text-->
            <div class="text-dark flex-grow-1 font-size-h5 m-0 mr-3">
                <div class="font-weight-bold">{{ auth()->user()->name ?? '' }}</div>
                <div class="text-muted" style="font-size: 13px">
                    {{ auth()->check() &&auth()->user()->groups()->count()? auth()->user()->groups()->first()->name: '-' }}
                </div>
            </div>
            {{-- <span class="label label-light-danger label-lg font-weight-bold label-inline">3 messages</span> --}}
            <!--end::Text-->
        </div>
        <div class="separator separator-solid"></div>
        <!--end::Header-->
        <!--begin::Nav-->
        <div class="navi navi-spacer-x-0 pt-3">
            <!--begin::Item-->
            <a href="{{ route('settings.profile.index') }}" class="navi-item base-content--replace px-8">
                <div class="navi-link">
                    <div class="navi-icon mr-2">
                        <i class="fa fa-user text-danger"></i>
                    </div>
                    <div class="navi-text">
                        <div class="font-weight-bold">My Profile</div>
                        <div class="text-muted">Pengaturan akun dan lain-lain</div>
                    </div>
                </div>
            </a>
            <!--end::Item-->
            <!--begin::Item-->
            <a href="{{ route('settings.notification.index') }}" class="navi-item base-content--replace px-8">
                <div class="navi-link">
                    <div class="navi-icon mr-2">
                        <i class="fas fa-bell text-danger"></i>
                    </div>
                    <div class="navi-text">
                        <div class="font-weight-bold">My Notification</div>
                        <div class="text-muted">Semua Notifikasi</div>
                    </div>
                </div>
            </a>
            <!--end::Item-->
            <!--begin::Item-->
            <a href="{{ route('settings.activity.index') }}" class="navi-item base-content--replace px-8">
                <div class="navi-link">
                    <div class="navi-icon mr-2">
                        <i class="fas fa-chart-line text-danger"></i>
                    </div>
                    <div class="navi-text">
                        <div class="font-weight-bold">My Activities</div>
                        <div class="text-muted">Semua aktifitas logs</div>
                    </div>
                </div>
            </a>
            <!--end::Item-->
            <!--begin::Footer-->
            <div class="navi-separator mt-3"></div>
            <div class="navi-footer float-right px-3 py-3">
                <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="btn btn-light-primary font-weight-bold">Sign Out</a>
            </div>
            <!--end::Footer-->
        </div>
        <!--end::Nav-->
    </div>
    <!--end::Dropdown-->
</div>
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
