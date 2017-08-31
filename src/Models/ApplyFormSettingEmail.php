<?php
namespace Vis\ApplyForm\Models;

class ApplyFormSettingEmail extends AbstractApplyFormSetting
{
    protected $table = 'vis_apply_form_setting_emails';

    protected function getValue(string $slug)
    {
        return $this->whereSlug($slug)->value('emails');
    }

}
