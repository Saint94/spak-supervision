<?php

namespace App\Http\Controllers;

use App\Models\TemporaryLogoEntite;
use Illuminate\Http\Request;

class UploadTemporaryLogoEntiteController extends Controller
{
    public function __invoke(Request $request)
    {
        if($request->hasFile('EnChemLogo'))
        {
            $piece_jointe = $request->file('EnChemLogo');
            $fileName = $piece_jointe->getClientOriginalName();
            $folder = uniqid('logos-entites-',true);

            $piece_jointe->move(('assets/img/entites/'),$fileName);

            TemporaryLogoEntite::Create([
                'folder' => $folder,
                'file' => $fileName
            ]);

            return $folder;
        }

        return '';
    }
}
