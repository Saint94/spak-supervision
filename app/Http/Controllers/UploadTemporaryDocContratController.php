<?php

namespace App\Http\Controllers;

use App\Models\TemporaryDocContrat;
use Illuminate\Http\Request;

class UploadTemporaryDocContratController extends Controller
{
    public function __invoke(Request $request)
    {
        if($request->hasFile('AcChemDocContrat'))
        {
            $doc_contrat = $request->file('AcChemDocContrat');
            $fileName = $doc_contrat->getClientOriginalName();
            $folder = uniqid('docs-contrats-',true);

            $doc_contrat->move(('assets/documents/contrats/'),$fileName);

            TemporaryDocContrat::Create([
                'folder' => $folder,
                'file' => $fileName
            ]);

            return $folder;
        }

        return '';
    }
}
