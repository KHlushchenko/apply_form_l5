<?php
namespace Vis\ApplyForm\Models;

use \App;

/** Message settings class for ApplyForm
 * Class ApplyFormSettingMessage
 * @package Vis\ApplyForm\Models
 */
class ApplyFormSettingMessage extends AbstractApplyFormSetting
{
    /** Defines settings table
     * @var string
     */
    protected $table = 'vis_apply_form_setting_messages';

    /** Gets value from ApplyFormSettingMessage
     * @param string $slug
     * @return array
     */
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
