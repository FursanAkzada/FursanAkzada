@php
    $unreadCount = 0;
    if (auth()->check()) {
        $unreadCount = auth()
            ->user()
            ->unreadNotifications()
            ->count();
    }
@endphp
<div class="dropdown">
    <!--begin::Toggle-->
    <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
        <div class="btn btn-icon btn-clean btn-dropdown btn-lg pulse @if ($unreadCount) pulse-danger @else pulse-primary @endif mr-1"
            @if ($unreadCount) data-toggle="tooltop" data-tooltip="{{ $unreadCount }}" title="{{ $unreadCount }} Notifikasi baru" @endif
            style="position: relative">
            <span
                class="svg-icon svg-icon-xl @if ($unreadCount) svg-icon-danger @else svg-icon-primary @endif">
                <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                    height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <path
                            d="M17,12 L18.5,12 C19.3284271,12 20,12.6715729 20,13.5 C20,14.3284271 19.3284271,15 18.5,15 L5.5,15 C4.67157288,15 4,14.3284271 4,13.5 C4,12.6715729 4.67157288,12 5.5,12 L7,12 L7.5582739,6.97553494 C7.80974924,4.71225688 9.72279394,3 12,3 C14.2772061,3 16.1902508,4.71225688 16.4417261,6.97553494 L17,12 Z"
                            fill="#000000" />
                        <rect fill="#000000" opacity="0.3" x="10" y="16" width="4" height="4"
                            rx="2" />
                    </g>
                </svg>
                <!--end::Svg Icon-->
            </span>
            <div class="user-notification-badge {{ $unreadCount === 0 ? 'hide' : '' }}"
                style="margin-top: -20px; margin-left: -10px; z-index: 11">
                <span class="label label-light-danger label-pill label-inline mr-2">{{ $unreadCount }}</span>
            </div>
        </div>
    </div>
    <!--end::Toggle-->
    <!--begin::Dropdown-->
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg m-0 p-0">
        <form>
            <!--begin::Header-->
            <div class="d-flex flex-column bgi-size-cover bgi-no-repeat rounded-top pt-12"
                style="background-image: url({{ '/assets/media/misc/bg-1.jpg' }})">
                <!--begin::Title-->
                <h4 class="d-flex flex-center rounded-top">
                    <span class="text-white">Notifikasi</span>
                    <span class="btn btn-text btn-success btn-sm font-weight-bold btn-font-md ml-2">{{ $unreadCount }}
                        new</span>
                </h4>
                <!--end::Title-->
                <!--begin::Tabs-->
                <ul class="nav nav-bold nav-tabs nav-tabs-line nav-tabs-line-3x nav-tabs-line-transparent-white nav-tabs-line-active-border-success mt-3 px-8"
                    role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active show" data-toggle="tab" href="#topbar_notifications_events">Logs</a>
                    </li>
                </ul>
                <!--end::Tabs-->
            </div>
            <!--end::Header-->
            <!--begin::Content-->
            <div class="tab-content">
                <!--begin::Tabpane-->
                <div class="tab-pane active show" id="topbar_notifications_events" role="tabpanel">
                    <!--begin::Nav-->
                    <div class="navi navi-hover scroll my-2" data-scroll="true" data-height="300"
                        data-mobile-height="200">
                        <!--begin::Item-->
                        @forelse ((auth()->check() ? auth()->user()->latestNotify() : []) as $item)
                            @php
                                $icon = [
                                    'pengajuan.tad' => 'fa fa-user-edit',
                                    'pengajuan.form' => 'fa fa-user-edit',
                                    'pengajuan.tad.form' => 'fa fa-user-edit',
                                    'pengajuan.quota' => 'fa fa-user-edit',
                                    'pengajuan.tad.kandidat' => 'fa fa-user-edit',
                                    'pengajuan.tad.wawancara' => 'fa fa-user-edit',
                                    'pengajuan.tad.penerimaan' => 'fa fa-user-edit',
                                    'resign_mutasi.resign' => 'fas fa-sign-out-alt',
                                    'resign_mutasi.mutasi' => 'fas fa-exchange-alt',
                                    'pengajuan.mutasi' => 'fas fa-exchange-alt',
                                    // Quota
                                    'personil.quota' => 'fas fa-id-card',
                                    // Penilaian
                                    'penilaian.vendor' => 'fas fa-star',
                                    'penilaian.tad' => 'fas fa-star',
                                    'penilaian.perpanjangan' => 'fas fa-star',
                                    // Punishment & Reward
                                    'pu.reward' => 'fas fa-medal',
                                    'pu.punishment' => 'fas fa-medal',
                                    'pu.pembinaan' => 'fas fa-medal',
                                ];
                            @endphp
                            @if (isset($item->data['type']))
                                <a href="{{ route('userNotificationRead', $item->id) }}" class="navi-item">
                                    <div class="navi-link">
                                        <div class="navi-icon mr-2">
                                            <i class="{{ $icon[$item->data['type']] }} text-success"></i>
                                        </div>
                                        <div class="navi-text">
                                            <div class="font-weight-bold">{{ $item->data['title'] }}</div>
                                            <div class="text-muted">{{ $item->data['message'] }}</div>
                                            <div class="text-muted">
                                                {{ $item->created_at->translatedFormat('d M Y H:i:s') }}</div>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        @empty
                            <div class="d-flex flex-center text-muted min-h-200px text-center">
                                Tidak ada notifikasi
                            </div>
                        @endforelse
                    </div>
                    <!--end::Nav-->
                    <div class="d-flex flex-center py-4">
                        <a href="{{ route('settings.notification.index') }}"
                            class="btn btn-light-primary font-weight-bold text-center">See All</a>
                    </div>
                </div>
                <!--end::Tabpane-->
            </div>
            <!--end::Content-->
        </form>
    </div>
    <!--end::Dropdown-->
</div>
