<?php

return [

    /**
     * List of apply forms to be registered in application
     * Example: 'anonymous_message'      => App\Models\ApplyForm\ApplyFormAnonymousMessage::class,
     */
    'apply_forms' => [

    ],

    /**
     * Defines usage of Google Invisible reCaptcha
     * @link https://www.google.com/recaptcha/admin
     */
    'grecaptcha' => [
        'enabled'    => false,
        'site_key'   => '',
        'secret_key' => ''
    ],

    /**
     * Enables transaction in ApplyForm saving method
     * Recommended false in development and true in production
     */
    'transaction_enabled' => true,

];