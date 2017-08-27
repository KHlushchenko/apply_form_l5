<?php

Route::pattern('formSlug', '[a-z-_]+');

Route::group(
    [
        'prefix'     => LaravelLocalization::setLocale(),
        'namespace'  => 'Vis\ApplyForm\Controllers',
    ],
    function () {
        if (Request::ajax()) {
            Route::post('/apply-form/{formSlug}', 'ApplyFormController@doApplyForm');
        }
    }
);
