/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/base/BaseApp.js":
/*!**************************************!*\
  !*** ./resources/js/base/BaseApp.js ***!
  \**************************************/
/***/ ((module) => {

var BaseApp = function () {
  return {
    init: function init() {
      BaseList.init();
      BasePlugin.init();
      BaseContent.init();
      BaseUtil.init();
      BaseForm.approval();
    },
    documentEvent: function documentEvent() {
      BaseList.documentEvent();
      $(document).on('change', '.custom-file input', function (e) {
        var fileName = '';
        var url_path = '';
        var size = $(this).attr('size');
        var limitSize = false;

        if (e.target.files.length) {
          for (var i = 0; i < this.files.length; i++) {
            if (size && this.files[i].size > size * 2000) {
              limitSize = true;
            }
            fileName += this.files[i + 1] ? this.files[i].name + ', ' : this.files[i].name;
          }
        }
        $.ajax({
          url: window.location.origin + "/ajax/getTempFiles",
          type: 'GET',
          success: function (data) {
            console.log(data);
            console.log(window.location.origin +'/storage/' +data.path);
            // $(newLocal).attr("href", window.location.origin +'/storage/' +data.path);
            $('.progress-container[data-uid]:last .alert-text a').attr("href", window.location.origin +'/storage/' +data.path);
          },
          error: function (data, textStatus, errorThrown) {
              console.log(data);
          },
        });
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

        }
      });
      $(document).on('click', '.base-content--replace', function (e) {
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
          callbackSuccess: function callbackSuccess(resp, form, options) {
            BaseUtil.redirect('/');
          }
        });
      });
      /**
       * ============================= MODAL SAVE ============================
       */

      $(document).on('click', '.base-form--submit-modal', function (e) {
        var _me$data;

        e.preventDefault();
        var me = $(this),
            form = me.closest('form'),
            swalConfirm = me.data('swal-confirm'),
            swalTitle = me.data('swal-title') ? me.data('swal-title') : 'Apakah Anda yakin data sudah sesuai?',
            swalText = me.data('swal-text');

        if (form.find('[name="is_submit"]').length == 0) {
          form.append('<input type="hidden" name="is_submit" value="1">');
        }

        form.find('[name="is_submit"]').val((_me$data = me.data('submit')) !== null && _me$data !== void 0 ? _me$data : 1);

        if (swalConfirm) {
          swal({
            title: swalTitle,
            text: swalText,
            icon: 'warning',
            buttons: ['Tidak', 'Ya']
          }).then(function (result) {
            if (result === true) {
              BaseForm.submit(form, {
                btnSubmit: me
              });
            }
          });
        } else {
          BaseForm.submit(form, {
            btnSubmit: me
          });
        }
      });
      $(document).on('click', '.base-form--submit-page', function (e) {
        var _me$data2;


        e.preventDefault();
        var me = $(this),
            form = me.closest('form'),
            swalConfirm = me.data('swal-confirm'),
            swalTitle = me.data('swal-title') ? me.data('swal-title') : 'Apakah Anda yakin data sudah sesuai?',
            swalText = me.data('swal-text');

        if (form.find('[name="is_submit"]').length == 0) {
          form.append('<input type="hidden" name="is_submit" value="1">');
        }

        form.find('[name="is_submit"]').val((_me$data2 = me.data('submit')) !== null && _me$data2 !== void 0 ? _me$data2 : 1);

        console.log(134, me.data(), 'swalConfirm:', swalConfirm, swalConfirm === true);

        if (swalConfirm === true) {
            console.log(137, 'save with confirm');
            swal({
            title: swalTitle,
            text: swalText,
            icon: 'warning',
            buttons: ['Tidak', 'Ya']
          }).then(function (result) {
            if (result === true) {
            console.log(145, 'save confirmed');
                var spinner = $('#loader');
                spinner.show();
                BaseForm.submit(form, {
                    btnSubmit: me,
                    btnBack: '.btn-back',
                    loaderModal: true
                });
            }
          });
        } else {
            console.log(153, 'save w/o confirm');
                BaseForm.submit(form, {
                    btnSubmit: me,
                    btnBack: '.btn-back',
                    loaderModal: false
                });
        }
      });
      /**
       * ============================= BUTTON BASE DELETE ============================
       */

      $(document).on('click', '.base-modal--delete', function (e) {
        e.preventDefault();
        BaseModal["delete"](this);
      });
      $(document).on('click', '.base-modal--confirm', function (e) {
        e.preventDefault();
        BaseModal.confirm(this);
      });
      $(document).on('click', '.base-form--delete', function (e) {
        e.preventDefault();
        var me = $(this),
            url = me.attr('href') ? me.attr('href') : me.data('url') ? me.data('url') : '';
        me.closest('.modal').modal('hide');

        if (url) {
          BaseForm["delete"](url);
        }
      });
      $(document).on('click', '.base-form--submit-confirm', function (e) {
        e.preventDefault();
        var me = $(this),
            url = me.attr('href') ? me.attr('href') : me.data('url') ? me.data('url') : '';
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
            text = status == 1 ? 'Are you sure you want to activate this parameter?' : 'Are you sure you want to inactivate this parameter?';
        BaseModal.confirm(me, {
          method: 'POST',
          confirm_text: "<input type=\"hidden\" name=\"status\" value=\"" + status + "\">" + text
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
        } else {
          body.removeClass('activity-show');
          body.addClass('activity-minimize');
        }
      });
      $(document).on('focus', 'form input, form textarea, form .note-editing-area, .custom-file-input', function () {
        var me = $(this),
            fg = me.closest('.parent-group').length ? me.closest('.parent-group') : me.closest('.form-group');

        if (fg.length) {
          fg.find('.is-invalid').removeClass('is-invalid');
          fg.find('.is-invalid-message').remove();
          fg.find('.is-invalid-alert').remove();
        }
      });
      $(document).on('focus', '.select2, .base-plugin--select2-ajax, .base-plugin--select2, .custom-file-input', function () {
        var me = $(this),
            fg = me.closest('.parent-group').length ? me.closest('.parent-group') : me.closest('.form-group');

        if (fg.length) {
          fg.find('.is-invalid').removeClass('is-invalid');
          fg.find('.is-invalid-message').remove();
          fg.find('.is-invalid-alert').remove();
        }
      });
      $(document).on('change', 'select', function () {
        var me = $(this),
        fg = me.closest('.parent-group').length ? me.closest('.parent-group') : me.closest('.form-group');

        if (fg.length) {
          fg.find('.is-invalid').removeClass('is-invalid');
          fg.find('.is-invalid-message').remove();
          fg.find('.is-invalid-alert').remove();
        }
      });
      $(document).on('change', '.custom-file input[type=\"file\"]:not(.base-form--save-temp-files)', function (e) {
        if (e.target.files.length) {
          $(this).next('.custom-file-label').html(e.target.files[0].name);
        }
      });
      /**
       * ============================= NAV TAB ============================
       */

      $(document).on('click', '.tab-list', function () {
        var me = $(this);
        var colors = ['primary', 'info', 'success', 'warning', 'danger'];
        $.each(colors, function (i, color) {
          if (me.hasClass('nav-link-' + color)) {
            me.closest('.nav-tabs').addClass('nav-tabs-' + color);
          } else {
            me.closest('.nav-tabs').removeClass('nav-tabs-' + color);
          }
        });
      });
    },
    test: function test() {
      console.log('Example call base function ...');
    }
  };
}(); // webpack support


if ( true && typeof module.exports !== 'undefined') {
  module.exports = BaseApp;
}

$(function () {
  BaseApp.init();
  BaseApp.documentEvent();
});

/***/ }),

/***/ "./resources/js/base/BaseContent.js":
/*!******************************************!*\
  !*** ./resources/js/base/BaseContent.js ***!
  \******************************************/
/***/ ((module) => {

/*
 * BASE CONTENT
 */
var BASE_CONTENT_HISTORIES = [];

var BaseContent = function () {
  return {
    init: function init() {
      this.pushHistory();
      this.handlePushStyles();
    },
    getHistory: function getHistory() {
      return BASE_CONTENT_HISTORIES;
    },
    pushHistory: function pushHistory() {
      if (BaseContent.hasHistory() === false) {
        BASE_CONTENT_HISTORIES.push(window.location.href);
      }
    },
    hasHistory: function hasHistory() {
      if ($.inArray(window.location.href, BASE_CONTENT_HISTORIES) === -1) {
        return false;
      }

      return true;
    },
    replace: function replace(element) {
      var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
      var el = $(element);
      var url = el.attr('href') ? el.attr('href') : el.data('url');
      BaseContent.replaceByUrl(url, options);
    },
    replaceByUrl: function replaceByUrl(url) {
      var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
      return function (options) {
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
              headers: {
                'Base-Replace-Content': true
              },
              beforeSend: function beforeSend() {
                BaseContent.clearBody();
              },
              success: function success(resp) {
                clearTimeout(contentLoading);
                BaseContent.handleContentPageState(resp, options);
                BaseContent.handlePushStyles();
                BaseContent.loader(false);
              },
              error: function error() {
                BaseUtil.redirect(options.url);
              }
            });
          } else {
            BaseUtil.redirect(options.url);
          }
        } else {
          BaseUtil.redirect(url);
        }
      }(options);
    },
    clearBody: function clearBody() {
      $('body > :not(.no-body-clear, #gritter-notice-wrapper, .swal-overlay)').remove();
    },
    loader: function loader() {
      var loading = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
      var fullbackdrop = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
      var body = $('body');

      if (!body.hasClass('page-loading')) {
        if (loading === true) {
          if (!body.hasClass('base-content-loading')) {
            body.addClass('base-content-loading');

            if (fullbackdrop === true) {
              body.addClass('full-backdrop');
            }
          }
        } else {
          body.removeClass('base-content-loading full-backdrop');
        }
      }
    },
    handlePushStyles: function handlePushStyles() {
      $('head [data-content-page-style="true"]').remove();
      $('#content style').attr('data-content-page-style', true).appendTo('head');
    },
    handlePushScripts: function handlePushScripts(resp) {
      if (BaseContent.hasHistory()) {
        return resp.replace('$(document).on', '$(".document-script-was-declarated").on');
      }

      return resp;
    },
    handleContentPageState: function handleContentPageState(resp, options) {
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

        $('body,html').animate({
          scrollTop: 0
        }, 500);
      } else {
        $('#content').css('opacity', 0);
        BaseUtil.redirect(options.url);
      }
    }
  };
}(); // webpack support


if ( true && typeof module.exports !== 'undefined') {
  module.exports = BaseContent;
}

/***/ }),

/***/ "./resources/js/base/BaseForm.js":
/*!***************************************!*\
  !*** ./resources/js/base/BaseForm.js ***!
  \***************************************/
/***/ ((module) => {

/*
 * BASE FORM
 */
var BaseForm = function () {
  return {
    submit: function submit(form) {
        console.log(449, 'BaseForm.submit');
      var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
      return function (options) {
        console.log(452, 'BaseForm.submit closure');
        var defaultOptions = {
          btnSubmit: null,
          btnBack: null,
          loaderModal: true,
          hideModal: true,
          drawDataTable: true,
          refreshSidebarBadge: true,
          scrollTop: false,
          swalSuccess: false,
          swalError: false,
          swalSuccessTimer: '3000',
          swalSuccessButton: {},
          swalErrorTimer: '',
          swalErrorButton: 'OK',
          redirectTo: false,
          fullCallbackSuccess: false,
          fullCallbackError: false,
          callbackSuccess: function callbackSuccess(resp, form, options) {},
          callbackError: function callbackError(resp, form, options) {}
        },
            options = $.extend(defaultOptions, options);
        var formLoading = setTimeout(function () {
          if (options.loaderModal && form.closest('.modal').length) {
            BaseModal.loader('#' + form.closest('.modal').attr('id'), true);
          } else {
            BaseContent.loader(true);
          }
        }, 700);

        if (options.btnSubmit !== null && options.btnSubmit.length) {
          options.btnSubmit.prop('disabled', true).addClass('btn-loader spinner spinner-white spinner-left');
        }

        form.ajaxSubmit({
          success: function success(resp) {
            clearTimeout(formLoading);

            if (options.fullCallbackSuccess === true) {
              if ($.isFunction(options.callbackSuccess)) {
                options.callbackSuccess(resp, form, options);
              }
            } else {
              BaseForm.validationMessages(resp, form);

              if (options.swalSuccess) {
                swal({
                  title: resp.title,
                  text: resp.message,
                  icon: 'success',
                  timer: options.swalSuccessTimer,
                  buttons: options.swalSuccessButton
                });
                BaseForm.defaultCallbackSuccess(resp, form, options);
              } else {
                $.gritter.add({
                  title: resp.title != undefined ? resp.title : 'Success!',
                  text: resp.message != undefined ? resp.message : 'Data saved successfull!',
                  image: baseurl + '/assets/images/icon/ui/check.png',
                  sticky: false,
                  time: '3000'
                });
                BaseForm.defaultCallbackSuccess(resp, form, options);
              }
            }
          },
          error: function error(resp) {
            clearTimeout(formLoading);

            if (options.fullCallbackError === true) {
              if ($.isFunction(options.callbackError)) {
                options.callbackError(resp, form, options);
              }
            } else {
              resp = resp.responseJSON;
              BaseForm.validationMessages(resp, form);
              BaseForm.defaultCallbackError(resp, form, options);

              if (resp.alert !== undefined) {
                form.find('.base-alert').remove();
                form.prepend("\n\t\t\t\t\t\t\t\t<div class=\"alert alert-" + resp.alert + " fade show base-alert\">\n\t\t\t\t\t\t\t\t\t<span class=\"close\" data-dismiss=\"alert\">\xD7</span>\n\t\t\t\t\t\t\t\t\t" + resp.message + "\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t").hide().fadeIn(700);
              }

              if (options.swalError) {
                swal({
                  title: resp.title != undefined ? resp.title : 'Failed!',
                  text: resp.message != undefined ? resp.message : 'Data failed to save!',
                  icon: 'error',
                  timer: options.swalErrorTimer,
                  buttons: options.swalErrorButton
                });
              } else {
                $.gritter.add({
                  title: resp.title != undefined ? resp.title : 'Failed!',
                  text: resp.message != undefined ? resp.message : 'Data failed to save!',
                  image: baseurl + '/assets/images/icon/ui/cross.png',
                  sticky: false,
                  time: '3000'
                });
              }
            }
          }
        });
      }(options);
    },
    validationMessages: function validationMessages(resp, form) {
      form.find('.is-invalid').removeClass('is-invalid');
      form.find('.is-invalid-message').remove();
      form.find('.is-invalid-alert').remove();

      if (resp.message == 'The given data was invalid.') {
        resp.message = 'Data yang Anda masukkan tidak valid.';
        $.each(resp.errors, function (name, messages) {
          var names = name.split('.'),
              name = names.reduce(function (all, item) {
            all += name == 0 ? item : '[' + item + ']';
            return all;
          }),

              field = $('[name^=\"' + names[0] + '[]\"], [name=\"' + name + '\"], [name=\"' + name + '[]\"], [data-name=\"' + name + '\"]'),
              parentGroup = field.closest('.parent-group').length ? field.closest('.parent-group') : field.closest('.form-group');
              console.log(name);
          field.addClass('is-invalid');
          field.closest('.bootstrap-select').addClass('is-invalid');
          field.closest('.base-plugin--select2-ajax').addClass('is-invalid');
          field.closest('textarea').addClass('is-invalid');
          field.closest('.uploaded').addClass('is-invalid');
          field.parent().find('.select2 .select2-selection').addClass('is-invalid');
          $.each(messages, function (i, message) {
            parentGroup.append('<p class="is-invalid-message text-danger my-1">' + message + '</p>');
            console.log(message);
          });
        });
        $('.is-invalid-message').hide().fadeIn(500);
      } else if (BaseUtil.isDev()) {
        if (resp.exception !== undefined && resp.file !== undefined && resp.line !== undefined && resp.message !== undefined) {
          BaseModal.render('body', {
            modal_id: '#alert-modal',
            modal_size: 'modal-lg',
            modal_bg: 'bg-light-danger',
            callback: function callback(options, modalLoadingTimer) {
              clearTimeout(modalLoadingTimer);
              $(options.modal_id + ' .modal-content').html("\n\t\t\t\t\t\t\t\t<div class=\"modal-header\">\n\t\t\t\t\t\t\t\t\t<h4 class=\"modal-title\">Error!</h4>\n\t\t\t\t\t\t\t\t\t<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">\xD7</button>\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t<div class=\"modal-body py-5\">\n\t\t\t\t\t\t\t\t\t<div class=\"alert alert-danger\">\n\t\t\t\t\t\t\t\t\t\tTerjadi kesalahan!\n\t\t\t\t\t\t\t\t\t\t<p mt-5><small>*Pesan ini hanya akan ditampilkan pada masa development</small></p>\n\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t<div class=\"table-responsive\">\n\t\t\t\t\t\t\t\t\t\t<table class=\"table\">\n\t\t\t\t\t\t\t\t\t\t\t<tbody>\n\t\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<td class=\"width-80\">File : </td>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<td>" + resp.file + "</td>\n\t\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<td class=\"width-80\">Line : </td>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<td>" + resp.line + "</td>\n\t\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<td class=\"width-80\">Message : </td>\n\t\t\t\t\t\t\t\t\t\t\t\t\t<td>" + resp.message + "</td>\n\t\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t\t</tbody>\n\t\t\t\t\t\t\t\t\t\t</table>\n\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t<div class=\"modal-footer\">\n\t\t\t\t\t\t\t\t\t<a href=\"javascript:;\" class=\"btn btn-white\" data-dismiss=\"modal\">Close</a>\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t");

              if (!$(options.modal_id).hasClass('show')) {
                $(options.modal_id).modal('show');
                BaseModal.loader(options.modal_id, false);
              }
            }
          });
        } else if ($.type(resp.errors) === "string") {
          var alerParent = form.find('.modal-body').length ? form.find('.modal-body').first() : form;
          alerParent.prepend("\n\t\t\t\t\t\t<div class=\"alert alert-danger fade show is-invalid-alert\">\n\t\t\t\t\t\t\t<span class=\"close alert-dismiss\" data-dismiss=\"alert\">\xD7</span>\n\t\t\t\t\t\t\t" + resp.errors + "\n\t\t\t\t\t\t\t<br/>\n\t\t\t\t\t\t\t<p><small>*Pesan ini hanya akan ditampilkan pada masa development</small></p>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t").hide().fadeIn(700);
        }
      }
    },
    defaultCallbackSuccess: function defaultCallbackSuccess(resp, form) {
      var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};

      if (resp.redirect) {
        BaseContent.replaceByUrl(resp.redirect);
        return false;
      }

      if (resp.redirectTo) {
        BaseUtil.redirect(resp.redirectTo);
        return false;
      }

      if (options.btnBack !== null && $(options.btnBack).length) {
        BaseContent.replace(options.btnBack);
        return false;
      }

      if (options.btnSubmit !== null && options.btnSubmit.length) {
        options.btnSubmit.prop('disabled', false).removeClass(['spinner', 'spinner-white', 'spinner-left']);
      }

      if (options.loaderModal && form.closest('.modal').length) {
        BaseModal.loader('#' + form.closest('.modal').attr('id'), false);
      } else {
        BaseContent.loader(false);
      }

      if (options.hideModal && form.closest('.modal').length) {
        form.closest('.modal').modal('hide');
      }

      if (options.drawDataTable) {
        BaseList.draw();
      }

      if (options.refreshSidebarBadge && resp.refreshSidebarNames) {
        BaseUtil.sidebarBadge({
          data: {
            _token: BaseUtil.getToken,
            names: resp.refreshSidebarNames
          }
        });
      }

      if (resp.refreshUsserNotification === true) {
        BaseUtil.userNotification();
      }

      if (options.redirectTo !== false) {
        BaseUtil.redirect(options.redirectTo);
      }

      if ($.isFunction(options.callbackSuccess)) {
        options.callbackSuccess(resp, form, options);
      }

      if (options.scrollTop) {
        $('body,html').animate({
          scrollTop: '5px'
        }, 500).animate({
          scrollTop: 0
        }, 800);
      }
    },
    defaultCallbackError: function defaultCallbackError(resp, form) {
      var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};

      if (options.btnSubmit !== null && options.btnSubmit.length) {
        options.btnSubmit.prop('disabled', false).removeClass(['spinner', 'spinner-white', 'spinner-left']);
      }

      if (options.loaderModal && form.closest('.modal').length) {
        BaseModal.loader('#' + form.closest('.modal').attr('id'), false);
      } else {
        BaseContent.loader(false);
      }

      if ($.isFunction(options.callbackError)) {
        options.callbackError(resp, form);
      }

      var firstError = form.find('.is-invalid').first();

      if (firstError.length && form.closest('.modal').length == 0) {
        $('body,html').animate({
          scrollTop: firstError.position().top
        }, 500);
      }
    },
    "delete": function _delete(url) {
      var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
      return function (options) {
        var defaultOptions = {
          swalSuccess: false,
          swalError: false
        },
            options = $.extend(defaultOptions, options);
        var contentLoading = setTimeout(function () {
          BaseContent.loader(true);
        }, 700);
        $.ajax({
          url: url,
          method: 'POST',
          data: {
            _token: BaseUtil.getToken(),
            _method: 'DELETE'
          },
          success: function success(resp) {
            clearTimeout(contentLoading);
            BaseContent.loader(false);

            if (options.swalSuccess) {
              swal({
                title: resp.title != undefined ? resp.title : 'Success!',
                text: resp.message != undefined ? resp.message : 'Data deleted successfully!',
                icon: 'success',
                timer: 3000,
                buttons: {}
              });
            } else {
              $.gritter.add({
                title: resp.title != undefined ? resp.title : 'Success!',
                text: resp.message != undefined ? resp.message : 'Data deleted successfully!',
                image: baseurl + '/assets/images/icon/ui/check.png',
                sticky: false,
                time: '3000'
              });

              if (resp.redirect) {
                location.href = resp.redirect;
              }
            }

            BaseList.draw();
            BaseUtil.sidebarBadge();
          },
          error: function error(resp) {
            clearTimeout(contentLoading);
            BaseContent.loader(false);

            if (options.swalError) {
              swal({
                title: resp.title != undefined ? resp.title : 'Failed!',
                text: resp.message != undefined ? resp.message : 'Data failed to delete!',
                icon: 'error' // timer: 3000,

              });
            } else {
              $.gritter.add({
                title: resp.title != undefined ? resp.title : 'Failed!',
                text: resp.message != undefined ? resp.message : 'Data failed to delete!',
                image: baseurl + '/assets/images/icon/ui/cross.png',
                sticky: false,
                time: '3000'
              });
            }
          }
        });
      }(options);
    },
    confirm: function confirm(url) {
      var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
      return function (options) {
        var defaultOptions = {
          swalSuccess: false,
          swalError: false
        },
            options = $.extend(defaultOptions, options);
        var contentLoading = setTimeout(function () {
          BaseContent.loader(true);
        }, 700);
        $.ajax({
          url: url,
          method: 'POST',
          data: {
            _token: BaseUtil.getToken(),
            _method: options.method
          },
          success: function success(resp) {
            clearTimeout(contentLoading);
            BaseContent.loader(false);

            if (options.swalSuccess) {
              swal({
                title: resp.title != undefined ? resp.title : 'Success!',
                text: resp.message != undefined ? resp.message : 'Data confirmation successfully!',
                icon: 'success',
                timer: 3000,
                buttons: {}
              });
            } else {
              $.gritter.add({
                title: resp.title != undefined ? resp.title : 'Success!',
                text: resp.message != undefined ? resp.message : 'Data confirmation successfully!',
                image: baseurl + '/assets/images/icon/ui/check.png',
                sticky: false,
                time: '3000'
              });

              if (resp.redirect) {
                location.href = resp.redirect;
              }
            }

            BaseList.draw();
            BaseUtil.sidebarBadge();
          },
          error: function error(resp) {
            clearTimeout(contentLoading);
            BaseContent.loader(false);

            if (options.swalError) {
              swal({
                title: resp.responseJSON.title != undefined ? resp.responseJSON.title : 'Failed!',
                text: resp.responseJSON.message != undefined ? resp.responseJSON.message : 'Data failed to confirm!',
                icon: 'error' // timer: 3000,

              });
            } else {
              $.gritter.add({
                title: resp.responseJSON.title != undefined ? resp.responseJSON.title : 'Failed!',
                text: resp.responseJSON.message != undefined ? resp.responseJSON.message : 'Data failed to confirm!',
                image: baseurl + '/assets/images/icon/ui/cross.png',
                sticky: false,
                time: '3000'
              });
            }
          }
        });
      }(options);
    },
    activate: function activate(url, status) {
      var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
      return function (options) {
        var defaultOptions = {
          swalSuccess: false,
          swalError: false
        },
            options = $.extend(defaultOptions, options);
        var contentLoading = setTimeout(function () {
          BaseContent.loader(true);
        }, 700);
        $.ajax({
          url: url,
          method: 'POST',
          data: {
            _token: BaseUtil.getToken(),
            _method: 'POST',
            status: status == 1 ? 0 : 1
          },
          success: function success(resp) {
            clearTimeout(contentLoading);
            BaseContent.loader(false);

            if (options.swalSuccess) {
              swal({
                title: resp.title != undefined ? resp.title : 'Success!',
                text: resp.message != undefined ? resp.message : 'Data has been activated successfully!',
                icon: 'success',
                timer: 3000,
                buttons: {}
              });
            } else {
              $.gritter.add({
                title: resp.title != undefined ? resp.title : 'Success!',
                text: resp.message != undefined ? resp.message : 'Data has been activated successfully!',
                image: baseurl + '/assets/images/icon/ui/check.png',
                sticky: false,
                time: '3000'
              });
            }

            BaseList.draw();
            BaseUtil.sidebarBadge();
          },
          error: function error(resp) {
            clearTimeout(contentLoading);
            BaseContent.loader(false);

            if (options.swalError) {
              swal({
                title: resp.title != undefined ? resp.title : 'Failed!',
                text: resp.message != undefined ? resp.message : 'Data failed to activation!',
                icon: 'error' // timer: 3000,

              });
            } else {
              $.gritter.add({
                title: resp.title != undefined ? resp.title : 'Failed!',
                text: resp.message != undefined ? resp.message : 'Data failed to activation!',
                image: baseurl + '/assets/images/icon/ui/cross.png',
                sticky: false,
                time: '3000'
              });
            }
          }
        });
      }(options);
    },
    approval: function approval() {
      $(document).on('click', '.sign', function (e) {
        var id = $(this).data('idx');
        var url = $(this).data('url');
        var redirect = $(this).data('redirect');
        var jabatan = $(this).data('jabatan');
        var name = $(this).data('name');
        var note = typeof $(this).data('note') != "undefined" ? $(this).data('note') : false;
        swal({
          title: 'Apakah Anda yakin ?',
          text: 'akan menandatangani ' + name + ' tersebut..!',
          icon: 'warning',
          buttons: {
            cancel: 'Batal',
            confirm: {
              text: 'Setujui',
              className: 'btn-primary'
            },
            revisi: {
              text: 'Revisi',
              className: 'btn-danger'
            }
          }
        }).then(function (result) {
          if (result === true) {
            BaseContent.loader(true);
            $.ajax({
              type: "POST",
              url: url,
              data: {
                '_token': BaseUtil.getToken(),
                'id': id,
                'jabatan': jabatan,
                'status': 1
              },
              success: function success(response) {
                BaseContent.loader(false);
                swal({
                  title: response.title,
                  text: name + ' berhasil di Approve',
                  icon: 'success',
                  timer: '2000',
                  buttons: false
                }).then(function () {
                  // location.reload();
                  location.href = redirect;
                });
              }
            });
          } else if (result === 'revisi') {
            BaseContent.loader(true);

            if (note == true) {
              Swal.mixin({
                showCancelButton: true,
                cancel: 'Batal'
              }).queue([{
                title: 'Catatan Revisi',
                input: 'textarea',
                confirmButtonText: 'Submit'
              }]).then(function (result) {
                if (result.value) {
                  BaseContent.loader(true);
                  $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                      '_token': BaseUtil.getToken(),
                      'id': id,
                      'jabatan': jabatan,
                      'status': 2,
                      'note': result.value[0]
                    },
                    success: function success(response) {
                      BaseContent.loader(false);
                      swal({
                        title: response.title,
                        text: name + ' berhasil disimpan untuk revisi',
                        icon: 'info',
                        timer: '2000',
                        buttons: false
                      }).then(function () {
                        location.href = redirect;
                      });
                    }
                  });
                } else {
                  BaseContent.loader(false);
                }
              });
            } else {
              $.ajax({
                type: "POST",
                url: url,
                data: {
                  '_token': BaseUtil.getToken(),
                  'id': id,
                  'jabatan': jabatan,
                  'status': 2
                },
                success: function success(response) {
                  BaseContent.loader(false);
                  swal({
                    title: response.title,
                    text: name + ' berhasil disimpan untuk revisi',
                    icon: 'info',
                    timer: '2000',
                    buttons: false
                  }).then(function () {
                    location.href = redirect;
                  });
                }
              });
            }
          }
        });
      });
      $(document).on('click', '.sign-pin', function (e) {
        var url = $(this).data('url');
        var redirect = $(this).data('redirect');
        var id = $(this).data('idx');
        var jabatan = $(this).data('jabatan');
        var checker = $(this).data('checker');
        var name = $(this).data('name');
        var note = typeof $(this).data('note') != "undefined" ? $(this).data('note') : false;
        swal({
          title: 'Apakah Anda yakin ?',
          text: 'akan menandatangani ' + name + ' tersebut..!',
          icon: 'warning',
          buttons: {
            cancel: 'Batal',
            confirm: {
              text: 'Setujui',
              className: 'btn-primary'
            },
            revisi: {
              text: 'Revisi',
              className: 'btn-danger'
            }
          }
        }).then(function (result) {
          if (result === true) {
            Swal.fire({
              title: 'Masukkan PIN Anda',
              input: 'password',
              inputAttributes: {
                autocapitalize: 'off'
              },
              showCancelButton: true,
              confirmButtonText: 'Submit',
              showLoaderOnConfirm: true,
              preConfirm: function preConfirm(pin) {
                return fetch(checker, {
                  method: 'PUT',
                  headers: {
                    'X-CSRF-TOKEN': BaseUtil.getToken(),
                    'Content-Type': 'application/x-www-form-urlencoded'
                  },
                  body: "pin=" + pin
                }).then(function (response) {
                  if (!response.ok) {
                    return response.text().then(function (err) {
                      return Promise.reject(err);
                    });
                  } else {
                    return response.json();
                  }
                })["catch"](function (error) {
                  Swal.showValidationMessage(error);
                });
              },
              allowOutsideClick: function allowOutsideClick() {
                return !Swal.isLoading();
              }
            }).then(function (result) {
              if (result.value) {
                BaseContent.loader(true);
                $.ajax({
                  type: "POST",
                  url: url,
                  data: {
                    '_token': BaseUtil.getToken(),
                    'id': id,
                    'jabatan': jabatan,
                    'status': 1
                  },
                  success: function success(response) {
                    BaseContent.loader(false);
                    swal({
                      title: response.title,
                      text: name + ' berhasil di Approve',
                      icon: 'success',
                      timer: '2000',
                      buttons: false
                    }).then(function () {
                      // location.reload();
                      location.href = redirect;
                    });
                  },
                  error: function error(_error) {
                    Swal.fire({
                      icon: 'danger',
                      title: 'Tanda Tangan Gagal',
                      showConfirmButton: false,
                      timer: '2000'
                    }).then(function (result) {});
                  }
                });
              }
            });
          } else if (result === 'revisi') {
            if (note == true) {
              Swal.mixin({
                showCancelButton: true,
                cancel: 'Batal',
                progressSteps: ['1', '2']
              }).queue([{
                title: 'Catatan Revisi',
                input: 'textarea',
                confirmButtonText: 'Selanjutnya'
              }, {
                title: 'Masukan PIN Anda',
                input: 'password',
                inputAttributes: {
                  autocapitalize: 'off'
                },
                confirmButtonText: 'Submit',
                showLoaderOnConfirm: true,
                preConfirm: function preConfirm(pin) {
                  return fetch(checker, {
                    method: 'PUT',
                    headers: {
                      'X-CSRF-TOKEN': BaseUtil.getToken(),
                      'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: "pin=" + pin
                  }).then(function (response) {
                    if (!response.ok) {
                      return response.text().then(function (err) {
                        return Promise.reject(err);
                      });
                    } else {
                      return response.json();
                    }
                  })["catch"](function (error) {
                    Swal.showValidationMessage(error);
                  });
                },
                allowOutsideClick: function allowOutsideClick() {
                  return !Swal.isLoading();
                }
              }]).then(function (result) {
                if (result.value) {
                  BaseContent.loader(true);
                  $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                      '_token': BaseUtil.getToken(),
                      'id': id,
                      'jabatan': jabatan,
                      'status': 2,
                      'note': result.value[0]
                    },
                    success: function success(response) {
                      BaseContent.loader(false);
                      swal({
                        title: response.title,
                        text: name + ' berhasil disimpan untuk revisi',
                        icon: 'info',
                        timer: '2000',
                        buttons: false
                      }).then(function () {
                        location.href = redirect;
                      });
                    }
                  });
                }
              });
            } else {
              Swal.fire({
                title: 'Masukkan PIN Anda',
                input: 'password',
                inputAttributes: {
                  autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Submit',
                showLoaderOnConfirm: true,
                preConfirm: function preConfirm(pin) {
                  return fetch(checker, {
                    method: 'PUT',
                    headers: {
                      'X-CSRF-TOKEN': BaseUtil.getToken(),
                      'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: "pin=" + pin
                  }).then(function (response) {
                    if (!response.ok) {
                      return response.text().then(function (err) {
                        return Promise.reject(err);
                      });
                    } else {
                      return response.json();
                    }
                  })["catch"](function (error) {
                    Swal.showValidationMessage(error);
                  });
                },
                allowOutsideClick: function allowOutsideClick() {
                  return !Swal.isLoading();
                }
              }).then(function (result) {
                if (result.value) {
                  BaseContent.loader(true);
                  $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                      '_token': BaseUtil.getToken(),
                      'id': id,
                      'jabatan': jabatan,
                      'status': 2
                    },
                    success: function success(response) {
                      BaseContent.loader(false);
                      swal({
                        title: response.title,
                        text: name + ' berhasil disimpan untuk revisi',
                        icon: 'info',
                        timer: '2000',
                        buttons: false
                      }).then(function () {
                        location.href = redirect;
                      });
                    }
                  });
                }
              });
            }
          }
        });
      });
    }
  };
}(); // webpack support


if ( true && typeof module.exports !== 'undefined') {
  module.exports = BaseForm;
}

/***/ }),

/***/ "./resources/js/base/BaseList.js":
/*!***************************************!*\
  !*** ./resources/js/base/BaseList.js ***!
  \***************************************/
/***/ ((module) => {

/**
 * ============================= BASE LIST ============================
 * return render(options), loader(modal_id, loading=true)
 */
var BaseList = function () {
  "use strict";

  return {
    init: function init() {
      var tables = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : ['#datatable_1', '#datatable_2', '#datatable_3', '#datatable_4', '#datatable_5', '#datatable_6'];
      var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
      this.draw(tables, options);
    },
    draw: function draw() {
      var tables = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : ['#datatable_1', '#datatable_2', '#datatable_3', '#datatable_4', '#datatable_5', '#datatable_6'];
      var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
      $.each(tables, function (i, table_id) {
        if ($(table_id).length) {
          BaseList.render(table_id, options);
        }
      });
    },
    lang: function lang() {
      if ($('html').attr('lang') == 'id') {
        return {
          "sProcessing": "<div class=\"spinners\"><div class=\"spinner-grow text-success\" role=\"status\">\n                            <span class=\"sr-only\">Loading...</span>\n                         </div>\n                         <div class=\"spinner-grow text-danger\" role=\"status\">\n                             <span class=\"sr-only\">Loading...</span>\n                         </div>\n                         <div class=\"spinner-grow text-warning\" role=\"status\">\n                             <span class=\"sr-only\">Loading...</span>\n                         </div></div>",
          "sLengthMenu": "Menampilkan _MENU_ data per halaman",
          "sZeroRecords": "Tidak ditemukan data yang sesuai",
          "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
          "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
          "sInfoFiltered": "(difilter dari _MAX_ keseluruhan data)",
          "sInfoPostFix": "",
          "sSearch": "Cari:",
          "sUrl": "",
          "oPaginate": {
            "sFirst": "Pertama",
            "sPrevious": "Sebelumnya",
            "sNext": "Selanjutnya",
            "sLast": "Terakhir"
          }
        };
      } else {
        return {
          "sProcessing": "<div class=\"spinners\"><div class=\"spinner-grow text-success\" role=\"status\">\n                            <span class=\"sr-only\">Loading...</span>\n                         </div>\n                         <div class=\"spinner-grow text-danger\" role=\"status\">\n                             <span class=\"sr-only\">Loading...</span>\n                         </div>\n                         <div class=\"spinner-grow text-warning\" role=\"status\">\n                             <span class=\"sr-only\">Loading...</span>\n                         </div></div>",
          "sLengthMenu": "Display _MENU_ data per page",
          "sZeroRecords": "Nothing found",
          "sInfo": "Showing _START_ to _END_ of _TOTAL_ data",
          "sInfoEmpty": "No data available",
          "sInfoFiltered": "(filtered from _MAX_ total data)",
          "sInfoPostFix": "",
          "sSearch": "Search:",
          "sUrl": "",
          "oPaginate": {
            "sFirst": "First",
            "sPrevious": "Previous",
            "sNext": "Next",
            "sLast": "End"
          }
        };
      }
    },
    render: function render(table_id) {
      var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
      return function (options) {
        var el = $(table_id);
        var defaultOptions = {
          columns: [],
          url: el.data('url'),
          method: 'POST',
          token: BaseUtil.getToken(),
          callback: false
        },
            options = $.extend(defaultOptions, options);

        if (el.length) {
          if (!$.fn.DataTable.isDataTable(table_id)) {
            $.each(el.find('thead th'), function (i) {
              var th = $(this);
              options.columns[i] = {
                sName: th.data('columns-name'),
                mData: th.data('columns-data'),
                label: th.data('columns-label'),
                bSortable: th.data('columns-sortable'),
                sWidth: th.data('columns-width'),
                sClass: th.data('columns-class-name')
              };
            });
            el.DataTable({
              lengthChange: false,
              filter: false,
              processing: true,
              serverSide: true,
              autoWidth: false,
              sorting: [],
              language: BaseList.lang(),
              paging: el.data('paging') == false ? false : true,
              info: el.data('info') == false ? false : true,
              ajax: {
                url: options.url,
                method: options.method,
                beforeSend: function beforeSend() {
                  setTimeout(function () {
                    el.removeClass('hide');
                  }, 500);
                },
                data: function data(request) {
                  request._token = options.token;
                  /* Panel Filter */

                  var outputFilter = $('#output-filter');

                  if (el.closest('.list-container').length) {
                    el.closest('.list-container').find('input.filter-control, select.filter-control').each(function () {
                      var name = $(this).data('post'),
                          val = $(this).attr('type') == 'checkbox' ? $(this).is(':checked') ? true : null : $(this).val(),
                          label = $(this).attr('type') == 'checkbox' ? $(this).is(':checked') ? $(this).parent().text() : null : $(this).is('select') ? $('option:selected', this).text() : $(this).val();
                      request[name] = val;
                      /* Panel Filter */

                      if (outputFilter.length) {
                        if (outputFilter.find('.badge').length < countFilter) {
                          outputFilter.append("<span class=\"badge badge-success mr-2 " + name + "\">" + val + "</span>");
                        }

                        $('.badge.' + name).text(label);
                      }
                    });
                  } else {
                    var countFilter = 0;
                    $('#dataFilters input.filter-control, #dataFilters select.filter-control, #dataFilters input.filter-control[class*=date]').each(function (e) {
                      var name = $(this).data('post'),
                          val = $(this).attr('type') == 'checkbox' ? $(this).is(':checked') ? true : null : $(this).val(),
                          label = $(this).attr('type') == 'checkbox' ? $(this).is(':checked') ? $(this).parent().text() : null : $(this).is('select') ? $('option:selected', this).text() : $(this).val();
                      request[name] = val;
                      countFilter = ++e;
                      /* Panel Filter */

                      if (outputFilter.length) {
                        if (outputFilter.find('.badge').length < countFilter) {
                          outputFilter.append("<span class=\"badge badge-success mr-2 " + name + "\">" + val + "</span>");
                        }

                        $('.badge.' + name).text(label);
                      }
                    });
                  }

                  if (options.extraData !== undefined) {
                    request['extraData'] = options.extraData;
                  }
                },
                error: function error(responseError, status) {
                  if (el.hasClass('first-render-has-error')) {
                    console.log(responseError);
                    return false;
                  } else {
                    el.addClass('first-render-has-error');
                    el.DataTable().draw();
                  }
                }
              },
              columns: options.columns,
              drawCallback: function drawCallback(resp) {
                el.removeClass('hide');
                this.api().column(0, {
                  search: 'applied',
                  order: 'applied'
                }).nodes().each(function (cell, i, x, y) {
                  if (parseInt(cell.innerHTML) + i + 1) {
                    cell.innerHTML = parseInt(cell.innerHTML) + i + 1;
                  }
                });

                if (el.data('badge') == true && el.data('tab-list') != undefined) {
                  var tab_list = $('.tab-list[href="' + el.data('tab-list') + '"]').first(),
                      total_records = resp.json.recordsTotal;

                  if (total_records && tab_list.length) {
                    tab_list.find('.tab-badge').remove();
                    tab_list.append("\n\t                    \t\t    \t<span class=\"label label-success tab-badge ml-2  label-inline\">" + total_records + "</span>\n\t                    \t\t    ");
                  }
                }

                BasePlugin.initTooltipPopover();
                el.find('td .btn').closest('td').addClass('with-button');

                if ($.isFunction(options.callback)) {
                  options.callback(options, resp);
                }
              }
            });

            if (BaseUtil.isDev() === false) {
              $.fn.dataTable.ext.errMode = 'none';
              el.on('error.dt', function (e, settings, techNote, message) {
                console.log('An error has been reported by DataTables: ', message);
              }).DataTable();
            }
          } else {
            el.DataTable().draw();
          }
        }
      }(options);
    },
    handleDataFilters: function handleDataFilters(parent) {
      var parent = $(parent);

      if (parent.length) {
        var isFiltered = false;
        $.each(parent.find('.filter-control'), function () {
          if ($(this).val()) {
            isFiltered = true;
            return false;
          }
        });

        if (isFiltered) {
          parent.find('.label-filter').addClass('hide');
          parent.find('.reset-filter').removeClass('hide');
        } else {
          parent.find('.reset-filter').addClass('hide');
          parent.find('.label-filter').removeClass('hide');
        }
      }
    },
    documentEvent: function documentEvent() {
      var filterTimer;
      $(document).on('keyup', '#dataFilters input.filter-control', function (e) {
        clearTimeout(filterTimer);
        filterTimer = setTimeout(function () {
          BaseList.draw();
        }, 500);

        if ($(this).closest('.list-container').length) {
          BaseList.handleDataFilters($(this).closest('.list-container'));
        } else {
          BaseList.handleDataFilters($(this).closest('#dataFilters'));
        }

        e.preventDefault();
      });
      $(document).on('change', '#dataFilters select.filter-control, #dataFilters input.filter-control[type="checkbox"], #dataFilters input.filter-control[class*=date]', function (e) {
        BaseList.draw();

        if ($(this).closest('.list-container').length) {
          BaseList.handleDataFilters($(this).closest('.list-container'));
        } else {
          BaseList.handleDataFilters($(this).closest('#dataFilters'));
        }

        e.preventDefault();
      });
      $(document).on('click', '#dataFilters .filter.button', function (e) {
        e.preventDefault();
        BaseList.draw();
      });
      $(document).on('click', '#dataFilters .reset.button', function (e) {
        $('#dataFilters .filter-control').val('');
        $('#dataFilters select.filter-control').select2('destroy');
        $('#dataFilters select.filter-control').each(function (index, item) {
          var $item = $(item);
          $item.select2({
            placeholder: $item.attr('title')
          });
        });
        $('#dataFilters .reset-filter').addClass('hide');
        $('#dataFilters .label-filter').removeClass('hide');
        BasePlugin.initSelectpicker('refresh');
        BasePlugin.initDatepicker();
        BaseList.draw();
      });
    }
  };
}(); // webpack support


if ( true && typeof module.exports !== 'undefined') {
  module.exports = BaseList;
}

/***/ }),

/***/ "./resources/js/base/BaseModal.js":
/*!****************************************!*\
  !*** ./resources/js/base/BaseModal.js ***!
  \****************************************/
/***/ ((module) => {

/**
 * ============================= BASE MODAL ============================
 * return render(options), loader(modal_id, loading=true)
 */
var BaseModal = function () {
  return {
    render: function render(element) {
      var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
      return function (options) {
        var _el$data, _el$data2;

        var el = $(element);
        var defaultOptions = {
          modal_id: (_el$data = el.data('modal-id')) !== null && _el$data !== void 0 ? _el$data : '#modal',
          modal_position: (_el$data2 = el.data('modal-position')) !== null && _el$data2 !== void 0 ? _el$data2 : 'modal-dialog-centered modal-dialog-right-bottom',
          modal_size: el.data('modal-size') ? el.data('modal-size') : 'modal-md',
          modal_bg: el.data('modal-bg') ? el.data('modal-bg') : '',
          modal_timer: el.data('modal-timer') ? el.data('modal-timer') : 500,
          modal_parent: el.data('modal-parent') ? el.data('modal-parent') : 'body',
          modal_backdrop: el.data('modal-backdrop') ? el.data('modal-backdrop') : 'static',
          modal_keyboard: el.data('modal-keyboard') ? el.data('modal-keyboard') : false,
          modal_ajax: el.data('modal-ajax') == false ? false : true,
          modal_url: el.attr('href') ? el.attr('href') : el.data('modal-url') ? el.data('modal-url') : '',
          callback: false
        },
            options = $.extend(defaultOptions, options);
        $(options.modal_id).remove();

        if ($(options.modal_parent).length == 0) {
          options.modal_parent = 'body';
        }

        $(options.modal_parent).append("\n\t\t\t\t<div class=\"modal fade modal-loading\"\n\t\t\t\t\tid=\"" + options.modal_id.replace('#', '') + "\"\n\t\t\t\t\tdata-keyboard=\"" + options.modal_keyboard + "\"\n\t\t\t\t\tdata-backdrop=\"" + options.modal_backdrop + "\" >\n\t\t\t\t\t<div class=\"modal-dialog content-page " + options.modal_size + " " + options.modal_position + "\" data-module-name=\"" + ModuleApp.getName() + "\">\n\t\t\t\t\t\t<div class=\"modal-content " + options.modal_bg + "\"></div>\n\t\t\t\t\t</div>\n\t\t\t\t</div>\n\t\t\t");
        var modalLoadingTimer = setTimeout(function () {
          $(options.modal_id).modal('show');
          BaseModal.loader(options.modal_id, true);
        }, options.modal_timer);

        if (options.modal_ajax !== false && options.modal_url !== '') {
          BaseModal.handleByAjax(options, modalLoadingTimer);
        } else {
          if ($.isFunction(options.callback)) {
            options.callback(options, modalLoadingTimer);
          }
        }
      }(options);
    },
    loader: function loader() {
      var modal_id = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '#modal';
      var loading = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;

      if ($(modal_id).length) {
        if ($(modal_id + ' .modal-loader').length == 0) {
          $(modal_id + ' .modal-content').append("\n\t\t\t\t\t\t<div class=\"modal-loader pt-6\">\n\t\t\t\t\t\t\t<span class=\"spinner spinner-primary\"></span>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t");
        }

        if (loading) {
          $(modal_id + ' .modal-loader').show();
        } else {
          $(modal_id + ' .modal-loader').hide();
        }
      }
    },
    handleByAjax: function handleByAjax(options, modalLoadingTimer) {
      $.ajax({
        url: options.modal_url,
        dataType: 'html',
        success: function success(resp) {
          clearTimeout(modalLoadingTimer);

          if (resp) {
            $(options.modal_id + ' .modal-content').html(resp);
          }

          if (!$(options.modal_id).hasClass('show')) {
            $(options.modal_id).modal('show');
            BaseModal.loader(options.modal_id, false);
          }

          BasePlugin.init(); // /* Table */

          var table = $(options.modal_id).find('table.is-datatable');

          if (table.length) {
            BaseList.render(table, {});
          } // /* Eof Table */


          if ($.isFunction(options.callback)) {
            options.callback(options);
          }
        },
        error: function error(resp) {
          clearTimeout(modalLoadingTimer);
          $(options.modal_id + ' .modal-content').html("\n\t\t\t\t\t\t<div class=\"modal-header border-0\">\n\t\t\t\t\t\t\t<h4 class=\"modal-title\">Error!</h4>\n\t\t\t\t\t\t\t<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><i aria-hidden=\"true\" class=\"ki ki-close\"></i></button>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t\t<div class=\"modal-body py-5\">\n\t\t\t\t\t\t\t<div class=\"alert alert-danger\">\n\t\t\t\t\t\t\t\tTerjadi kesalahan!\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t");

          if (!$(options.modal_id).hasClass('show')) {
            $(options.modal_id).modal('show');
            BaseModal.loader(options.modal_id, false);
          }
        }
      });
    },
    confirm: function confirm(element) {
      var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
      return function (options) {
        var el = $(element);
        var defaultOptions = {
          confirm_text: el.data('confirm-text') ? el.data('confirm-text') : 'Are you sure?',
          url: el.attr('href') ? el.attr('href') : el.data('modal-url') ? el.data('modal-url') : '',
          method: el.attr('method') ? el.attr('method') : el.data('method') ? el.data('method') : 'POST',
          submit_class: el.data('submit-class') ? el.data('submit-class') : 'base-form--submit-confirm',
          modal_ajax: false,
          modal_id: '#modal_confirm',
          modal_size: 'modal-confirm',
          modal_position: 'modal-dialog-centered modal-dialog-right-top',
          callback: function callback(options, modalLoadingTimer) {
            console.log(options.method);
            clearTimeout(modalLoadingTimer);
            $('#gritter-notice-wrapper').hide();
            $(options.modal_id + ' .modal-content').html("\n\t\t\t\t\t\t\t<div class=\"modal-body py-5 pl-5\">\n\t\t\t\t\t\t\t\t<form action=\"" + options.url + "\" method=\"" + options.method + "\">\n\t\t\t\t\t\t\t\t\t<input type=\"hidden\" name=\"_token\" value=\"" + BaseUtil.getToken() + "\">\n\t\t\t\t\t\t\t\t\t<input type=\"hidden\" name=\"_method\" value=\"" + options.method + "\">\n\t\t\t\t\t\t\t\t\t<div class=\"d-flex justify-content-between align-items-center\">\n\t\t\t\t\t\t\t\t\t\t<div class=\"mr-3\">\n\t\t\t\t\t\t\t\t\t\t\t" + options.confirm_text + "\n\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t<div class=\"d-flex\">\n\t\t\t\t\t\t\t\t\t\t\t<button type=\"submit\" class=\"btn btn-icon d-inline-block btn-pill mr-2 btn-sm btn-success " + options.submit_class + "\" data-url=\"" + options.url + "\"><i class=\"fa fa-check p-0\"></i></button>\n\t\t\t\t\t\t\t\t\t\t\t<button type=\"button\" class=\"btn btn-icon d-inline-block btn-pill btn-sm btn-danger\" data-dismiss=\"modal\" aria-hidden=\"true\"><i class=\"ki ki-close p-0\"></i></button>\n\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t</form>\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t");

            if (!$(options.modal_id).hasClass('show')) {
              $(options.modal_id).modal('show');
              BaseModal.loader(options.modal_id, false);
            }
          }
        },
            options = $.extend(defaultOptions, options);
        BaseModal.render(element, options);
      }(options);
    },
    "delete": function _delete(element) {
      var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
      return function (options) {
        var el = $(element);
        var defaultOptions = {
          confirm_text: el.data('confirm-text') ? el.data('confirm-text') : 'Are you sure?',
          url: el.attr('href') ? el.attr('href') : el.data('modal-url') ? el.data('modal-url') : '',
          method: el.attr('method') ? el.attr('method') : el.data('method') ? el.data('method') : 'DELETE',
          submit_class: el.data('submit-class') ? el.data('submit-class') : 'base-form--delete',
          modal_ajax: false,
          modal_id: '#modal_confirm',
          modal_size: 'modal-confirm',
          modal_position: 'modal-dialog-centered modal-dialog-right-top',
          callback: function callback(options, modalLoadingTimer) {
            clearTimeout(modalLoadingTimer);
            $('#gritter-notice-wrapper').hide();
            $(options.modal_id + ' .modal-content').html("\n\t\t\t\t\t\t\t<div class=\"modal-body py-5 pl-5\">\n\t\t\t\t\t\t\t\t<form action=\"" + options.url + "\" method=\"POST\">\n\t\t\t\t\t\t\t\t\t<input type=\"hidden\" name=\"_token\" value=\"" + BaseUtil.getToken() + "\">\n\t\t\t\t\t\t\t\t\t<input type=\"hidden\" name=\"_method\" value=\"" + options.method + "\">\n\t\t\t\t\t\t\t\t\t<div class=\"d-flex justify-content-between align-items-center\">\n\t\t\t\t\t\t\t\t\t\t<div>\n\t\t\t\t\t\t\t\t\t\t\t" + options.confirm_text + "\n\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t\t<div class=\"d-flex\">\n\t\t\t\t\t\t\t\t\t\t\t<button type=\"submit\" class=\"btn btn-icon d-inline-block btn-pill mr-2 btn-sm btn-success " + options.submit_class + "\" data-url=\"" + options.url + "\"><i class=\"fa fa-check p-0\"></i></button>\n\t\t\t\t\t\t\t\t\t\t\t<button type=\"button\" class=\"btn btn-icon d-inline-block btn-pill btn-sm btn-danger\" data-dismiss=\"modal\" aria-hidden=\"true\"><i class=\"ki ki-close p-0\"></i></button>\n\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t</form>\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t");

            if (!$(options.modal_id).hasClass('show')) {
              $(options.modal_id).modal('show');
              BaseModal.loader(options.modal_id, false);
            }
          }
        },
            options = $.extend(defaultOptions, options);
        BaseModal.render(element, options);
      }(options);
    }
  };
}(); // webpack support


if ( true && typeof module.exports !== 'undefined') {
  module.exports = BaseModal;
}

/***/ }),

/***/ "./resources/js/base/BasePlugin.js":
/*!*****************************************!*\
  !*** ./resources/js/base/BasePlugin.js ***!
  \*****************************************/
/***/ ((module) => {

/**
 * ============================= BASE PLUGINS ============================
 * return render(options), loader(modal_id, loading=true)
 */
var BasePlugin = function () {
  "use strict";

  return {
    init: function init() {
      this.initTooltipPopover();
      this.initSelectpicker();
      this.initSelect2();
      this.initDatepicker();
      this.initTimepicker();
      this.initSummernote();
      this.initInputMask();
      this.initTouchSpin();
    },
    initTooltipPopover: function initTooltipPopover() {
      if ($('[data-toggle="tooltip"]').length) {
        $('[data-toggle=tooltip]').tooltip();
      }

      if ($('[data-toggle="popover"]').length) {
        $('[data-toggle=popover]').popover();
      }
    },
    initSelect2: function initSelect2() {
      if ($('select.base-plugin--select2').length) {
        $.each($('select.base-plugin--select2'), function (i, el) {
          var me = $(el);

          if (me.parent().hasClass('select2')) {
            me.select2();
          } else {
            var _me$attr, _me$data;

            var defaultsOptions = {
              placeholder: (_me$attr = me.attr('title')) !== null && _me$attr !== void 0 ? _me$attr : 'Pilih salah satu'
            },
                options = (_me$data = me.data('options')) !== null && _me$data !== void 0 ? _me$data : {};
            options = $.extend(defaultsOptions, options);
            me.select2(options);
          }
        });
      }

      if ($('.base-plugin--select2-ajax').length) {
        $.each($('.base-plugin--select2-ajax'), function (i, el) {
          var _me$data2, _me$data3, _me$data4, _me$attr2, _me$data5;

          var me = $(el),
              defaultsOptions = {
            url: me.data('url'),
            method: (_me$data2 = me.data('method')) !== null && _me$data2 !== void 0 ? _me$data2 : 'POST',
            delay: (_me$data3 = me.data('delay')) !== null && _me$data3 !== void 0 ? _me$data3 : 500,
            cache: (_me$data4 = me.data('cache')) !== null && _me$data4 !== void 0 ? _me$data4 : true,
            minimumInputLength: me.data('min-input-length'),
            placeholder: (_me$attr2 = me.attr('title')) !== null && _me$attr2 !== void 0 ? _me$attr2 : 'Pilih salah satu'
          },
              options = (_me$data5 = me.data('options')) !== null && _me$data5 !== void 0 ? _me$data5 : {};
          options = $.extend(defaultsOptions, options);

          if (options.url) {
            var _options$placeholder;

            me.select2({
              ajax: {
                url: options.url,
                type: options.method,
                dataType: 'json',
                delay: options.delay,
                cache: options.cache,
                data: function data(params) {
                  return {
                    _token: BaseUtil.getToken(),
                    q: params.term,
                    // search term
                    page: params.page
                  };
                },
                processResults: function processResults(resp, params) {
                  var _resp$results, _resp$more;

                  params.page = params.page || 1;
                  return {
                    results: (_resp$results = resp.results) !== null && _resp$results !== void 0 ? _resp$results : [],
                    pagination: {
                      more: (_resp$more = resp.more) !== null && _resp$more !== void 0 ? _resp$more : false
                    }
                  };
                }
              },
              minimumInputLength: options.minimumInputLength,
              placeholder: (_options$placeholder = options.placeholder) !== null && _options$placeholder !== void 0 ? _options$placeholder : 'Choose One'
            });
          }
        });
      }
    },
    initSelectpicker: function initSelectpicker() {
      if ($('select.base-plugin--select, select.base-plugin--selectpicker').length) {
        $.each($('select.base-plugin--select, select.base-plugin--selectpicker'), function (i, el) {
          var me = $(el);

          if (me.parent().hasClass('bootstrap-select')) {
            me.selectpicker('refresh');
          } else {
            var _me$data6, _me$attr3, _me$data7, _me$data8, _me$data9, _me$data10;

            var defaultsOptions = {
              container: (_me$data6 = me.data('container')) !== null && _me$data6 !== void 0 ? _me$data6 : 'body',
              title: (_me$attr3 = me.attr('title')) !== null && _me$attr3 !== void 0 ? _me$attr3 : 'Pilih salah satu',
              dropupAuto: (_me$data7 = me.data('dropup-auto')) !== null && _me$data7 !== void 0 ? _me$data7 : false,
              liveSearch: (_me$data8 = me.data('live-search')) !== null && _me$data8 !== void 0 ? _me$data8 : true,
              size: (_me$data9 = me.data('size')) !== null && _me$data9 !== void 0 ? _me$data9 : 5
            },
                options = (_me$data10 = me.data('options')) !== null && _me$data10 !== void 0 ? _me$data10 : {};
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
          } else {
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
          } else {
            me.closest('.bootstrap-select').removeClass('width-full');
            me.closest('.bootstrap-select').addClass('width-70');
            me.closest('.bootstrap-select').css('border-radius', '20px');
          }
        });
      }
    },
    initDatepicker: function initDatepicker() {
      $.fn.datepicker.defaults.format = "dd/mm/yyyy";
      $.fn.datepicker.defaults.language = "id";

      if ($('.base-plugin--datepicker, .base-plugin--datepicker-1').length) {
        $.each($('.base-plugin--datepicker, .base-plugin--datepicker-1'), function (i, el) {
          var _me$data11, _me$data12, _me$data13, _me$data14, _me$data15, _me$data16, _me$data17, _me$data18;

          var me = $(el),
              defaultsOptions = {
            autoclose: (_me$data11 = me.data('auto-close')) !== null && _me$data11 !== void 0 ? _me$data11 : true,
            todayHighlight: (_me$data12 = me.data('today-highlight')) !== null && _me$data12 !== void 0 ? _me$data12 : true,
            orientation: (_me$data13 = me.data('orientation')) !== null && _me$data13 !== void 0 ? _me$data13 : 'auto bottom',
            format: (_me$data14 = me.data('format')) !== null && _me$data14 !== void 0 ? _me$data14 : 'dd/mm/yyyy',
            startView: (_me$data15 = me.data('start-view')) !== null && _me$data15 !== void 0 ? _me$data15 : "days",
            minViewMode: (_me$data16 = me.data('min-view')) !== null && _me$data16 !== void 0 ? _me$data16 : "days",
            language: (_me$data17 = me.data('language')) !== null && _me$data17 !== void 0 ? _me$data17 : 'id'
          },
              options = (_me$data18 = me.data('options')) !== null && _me$data18 !== void 0 ? _me$data18 : {};
          options = $.extend(defaultsOptions, options);
          me.datepicker(options);
        });
      }

      if ($('.base-plugin--datepicker-2').length) {
        $.each($('.base-plugin--datepicker-2'), function (i, el) {
          var _me$data19, _me$data20, _me$data21, _me$data22, _me$data23, _me$data24, _me$data25, _me$data26;

          var me = $(el),
              defaultsOptions = {
            autoclose: (_me$data19 = me.data('auto-close')) !== null && _me$data19 !== void 0 ? _me$data19 : true,
            todayHighlight: (_me$data20 = me.data('today-highlight')) !== null && _me$data20 !== void 0 ? _me$data20 : true,
            orientation: (_me$data21 = me.data('orientation')) !== null && _me$data21 !== void 0 ? _me$data21 : 'auto',
            format: (_me$data22 = me.data('format')) !== null && _me$data22 !== void 0 ? _me$data22 : 'mm/yyyy',
            startView: (_me$data23 = me.data('start-view')) !== null && _me$data23 !== void 0 ? _me$data23 : "months",
            minViewMode: (_me$data24 = me.data('min-view')) !== null && _me$data24 !== void 0 ? _me$data24 : "months",
            language: (_me$data25 = me.data('language')) !== null && _me$data25 !== void 0 ? _me$data25 : 'id'
          },
              options = (_me$data26 = me.data('options')) !== null && _me$data26 !== void 0 ? _me$data26 : {};
          options = $.extend(defaultsOptions, options);
          me.datepicker(options);
        });
      }

      if ($('.base-plugin--datepicker-3').length) {
        $.each($('.base-plugin--datepicker-3'), function (i, el) {
          var _me$data27, _me$data28, _me$data29, _me$data30, _me$data31, _me$data32, _me$data33, _me$data34;

          var me = $(el),
              defaultsOptions = {
            autoclose: (_me$data27 = me.data('auto-close')) !== null && _me$data27 !== void 0 ? _me$data27 : true,
            todayHighlight: (_me$data28 = me.data('today-highlight')) !== null && _me$data28 !== void 0 ? _me$data28 : true,
            orientation: (_me$data29 = me.data('orientation')) !== null && _me$data29 !== void 0 ? _me$data29 : 'auto',
            format: (_me$data30 = me.data('format')) !== null && _me$data30 !== void 0 ? _me$data30 : 'yyyy',
            startView: (_me$data31 = me.data('format')) !== null && _me$data31 !== void 0 ? _me$data31 : "years",
            minViewMode: (_me$data32 = me.data('format')) !== null && _me$data32 !== void 0 ? _me$data32 : "years",
            language: (_me$data33 = me.data('language')) !== null && _me$data33 !== void 0 ? _me$data33 : 'id'
          },
              options = (_me$data34 = me.data('options')) !== null && _me$data34 !== void 0 ? _me$data34 : {};
          options = $.extend(defaultsOptions, options);
          console.log(options);
          me.datepicker(options);
        });
      }
    },
    initTimepicker: function initTimepicker() {
      if ($('.base-plugin--timepicker').length) {
        $.each($('.base-plugin--timepicker'), function (i, el) {
          var _me$data35, _me$data36, _me$data37, _me$data38, _me$data39, _me$data40, _me$data41;

          var me = $(el),
              defaultsOptions = {
            minuteStep: (_me$data35 = me.data('minute-step')) !== null && _me$data35 !== void 0 ? _me$data35 : 2,
            defaultTime: (_me$data36 = me.data('default-time')) !== null && _me$data36 !== void 0 ? _me$data36 : '00:00',
            showSeconds: (_me$data37 = me.data('show-seconds')) !== null && _me$data37 !== void 0 ? _me$data37 : false,
            showMeridian: (_me$data38 = me.data('show-meridian')) !== null && _me$data38 !== void 0 ? _me$data38 : false,
            snapToStep: (_me$data39 = me.data('snap-to-step')) !== null && _me$data39 !== void 0 ? _me$data39 : true,
            orientation: (_me$data40 = me.data('orientation')) !== null && _me$data40 !== void 0 ? _me$data40 : 'auto'
          },
              options = (_me$data41 = me.data('options')) !== null && _me$data41 !== void 0 ? _me$data41 : {};
          options = $.extend(defaultsOptions, options);
          me.timepicker(options);
        });
      }
    },
    initSummernote: function initSummernote() {
      if ($('.base-plugin--summernote-readonly').length) {
        $('.base-plugin--summernote-readonly').append("\n\t\t\t\t\t<div class=\"note-statusbar\" role=\"status\">\n\t\t\t\t\t\t<div class=\"note-resizebar\" aria-label=\"Resize\">\n\t\t\t\t\t\t\t<div class=\"note-icon-bar\"></div>\n\t\t\t\t\t\t\t<div class=\"note-icon-bar\"></div>\n\t\t\t\t\t\t\t<div class=\"note-icon-bar\"></div>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t</div>");
      }

      if ($('.base-plugin--summernote, .base-plugin--summernote-1').length) {
        $.each($('.base-plugin--summernote, .base-plugin--summernote-1'), function (i, el) {
          var _me$data42, _me$data43, _me$data44;

          var me = $(el),
              defaultsOptions = {
            height: (_me$data42 = me.data('height')) !== null && _me$data42 !== void 0 ? _me$data42 : 100,
            toolbar: (_me$data43 = me.data('toolbar')) !== null && _me$data43 !== void 0 ? _me$data43 : false
          },

          options = (_me$data44 = me.data('options')) !== null && _me$data44 !== void 0 ? _me$data44 : {};
          options = $.extend(defaultsOptions, options);
          me.summernote(options);
        });
      }

      if ($('.base-plugin--summernote-2').length) {
        $.each($('.base-plugin--summernote-2'), function (i, el) {
          var _me$data45, _me$data46;

          var me = $(el),
              defaultsOptions = {
            height: (_me$data45 = me.data('height')) !== null && _me$data45 !== void 0 ? _me$data45 : 300
          },
              options = (_me$data46 = me.data('options')) !== null && _me$data46 !== void 0 ? _me$data46 : {};
          options = $.extend(defaultsOptions, options);
          me.summernote(options);
          if (me.prop('disabled')) {
            me.summernote('destroy');
            me.summernote({
              toolbar: false
            });
            me.parent().find('.note-editable').attr('contenteditable', false);
          }
        });
      }

      if ($('.base-plugin--summernote-simple').length) {
        $.each($('.base-plugin--summernote-simple'), function (i, el) {
          var _me$data47;

          var me = $(el),
              defaultsOptions = {
            addclass: {
              debug: false,
              classTags: [{
                title: "Button",
                "value": "btn btn-success"
              }, "jumbotron", "lead", "img-rounded", "img-circle", "img-responsive", "btn", "btn btn-success", "btn btn-danger", "text-muted", "text-primary", "text-warning", "text-danger", "text-success", "table-bordered", "table-responsive", "alert", "alert alert-success", "alert alert-info", "alert alert-warning", "alert alert-danger", "visible-sm", "hidden-xs", "hidden-md", "hidden-lg", "hidden-print"]
            },
            toolbar: [['font', ['bold', 'italic', 'underline']], ['para', ['ul', 'ol', 'paragraph']]],
            height: 150
          },
              options = (_me$data47 = me.data('options')) !== null && _me$data47 !== void 0 ? _me$data47 : {};
          options = $.extend(defaultsOptions, options);
          me.summernote(options);
        });
      }
    },
    initInputMask: function initInputMask() {
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
    initTouchSpin: function initTouchSpin() {
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
          maxboostedstep: 10
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
          maxboostedstep: 10
        });
      }
    }
  };
}(); // webpack support


if ( true && typeof module.exports !== 'undefined') {
  module.exports = BasePlugin;
}

/***/ }),

/***/ "./resources/js/base/BaseUtil.js":
/*!***************************************!*\
  !*** ./resources/js/base/BaseUtil.js ***!
  \***************************************/
/***/ ((module) => {

var BaseUtil = function () {
  return {
    init: function init() {
      this.refreshComponent();
      this.handleOnPopstate();
      this.handleAsideMenuTree();
      this.menuActivation();
      this.userNotification(); // this.handleServerSendEvent();
    },
    refreshComponent: function refreshComponent() {
      this.bodyClasses();
      this.sidebarMini();
      this.menuActivation();
      this.sidebarBadge();
    },
    isDev: function isDev() {
      return $('meta[name="debug"]').attr('content');
    },
    getToken: function getToken() {
      return $('meta[name="csrf-token"]').attr('content');
    },
    redirect: function redirect(url) {
      window.location = url;
    },
    bodyClasses: function bodyClasses() {
      var body = $('body');

      if (body.find('.subheader').length) {
        if (!body.hasClass('subheader-enabled')) {
          body.addClass('subheader-enabled subheader-fixed');
        }
      } else {
        body.removeClass('subheader-enabled subheader-fixed');
      }
    },
    initScroll: function initScroll() {
      $('[data-scroll="true"]').each(function () {
        var el = $(this);
        KTUtil.scrollInit(this, {
          mobileNativeScroll: true,
          handleWindowResize: true,
          rememberPosition: el.data('remember-position') == 'true' ? true : false,
          height: function height() {
            if (KTUtil.isBreakpointDown('lg') && el.data('mobile-height')) {
              return el.data('mobile-height');
            } else {
              return el.data('height');
            }
          }
        });
      });
    },
    menuActivation: function menuActivation() {
      var pageName = $('#content-page').attr('data-sidebar-name');
      var menuActive = '#sidebar .menu-link[data-name="' + pageName + '"]';

      if ($(menuActive).length === 0) {
        menuActive = '#sidebar .menu-link[href="' + window.location.pathname + '"]';
      }

      if ($(menuActive).length) {
        if ($('#sidebar.custom-sidebar').length) {
          $('#sidebar ul, #sidebar li').removeClass('active');
          $(menuActive).parents('ul, li').addClass('active');
          $(menuActive).closest('li').find('ul').addClass('active');
          $('#sidebar li:not(.active)').removeClass('expand').addClass('closed');
          $('#sidebar ul:not(.active)').removeClass('expand').addClass('closed').hide();
        } else {
          $('#sidebar .menu-item').removeClass('menu-item-active');
          $('#sidebar .menu-item-submenu').removeClass('menu-item-open');
          $(menuActive).last().addClass('active');
          $(menuActive).last().parents('.menu-item').addClass('menu-item-active');
          $(menuActive).last().parents('.menu-item-submenu').addClass('menu-item-open');
        }
      }
    },
    sidebarMini: function sidebarMini() {
      var body = $('body'),
          mini = $('#content-page').data('sidebar-mini');

      if (mini == true) {
        body.addClass('aside-minimize');
        KTCookie.setCookie('kt_aside_toggle_state', 'on');
      } else {
        body.removeClass('aside-minimize');
        KTCookie.setCookie('kt_aside_toggle_state', 'off');
      }
    },
    sidebarBadge: function sidebarBadge() {
      var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
      return function (options) {
        if ($('[data-sidebar-badge="true"]').length) {
          var defaultOptions = {
            url: '/globals/refresh-sidebar-badge',
            type: 'POST',
            data: {
              _token: BaseUtil.getToken()
            }
          },
              options = $.extend(defaultOptions, options);
          $.ajax({
            url: options.url,
            type: options.type,
            data: options.data,
            success: function success(resp) {
              $('.base-notification-wrapper').html(resp);
            },
            error: function error(resp) {
              dd(resp);
            }
          });
        }
      }(options);
    },
    userNotification: function userNotification() {
      var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
      return function (options) {
        var wrapper = $('.base-notification-wrapper');

        if (wrapper.length) {
          var defaultOptions = {
            url: '/globals/utility/user-notification',
            type: 'GET'
          },
              options = $.extend(defaultOptions, options);
          $.ajax({
            url: options.url,
            type: options.type,
            data: options.data,
            success: function success(resp) {
              wrapper.html(resp);
              wrapper.find('[data-scroll="true"]').each(function () {
                var el = $(this);
                KTUtil.scrollInit(this, {
                  mobileNativeScroll: true,
                  handleWindowResize: true,
                  rememberPosition: el.data('remember-position') == 'true' ? true : false,
                  height: function height() {
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
                wrapper.closest('.dropdown').find('.user-notification-header span').html(count + ' New');
                wrapper.closest('.dropdown').find('.pulse-ring').removeClass('hide');
                wrapper.closest('.dropdown').find('.user-notification-badge').removeClass('hide');
                wrapper.closest('.dropdown').find('.user-notification-header').removeClass('hide');
              } else {
                wrapper.closest('.dropdown').find('.pulse-ring').addClass('hide');
                wrapper.closest('.dropdown').find('.user-notification-badge').addClass('hide');
                wrapper.closest('.dropdown').find('.user-notification-header').addClass('hide');
              }
            },
            error: function error(resp) {
              console.log(resp);
            }
          });
        }
      }(options);
    },
    handleOnPopstate: function handleOnPopstate() {
      if (typeof window.onpopstate != 'undefined') {
        window.onpopstate = function (e) {
          e.preventDefault();
          window.location.reload();
        };
      } else {
        $.gritter.add({
          title: 'Warning!',
          text: 'Browser Anda tidak support History PushState.<br/>Anda tidak akan mendapatkan loading page tanpa reload!',
          // image: '/assets/images/icon/ui/cross.png',
          sticky: false,
          time: '10000'
        });
      }
    },
    handleServerSendEvent: function handleServerSendEvent() {
      if (typeof EventSource !== "undefined") {
        // withCredentials=true: pass the cross-domain cookies to server-side
        var source = new EventSource('/globals/sse', {
          withCredentials: false
        });
        source.addEventListener('news', function (event) {
          var data = JSON.parse(event.data);
          handleRefreshSidebarBadge(data.news.sidebar); // source.close(); // disconnect stream
        }, false);
      } else {
        $.gritter.add({
          title: 'Warning!',
          text: 'Browser Anda tidak support HTML5 SSE API.<br/>Anda tidak akan mendapatkan realtime notifications!',
          // image: '/assets/images/icon/ui/cross.png',
          sticky: false,
          time: '10000'
        });
      }
    },
    handleAsideMenuTree: function handleAsideMenuTree() {
      var expandTime = $('.custom-sidebar').attr('data-disable-slide-animation') ? 0 : 250;
      $(document).on('click', '.custom-sidebar.sidebar-menu .nav > .has-sub > a', function () {
        var target = $(this).next('.sub-menu');
        var otherMenu = $('.custom-sidebar.sidebar-menu .nav > li.has-sub > .sub-menu').not(target);

        if ($('.page-sidebar-minified').length === 0) {
          $(otherMenu).closest('li').addClass('closing');
          $(otherMenu).slideUp(expandTime, function () {
            $(otherMenu).closest('li').addClass('closed').removeClass('expand closing');
          });

          if ($(target).is(':visible')) {
            $(target).closest('li').addClass('closing').removeClass('expand');
          } else {
            $(target).closest('li').addClass('expanding').removeClass('closed');
          }

          $(target).slideToggle(expandTime, function () {
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
      $(document).on('click', '.custom-sidebar.sidebar-menu .nav > .has-sub .sub-menu li.has-sub > a', function () {
        if ($('.page-sidebar-minified').length === 0) {
          var target = $(this).next('.sub-menu');

          if ($(target).is(':visible')) {
            $(target).closest('li').addClass('closing').removeClass('expand');
          } else {
            $(target).closest('li').addClass('expanding').removeClass('closed');
          }

          $(target).slideToggle(expandTime, function () {
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
      $(document).on('click', '.custom-sidebar.sidebar-filter .nav > .has-sub > a', function () {
        var target = $(this).next('.sub-menu');
        var otherMenu = $('.custom-sidebar .nav > li.has-sub > .sub-menu').not(target);

        if ($('.page-sidebar-minified').length === 0) {
          $(otherMenu).closest('li').addClass('closing');
          $(otherMenu).slideUp(expandTime, function () {
            $(otherMenu).closest('li').addClass('closed').removeClass('expand closing');
          });

          if ($(target).is(':visible')) {
            $(target).closest('li').addClass('closing').removeClass('expand');
          } else {
            $(target).closest('li').addClass('expanding').removeClass('closed');
          }

          $(target).slideToggle(expandTime, function () {
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
      $(document).on('click', '.custom-sidebar.sidebar-filter .nav > .has-sub .sub-menu li.has-sub > a', function () {
        if ($('.page-sidebar-minified').length === 0) {
          var target = $(this).next('.sub-menu');

          if ($(target).is(':visible')) {
            $(target).closest('li').addClass('closing').removeClass('expand');
          } else {
            $(target).closest('li').addClass('expanding').removeClass('closed');
          }

          $(target).slideToggle(expandTime, function () {
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
      }); // $(document).on('click', '.base-util--user-notifications', function () {
      // 	var me = $(this),
      // 		options = {
      // 			url: me.data('url')
      // 		}
      // 	BaseUtil.handleUserNotification(options);
      // });
    }
  };
}(); // webpack support


if ( true && typeof module.exports !== 'undefined') {
  module.exports = BaseUtil;
}

/***/ }),

/***/ "./resources/js/base/index.js":
/*!************************************!*\
  !*** ./resources/js/base/index.js ***!
  \************************************/
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

window.BaseApp = __webpack_require__(/*! ./BaseApp.js */ "./resources/js/base/BaseApp.js");
window.BaseUtil = __webpack_require__(/*! ./BaseUtil.js */ "./resources/js/base/BaseUtil.js");
window.BasePlugin = __webpack_require__(/*! ./BasePlugin.js */ "./resources/js/base/BasePlugin.js");
window.BaseContent = __webpack_require__(/*! ./BaseContent.js */ "./resources/js/base/BaseContent.js");
window.BaseModal = __webpack_require__(/*! ./BaseModal.js */ "./resources/js/base/BaseModal.js");
window.BaseList = __webpack_require__(/*! ./BaseList.js */ "./resources/js/base/BaseList.js");
window.BaseForm = __webpack_require__(/*! ./BaseForm.js */ "./resources/js/base/BaseForm.js");

/***/ }),

/***/ "./resources/sass/style.scss":
/*!***********************************!*\
  !*** ./resources/sass/style.scss ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/assets/js/base.bundle": 0,
/******/ 			"assets/css/style.bundle": 0
/******/ 		};
/******/
/******/ 		// no chunk on demand loading
/******/
/******/ 		// no prefetching
/******/
/******/ 		// no preloaded
/******/
/******/ 		// no HMR
/******/
/******/ 		// no HMR manifest
/******/
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			for(moduleId in moreModules) {
/******/ 				if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 					__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 				}
/******/ 			}
/******/ 			if(runtime) var result = runtime(__webpack_require__);
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkIds[i]] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/
/******/ 		var chunkLoadingGlobal = self["webpackChunk"] = self["webpackChunk"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/
/************************************************************************/
/******/
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["assets/css/style.bundle"], () => (__webpack_require__("./resources/js/base/index.js")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["assets/css/style.bundle"], () => (__webpack_require__("./resources/sass/style.scss")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/
/******/ })()
;
