'use strict';

var ApplyForm =
    {
        apply_forms: [

        ],

        active_apply_form_name: '',

        grecaptcha_enabled: true,
        grecaptcha_response : '',

        modal_timeout: 5000,

        setApplyForms: function (applyForms) {
            ApplyForm.apply_forms = applyForms;
        },

        setActiveForm: function (formName) {
          ApplyForm.active_apply_form_name = formName;
        },

        setGrecaptchaEnabled: function (value) {
            ApplyForm.grecaptcha_enabled = !!value;
        },

        setGrecaptchaResponse: function (response) {
            ApplyForm.grecaptcha_response = response;
        },

        setModalTimeout: function (milliseconds) {
            if (parseInt(milliseconds)) {
                ApplyForm.modal_timeout = milliseconds;
            }
        },

        executeCaptcha: function () {
            grecaptcha.execute();
        },

        resetCaptcha: function () {
            grecaptcha.reset();
        },

        disableSubmitButton: function (form, value) {
            $(form).find('button[type="submit"]').attr('disabled', value);
        },

        submitActiveForm: function () {
            if(!ApplyForm.active_apply_form_name){
                return false
            }

            var $form = $('#' + ApplyForm.active_apply_form_name + '_form');
            var data = new FormData($form[0]);

            data.append('grecaptcha_response', ApplyForm.grecaptcha_response);

            $.ajax({
                url: App.lang_segment + '/apply-form/' + ApplyForm.active_apply_form_name,
                type: 'POST',
                dataType: 'json',
                cache: false,
                data: data,
                contentType: false,
                processData: false,
                success: function (response) {
                    ApplyForm.disableSubmitButton(ApplyForm.active_apply_form_name, true);
                    if (response.status) {
                        ApplyForm.resetForm($form);
                        ApplyForm.showSuccessModal(response.message.title, response.message.message);
                    } else {
                        ApplyForm.showErrorModal(response.message.title, response.message.message);
                    }
                }
            });
        },

        resetForm: function (form) {
            form[0].reset();
            form.find(".btn_upload, .file_name_placeholder").show();
            form.find(".btn_delete, .file_name").hide();
            ApplyForm.setActiveForm('');

            if (ApplyForm.grecaptcha_enabled) {
                ApplyForm.setGrecaptchaResponse('');
                ApplyForm.resetCaptcha();
            }
        },

        showSuccessModal: function(title,message){
            if (title !== undefined) {
                $('#popup-success').find('h2').html(title);
            }

            if (message !== undefined) {
                $('#popup-success').find('h3').html(message);
            }
            
            Popup.show('popup-success');

            setTimeout(function () {
                Popup.hide('popup-success');
            }, ApplyForm.modal_timeout);
        },

        showErrorModal: function(title, message){
            if (title !== undefined) {
                $('#popup-error').find('h2').html(title);
            }

            if (message !== undefined) {
                $('#popup-error').find('h3').html(message);
            }
            Popup.show('popup-error');

            setTimeout(function () {
                Popup.hide('popup-error');
            }, ApplyForm.modal_timeout);
        },

        initApplyForms: function () {
            $.each(ApplyForm.apply_forms, function (index, formName) {

                var $form = $('#' + formName + '_form');

                if (!$form.length) {
                    return;
                }

                $form.validate({
                    rules: ApplyForm[formName + "_rules"],
                    messages: ApplyForm[formName + "_messages"],
                    errorPlacement: function (error, element) {
                    },
                    submitHandler: function (form) {
                        ApplyForm.disableSubmitButton(form, false);
                        ApplyForm.setActiveForm(formName);

                        if (ApplyForm.grecaptcha_enabled) {
                            ApplyForm.executeCaptcha();
                        } else {
                            ApplyForm.submitActiveForm();
                        }
                    }
                });
            });
        },

        init: function () {
            ApplyForm.initApplyForms();
        }
    };