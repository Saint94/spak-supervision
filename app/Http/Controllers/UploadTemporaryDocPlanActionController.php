<?php

namespace App\Http\Controllers;

use App\Models\TemporaryDocPlanAction;
use Illuminate\Http\Request;

class UploadTemporaryDocPlanActionController extends Controller
{
    public function __invoke(Request $request)
    {
        if($request->hasFile('SrDocumPlanAct'))
        {
            $doc_plan_action = $request->file('SrDocumPlanAct');
            $fileName = $doc_plan_action->getClientOriginalName();
            $folder = uniqid('docs-plan-actions-',true);

            $doc_plan_action->move(('assets/documents/plan-actions/'),$fileName);

            TemporaryDocPlanAction::Create([
                'folder' => $folder,
                'file' => $fileName
            ]);

            return $folder;
        }

        return '';
    }
}
