@inject('user', 'App\Entities\User')

@foreach ($items as $item)
    @if (auth()->check() &&
            (!isset($item['perms']) ||
                (auth()->user()->checkPerms($item['perms'] . '.view') ||
                    auth()->user()->checkPerms($item['perms']))))
        @if (isset($item['submenu']))
            @php
                $has_child      = false;
                $count          = 0;
                $perms          = collect($item['submenu'])->pluck('perms');
                // dd($perms);
            @endphp
            @foreach ($perms as $perm)
                @if ((auth()->user()->checkPerms($perm . '.view') || auth()->user()->checkPerms($perm)))
                    @php
                        $has_child  = $has_child && true;
                        $count++;
                    @endphp
                @endif
            @endforeach
            @if ($count < 1)
                @continue
            @endif
            <li {{-- class="{{ $item['isActive'] ? 'active' : '' }} @if ($item->hasChildren()) has-sub 'closed' {{ $item['isActive'] ? 'expand' : '' }} @endif"> --}} class="@if (isset($item['submenu'])) has-sub closed @endif">
                <a href="{!! $item['url'] ?? 'javascript:void(0)' !!}" class="menu-link">
                    @if (isset($item['submenu']))
                        <b class="caret"></b>
                    @endif
                    <i class="menu-icon {{ $item['icon'] ?? '' }}"></i>
                    <span class="text-capitalize">{!! $item['title'] ?? '' !!}</span>
                    @if (isset($item['badge']))
                        @if ($user->getNotif($item->badge) != 0)
                            <span class="menu-label">
                                <span class="label pulse pulse-info bg-primary">
                                    <span
                                        class="position-relative font-weight-boldest text-white">{{ $user->getNotif($item->badge) }}</span>
                                    <span class="pulse-ring"></span>
                                </span>
                            </span>
                        @endif
                    @endif
                </a>
                {{-- <ul class="sub-menu closed" @if (!$item->isActive) style="display: none;" @endif> --}}
                <ul class="sub-menu closed">
                    @include('layouts.partials.menu', ['items' => $item['submenu']])
                </ul>
            </li>
        @else
            <li {{-- class="{{ $item['isActive'] ? 'active' : '' }} @if ($item->hasChildren()) has-sub 'closed' {{ $item['isActive'] ? 'expand' : '' }} @endif"> --}} class="@if (isset($item['submenu'])) has-sub closed @endif">
                <a href="{!! $item['url'] ?? 'javascript:void(0)' !!}" class="menu-link">
                    @if (isset($item['submenu']))
                        <b class="caret"></b>
                    @endif
                    <i class="menu-icon {{ $item['icon'] ?? '' }}"></i>
                    <span class="text-capitalize">{!! $item['title'] ?? '' !!}</span>
                    @if (isset($item['badge']))
                        @if ($user->getNotif($item->badge) != 0)
                            <span class="menu-label">
                                <span class="label pulse pulse-info bg-primary">
                                    <span
                                        class="position-relative font-weight-boldest text-white">{{ $user->getNotif($item->badge) }}</span>
                                    <span class="pulse-ring"></span>
                                </span>
                            </span>
                        @endif
                    @endif
                </a>
                @if (isset($item['submenu']))
                    {{-- <ul class="sub-menu closed" @if (!$item->isActive) style="display: none;" @endif> --}}
                    <ul class="sub-menu closed">
                        @include('layouts.partials.menu', ['items' => $item['submenu']])
                    </ul>
                @endif
            </li>
        @endif
    @endif
@endforeach
