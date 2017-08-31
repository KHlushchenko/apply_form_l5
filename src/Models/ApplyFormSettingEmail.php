<?php
namespace Vis\ApplyForm\Models;

/** Email settings class for ApplyForm
 * Class ApplyFormSettingEmail
 * @package Vis\ApplyForm\Models
 */
class ApplyFormSettingEmail extends AbstractApplyFormSetting
{
    /** Defines settings table
     * @var string
     */
    protected $table = 'vis_apply_form_setting_emails';

    /** Gets value from ApplyFormSettingEmail
     * @param string $slug
     * @return mixed
     */
    protected function getValue(string $slug)
    {
        return $this->whereSlug($slug)->value('emails');
    }

}
