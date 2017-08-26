<?php
namespace Vis\ApplyForms\Models;

use \Exception;

use App\Models\ApplyForms as ApplyForms;

class ApplyFormFactory
{
	public static function create(string $type): AbstractApplyForm
	{
		$applyForm = null;

		//fixme redo this shit
		switch ($type) {
            case 'anonymous_message':
                $applyForm = new ApplyForms\ApplyFormAnonymousMessage();
                break;
            case 'authorized_message':
                $applyForm = new ApplyForms\ApplyFormAuthorizedMessage();
                break;
            case 'callback':
                $applyForm = new ApplyForms\ApplyFormCallback();
                break;
            case 'feedback':
                $applyForm = new ApplyForms\ApplyFormFeedback();
                break;
            case 'partner':
                $applyForm = new ApplyForms\ApplyFormPartner();
                break;
            case 'resume':
                $applyForm = new ApplyForms\ApplyFormResume();
                break;
			case 'service_quality_rating':
				$applyForm = new ApplyForms\ApplyFormServiceQualityRating();
				break;
			default:
		}

		if(!$applyForm){
            throw new Exception('Apply form type is not defined!');
        }

		return $applyForm;
	} // end create
}
