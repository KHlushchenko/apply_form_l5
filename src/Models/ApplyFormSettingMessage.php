<?php

namespace Vis\ApplyForm\Models;

use \App;

/**
 * Message settings class for ApplyForm
 * Class ApplyFormSettingMessage
 * @package Vis\ApplyForm\Models
 */
class ApplyFormSettingMessage extends AbstractApplyFormSetting
{
    /**
     * Defines settings table
     * @var string
     */
    protected $table = 'vis_apply_form_setting_messages';

    /**
     * Gets value for retrieved ApplyFormSetting model
     * @param AbstractApplyFormSetting $record
     * @return mixed
     */
    protected function getValue(AbstractApplyFormSetting $record)
    {
        $fieldPostFix = App::getLocale() !== config('translations.config.def_locale') ? "_" . App::getLocale() : '';

        return [
            'title'       => $record->getAttribute('message_title'.$fieldPostFix),
            'description' => $record->getAttribute('message_description'.$fieldPostFix)
        ];
    }

}
