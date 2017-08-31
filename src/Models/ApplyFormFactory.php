<?php
namespace Vis\ApplyForm\Models;

use \Exception;

/**
 * Class ApplyFormFactory
 * @package Vis\ApplyForm\Models
 */
class ApplyFormFactory
{
    /** Creates an instance of ApplyForm based on config
     * @param string $type
     * @return AbstractApplyForm
     * @throws Exception
     */
    public static function create(string $type): AbstractApplyForm
    {
        $applyForms = config('apply_form.apply_form.apply_forms');

        $applyForm = $applyForms[$type] ?? null;

        if (!$applyForm) {
            throw new Exception('Apply form type is not defined!');
        }

        if (!class_exists($applyForm)) {
            throw new Exception('Apply form class does not exist!');
        }

        return new $applyForm();
    } // end create
}
