'use strict';

var ApplyForm =
    {
        modal_timeout: 5000,

        active_apply_form_name: '',
        re_captcha_token : '',

        disableSubmitButton: function (form, value) {
            $(form).find('button[type="submit"]').attr('disabled', value);
        },

        executeCaptcha: function () {
            grecaptcha.execute();
        },

        resetCaptcha: function () {
            grecaptcha.reset();
        },

        setModalTimeout: function (seconds) {
            if (parseInt(seconds)) {
                ApplyForm.modal_timeout = seconds * 1000;
            }
        },

        setActiveForm: function (formName) {
          ApplyForm.active_apply_form_name = formName;
        },

        setReCaptchaToken: function (token) {
            ApplyForm.re_captcha_token = token;
        },

        submitActiveForm: function () {

            var $form = $('#' + ApplyForm.active_apply_form_name + '-form');
            var data = new FormData($form[0]);

            data.append('g-recaptcha-response', ApplyForm.re_captcha_token);

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

            ApplyForm.resetCaptcha();
        },

        resetForm: function (form) {
            form[0].reset();
            form.find(".btn_upload, .file_name_placeholder").show();
            form.find(".btn_delete, .file_name").hide();
            ApplyForm.setActiveForm('');
            ApplyForm.setReCaptchaToken('');
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

                var $form = $('#' + formName + '-form');

                if (!$form.length) {
                    return;
                }

                $form.validate({
                    rules: ApplyForm["apply_form_" + formName + "_rules"],
                    messages: ApplyForm["apply_form_" + formName + "_messages"],
                    errorPlacement: function (error, element) {},
                    submitHandler: function (form) {
                        ApplyForm.disableSubmitButton(form, false);
                        ApplyForm.setActiveForm(formName);
                        ApplyForm.executeCaptcha();
                    },

                });

            });
        },

        init: function () {
            ApplyForm.initApplyForms();
        },
    };