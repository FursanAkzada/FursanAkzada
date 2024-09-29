<form action="{{ route($route . '.store') }}" autocomplete="off" method="POST">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="name">@lang('Nama Lengkap')</label>
            <div class="col-sm-8 parent-group">
                <input type="text" id="name" name="name" class="form-control"
                    placeholder="@lang('Nama Lengkap')">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="username">@lang('Username')</label>
            <div class="col-sm-8 parent-group">
                <input type="text" id="username" name="username" class="form-control"
                    placeholder="@lang('Username')">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="email">@lang('Email')</label>
            <div class="col-sm-8 parent-group">
                <input type="text" id="email" name="email" class="form-control"
                    placeholder="@lang('Email')">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="vendor">@lang('Vendor')</label>
            <div class="col-sm-8 parent-group">
                <select name="vendor_id" class="form-control base-plugin--select2" id="">
                    <option value=""></option>
                    @foreach (\Modules\Master\Entities\Vendor::get() as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="group">Hak Akses</label>
            <div class="col-sm-8 parent-group">
                @php
                    $temp_vendor = 0;
                @endphp
                <select class="form-control base-plugin--select2 show-tick" disabled>
                    @foreach (\App\Entities\Group::orderBy('name', 'asc')->get() as $item)
                        @if ($item->name == 'Vendor')
                            @php
                                $temp_vendor = $item->id;
                            @endphp
                            <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                        @endif
                    @endforeach
                </select>
                <input name="groups" id="" class="form-control" value="{{ $temp_vendor }}" type="hidden">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="group">Status</label>
            <div class="col-sm-8 parent-group">
                <select name="status" id="" class="form-control base-plugin--select2 show-tick"
                    data-placeholder="{{ __('Pilih Status') }}">
                    <option value="active">Aktif</option>
                    <option value="nonactive">Non Aktif</option>
                </select>
            </div>
        </div>
        <hr>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="password">@lang('Password')</label>
            <div class="col-sm-8 parent-group toggle-password">
                <input type="password" id="password" name="password" class="form-control"
                    placeholder="@lang('Password')">
                <div style="position: absolute; right: 20px; top: 12px; z-index:20;" tabindex="-1">
                    <a href="javascript:;" tabindex="-1"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="password">@lang('Konfirmasi Ulang Password')</label>
            <div class="col-sm-8 parent-group toggle-password">
                <input type="password" id="password" name="password_confirmation" class="form-control"
                    placeholder="@lang('Konfirmasi Password')">
                <div style="position: absolute; right: 20px; top: 12px; z-index:20;" tabindex="-1">
                    <a href="javascript:;" tabindex="-1"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer border-0 pt-0">
        <x-btn-save via="base-form--submit-modal" confirm="0" />
    </div>
</form>

<script>
    $('.modal-dialog').removeClass('modal-md').addClass('modal-lg');
    $(".masking-nik").inputmask({
        "mask": "9",
        "repeat": 16,
        "greedy": false
    });
    $(".toggle-password a").on('click', function(event) {
        event.preventDefault();
        if ($(this).parent().parent().find('input').attr("type") == "text") {
            $(this).parent().parent().find('input').attr('type', 'password');
            $(this).parent().parent().find('i').addClass("fa-eye-slash");
            $(this).parent().parent().find('i').removeClass("fa-eye");
        } else if ($(this).parent().parent().find('input').attr("type") == "password") {
            $(this).parent().parent().find('input').attr('type', 'text');
            $(this).parent().parent().find('i').removeClass("fa-eye-slash");
            $(this).parent().parent().find('i').addClass("fa-eye");
        }
    });
</script>
