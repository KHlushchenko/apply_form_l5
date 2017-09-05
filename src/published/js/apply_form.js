'use strict';

var ApplyForm =
    {
        lang_prefix: '',

        apply_forms: [

        ],
        active_apply_form_name: '',

        grecaptcha_enabled: false,
        grecaptcha_response : '',

        setLangPrefix: function (langPrefix) {
            ApplyForm.lang_prefix = langPrefix;
        },

        setApplyForms: function (applyForms) {
            ApplyForm.apply_forms = applyForms;
        },

        setActiveForm: function (formName) {
          ApplyForm.active_apply_form_name = formName;
        },

        setGrecaptchaEnabled: function (value) {
            ApplyForm.grecaptcha_enabled = value;
        },

        setGrecaptchaResponse: function (response) {
            ApplyForm.grecaptcha_response = response;
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

        resetForm: function (form) {
            form[0].reset();
            form.find(".btn_upload, .file_name_placeholder").show();
            form.find(".btn_delete, .file_name").hide();
            ApplyForm.setActiveForm('');
        },

        successCallback: function(message){

        },

        failCallback: function(message){

        },

        submitActiveForm: function () {
            if(!ApplyForm.active_apply_form_name){
                return false
            }

            var $form = $('#' + ApplyForm.active_apply_form_name + '_form');
            var data = new FormData($form[0]);

            if (ApplyForm.grecaptcha_enabled) {
                data.append('grecaptcha_response', ApplyForm.grecaptcha_response);
            }
            
            $.ajax({
                url: ApplyForm.lang_prefix + '/apply-form/' + ApplyForm.active_apply_form_name,
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
                        ApplyForm.successCallback(response.message);
                    } else {
                        ApplyForm.failCallback(response.message);
                    }
                }
            });

            if (ApplyForm.grecaptcha_enabled) {
                ApplyForm.setGrecaptchaResponse('');
                ApplyForm.resetCaptcha();
            }
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