{!!  Minify::javascript(array(
    '/packages/vis/apply_form/apply_form.js',
    '/js/apply_form_rules.js'
))  !!}

<script>
    ApplyForm.setApplyForms({!!json_encode(array_keys(config('apply_form.apply_form.apply_forms')))!!})
    ApplyForm.setGrecaptchaEnabled({{config('apply_form.apply_form.grecaptcha.enabled')}});

    $(document).ready(function () {
        ApplyFormRules.init();
        ApplyForm.init();
    });
</script>

@if(config('apply_form.apply_form.grecaptcha.enabled'))
    @include('apply_form::grecaptcha')
@endif