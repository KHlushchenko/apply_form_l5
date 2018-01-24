<?php

namespace Vis\ApplyForm\Models;

/**
 * Email settings class for ApplyForm
 * Class ApplyFormSettingEmail
 * @package Vis\ApplyForm\Models
 */
class ApplyFormSettingEmail extends AbstractApplyFormSetting
{
    /**
     * Defines settings table
     * @var string
     */
    protected $table = 'vis_apply_form_setting_emails';

    /**
     * Gets value for retrieved ApplyFormSetting model
     * @param AbstractApplyFormSetting $record
     * @return mixed
     */
    protected function getValue(AbstractApplyFormSetting $record)
    {
        return $record->getAttribute('emails');
    }

}
