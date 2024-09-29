const BaseUtil = function () {
	return {
		init: function () {
			this.refreshComponent();
			this.handleOnPopstate();
			this.handleAsideMenuTree();
			this.menuActivation();
			this.userNotification();
			// this.handleServerSendEvent();

		},
		refreshComponent: function () {
			this.bodyClasses();
			this.sidebarMini();
			this.menuActivation();
			this.sidebarBadge();
		},
		isDev: function () {
			return $('meta[name="debug"]').attr('content');
		},
		getToken: function () {
			return $('meta[name="csrf-token"]').attr('content');
		},
		redirect: function (url) {
			window.location = url;
		},
		bodyClasses: function () {
			var body = $('body');
			if (body.find('.subheader').length) {
				if (!body.hasClass('subheader-enabled')) {
					body.addClass('subheader-enabled subheader-fixed');
				}
			}
			else {
				body.removeClass('subheader-enabled subheader-fixed');
			}
		},
		initScroll: function() {
		    $('[data-scroll="true"]').each(function() {
		        var el = $(this);

		        KTUtil.scrollInit(this, {
		            mobileNativeScroll: true,
		            handleWindowResize: true,
		            rememberPosition: (el.data('remember-position') == 'true' ? true : false),
		            height: function() {
		                if (KTUtil.isBreakpointDown('lg') && el.data('mobile-height')) {
		                    return el.data('mobile-height');
		                } else {
		                    return el.data('height');
		                }
		            }
		        });
		    });
		},
		menuActivation: function () {
			var pageName = $('#content-page').attr('data-sidebar-name');
			var menuActive = '#sidebar .menu-link[data-name="'+ pageName +'"]';

			if ($(menuActive).length === 0) {
				menuActive = '#sidebar .menu-link[href="'+ window.location.pathname +'"]';
			}

			if ($(menuActive).length) {
				if ($('#sidebar.custom-sidebar').length) {
					$('#sidebar ul, #sidebar li').removeClass('active');
					$(menuActive).parents('ul, li').addClass('active');
					$(menuActive).closest('li').find('ul').addClass('active');
					$('#sidebar li:not(.active)').removeClass('expand').addClass('closed');
					$('#sidebar ul:not(.active)').removeClass('expand').addClass('closed').hide();
				}
				else {
					$('#sidebar .menu-item').removeClass('menu-item-active');
					$('#sidebar .menu-item-submenu').removeClass('menu-item-open');
					$(menuActive).last().addClass('active');
					$(menuActive).last().parents('.menu-item').addClass('menu-item-active');
					$(menuActive).last().parents('.menu-item-submenu').addClass('menu-item-open');
				}
			}

		},
		sidebarMini: function () {
			var body = $('body'),
				mini = $('#content-page').data('sidebar-mini');
			if (mini == true) {
				body.addClass('aside-minimize');
				KTCookie.setCookie('kt_aside_toggle_state', 'on');
			}
			else {
				body.removeClass('aside-minimize');
				KTCookie.setCookie('kt_aside_toggle_state', 'off');
			}
		},
		sidebarBadge: function (options = {}) {
			if ($('[data-sidebar-badge="true"]').length) {
				var defaultOptions = {
						url: '/globals/refresh-sidebar-badge',
						type: 'POST',
						data: {
							_token : BaseUtil.getToken()
						}
					},
					options = $.extend(defaultOptions, options);

				$.ajax({
					url: options.url,
					type: options.type,
					data: options.data,
					success: function (resp) {
					    $('.base-notification-wrapper').html(resp);
					},
					error: function (resp) {
						dd(resp);
					}
				});
			}
		},
		userNotification: function (options = {}) {
			var wrapper = $('.base-notification-wrapper');
			if (wrapper.length) {
				var defaultOptions = {
						url: '/globals/utility/user-notification',
						type: 'GET',
					},
					options = $.extend(defaultOptions, options);

				$.ajax({
					url: options.url,
					type: options.type,
					data: options.data,
					success: function (resp) {
					    wrapper.html(resp);
					    wrapper.find('[data-scroll="true"]').each(function() {
					        var el = $(this);

					        KTUtil.scrollInit(this, {
					            mobileNativeScroll: true,
					            handleWindowResize: true,
					            rememberPosition: (el.data('remember-position') == 'true' ? true : false),
					            height: function() {
					                if (KTUtil.isBreakpointDown('lg') && el.data('mobile-height')) {
					                    return el.data('mobile-height');
					                } else {
					                    return el.data('height');
					                }
					            }
					        });
					    });
					    var el_items = wrapper.find('.user-notification-items').first();
					    if (el_items.length && parseInt(el_items.data('count')) > 0) {
					    	var count = parseInt(el_items.data('count'));
					    	wrapper.closest('.dropdown').find('.user-notification-badge span').html(count);
					    	wrapper.closest('.dropdown').find('.user-notification-header span').html(count+' New');
					    	wrapper.closest('.dropdown').find('.pulse-ring').removeClass('hide');
					    	wrapper.closest('.dropdown').find('.user-notification-badge').removeClass('hide');
					    	wrapper.closest('.dropdown').find('.user-notification-header').removeClass('hide');
					    }
					    else {
					    	wrapper.closest('.dropdown').find('.pulse-ring').addClass('hide');
					    	wrapper.closest('.dropdown').find('.user-notification-badge').addClass('hide');
					    	wrapper.closest('.dropdown').find('.user-notification-header').addClass('hide');
					    }
					},
					error: function (resp) {
						console.log(resp);
					}
				});
			}
		},
		handleOnPopstate: function () {
			if (typeof window.onpopstate != 'undefined') {
				window.onpopstate = function(e) {
					e.preventDefault();
					window.location.reload();
				};
			}
			else {
				$.gritter.add({
					title: 'Warning!',
					text: 'Browser Anda tidak support History PushState.<br/>Anda tidak akan mendapatkan loading page tanpa reload!',
					// image: '/assets/images/icon/ui/cross.png',
					sticky: false,
					time: '10000'
				});
			}
		},
		handleServerSendEvent: function () {
			if(typeof(EventSource) !== "undefined") {
				// withCredentials=true: pass the cross-domain cookies to server-side
				const source = new EventSource('/globals/sse', {withCredentials:false});
				source.addEventListener('news', function(event) {
				    var data = JSON.parse(event.data);
				    handleRefreshSidebarBadge(data.news.sidebar);

				    // source.close(); // disconnect stream
				}, false);
			}
			else {
				$.gritter.add({
					title: 'Warning!',
					text: 'Browser Anda tidak support HTML5 SSE API.<br/>Anda tidak akan mendapatkan realtime notifications!',
					// image: '/assets/images/icon/ui/cross.png',
					sticky: false,
					time: '10000'
				});
			}
		},
		handleAsideMenuTree: function () {
			var expandTime = ($('.custom-sidebar').attr('data-disable-slide-animation')) ? 0 : 250;
			$(document).on('click', '.custom-sidebar.sidebar-menu .nav > .has-sub > a', function() {
				var target = $(this).next('.sub-menu');
				var otherMenu = $('.custom-sidebar.sidebar-menu .nav > li.has-sub > .sub-menu').not(target);

				if ($('.page-sidebar-minified').length === 0) {
					$(otherMenu).closest('li').addClass('closing');
					$(otherMenu).slideUp(expandTime, function() {
						$(otherMenu).closest('li').addClass('closed').removeClass('expand closing');
					});
					if ($(target).is(':visible')) {
						$(target).closest('li').addClass('closing').removeClass('expand');
					} else {
						$(target).closest('li').addClass('expanding').removeClass('closed');
					}
					$(target).slideToggle(expandTime, function() {
						var targetLi = $(this).closest('li');
						if (!$(target).is(':visible')) {
							$(targetLi).addClass('closed');
							$(targetLi).removeClass('expand');
						} else {
							$(targetLi).addClass('expand');
							$(targetLi).removeClass('closed');
						}
						$(targetLi).removeClass('expanding closing');
					});
				}
			});
			$(document).on('click', '.custom-sidebar.sidebar-menu .nav > .has-sub .sub-menu li.has-sub > a', function() {
				if ($('.page-sidebar-minified').length === 0) {
					var target = $(this).next('.sub-menu');
					if ($(target).is(':visible')) {
						$(target).closest('li').addClass('closing').removeClass('expand');
					} else {
						$(target).closest('li').addClass('expanding').removeClass('closed');
					}
					$(target).slideToggle(expandTime, function() {
						var targetLi = $(this).closest('li');
						if (!$(target).is(':visible')) {
							$(targetLi).addClass('closed');
							$(targetLi).removeClass('expand');
						} else {
							$(targetLi).addClass('expand');
							$(targetLi).removeClass('closed');
						}
						$(targetLi).removeClass('expanding closing');
					});
				}
			});

			$(document).on('click', '.custom-sidebar.sidebar-filter .nav > .has-sub > a', function() {
				var target = $(this).next('.sub-menu');
				var otherMenu = $('.custom-sidebar .nav > li.has-sub > .sub-menu').not(target);

				if ($('.page-sidebar-minified').length === 0) {
					$(otherMenu).closest('li').addClass('closing');
					$(otherMenu).slideUp(expandTime, function() {
						$(otherMenu).closest('li').addClass('closed').removeClass('expand closing');
					});
					if ($(target).is(':visible')) {
						$(target).closest('li').addClass('closing').removeClass('expand');
					} else {
						$(target).closest('li').addClass('expanding').removeClass('closed');
					}
					$(target).slideToggle(expandTime, function() {
						var targetLi = $(this).closest('li');
						if (!$(target).is(':visible')) {
							$(targetLi).addClass('closed');
							$(targetLi).removeClass('expand');
						} else {
							$(targetLi).addClass('expand');
							$(targetLi).removeClass('closed');
						}
						$(targetLi).removeClass('expanding closing');
					});
				}
			});
			$(document).on('click', '.custom-sidebar.sidebar-filter .nav > .has-sub .sub-menu li.has-sub > a', function() {
				if ($('.page-sidebar-minified').length === 0) {
					var target = $(this).next('.sub-menu');
					if ($(target).is(':visible')) {
						$(target).closest('li').addClass('closing').removeClass('expand');
					} else {
						$(target).closest('li').addClass('expanding').removeClass('closed');
					}
					$(target).slideToggle(expandTime, function() {
						var targetLi = $(this).closest('li');
						if (!$(target).is(':visible')) {
							$(targetLi).addClass('closed');
							$(targetLi).removeClass('expand');
						} else {
							$(targetLi).addClass('expand');
							$(targetLi).removeClass('closed');
						}
						$(targetLi).removeClass('expanding closing');
					});
				}
			});
			// $(document).on('click', '.base-util--user-notifications', function () {
			// 	var me = $(this),
			// 		options = {
			// 			url: me.data('url')
			// 		}
			// 	BaseUtil.handleUserNotification(options);
			// });
		}
	}
}();

// webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = BaseUtil;
}