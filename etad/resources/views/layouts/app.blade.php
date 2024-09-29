<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'Laravel 8.x') }} - @yield('title', 'Login')</title>
    <meta name="description" content="Welcome to {{ config('app.client') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ config('app.url') }}">
    <meta name="id" content="{{ auth()->id() }}">
    <link href="/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet"
        type="text/css" />
    <link href="/assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/themes/layout/header/base/light.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/themes/layout/header/menu/light.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/themes/layout/brand/light.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/themes/layout/aside/light.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="/assets/media/logos/favicon-bjicon.png" />

    <style>
        /* Side Mini Menu */
        .aside-minimize .separator-section {
            text-align: center !important;
        }

        .aside-minimize .separator-text {
            display: none !important;
        }

        .separator-icon {
            display: none;
        }

        .aside-minimize .separator-icon {
            display: block !important;
        }

        .thumb-avatar {
            width: 45px;
            height: 45px;
            background-color: #333;
            background-size: 100% auto;
            background-repeat: no-repeat;
            background-position: center;
            border-radius: 10px;
            margin: auto;
        }
    </style>
    @stack('styles')
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body"
    class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize page-loading">
    <!--begin::Main-->
    <!--begin::Header Mobile-->
    <div id="kt_header_mobile" class="header-mobile align-items-center header-mobile-fixed">
        <!--begin::Logo-->
        <a href="index.html">
            <img alt="Logo" src="/assets/media/logos/logo-light.png" />
        </a>
        <!--end::Logo-->
        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <!--begin::Aside Mobile Toggle-->
            <button class="p-0 btn burger-icon burger-icon-left" id="kt_aside_mobile_toggle">
                <span></span>
            </button>
            <!--end::Aside Mobile Toggle-->
            <!--begin::Header Menu Mobile Toggle-->
            <!-- <button class="p-0 ml-4 btn burger-icon" id="kt_header_mobile_toggle">
                <span></span>
            </button> -->
            <!--end::Header Menu Mobile Toggle-->
            <!--begin::Topbar Mobile Toggle-->
            <button class="p-0 ml-2 btn btn-hover-text-primary" id="kt_header_mobile_topbar_toggle">
                <span class="svg-icon svg-icon-xl">
                    <!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon points="0 0 24 0 24 24 0 24" />
                            <path
                                d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z"
                                fill="#000000" fill-rule="nonzero" opacity="0.3" />
                            <path
                                d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z"
                                fill="#000000" fill-rule="nonzero" />
                        </g>
                    </svg>
                    <!--end::Svg Icon-->
                </span>
            </button>
            <!--end::Topbar Mobile Toggle-->
        </div>
        <!--end::Toolbar-->
    </div>
    <!--end::Header Mobile-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="flex-row d-flex flex-column-fluid page">
            {{-- Aside --}}
            @include('layouts.partials.aside')
            {{-- End Aside --}}

            <!--begin::Wrapper-->
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                <!--begin::Header-->
                <div id="kt_header" class="header header-fixed">
                    <!--begin::Container-->
                    <div class="container-fluid d-flex align-items-stretch justify-content-between">
                        <!--begin::Header Menu Wrapper-->
                        @include('layouts.partials.header-nav')
                        <!--end::Header Menu Wrapper-->
                        <!--begin::Topbar-->
                        <div class="topbar">
                            <!--begin::Notifications-->
                            @include('layouts.partials.notification')
                            <!--end::Notifications-->
                            <!--begin::User-->
                            <div class="topbar-item">
                                @include('layouts.partials.profile')
                            </div>
                            <!--end::User-->
                        </div>
                        <!--end::Topbar-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Header-->
                <!--begin::Content-->
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <!--begin::Subheader-->
                    @include('layouts.partials.subheader')
                    <!--end::Subheader-->
                    <!--begin::Entry-->
                    <div id="content-page" class="d-flex flex-column-fluid"
                        data-sidebar-mini="{{ isset($sidebarMini) && $sidebarMini === true ? 'true' : 'false' }}">
                        <!--begin::Container-->
                        <div class="{{ empty($container) ? 'container-fluid' : $container }}">
                            @yield('content')
                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Entry-->
                </div>
                <!--end::Content-->
                {{-- Footer --}}
                @include('layouts.partials.footer')
                {{-- End Footer --}}
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Main-->
    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop">
        <span class="svg-icon">
            <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Up-2.svg-->
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                height="24px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <polygon points="0 0 24 0 24 24 0 24" />
                    <rect fill="#000000" opacity="0.3" x="11" y="10" width="2"
                        height="10" rx="1" />
                    <path
                        d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z"
                        fill="#000000" fill-rule="nonzero" />
                </g>
            </svg>
            <!--end::Svg Icon-->
        </span>
    </div>
    <!--end::Scrolltop-->
    <!--begin::Sticky Toolbar-->
    <!--end::Sticky Toolbar-->
    <!--begin::Global Config(global config for global JS scripts)-->
    <script>
        var baseurl = document.querySelector("meta[name=base-url]").getAttribute("content");
        var csrf = document.querySelector("meta[name=csrf-token]").getAttribute("content");
        var unid = document.querySelector("meta[name=id]").getAttribute("content");
    </script>
    <script>
        var KTAppSettings = {
            "breakpoints": {
                "sm": 576,
                "md": 768,
                "lg": 992,
                "xl": 1200,
                "xxl": 1400
            },
            "colors": {
                "theme": {
                    "base": {
                        "white": "#ffffff",
                        "primary": "#3699FF",
                        "secondary": "#E5EAEE",
                        "success": "#1BC5BD",
                        "info": "#8950FC",
                        "warning": "#FFA800",
                        "danger": "#F64E60",
                        "light": "#E4E6EF",
                        "dark": "#181C32"
                    },
                    "light": {
                        "white": "#ffffff",
                        "primary": "#E1F0FF",
                        "secondary": "#EBEDF3",
                        "success": "#C9F7F5",
                        "info": "#EEE5FF",
                        "warning": "#FFF4DE",
                        "danger": "#FFE2E5",
                        "light": "#F3F6F9",
                        "dark": "#D6D6E0"
                    },
                    "inverse": {
                        "white": "#ffffff",
                        "primary": "#ffffff",
                        "secondary": "#3F4254",
                        "success": "#ffffff",
                        "info": "#ffffff",
                        "warning": "#ffffff",
                        "danger": "#ffffff",
                        "light": "#464E5F",
                        "dark": "#ffffff"
                    }
                },
                "gray": {
                    "gray-100": "#F3F6F9",
                    "gray-200": "#EBEDF3",
                    "gray-300": "#E4E6EF",
                    "gray-400": "#D1D3E0",
                    "gray-500": "#B5B5C3",
                    "gray-600": "#7E8299",
                    "gray-700": "#5E6278",
                    "gray-800": "#3F4254",
                    "gray-900": "#181C32"
                }
            },
            "font-family": "Poppins"
        };
    </script>
    <!--end::Global Config-->
    {{-- Used Global Page --}}
    <script src="/assets/plugins/global/plugins.bundle.js"></script>
    {{-- <script src="/assets/plugins/custom/prismjs/prismjs.bundle.js"></script> --}}
    <script src="/assets/js/scripts.bundle.js"></script>
    <script src="/assets/js/theme.bundle.js"></script>
    <script src="/assets/js/base.bundle.js"></script>
    <script src="/assets/js/modules.bundle.js"></script>
    <script src="/assets/js/app.js"></script>

    {{-- Use Specific Page --}}
    <script src="/assets/js/pages/widgets.js"></script>
    <script src="/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
    @stack('scripts')
    <script>
        BaseForm.saveTempFiles      = function(el, event, options = []) {
            var el = $(el),
                form = el.closest('form'),
                files = event.target.files;

            var defaultOptions = {
                    parentClass: el.data('container') ?? 'form-group',
                    maxFile: el.data('max-file') ?? 1, //1:Singgle, 2,...:Multiple
                    maxSize: el.data('max-size') ?? 5024, //5mb
                    type: el.data('container') ?? null,
                    callbackSuccess: false,
                    callbackError: false,
                },
                options = $.extend(defaultOptions, options);

            var parent = el.closest('.' + options.parentClass);
            if (!parent.length) {
                parent = el.closest('.custom-file').parent();
            }
            if ((parent.find('.progress-container:not(.error-uploaded)').length >= options.maxFile) || (files.length >
                    options.maxFile)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Opps',
                    text: 'Maximal File ' + options.maxFile,
                    showConfirmButton: false,
                    // timer: 1500
                });
                el.val("");
                el.parent().find('.custom-file-label').text('Choose file');
                return false;
            }

            if (files.length) {
                var filesTooBig = [];
                $.each(files, function(index, file) {
                    if (file && file.size && (Math.round((file.size / 1024)) >= options.maxSize)) {
                        filesTooBig.push(file);
                    }
                });
                if (filesTooBig.length) {
                    var showSize = function(bytes) {
                        if (bytes === 0) {
                            return '0 Bytes';
                        } else {
                            var k = 1024;
                            var dm = 2;
                            var sizes = ['KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
                            var i = Math.floor(Math.log(bytes) / (Math.log(k)));
                            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
                        }
                    }

                    var fileNames = '<ul class="pl-20px text-left">';
                    $.each(filesTooBig, function(index, file) {
                        fileNames = fileNames + '<li>' + file.name + ' (' + (showSize(file.size / 1024)) + ')' +
                            ' </li>';
                    })
                    fileNames = fileNames + '</ul>';
                    Swal.fire({
                        icon: 'warning',
                        title: 'Opps',
                        html: 'Maximum File Size is ' + showSize(options.maxSize) +
                            '<br>Please check file fize:' + fileNames,
                        confirmButtonText: 'OKE',
                    });
                    if (parent.find('.success-uploaded').length == 0) {
                        el.val("");
                        parent.find('.custom-file-label').text('Choose file');
                    }
                } else {
                    $.each(files, function(index, file) {
                        if (file && file.size) {
                            var fmData = new FormData();
                            var uniqueid = Math.floor(Math.random() * 26) + Date.now();
                            fmData.append('_token', BaseUtil.getToken());
                            fmData.append('file', file);
                            fmData.append('type', options.type ? options.type : null);
                            fmData.append('uniqueid', uniqueid);

                            parent.find('.custom-file-label').text(files.length + ' Files Attached');

                            $.ajax({
                                url: '{{ url("ajax/saveTempFiles") }}',
                                type: 'POST',
                                data: fmData,
                                contentType: false,
                                processData: false,
                                // async: false,
                                beforeSend: function(e) {
                                    parent.append(`
                                    <div class="progress-container w-100" data-uid="` + uniqueid + `">
                                        <div class="progress uploading mt-2">
                                            <div class="progress-bar bar-` + uniqueid + ` progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">0%</div>
                                        </div>
                                    </div>
                                `);
                                },
                                xhr: function(resp) {
                                    var xhr = new window.XMLHttpRequest();
                                    xhr.upload.addEventListener("progress", function(evt) {
                                        if (evt.lengthComputable) {
                                            var percentComplete = parseInt((evt.loaded / evt
                                                .total) * 100);
                                            form.find('button[type="submit"]').attr(
                                                'disabled', 'disabled');
                                            form.find('.progress-bar.bar-' + uniqueid)
                                                .attr('aria-valuenow', percentComplete)
                                                .css('width', percentComplete + '%')
                                                .text(percentComplete + '%');
                                        }
                                    }, false);
                                    return xhr;
                                },
                                success: function(resp) {
                                    if ($.isFunction(options.callbackSuccess)) {
                                        options.callback(resp, el, uniqueid);
                                    }
                                    var icon = 'far fa-file-alt';
                                    if (resp.file.file_type == 'pdf') {
                                        icon = 'text-danger far fa-file-pdf';
                                    } else if (resp.file.file_type == 'xlsx') {
                                        icon = 'text-success far fa-file-excel';
                                    } else if (resp.file.file_type == 'jpg' || resp.file
                                        .file_type == 'png') {
                                        icon = 'text-warning far fa-file-image';
                                    } else if (resp.file.file_type == 'ppt') {
                                        icon = 'text-danger far fa-file-powerpoint';
                                    } else if (resp.file.file_type == 'docx') {
                                        icon = 'text-primary far fa-file-word';
                                    }
                                    parent.find('.uploaded').val(1);
                                    parent.find('.progress-container[data-uid="' + uniqueid + '"]')
                                        .prepend(`
                                        <div class="alert alert-custom alert-light fade show py-2 px-4 mb-0 mt-2 success-uploaded" role="alert">
                                            <div class="alert-icon">
                                                <i class="` + icon + `"></i>
                                            </div>
                                            <div class="alert-text text-left">
                                                <input type="hidden" name="` + el.data('name') +
                                            `[temp_files_ids][]" value="` + resp.file.id + `">
                                                <div>Upload File:</div>
                                                <a href="` + window.location.origin +'/storage/' + resp.file.path +
                                            `" target="_blank" class="text-primary">` + resp.file
                                            .name + `</a>
                                            </div>
                                            <div class="alert-close">
                                                <button type="button" class="close base-form--remove-temp-files" data-toggle="tooltip" title="Remove">
                                                    <span aria-hidden="true">
                                                        <i class="ki ki-close"></i>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    `);
                                    parent.find('.progress-container[data-uid="' + uniqueid +
                                            '"] .progress')
                                        .removeClass('mt-2');
                                    form.find('button[type="submit"]').removeAttr('disabled');
                                    form.find('.progress-bar.bar-' + uniqueid)
                                        .removeClass('progress-bar-striped')
                                        .text('Done');
                                    // var myEvent = window.attachEvent || window.addEventListener;
                                    // var chkevent = window.attachEvent ? 'onbeforeunload' : 'beforeunload';
                                    // myEvent(chkevent, function (e) { // For >=IE7, Chrome, Firefox
                                    //     var confirmationMessage = 'Are you sure to leave the page?'; // a space
                                    //     (e || window.event).returnValue = confirmationMessage;
                                    //     return confirmationMessage;
                                    // });
                                    parent.find('.custom-file-label').text('Add file');
                                    BasePlugin.initTooltipPopover();
                                    if ((parent.find('.progress-container').length >= options
                                            .maxFile) || (files.length > options.maxFile)) {
                                        // el.prop('disabled', true);
                                        parent.find('.custom-file-label').text('Uploaded');
                                    }
                                },
                                error: function(resp) {
                                    parent.find('.progress-container[data-uid="' + uniqueid + '"]')
                                        .remove();
                                    parent.append(`
                                        <div class="alert alert-custom alert-light-danger fade show py-2 px-4 my-2 error-uploaded" role="alert">
                                            <div class="alert-icon">
                                                <i class="flaticon-warning"></i>
                                            </div>
                                            <div class="alert-text text-left">Error Upload File: ` + file.name + `</div>
                                            <div class="alert-text text-left">
                                                <div>Upload File:</div>
                                                <a href="/hello" class="text-primary">` + file.name + `</a>
                                            </div>
                                            <div class="alert-close">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">
                                                            <i class="ki ki-close"></i>
                                                        </span>
                                                    </button>
                                            </div>
                                        </div>
                                    `);
                                    form.find('button[type="submit"]').removeAttr('disabled');
                                    form.find('.progress-bar.bar-' + uniqueid)
                                        .removeClass('progress-bar-striped')
                                        .text('Error');
                                    if (parent.find('.success-uploaded').length == 0) {
                                        parent.find('.uploaded').val('');
                                    }
                                    parent.find('.custom-file-label').text('Choose file');
                                },
                            });
                        }
                    });
                }
            }
        };
        BaseForm.removeTempFiles    = function (el) {
            var me = $(el),
                container = me.closest('.progress-container'),
                parent = container.parent();

            me.tooltip('hide');
            parent.find('input[type="file"]').val('');
            parent.find('.custom-file-label').text('Choose file');
            if (parent.find('.success-uploaded').length == 0) {
                parent.find('.uploaded').val('');
            }
            container.remove();
            BasePlugin.initTooltipPopover();
        };
        $(document)
            .on('change', 'input.base-form--save-temp-files', function (e) {
                BaseForm.saveTempFiles(this, e);
            })
            .on('click', '.base-form--remove-temp-files', function (e) {
                // e.preventDefault();
                BaseForm.removeTempFiles(this);
            })
            .on('change', '.custom-file input[type="file"]:not(.base-form--save-temp-files)', function (e) {
                if (e.target.files.length) {
                    $(this).next('.custom-file-label').html(e.target.files[0].name);
                }
            });
    </script>
</body>
<!--end::Body-->

</html>
