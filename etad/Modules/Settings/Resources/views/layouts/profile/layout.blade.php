@extends('layouts.app')

@section('buttons')
@endsection

@php
    $user = auth()->user();
@endphp

@section('content')
    <style>
        .profile-avatar {
            width: 120px;
            height: 120px;
            background-color: #333;
            background-size: 100% auto;
            background-repeat: no-repeat;
            background-position: center;
            border-radius: 50%;
            margin: auto;
        }
    </style>
    <div class="row">
        <div class="col-3">
            <div class="card card-custom">
                <div class="card-body">
                    <div class="mb-10 text-center">
                        <a href="{{ route('settings.profile.photo') }}" class="base-modal--render">
                            @if (auth()->user()->photo)
                                <div class="profile-avatar"
                                    style="background-image: url({{ '/storage/profile/' . auth()->user()->photo }})"></div>
                            @else
                                <div class="symbol symbol-60 symbol-circle symbol-xl-90">
                                    <span class="symbol-label font-size-h2">{{ $user->name[0] }}</span>
                                    <i class="symbol-badge symbol-badge-bottom bg-success"></i>
                                </div>
                            @endif
                        </a>
                        <h4 class="font-weight-bold my-2">{{ $user->name }}</h4>
                        <div class="text-muted mb-2">{{ $user->cabang }}</div>
                        <span class="label label-light-warning label-inline font-weight-bold label-lg">Active</span>
                    </div>
                    <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                        <div class="navi-item mb-2">
                            <a href="{{ route('settings.profile.index') }}"
                                class="navi-link {{ \Route::currentRouteName() == 'settings.profile.index' ? 'active' : '' }} mb-2">
                                <i
                                    class="fas fa-user {{ \Route::currentRouteName() == 'settings.profile.index' ? 'text-primary' : '' }} fa-fw mr-2"></i>
                                Profil
                            </a>
                        </div>
                        <div class="navi-item mb-2">
                            <a href="{{ route('settings.notification.index') }}"
                                class="navi-link {{ \Route::currentRouteName() == 'settings.notification.index' ? 'active' : '' }} mb-2">
                                <i
                                    class="fas fa-bell {{ \Route::currentRouteName() == 'settings.notification.index' ? 'text-primary' : '' }} fa-fw mr-2"></i>
                                Notifikasi
                            </a>
                        </div>
                        <div class="navi-item mb-2">
                            <a href="{{ route('settings.activity.index') }}"
                                class="navi-link {{ \Route::currentRouteName() == 'settings.activity.index' ? 'active' : '' }} mb-2">
                                <i
                                    class="fas fa-chart-line {{ \Route::currentRouteName() == 'settings.activity.index' ? 'text-primary' : '' }} fa-fw mr-2"></i>
                                Aktivitas Log
                            </a>
                        </div>
                        <div class="navi-item mb-2">
                            <a href="{{ route('settings.change-password.index') }}"
                                class="navi-link {{ \Route::currentRouteName() == 'settings.change-password.index' ? 'active' : '' }}">
                                <i
                                    class="fas fa-unlock-alt {{ \Route::currentRouteName() == 'settings.change-password.index' ? 'text-primary' : '' }} fa-fw mr-2"></i>
                                Ganti Password
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            @yield('content-profile')
        </div>
    </div>
@endsection
