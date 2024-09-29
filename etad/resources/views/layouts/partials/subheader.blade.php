<div class="subheader py-lg-4 subheader-solid py-2" id="kt_subheader">
    <div class="container-fluid d-flex align-items-center justify-content-between flex-sm-nowrap flex-wrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center mr-2 flex-wrap">
            <!--begin::Page Title-->
            <h5 class="text-dark font-weight-bold mb-2 mr-3 mt-2">{!! $title ?? '' !!}</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mb-2 mr-3 mt-2 bg-gray-200"></div>
            {{-- <span class="text-dark font-weight-light mt-2 mb-2 mr-3">{{ $subtitle }}</span> --}}
            <div class="">
                @section('breadcrumb')
                    @php
                        $keys = array_keys($breadcrumb ?? []);
                        $end = end($keys);
                    @endphp
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold font-size-sm my-2 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ url(App\Providers\RouteServiceProvider::HOME) }}" class="text-muted"><i
                                    class="fas fa-home"></i></a>
                        </li>
                        @foreach ($breadcrumb ?? [] as $name => $link)
                            <li class="breadcrumb-item">
                                <a href="{{ $link }}" class="text-muted">{{ $name }}</a>
                            </li>
                        @endforeach
                    </ul>
                @show
            </div>
            <!--end::Actions-->
        </div>
        <div class="col text-right">
            @yield('buttons-before')
            {{-- @section('buttons')
          @if (\Route::has($route . '.create'))
            <a href="{{ route($route.'.create') }}" class="btn btn-info ml-2 {{ empty($baseContentReplace) ? 'base-modal--render' : 'base-content--replace' }}" data-modal-backdrop="false" data-modal-v-middle="false" data-toggle="tooltip" data-original-title="{{ __('Data') }}" data-placement="bottom">
              <i class="fa fa-plus mr-1"></i> {{ __('Data') }}
            </a>
          @endif
        @show --}}
            @section('buttons-after')
                @if (
                    \Route::has($route . '.create') &&
                        (isset($perms) &&
                            auth()->user()->checkPerms($perms . '.add')))
                    <a href="{{ route($route . '.create') }}"
                        class="btn btn-info {{ empty($baseContentReplace) ? 'base-modal--render' : 'base-content--replace' }} ml-2"
                        data-modal-backdrop="false" data-modal-v-middle="false" data-toggle="tooltip"
                        data-original-title="{{ __('Data') }}" data-placement="bottom">
                        <i class="fa fa-plus mr-1"></i> {{ __('Data') }}
                    </a>
                @endif
            @show
        </div>
        <!--end::Info-->
    </div>
</div>
