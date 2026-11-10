<?php

namespace App\Http\Controllers;

use App\Models\TemporaryDocEnquete;
use Illuminate\Http\Request;

class UploadTemporaryDocEnqueteController extends Controller
{
    public function __invoke(Request $request)
    {
        if($request->hasFile('EcChemDocEnq'))
        {
            $doc_enquete = $request->file('EcChemDocEnq');
            $fileName = $doc_enquete->getClientOriginalName();
            $folder = uniqid('docs-enquetes-',true);

            $doc_enquete->move(('assets/documents/enquetes/'),$fileName);

            TemporaryDocEnquete::Create([
                'folder' => $folder,
                'file' => $fileName
            ]);

            return $folder;
        }

        return '';
    }
}
