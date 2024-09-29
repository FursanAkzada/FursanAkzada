/*
 * BASE CONTENT
 */

var BASE_CONTENT_HISTORIES = [];
const BaseContent = function () {
	return {
		init: function () {
			this.pushHistory();
			this.handlePushStyles();
		},
		getHistory: function () {
			return BASE_CONTENT_HISTORIES;
		},
		pushHistory: function () {
			if (BaseContent.hasHistory() === false) {
				BASE_CONTENT_HISTORIES.push(window.location.href);
			}
		},
		hasHistory: function () {
			if ($.inArray(window.location.href, BASE_CONTENT_HISTORIES) === -1) {
				return false;
			}
			return true;
		},
		replace: function (element, options = {}) {
			var el = $(element);
			var url = el.attr('href') ? el.attr('href') : el.data('url');

			BaseContent.replaceByUrl(url, options);
		},
		replaceByUrl: function (url, options = {}) {
			if ($('meta[data-replace="true"]').length) {
				var defaultOptions = {
					url: url,
					data: {
						_token: BaseUtil.getToken()
					}
				},
					options = $.extend(defaultOptions, options);

				var contentLoading = setTimeout(function () {
					BaseContent.loader(true, false);
				}, 1200);

				if (BASE_CONTENT_HISTORIES.length < 1000 && $.isFunction(window.history.pushState)) {
					$.ajax({
						url: options.url,
						dataType: 'html',
						headers: { 'Base-Replace-Content': true },
						beforeSend: function () {
							BaseContent.clearBody();
						},
						success: function (resp) {
							clearTimeout(contentLoading);
							BaseContent.handleContentPageState(resp, options);
							BaseContent.handlePushStyles();
							BaseContent.loader(false);
						},
						error: function () {
							BaseUtil.redirect(options.url);
						}
					});
				}
				else {
					BaseUtil.redirect(options.url);
				}
			} else {
					BaseUtil.redirect(url);
			}
		},
		clearBody: function () {
			$('body > :not(.no-body-clear, #gritter-notice-wrapper, .swal-overlay)').remove();
		},
		loader: function (loading = true, fullbackdrop = true) {
			let body = $('body');
			if (!body.hasClass('page-loading')) {
				if (loading === true) {
					if (!body.hasClass('base-content-loading')) {
						body.addClass('base-content-loading');
						if (fullbackdrop === true) {
							body.addClass('full-backdrop');
						}
					}
				} 
				else {
					body.removeClass('base-content-loading full-backdrop');
				}
			}
		},
		handlePushStyles: function () {
			$('head [data-content-page-style="true"]').remove();
			$('#content style').attr('data-content-page-style', true).appendTo('head');	
		},
		handlePushScripts: function (resp) {
			if (BaseContent.hasHistory()) {
				return resp.replace('$(document).on', '$(".document-script-was-declarated").on');
			}
			return resp;
		},
		handleContentPageState: function (resp, options) {
			resp = BaseContent.handlePushScripts(resp);
			$('#content').html(resp);
			var dataContent = $('#content').find('.base-content--state').first();
				
			if (dataContent.length) {
				var state = {
					title: dataContent.data('title') ? dataContent.data('title') : $('title').text,
					url: dataContent.data('url') ? dataContent.data('url') : options.url
				};
				window.history.pushState(state, state.title, state.url);
				document.title = state.title;
				BaseList.init();
				BasePlugin.init();
				BaseUtil.refreshComponent();
				if ($('[data-refresh-user-notification="true"').length) {
					BaseUtil.userNotification();
				}
				BaseContent.pushHistory();
				ModuleApp.init();

				if ($.isFunction(options.callback)) {
					options.callback();
				}
				$('body,html').animate({scrollTop: 0}, 500);
			}
			else {
				$('#content').css('opacity', 0);
				BaseUtil.redirect(options.url);
			}
		},
	}
}();

// webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = BaseContent;
}