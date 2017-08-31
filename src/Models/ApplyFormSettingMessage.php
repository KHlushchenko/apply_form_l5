<?php
namespace Vis\ApplyForm\Models;

use \App;

class ApplyFormSettingMessage extends AbstractApplyFormSetting
{
    protected $table = 'vis_apply_form_setting_messages';

    protected function getValue(string $slug)
    {
        $fieldPostFix = App::getLocale() !== config('translations.config.def_locale') ? "_" . App::getLocale() : '';
        $message = $this->whereSlug($slug)->first();

        return [
            'title'       => $message->{'message_title'.$fieldPostFix},
            'description' => $message->{'message_description'.$fieldPostFix},
        ];
    }

}
