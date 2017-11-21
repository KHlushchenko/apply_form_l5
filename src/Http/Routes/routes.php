<?php

Route::pattern('formSlug', '[a-z-_]+');

Route::group(
    [
        'prefix'     => LaravelLocalization::setLocale(),
        'namespace'  => 'Vis\ApplyForm\Controllers',
        'middleware' => ['web'],
    ],
    function () {
        if (Request::ajax()) {
            Route::post('/apply-form/{formSlug}', 'ApplyFormController@doApplyForm');
        }
    }
);
