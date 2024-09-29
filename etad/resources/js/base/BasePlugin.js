/**
 * ============================= BASE PLUGINS ============================
 * return render(options), loader(modal_id, loading=true)
 */
const BasePlugin = function () {
	"use strict";
	return {
		init: function () {
			this.initTooltipPopover();
			this.initSelectpicker();
			this.initSelect2();
			this.initDatepicker();
			this.initTimepicker();
			this.initSummernote();
			this.initInputMask();
			this.initTouchSpin();
		},
		initTooltipPopover: function () {
			if ($('[data-toggle="tooltip"]').length) {
				$('[data-toggle=tooltip]').tooltip();
			}
			if ($('[data-toggle="popover"]').length) {
				$('[data-toggle=popover]').popover();
			}
		},
		initSelect2: function () {
			if ($('select.base-plugin--select2').length) {
				$.each($('select.base-plugin--select2'), function (i, el) {
					let me = $(el);
					if (me.parent().hasClass('select2')) {
						me.select2();
					}
					else {
						let defaultsOptions = {
								placeholder     : me.attr('title') ?? 'Pilih salah satu',
							},
							options = me.data('options') ?? {};
						options = $.extend(defaultsOptions, options);
						me.select2(options);
					}
				});
			}
			if ($('.base-plugin--select2-ajax').length) {
				$.each($('.base-plugin--select2-ajax'), function (i, el) {
					let me = $(el),
						defaultsOptions = {
							url     : me.data('url'),
							method: me.data('method') ?? 'POST',
							delay   : me.data('delay') ?? 500,
							cache   : me.data('cache') ?? true,
							minimumInputLength: me.data('min-input-length'),
							placeholder: me.attr('title') ?? 'Pilih salah satu',
						},
						options = me.data('options') ?? {};

					options = $.extend(defaultsOptions, options);

					if (options.url) {
						me.select2({
							ajax: {
								url: options.url,
								type: options.method,
								dataType: 'json',
								delay: options.delay,
								cache: options.cache,
								data: function (params) {
									return {
										_token: BaseUtil.getToken(),
										q: params.term, // search term
										page: params.page
									};
								},
								processResults: function (resp, params) {
									params.page = params.page || 1;

									return {
										results: resp.results ?? [],
										pagination: {
											more: resp.more ?? false
										}
									};
								},
							},
							minimumInputLength: options.minimumInputLength,
							placeholder: options.placeholder ?? 'Choose One'
						});
					}
				});
			}
		},
		initSelectpicker: function () {
			if ($('select.base-plugin--select, select.base-plugin--selectpicker').length) {
				$.each($('select.base-plugin--select, select.base-plugin--selectpicker'), function (i, el) {
					let me = $(el);
					if (me.parent().hasClass('bootstrap-select')) {
						me.selectpicker('refresh');
					}
					else {
						let defaultsOptions = {
								container : me.data('container') ?? 'body',
								title     : me.attr('title') ?? 'Pilih salah satu',
								dropupAuto: me.data('dropup-auto') ?? false,
								liveSearch: me.data('live-search') ?? true,
								size      : me.data('size') ?? 5,
							},
							options = me.data('options') ?? {};

						options = $.extend(defaultsOptions, options);

						me.selectpicker(options);
					}
				});
			}
			if ($('.base-plugin--select_add').length) {
				$('.base-plugin--select_add').selectpicker();
				$('select.base-plugin--select_add').on('change', function () {
					var me = $(this),
						val = me.val();
					if (val) {
						me.closest('.bootstrap-select').removeClass('width-70');
						me.closest('.bootstrap-select').addClass('width-full');
					}
					else {
						me.closest('.bootstrap-select').removeClass('width-full');
						me.closest('.bootstrap-select').addClass('width-70');
						me.closest('.bootstrap-select').css('border-radius', '20px');
					}
				});
				$.each($('select.base-plugin--select_add'), function () {
					var me = $(this),
						val = me.val();
					if (val) {
						me.closest('.bootstrap-select').removeClass('width-70');
						me.closest('.bootstrap-select').addClass('width-full');
					}
					else {
						me.closest('.bootstrap-select').removeClass('width-full');
						me.closest('.bootstrap-select').addClass('width-70');
						me.closest('.bootstrap-select').css('border-radius', '20px');
					}
				})
			}
		},
		initDatepicker: function () {
			$.fn.datepicker.defaults.format = "dd/mm/yyyy";
			$.fn.datepicker.defaults.language = "en";
			if ($('.base-plugin--datepicker, .base-plugin--datepicker-1').length) {
				$.each($('.base-plugin--datepicker, .base-plugin--datepicker-1'), function (i, el) {
					let me = $(el),
						defaultsOptions = {
							autoclose     : me.data('auto-close') ?? true,
							todayHighlight: me.data('today-highlight') ?? true,
							orientation   : me.data('orientation') ?? 'auto bottom',
							format: me.data('format') ?? 'dd/mm/yyyy',
							startView: me.data('start-view') ?? "days",
							minViewMode: me.data('min-view') ?? "days",
							language : me.data('language') ?? 'id'
						},
						options = me.data('options') ?? {};

					options = $.extend(defaultsOptions, options);

					me.datepicker(options);
				});
			}
			if ($('.base-plugin--datepicker-2').length) {
				$.each($('.base-plugin--datepicker-2'), function (i, el) {
					let me = $(el),
						defaultsOptions = {
							autoclose     : me.data('auto-close') ?? true,
							todayHighlight: me.data('today-highlight') ?? true,
							orientation   : me.data('orientation') ?? 'auto',
							format: me.data('format') ?? 'mm/yyyy',
							startView: me.data('start-view') ?? "months",
							minViewMode: me.data('min-view') ?? "months",
							language : me.data('language') ?? 'id'
						},
						options = me.data('options') ?? {};

					options = $.extend(defaultsOptions, options);

					me.datepicker(options);
				});
			}
			if ($('.base-plugin--datepicker-3').length) {
				$.each($('.base-plugin--datepicker-3'), function (i, el) {
					let me = $(el),
						defaultsOptions = {
							autoclose     : me.data('auto-close') ?? true,
							todayHighlight: me.data('today-highlight') ?? true,
							orientation   : me.data('orientation') ?? 'auto',
							format: me.data('format') ?? 'yyyy',
							startView: me.data('format') ?? "years",
							minViewMode: me.data('format') ?? "years",
							language : me.data('language') ?? 'id'
						},
						options = me.data('options') ?? {};

					options = $.extend(defaultsOptions, options);
					console.log(options);
					me.datepicker(options);
				});
			}
		},
		initTimepicker: function () {
			if ($('.base-plugin--timepicker').length) {
				$.each($('.base-plugin--timepicker'), function (i, el) {
					let me = $(el),
						defaultsOptions = {
							minuteStep  : me.data('minute-step') ?? 2,
							defaultTime : me.data('default-time') ?? '00:00',
							showSeconds : me.data('show-seconds') ?? false,
							showMeridian: me.data('show-meridian') ?? false,
							snapToStep  : me.data('snap-to-step') ?? true,
							orientation : me.data('orientation') ?? 'auto'
						},
						options = me.data('options') ?? {};

					options = $.extend(defaultsOptions, options);

					me.timepicker(options);
				});
			}
		},
		initSummernote: function () {
			if ($('.base-plugin--summernote-readonly').length) {
				$('.base-plugin--summernote-readonly').append(`
					<div class="note-statusbar" role="status">
						<div class="note-resizebar" aria-label="Resize">
							<div class="note-icon-bar"></div>
							<div class="note-icon-bar"></div>
							<div class="note-icon-bar"></div>
						</div>
					</div>`);
			}
			if ($('.base-plugin--summernote, .base-plugin--summernote-1').length) {
				$.each($('.base-plugin--summernote, .base-plugin--summernote-1'), function (i, el) {
					let me = $(el),
						defaultsOptions = {
							height  : me.data('height') ?? 100,
							toolbar : me.data('toolbar') ?? false,
						},
						options = me.data('options') ?? {};

					options = $.extend(defaultsOptions, options);
					me.summernote(options);
				});
			}
			if ($('.base-plugin--summernote-2').length) {
				$.each($('.base-plugin--summernote-2'), function (i, el) {
					let me = $(el),
						defaultsOptions = {
							height  : me.data('height') ?? 300,
						},
						options = me.data('options') ?? {};

					options = $.extend(defaultsOptions, options);
					me.summernote(options);
				});
			}
			if ($('.base-plugin--summernote-simple').length) {
				$.each($('.base-plugin--summernote-simple'), function (i, el) {
					let me = $(el),
						defaultsOptions = {
							addclass: {
				            debug: false,
				            classTags: [
				            			{
				            				title:"Button","value":"btn btn-success"
				            			},
				            			"jumbotron",
				            			"lead",
				            			"img-rounded",
				            			"img-circle",
				            			"img-responsive",
				            			"btn",
				            			"btn btn-success",
				            			"btn btn-danger",
				            			"text-muted",
				            			"text-primary",
				            			"text-warning",
				            			"text-danger",
				            			"text-success",
				            			"table-bordered", "table-responsive", "alert", "alert alert-success", "alert alert-info", "alert alert-warning", "alert alert-danger", "visible-sm", "hidden-xs", "hidden-md", "hidden-lg", "hidden-print"]
				        },
					        toolbar: [
					                    ['font', ['bold', 'italic', 'underline']],
					                    ['para', ['ul', 'ol', 'paragraph']],
					                ],
					        height: 150
						},
						options = me.data('options') ?? {};

					options = $.extend(defaultsOptions, options);
					me.summernote(options);
				});
			}
		},
		initInputMask: function () {
			if ($('.base-plugin--inputmask_currency').length) {
				$(".base-plugin--inputmask_currency").inputmask({
					'repeat': '15',
					'groupSeparator': ".",
					'alias': "numeric",
					// 'placeholder': "0",
					'autoGroup': true,
					'radixPoint': ".",
					// 'digits': 2,
					// 'digitsOptional': false,
					'clearMaskOnLostFocus': false
				});
			}

			if ($('.base-plugin--inputmask_int').length) {
				$(".base-plugin--inputmask_int").inputmask({
					"mask": "9",
					"repeat": 11,
					"greedy": false
				});
			}
			if ($('.base-plugin--inputmask_int_4').length) {
				$(".base-plugin--inputmask_int_4").inputmask({
					"mask": "9",
					"repeat": 4,
					"greedy": false
				});
			}
			if ($('.base-plugin--inputmask_int_2').length) {
				$(".base-plugin--inputmask_int_2").inputmask({
					"mask": "9",
					"repeat": 4,
					"greedy": false
				});
			}
			if ($('.base-plugin--inputmask_dec_2').length) {
				$(".base-plugin--inputmask_dec_2").inputmask('decimal', {
					"digits": 2,
					"rightAlign": false,
					"repeat": 4,
					"greedy": false
				});
			}
			if ($('.base-plugin--inputmask_phone').length) {
				$(".base-plugin--inputmask_phone").inputmask({
					"mask": "9",
					"repeat": 15,
					"greedy": false
				});
			}
		},
		initTouchSpin: function () {
			if ($('.base-plugin--touchspin_int').length) {
				$('.base-plugin--touchspin_int').TouchSpin({
					buttondown_class: 'btn btn-secondary',
					buttonup_class: 'btn btn-secondary',
					verticalbuttons: true,
					verticalup: '<i class="ki ki-plus"></i>',
					verticaldown: '<i class="ki ki-minus"></i>',

					min: 0,
					max: 9999999999,
					step: 1,
					decimals: 0,
					boostat: 5,
					maxboostedstep: 10,
				});
			}

			if ($('.base-plugin--touchspin_dec').length) {
				$('.base-plugin--touchspin_dec').TouchSpin({
					buttondown_class: 'btn btn-secondary',
					buttonup_class: 'btn btn-secondary',
					verticalbuttons: true,
					verticalup: '<i class="ki ki-plus"></i>',
					verticaldown: '<i class="ki ki-minus"></i>',

					min: 0,
					max: 9999999999,
					step: 0.01,
					decimals: 2,
					boostat: 5,
					maxboostedstep: 10,
				});
			}


		}
	}
}();

// webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = BasePlugin;
}
