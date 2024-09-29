/*
 * BASE FORM
 */
const BaseForm = function () {

	return {
		submit: function (form, options = {}) {
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
					callbackSuccess: function(resp, form, options) {},
					callbackError: function(resp, form, options) {}
				},
				options = $.extend(defaultOptions, options);

			var formLoading = setTimeout(function(){
				if (options.loaderModal && form.closest('.modal').length) {
					BaseModal.loader('#'+form.closest('.modal').attr('id'), true);
				}
				else {
					BaseContent.loader(true);
				}
			}, 700);

			if (options.btnSubmit !== null && options.btnSubmit.length) {
				options.btnSubmit.prop('disabled', true)
						 .addClass('btn-loader spinner spinner-white spinner-left');
			}

			form.ajaxSubmit({
				success: function (resp) {
					clearTimeout(formLoading);
					if (options.fullCallbackSuccess === true) {
						if ($.isFunction(options.callbackSuccess)) {
							options.callbackSuccess(resp, form, options);
						}
					}
					else {
						BaseForm.validationMessages(resp, form);
						if (options.swalSuccess) {
							swal({
								title: resp.title,
								text: resp.message,
								icon: 'success',
								timer: options.swalSuccessTimer,
								buttons: options.swalSuccessButton,
							});
							BaseForm.defaultCallbackSuccess(resp, form, options);
						}
						else {
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
				error: function (resp) {
					clearTimeout(formLoading);
					if (options.fullCallbackError === true) {
						if ($.isFunction(options.callbackError)) {
							options.callbackError(resp, form, options);
						}
					}
					else {
						resp = resp.responseJSON;
						BaseForm.validationMessages(resp, form);
						BaseForm.defaultCallbackError(resp, form, options);
						if (resp.alert !== undefined) {
							form.find('.base-alert').remove();
							form.prepend(`
								<div class="alert alert-`+resp.alert+` fade show base-alert">
									<span class="close" data-dismiss="alert">×</span>
									`+resp.message+`
								</div>
							`).hide().fadeIn(700);
						}
						if (options.swalError) {
							swal({
								title: resp.title != undefined ? resp.title : 'Failed!',
								text: resp.message != undefined ? resp.message : 'Data failed to save!',
								icon: 'error',
								timer: options.swalErrorTimer,
								buttons: options.swalErrorButton,
							});
						}
						else {
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
		},
		validationMessages: function (resp, form) {
			form.find('.is-invalid').removeClass('is-invalid');
			form.find('.is-invalid-message').remove();
			form.find('.is-invalid-alert').remove();
			if (resp.message == 'The given data was invalid.') {
				resp.message = 'Data yang Anda masukkan tidak valid.'
				$.each(resp.errors, function (name, messages) {
					var names = name.split('.'),
						name  = names.reduce((all, item) => {
									all += (name == 0 ? item : '[' + item + ']');
									return all;
								}),
						field = $('[name^=\"' + names[0] + '[]\"], [name=\"' + name + '\"], [name=\"' + name + '[]\"]'),
						parentGroup = field.closest('.parent-group').length ? field.closest('.parent-group') : field.closest('.form-group');

					field.addClass('is-invalid');
					field.closest('.bootstrap-select').addClass('is-invalid');
					field.parent().find('.select2 .select2-selection').addClass('is-invalid');
					// field.parent().find('.base-plugin--select2-ajax').addClass('is-invalid-message');
					// form.addClass('.is-invalid-message');
					$.each(messages, function (i, message) {
						parentGroup.append('<p class="is-invalid-message text-danger my-1">'+message+'</p>');
						// $('<p class="is-invalid-message text-danger my-1">' + message + '</p>').insertAfter(field).last();
					});
				});
				$('.is-invalid-message').hide().fadeIn(500);
			}
			else if (BaseUtil.isDev()) {
				if(resp.exception !== undefined && resp.file !== undefined && resp.line !== undefined && resp.message !== undefined) {
					BaseModal.render('body', {
						modal_id: '#alert-modal',
						modal_size: 'modal-lg',
						modal_bg: 'bg-light-danger',
						callback: function (options, modalLoadingTimer) {
							clearTimeout(modalLoadingTimer);
							$(options.modal_id+' .modal-content').html(`
								<div class="modal-header">
									<h4 class="modal-title">Error!</h4>
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
								</div>
								<div class="modal-body py-5">
									<div class="alert alert-danger">
										Terjadi kesalahan!
										<p mt-5><small>*Pesan ini hanya akan ditampilkan pada masa development</small></p>
									</div>
									<div class="table-responsive">
										<table class="table">
											<tbody>
												<tr>
													<td class="width-80">File : </td>
													<td>`+resp.file+`</td>
												</tr>
												<tr>
													<td class="width-80">Line : </td>
													<td>`+resp.line+`</td>
												</tr>
												<tr>
													<td class="width-80">Message : </td>
													<td>`+resp.message+`</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div class="modal-footer">
									<a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>
								</div>
							`);

							if (!$(options.modal_id).hasClass('show')) {
								$(options.modal_id).modal('show');
								BaseModal.loader(options.modal_id, false);
							}
						}
					});
				}
				else if($.type( resp.errors ) === "string") {
					var alerParent = form.find('.modal-body').length ? form.find('.modal-body').first() : form;
					alerParent.prepend(`
						<div class="alert alert-danger fade show is-invalid-alert">
							<span class="close alert-dismiss" data-dismiss="alert">×</span>
							`+resp.errors+`
							<br/>
							<p><small>*Pesan ini hanya akan ditampilkan pada masa development</small></p>
						</div>
					`).hide().fadeIn(700);
				}
			}
		},
		defaultCallbackSuccess: function (resp, form, options = {}) {
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
				options.btnSubmit.prop('disabled', false).removeClass(['spinner' ,'spinner-white', 'spinner-left']);
			}
			if (options.loaderModal && form.closest('.modal').length) {
				BaseModal.loader('#'+form.closest('.modal').attr('id'), false);
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
						names: resp.refreshSidebarNames,
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
				$('body,html').animate({scrollTop: '5px'}, 500).animate({scrollTop: 0}, 800);
			}
		},
		defaultCallbackError: function (resp, form, options = {}) {
			if (options.btnSubmit !== null && options.btnSubmit.length) {
				options.btnSubmit.prop('disabled', false).removeClass(['spinner' ,'spinner-white', 'spinner-left']);
			}
			if (options.loaderModal && form.closest('.modal').length) {
				BaseModal.loader('#'+form.closest('.modal').attr('id'), false);
			} else {
				BaseContent.loader(false);
			}
			if ($.isFunction(options.callbackError)) {
				options.callbackError(resp, form);
			}
			var firstError = form.find('.is-invalid').first();
			if (firstError.length && form.closest('.modal').length == 0) {
				$('body,html').animate({scrollTop: firstError.position().top}, 500);
			}
		},
		delete: function (url, options = {}) {
			var defaultOptions = {
					swalSuccess: false,
					swalError: false,
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
				success: function (resp) {
					clearTimeout(contentLoading);
					BaseContent.loader(false);
					if (options.swalSuccess) {
						swal({
							title: resp.title != undefined ? resp.title : 'Success!',
							text: resp.message != undefined ? resp.message : 'Data deleted successfully!',
							icon: 'success',
							timer: 3000,
							buttons: {},
						});
					}
					else {
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
				error: function (resp) {
					clearTimeout(contentLoading);
					BaseContent.loader(false);
					if (options.swalError) {
						swal({
							title: resp.title != undefined ? resp.title : 'Failed!',
							text: resp.message != undefined ? resp.message : 'Data failed to delete!',
							icon: 'error',
							// timer: 3000,
						});
					}
					else {
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
		},
		confirm: function (url, options = {}) {
			var defaultOptions = {
					swalSuccess: false,
					swalError: false,
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
				success: function (resp) {
					clearTimeout(contentLoading);
					BaseContent.loader(false);
					if (options.swalSuccess) {
						swal({
							title: resp.title != undefined ? resp.title : 'Success!',
							text: resp.message != undefined ? resp.message : 'Data confirmation successfully!',
							icon: 'success',
							timer: 3000,
							buttons: {},
						});
					}
					else {
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
				error: function (resp) {
					clearTimeout(contentLoading);
					BaseContent.loader(false);
					if (options.swalError) {
						swal({
							title: resp.responseJSON.title != undefined ? resp.responseJSON.title : 'Failed!',
							text: resp.responseJSON.message != undefined ? resp.responseJSON.message : 'Data failed to confirm!',
							icon: 'error',
							// timer: 3000,
						});
					}
					else {
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
		},
		activate: function (url, status, options = {}) {
			var defaultOptions = {
					swalSuccess: false,
					swalError: false,
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
					status: status == 1 ? 0 : 1,
				},
				success: function (resp) {
					clearTimeout(contentLoading);
					BaseContent.loader(false);
					if (options.swalSuccess) {
						swal({
							title: resp.title != undefined ? resp.title : 'Success!',
							text: resp.message != undefined ? resp.message : 'Data has been activated successfully!',
							icon: 'success',
							timer: 3000,
							buttons: {},
						});
					}
					else {
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
				error: function (resp) {
					clearTimeout(contentLoading);
					BaseContent.loader(false);
					if (options.swalError) {
						swal({
							title: resp.title != undefined ? resp.title : 'Failed!',
							text: resp.message != undefined ? resp.message : 'Data failed to activation!',
							icon: 'error',
							// timer: 3000,
						});
					}
					else {
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
		},
		approval: function () {
            $(document).on('click', '.sign', function(e) {
                var id = $(this).data('idx');
                var url = $(this).data('url');
                var redirect = $(this).data('redirect');
                var jabatan = $(this).data('jabatan');
                var name = $(this).data('name');
                var note = typeof($(this).data('note')) != "undefined" ? $(this).data('note') : false;

                swal({
                    title: 'Apakah Anda yakin ?',
                    text: 'akan menandatangani '+name+' tersebut..!',
                    icon: 'warning',
                    buttons: {
                        cancel : 'Batal',
                        confirm : {text:'Setujui',className:'btn-primary'},
                        revisi : {text:'Revisi',className:'btn-danger'},
                    }
                }).then(function (result) {
                    if (result === true) {
                        BaseContent.loader(true);
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: {
                                '_token' : BaseUtil.getToken(),
                                'id' : id,
                                'jabatan' : jabatan,
                                'status' : 1,
                            },
                            success: function (response) {
                                BaseContent.loader(false);
                                swal({
                                    title: response.title,
                                    text: name+' berhasil di Approve',
                                    icon: 'success',
                                    timer: '2000',
                                    buttons: false,
                                }).then(() => {
                                    // location.reload();
                                    location.href = redirect;
                                })
                            }
                        });
                    }else if(result === 'revisi') {
                        BaseContent.loader(true);
                        if (note == true) {
                                Swal.mixin({
                                    showCancelButton: true,
                                    cancel : 'Batal',
                                }).queue([
                                    {
                                        title: 'Catatan Revisi',
                                        input: 'textarea',
                                        confirmButtonText: 'Submit',
                                    },
                                ]).then((result) => {
                                    if (result.value) {
                                        BaseContent.loader(true);
                                        $.ajax({
                                            type: "POST",
                                            url: url,
                                            data: {
                                                '_token' : BaseUtil.getToken(),
                                                'id' : id,
                                                'jabatan' : jabatan,
                                                'status' : 2,
                                                'note' : result.value[0]
                                            },
                                            success: function (response) {
                                                BaseContent.loader(false);
                                                swal({
                                                    title: response.title,
                                                    text: name+' berhasil disimpan untuk revisi',
                                                    icon: 'info',
                                                    timer: '2000',
                                                    buttons: false,
                                                }).then(() => {
                                                    location.href = redirect;
                                                })
                                            }
                                        });
                                    } else {
										BaseContent.loader(false);
									}
                                });
                        }else{
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: {
                                    '_token' : BaseUtil.getToken(),
                                    'id' : id,
                                    'jabatan' : jabatan,
                                    'status' : 2,
                                },
                                success: function (response) {
                                    BaseContent.loader(false);
                                    swal({
                                        title: response.title,
                                        text: name+' berhasil disimpan untuk revisi',
                                        icon: 'info',
                                        timer: '2000',
                                        buttons: false,
                                    }).then(() => {
                                        location.href = redirect;
                                    })
                                }
                            });
                        }
                    }
                });
            });

            $(document).on('click', '.sign-pin', function(e) {
                var url = $(this).data('url');
                var redirect = $(this).data('redirect');
                var id = $(this).data('idx');
                var jabatan = $(this).data('jabatan');
                var checker = $(this).data('checker');
                var name = $(this).data('name');
                var note = typeof($(this).data('note')) != "undefined" ? $(this).data('note') : false;

                swal({
                    title: 'Apakah Anda yakin ?',
                    text: 'akan menandatangani '+name+' tersebut..!',
                    icon: 'warning',
                    buttons: {
                        cancel : 'Batal',
                        confirm : {text:'Setujui',className:'btn-primary'},
                        revisi : {text:'Revisi',className:'btn-danger'},
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
                            allowOutsideClick: () => !Swal.isLoading()
                        }).then(function (result) {
                            if (result.value) {
                                BaseContent.loader(true);
                                $.ajax({
                                    type: "POST",
                                    url: url,
                                    data: {
                                        '_token' : BaseUtil.getToken(),
                                        'id' : id,
                                        'jabatan' : jabatan,
                                        'status' : 1,
                                    },
                                    success: function (response) {
                                        BaseContent.loader(false);
                                        swal({
                                            title: response.title,
                                            text: name+' berhasil di Approve',
                                            icon: 'success',
                                            timer: '2000',
                                            buttons: false,
                                        }).then(() => {
                                            // location.reload();
                                            location.href = redirect;
                                        })
                                    },
                                    error: function error(_error) {
                                        Swal.fire({
                                            icon: 'danger',
                                            title: 'Tanda Tangan Gagal',
                                            showConfirmButton: false,
                                            timer: '2000',
                                        }).then(function (result) {

                                        });
                                    }
                                });
                            }
                        })

                    } else if(result === 'revisi') {
                        if (note == true) {
                            Swal.mixin({
                                showCancelButton: true,
                                cancel : 'Batal',
                                progressSteps: ['1', '2']
                            }).queue([
                                {
                                    title: 'Catatan Revisi',
                                    input: 'textarea',
                                    confirmButtonText: 'Selanjutnya',
                                },
                                {
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
                                    allowOutsideClick: () => !Swal.isLoading()
                                },
                            ]).then((result) => {
                                if (result.value) {
                                    BaseContent.loader(true);
                                    $.ajax({
                                        type: "POST",
                                        url: url,
                                        data: {
                                            '_token' : BaseUtil.getToken(),
                                            'id' : id,
                                            'jabatan' : jabatan,
                                            'status' : 2,
                                            'note' : result.value[0]
                                        },
                                        success: function (response) {
                                            BaseContent.loader(false);
                                            swal({
                                                title: response.title,
                                                text: name+' berhasil disimpan untuk revisi',
                                                icon: 'info',
                                                timer: '2000',
                                                buttons: false,
                                            }).then(() => {
                                                location.href = redirect;
                                            })
                                        }
                                    });
                                }
                            });
                        }else{
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
                                allowOutsideClick: () => !Swal.isLoading()
                            }).then(function (result) {
                                if (result.value) {
                                    BaseContent.loader(true);
                                    $.ajax({
                                        type: "POST",
                                        url: url,
                                        data: {
                                            '_token' : BaseUtil.getToken(),
                                            'id' : id,
                                            'jabatan' : jabatan,
                                            'status' : 2,
                                        },
                                        success: function (response) {
                                            BaseContent.loader(false);
                                            swal({
                                                title: response.title,
                                                text: name+' berhasil disimpan untuk revisi',
                                                icon: 'info',
                                                timer: '2000',
                                                buttons: false,
                                            }).then(() => {
                                                location.href = redirect;
                                            })
                                        }
                                    });
                                }
                            });
                        }
                    }
                });
            });
		}
	}
}();

// webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = BaseForm;
}
