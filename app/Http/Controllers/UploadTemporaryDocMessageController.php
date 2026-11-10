<?php

namespace App\Http\Controllers;

use App\Models\TemporaryDocMessage;
use Illuminate\Http\Request;

class UploadTemporaryDocMessageController extends Controller
{
    public function __invoke(Request $request)
    {
        if($request->hasFile('MgChemDocFile'))
        {
            $doc_message = $request->file('MgChemDocFile');
            $fileName = $doc_message->getClientOriginalName();
            $folder = uniqid('docs-messages-',true);

            $doc_message->move(('assets/documents/messages/'),$fileName);

            TemporaryDocMessage::Create([
                'folder' => $folder,
                'file' => $fileName
            ]);

            return $folder;
        }

        return '';
    }
}
