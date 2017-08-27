<?php
namespace Vis\ApplyForm\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;

use Vis\ApplyForm\Models\ApplyFormFactory;

class ApplyFormController extends Controller
{
    public function doApplyForm(string $slug)
    {
        //fixme return response
        $applyForm = ApplyFormFactory::create($slug)->setInputData(Input::all());

        return response()->json([
            'status'  => $applyForm->apply(),
            'message' => $applyForm->getMessage(),
        ]);

    }

}
