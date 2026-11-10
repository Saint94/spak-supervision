<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\TemporaryImageArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = DB::table('articles')
            ->leftJoin('fournisseurs','fournisseurs.FoNumero','=','articles.ArFournDef')
            ->leftJoin('users AS user_creer', 'user_creer.id', '=', 'articles.ArCreePar')
            ->leftJoin('users AS user_modifier', 'user_modifier.id', '=', 'articles.ArUserModif')
            ->where('articles.ArEntite',Auth::user()->entite)
            ->orderBy('articles.ArNom','asc')
            ->get();

        return view('articles.list',compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fournisseurs = DB::table('fournisseurs')
            ->where('fournisseurs.FoEntite', Auth::user()->entite)
            ->orderBy('fournisseurs.FoNom', 'asc')
            ->get();

        return view('articles.add',compact('fournisseurs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $article = DB::table('articles')
            ->where('articles.ArCode',$request->ArCode)
            ->where('articles.ArEntite',Auth::user()->entite)
            ->first();

        if(empty($article)) {

            DB::beginTransaction();

            try{
                // Do something and save to the db...
                $article = new Article();
                $article->ArEntite = Auth::user()->entite;
                $article->ArCode = strtoupper($request->ArCode);
                $article->ArNom = $request->ArNom;
                $article->ArType = $request->ArType;
                $article->ArIsCoulNoirJaunOn = $request->ArIsCoulNoirJaunOn;
                $article->ArPrixAchatCons = $request->ArPrixAchatCons;
                $article->ArPrixAchatMax = $request->ArPrixAchatMax;
                $article->ArIsPayeParAgent = $request->ArIsPayeParAgent ?? 'N';
                $article->ArPrixAgent = $request->ArPrixAgent ?? NULL;
                $article->ArQteStock = $request->ArQteStock ?? 0;
                $article->ArStockSeuil = $request->ArStockSeuil ?? 0;
                $article->ArFournDef = $request->ArFournDef ?? NULL;
                $article->ArIsOn = 'Y';
                $article->ArCreePar = Auth::user()->id;
                $article->ArDateHCree = date('Y-m-d H:i:s');
                $article->ArUserModif = NULL;
                $article->ArDateHModif = NULL;

                $temporaryImage = TemporaryImageArticle::where('folder',$request->ArPhoto)->first();

                if($temporaryImage)
                {
                    $article->ArPhoto = $temporaryImage->file;
                }

                $article->save();

                DB::commit();
            }
            catch (\Throwable $th){
                // An error occured; cancel the transaction...
                DB::rollback();
                // and throw the error again.
                throw $th;
            }

            DB::table('temporary_image_article')->truncate();
            return redirect()->route('articles.index')->with('alert', 'Article crée avec succès.');
        } else {
            return redirect()->route('articles.index')->with('alert-error', 'Cet article existe déjà.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $ArNumero)
    {
        return DB::table('articles')
            ->leftJoin('fournisseurs','fournisseurs.FoNumero','=','articles.ArFournDef')
            ->leftJoin('users AS user_creer', 'user_creer.id', '=', 'articles.ArCreePar')
            ->leftJoin('users AS user_modifier', 'user_modifier.id', '=', 'articles.ArUserModif')
            ->where('articles.ArNumero',$ArNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $ArNumero)
    {
        $ArNumero = decrypter($ArNumero);

        $article = DB::table('articles')
            ->where('articles.ArNumero',$ArNumero)
            ->first();

        $fournisseurs = DB::table('fournisseurs')
            ->where('fournisseurs.FoEntite', Auth::user()->entite)
            ->orderBy('fournisseurs.FoNom', 'asc')
            ->get();

        return view('articles.edit',compact('article','fournisseurs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $ArNumero)
    {
        $temporaryImage = TemporaryImageArticle::where('folder',$request->ArPhoto)->first();

        DB::beginTransaction();

        try{
            // Do something and save to the db...
            if($temporaryImage)
            {
                DB::table('articles')
                    ->where('articles.ArNumero',$ArNumero)
                    ->update([
                        'ArCode' => strtoupper($request->ArCode),
                        'ArNom' => $request->ArNom,
                        'ArType' => $request->ArType,
                        'ArIsCoulNoirJaunOn' => $request->ArIsCoulNoirJaunOn,
                        'ArPrixAchatCons' => $request->ArPrixAchatCons,
                        'ArPrixAchatMax' => $request->ArPrixAchatMax,
                        'ArIsPayeParAgent' => $request->ArIsPayeParAgent,
                        'ArPrixAgent' => $request->ArPrixAgent,
                        'ArPhoto' => $temporaryImage->file,
                        'ArQteStock' => $request->ArQteStock,
                        'ArStockSeuil' => $request->ArStockSeuil,
                        'ArFournDef' => $request->ArIsCoulNoirJaunOn,
                        'ArUserModif' => Auth::user()->id,
                        'ArDateHModif' => date('Y-m-d H:i:s')
                    ]);

            } else {

                DB::table('articles')
                    ->where('articles.ArNumero',$ArNumero)
                    ->update([
                        'ArCode' => strtoupper($request->ArCode),
                        'ArNom' => $request->ArNom,
                        'ArType' => $request->ArType,
                        'ArIsCoulNoirJaunOn' => $request->ArIsCoulNoirJaunOn,
                        'ArPrixAchatCons' => $request->ArPrixAchatCons,
                        'ArPrixAchatMax' => $request->ArPrixAchatMax,
                        'ArIsPayeParAgent' => $request->ArIsPayeParAgent,
                        'ArPrixAgent' => $request->ArPrixAgent,
                        'ArQteStock' => $request->ArQteStock,
                        'ArStockSeuil' => $request->ArStockSeuil,
                        'ArFournDef' => $request->ArIsCoulNoirJaunOn,
                        'ArUserModif' => Auth::user()->id,
                        'ArDateHModif' => date('Y-m-d H:i:s')
                    ]);
            }

            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        DB::table('temporary_image_article')->truncate();
        return redirect()->route('articles.index')->with('alert', 'Article modifié avec succès.');
    }

    public function activer(string $ArNumero)
    {
        DB::table('articles')
            ->where('articles.ArNumero',$ArNumero)
            ->update([
                'articles.ArIsOn' => 'Y',
                'ArUserModif' => Auth::user()->id,
                'ArDateHModif' => date('Y-m-d H:i:s')
            ]);
    }

    public function desactiver(string$ArNumero)
    {
        DB::table('articles')
            ->where('articles.ArNumero',$ArNumero)
            ->update([
                'articles.ArIsOn' => 'N',
                'ArUserModif' => Auth::user()->id,
                'ArDateHModif' => date('Y-m-d H:i:s')
            ]);
    }

    public function delete(string $ArNumero)
    {
        Article::find($ArNumero)->delete();
    }

}
