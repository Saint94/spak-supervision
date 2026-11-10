<?php

namespace App\Http\Controllers;

use App\Models\TemporaryDocEvenement;
use Illuminate\Http\Request;

class UploadTemporaryDocEvenementController extends Controller
{
    public function __invoke(Request $request)
    {
        if($request->hasFile('EvChemRapport'))
        {
            $doc_evenement = $request->file('EvChemRapport');
            $fileName = $doc_evenement->getClientOriginalName();
            $folder = uniqid('docs-evenements-',true);

            $doc_evenement->move(('assets/documents/evenements/'),$fileName);

            TemporaryDocEvenement::Create([
                'folder' => $folder,
                'file' => $fileName
            ]);

            return $folder;
        }

        return '';
    }
}
