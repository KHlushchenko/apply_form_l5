{!!  Minify::javascript(array(
    '/packages/vis/apply_form/apply_form.js',
    '/js/apply_form_rules.js'
))  !!}

<script>
    ApplyForm.setLangPrefix('{{App::getLocale() != config('translations.config.def_locale') ? "/" . App::getLocale() : ''}}')
    ApplyForm.setApplyForms({!!json_encode(array_keys(config('apply_form.apply_form.apply_forms')))!!})

    $(document).ready(function () {
        ApplyFormRules.init();
        ApplyForm.init();
    });
</script>

@if(config('apply_form.apply_form.grecaptcha.enabled'))
    @include('apply_form::grecaptcha')
@endif