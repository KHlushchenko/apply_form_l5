<?php
namespace Vis\ApplyForm\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;

use Vis\ApplyForm\Models\ApplyFormFactory;

/**
 * Class ApplyFormController
 * @package Vis\ApplyForm\Controllers
 */
class ApplyFormController extends Controller
{
    /** Entry point for applyForm
     * @param string $slug
     * @return mixed
     */
    public function doApplyForm(string $slug)
    {
        $applyForm = ApplyFormFactory::create($slug)->setInputData(Input::all());

        return response()->json([
            'status'  => $applyForm->apply(),
            'message' => $applyForm->getMessage(),
        ]);
    }

}
