const BaseApp = function () {
	return {
		init: function () {
			BaseList.init();
			BasePlugin.init();
			BaseContent.init();
			BaseUtil.init();
			BaseForm.approval();
		},
		documentEvent: function () {
			BaseList.documentEvent();

			$(document).on('change', '.custom-file input', function (e) {
		        var fileName = '';
						var size = $(this).attr('size');
							var limitSize = false;
							if (e.target.files.length) {
								for(var i = 0 ; i < this.files.length ; i++){
									if (size && this.files[i].size > (size * 1000)) {
										limitSize = true;
									}
									fileName += this.files[i + 1] ? this.files[i].name + ', ' : this.files[i].name;
								}
							}
							if (limitSize) {
								$.gritter.add({
									title: 'Failed!',
									text: 'File terlalu besar',
									image: baseurl + '/assets/images/icon/ui/cross.png',
									sticky: false,
									time: '3000'
								});
								$(this).next('.custom-file-label').html('Pilih file');
							} else {
								$(this).next('.custom-file-label').html(fileName);
								$('.primary-text').attr("href", "hello");

							}
			});

			$(document).on('click', '.base-content--replace', function(e) {
				e.preventDefault();
				BaseContent.replace(this);
			});

			$(document).on('click', '.base-modal--render', function (e) {
				e.preventDefault();
				BaseModal.render(this);
			});

			$(document).on('click', '[data-toggle="tooltip"]', function () {
				$(this).tooltip('hide');
			});

			/* ========================================================================================
			 * BASE FORM
			 * ========================================================================================/

			/* ============================= LOGIN ============================ */
			$(document).on('click', '.base-form--submit-login', function (e) {
				e.preventDefault();

				var me = $(this),
					form = me.closest('form');

				BaseForm.submit(form, {
					btnSubmit: me,
					swalError: false,
					fullCallbackSuccess: true,
					callbackSuccess: function (resp, form, options) {
						BaseUtil.redirect('/');
					},
				});
			});

			/**
			 * ============================= MODAL SAVE ============================
			 */
			$(document).on('click', '.base-form--submit-modal', function (e) {
				e.preventDefault();

				var me = $(this),
					form = me.closest('form'),
					swalConfirm = me.data('swal-confirm'),
					swalTitle = me.data('swal-title') ? me.data('swal-title') : 'Apakah Anda yakin data sudah sesuai?',
					swalText = me.data('swal-text');

				if (form.find('[name="is_submit"]').length == 0) {
					form.append('<input type="hidden" name="is_submit" value="1">');
				}
				form.find('[name="is_submit"]').val(me.data('submit') ?? 1);

				if (swalConfirm) {
					swal({
						title: swalTitle,
						text: swalText,
						icon: 'warning',
						buttons: ['Tidak', 'Ya']
					}).then(function (result) {
						if (result === true) {
							BaseForm.submit(form, {
								btnSubmit: me,
							});
						}
					});
				}
				else {
					BaseForm.submit(form, {
						btnSubmit: me,
					});
				}
			});

			$(document).on('click', '.base-form--submit-page', function (e) {
				e.preventDefault();

				var me = $(this),
					form = me.closest('form'),
					swalConfirm = me.data('swal-confirm'),
					swalTitle = me.data('swal-title') ? me.data('swal-title') : 'Apakah Anda yakin data sudah sesuai?',
					swalText = me.data('swal-text');

				if (form.find('[name="is_submit"]').length == 0) {
					form.append('<input type="hidden" name="is_submit" value="1">');
				}
				form.find('[name="is_submit"]').val(me.data('submit') ?? 1);

				if (swalConfirm !== false) {
					swal({
						title: swalTitle,
						text: swalText,
						icon: 'warning',
						buttons: ['Tidak', 'Ya']
					}).then(function (result) {
						if (result === true) {
							BaseForm.submit(form, {
								btnSubmit: me,
								btnBack: '.btn-back',
								loaderModal: false,
							});
						}
					});
				}
				else {
					BaseForm.submit(form, {
						btnSubmit: me,
						btnBack: '.btn-back',
						loaderModal: false,
					});
				}
			});

			/**
			 * ============================= BUTTON BASE DELETE ============================
			 */
			$(document).on('click', '.base-modal--delete', function (e) {
				e.preventDefault();
				BaseModal.delete(this);
			});

			$(document).on('click', '.base-modal--confirm', function (e) {
				e.preventDefault();
				BaseModal.confirm(this);
			});

			$(document).on('click', '.base-form--delete', function (e) {
				e.preventDefault();

				var me = $(this),
					url = me.attr('href') ? me.attr('href') : (me.data('url') ? me.data('url') : '' );

				me.closest('.modal').modal('hide');
				if (url) {
					BaseForm.delete(url);
				}
			});

			$(document).on('click', '.base-form--submit-confirm', function (e) {
				e.preventDefault();

				var me = $(this),
					url = me.attr('href') ? me.attr('href') : (me.data('url') ? me.data('url') : '' );

				me.closest('.modal').modal('hide');
				if (url) {
					BaseForm.confirm(url);
				}
			});

			/**
			 * ============================= BUTTON BASE ACTIVATE ============================
			 */
			$(document).on('click', '.base-form--activate', function (e) {
				e.preventDefault();

				var me = $(this),
					status = $(this).data('status') == 1 ? 0 : 1,
					text = status == 1 ? 'Are you sure you want to activate this parameter?'
										: 'Are you sure you want to inactivate this parameter?';

				BaseModal.confirm(me, {
					method: 'POST',
					confirm_text: `<input type="hidden" name="status" value="`+status+`">`+text,
				});
			});

			/**
			 * ============================= ACTIVITY TOGGLE ============================
			 */
			$(document).on('click', '.activity-toggle', function (e) {
				e.preventDefault();

				var body = $('body');
				if (body.hasClass('activity-minimize')) {
					body.removeClass('activity-minimize');
					body.addClass('activity-show');
				}
				else {
					body.removeClass('activity-show');
					body.addClass('activity-minimize');
				}
			});

			$(document).on('focus', 'form input, form textarea', function () {
				var me = $(this),
					fg = me.closest('.parent-group').length ? me.closest('.parent-group') : me.closest('.form-group');
				if (fg.length) {
					fg.find('.is-invalid').removeClass('is-invalid');
					fg.find('.is-invalid-message').remove();
					fg.find('.is-invalid-alert').remove();
				}
			});

			$(document).on('focus', '.select2', function () {
				var me = $(this),
					fg = me.closest('.parent-group').length ? me.closest('.parent-group') : me.closest('.form-group');
				if (fg.length) {
					fg.find('.is-invalid').removeClass('is-invalid');
					fg.find('.is-invalid-message').remove();
					fg.find('.is-invalid-alert').remove();
				}
			});

			/**
			 * ============================= NAV TAB ============================
			 */
			$(document).on('click', '.tab-list', function () {

				var me = $(this);
				var colors = ['primary', 'info', 'success', 'warning', 'danger'];
				$.each(colors, function (i, color) {
					if (me.hasClass('nav-link-'+color)) {
						me.closest('.nav-tabs').addClass('nav-tabs-'+color);
					}
					else {
						me.closest('.nav-tabs').removeClass('nav-tabs-'+color);
					}
				})
			});

		},
		test: function () {
			console.log('Example call base function ...');
		}
	}
}();

// webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = BaseApp;
}

$(function () {
	BaseApp.init();
	BaseApp.documentEvent();
});
