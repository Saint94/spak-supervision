<?php

namespace App\Http\Controllers;

use App\Models\TemporaryImageArticle;
use Illuminate\Http\Request;

class UploadTemporaryImageArticleController extends Controller
{
    public function __invoke(Request $request)
    {
        if($request->hasFile('ArPhoto'))
        {
            $image_article = $request->file('ArPhoto');
            $fileName = $image_article->getClientOriginalName();
            $folder = uniqid('images-articles-',true);

            $image_article->move(('assets/img/articles/'),$fileName);

            TemporaryImageArticle::Create([
                'folder' => $folder,
                'file' => $fileName
            ]);

            return $folder;
        }

        return '';
    }
}
