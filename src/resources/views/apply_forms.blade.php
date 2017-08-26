{!!  Minify::javascript(array(
    '/packages/vis/apply_forms/apply_forms.js',
    '/js/apply_form_rules.js'
))  !!}

<script>
    ApplyForm.setModalTimeout({{Setting::get("vremja-otobrazhenija-modalnyh-okon-sekund")}});

    $(document).ready(function () {
        ApplyForm.init();
        ApplyFormRules.init();
    });
</script>

<script>
    function ReCaptchaCallback(token){
        ApplyForm.setReCaptchaToken(token);
        ApplyForm.submitActiveForm()
    }
</script>

<script src='https://www.google.com/recaptcha/api.js?hl={{App::getLocale() == 'ua' ? 'uk' : App::getLocale()}}' async defer></script>

<div class="g-recaptcha"
     data-sitekey="{{env('RE_CAPTCHA_SITE_KEY')}}"
     data-size="invisible"
     data-badge="inline"
     data-callback="ReCaptchaCallback"
     style="display:none"
>
</div>
