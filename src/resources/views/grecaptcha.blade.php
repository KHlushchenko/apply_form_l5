<script>
    function GrecaptchaCallback(response){
        ApplyForm.setGrecaptchaResponse(response);
        ApplyForm.submitActiveForm()
    }
</script>

<script src='https://www.google.com/recaptcha/api.js?hl={{App::getLocale() == 'ua' ? 'uk' : App::getLocale()}}' async defer></script>

<div class="g-recaptcha"
     data-sitekey="{{config('apply_form.apply_form.grecaptcha.site_key')}}"
     data-size="invisible"
     data-badge="inline"
     data-callback="GrecaptchaCallback"
     style="display:none"
>
</div>
