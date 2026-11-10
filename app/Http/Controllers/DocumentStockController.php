<?php

namespace App\Http\Controllers;

use App\Models\DocumentStock;
use App\Models\MouvementStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Gloudemans\Shoppingcart\Facades\Cart;

class DocumentStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents_stocks = DB::table('documents_stock')
            ->leftJoin('users AS user_creer', 'user_creer.id', '=', 'documents_stock.DsCreePar')
            ->leftJoin('users AS user_modifier', 'user_modifier.id', '=', 'documents_stock.DsUserModif')
            ->leftJoin('fournisseurs', 'fournisseurs.FoNumero', '=', 'documents_stock.DsFournBenef')
            ->where('documents_stock.DsEntite', Auth::user()->entite)
            ->orderBy('documents_stock.DsDate', 'desc')
            ->orderBy('documents_stock.DsNumero', 'desc')
            ->get();

        return view('documents-stock.list', compact('documents_stocks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_entree()
    {
        $articles = DB::table('articles')
            ->where('articles.ArEntite',Auth::user()->entite)
            ->orderBy('articles.ArNom','asc')
            ->get();

        $fournisseurs = DB::table('fournisseurs')
            ->where('fournisseurs.FoEntite', Auth::user()->entite)
            ->orderBy('fournisseurs.FoNom', 'asc')
            ->get();

        $cart_articles_entree  = Cart::instance('entree_stock')->content();

        return view('documents-stock.entree', compact('fournisseurs','cart_articles_entree','articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_sortie()
    {
        $articles = DB::table('articles')
            ->where('articles.ArEntite',Auth::user()->entite)
            ->orderBy('articles.ArNom','asc')
            ->get();

        $beneficiaires = DB::table('personnels')
            ->where('personnels.PeEntite', Auth::user()->entite)
            ->orderBy('personnels.PeNom', 'asc')
            ->get();

        $cart_articles_sortie  = Cart::instance('sortie_stock')->content();

        return view('documents-stock.sortie', compact('beneficiaires','cart_articles_sortie','articles'));
    }


    /**
     * Display a listing of the resource.
     */
    public function articles(string $DsNumero)
    {
        $DsNumero = decrypter($DsNumero);

        $document_stock = DB::table('documents_stock')
            ->leftJoin('fournisseurs', 'fournisseurs.FoNumero', '=', 'documents_stock.DsFournBenef')
            ->where('documents_stock.DsEntite', Auth::user()->entite)
            ->where('documents_stock.DsNumero', $DsNumero)
            ->first();

        $articles = DB::table('mouvements_stock')
            ->leftJoin('articles', 'articles.ArNumero', '=', 'mouvements_stock.MsArticle')
            ->where('mouvements_stock.MsDocumStock', $DsNumero)
            ->where('mouvements_stock.MsEntite', Auth::user()->entite)
            ->orderBy('mouvements_stock.MsNumero', 'desc')
            ->get();

        return view('documents-stock.articles', compact('articles','document_stock'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeEntree(Request $request)
    {
         DB::beginTransaction();

        try{
            // Do something and save to the db...
            $document_stock = new DocumentStock();
            $document_stock->DsDate = $request->DsDate;
            $document_stock->DsNature = 'E';
            $document_stock->DsTypeDest =NULL;
            $document_stock->DsFournBenef = $request->DsFournBenef;
            $document_stock->DsRefer = $request->DsRefer;
            $document_stock->DsMontantHT = $request->DsMontantHT;
            $document_stock->DsMontantTTC = $request->DsMontantTTC;
            $document_stock->DsIsAgentSigne = $request->DsIsAgentSigne;
            $document_stock->DsIsGestSigne = $request->DsIsGestSigne;
            $document_stock->DsIsRespOperSigne = $request->DsIsRespOperSigne;
            $document_stock->DsCreePar = Auth::user()->id;
            $document_stock->DsDateHCree = date('Y-m-d H:i:s');
            $document_stock->DsUserModif = NULL;
            $document_stock->DsDateHModif = NULL;
            $document_stock->DsEntite = Auth::user()->entite;
            $document_stock->save();


            $items = Cart::instance('entree_stock')->content();

            foreach($items as $item)
            {
                $mouvements[] =
                [
                    'MsDocumStock'=>$document_stock->DsNumero,
                    'MsArticle'=>$item->options->MsArticle ?? 0,
                    'MsQuantite'=>$item->options->MsQuantite ?? 0,
                    'MsSensMvm'=>'E',
                    'MsQteApres'=>$item->options->MsNewStock ?? 0,
                    'MsPrixArticle'=>$item->options->MsPrixArticle,
                    'MsEntite'=>$document_stock->DsEntite
                ];

                DB::table('articles')
                    ->where('articles.ArNumero',$item->options->MsArticle)
                    ->update([
                        'articles.ArQteStock' => $item->options->MsNewStock
                    ]);
            }

            MouvementStock::insert($mouvements);

            Cart::instance('entree_stock')->destroy();

            // Commit the transaction
            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('documents-stock.index')->with('alert', 'Document d\'entrée de stock créé avec succès.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeSortie(Request $request)
    {
        // Cette méthode est gérée par les contrôleurs spécifiques
        return redirect()->route('documents-stock.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $DsNumero)
    {
        return DB::table('documents_stock')
            ->leftJoin('users AS user_creer', 'user_creer.id', '=', 'documents_stock.DsCreePar')
            ->leftJoin('users AS user_modifier', 'user_modifier.id', '=', 'documents_stock.DsUserModif')
            ->where('documents_stock.DsNumero', $DsNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $DsNumero)
    {
        // Cette méthode est gérée par les contrôleurs spécifiques
        $DsNumero = decrypter($DsNumero);

        $document = DB::table('documents_stock')
            ->where('documents_stock.DsNumero', $DsNumero)
            ->first();

        if ($document->DsNature == 'E') {
            return redirect()->route('mouvement-entree.edit', crypter($DsNumero));
        } else {
            return redirect()->route('mouvement-sortie.edit', crypter($DsNumero));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $DsNumero)
    {
        // Cette méthode est gérée par les contrôleurs spécifiques
        return redirect()->route('documents-stock.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $DsNumero)
    {
        try {
            DB::beginTransaction();

            // Supprimer d'abord les lignes de document (à adapter selon votre structure)
            // DB::table('documents_stock_lignes')->where('DslDocument', $DsNumero)->delete();

            // Supprimer le document
            DocumentStock::find($DsNumero)->delete();

            DB::commit();

            return redirect()->route('documents-stock.index')->with('alert', 'Document supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('documents-stock.index')->with('alert-error', 'Erreur lors de la suppression.');
        }
    }

    /**
     * Filter documents by date and nature.
     */
    public function filtre(Request $request)
    {
        $query = DB::table('documents_stock')
            ->leftJoin('users AS user_creer', 'user_creer.id', '=', 'documents_stock.DsCreePar')
            ->leftJoin('users AS user_modifier', 'user_modifier.id', '=', 'documents_stock.DsUserModif')
            ->where('documents_stock.DsEntite', Auth::user()->entite);

        // Filtre par date
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('documents_stock.DsDate', [$request->start_date, $request->end_date]);
        } elseif ($request->start_date) {
            $query->where('documents_stock.DsDate', '>=', $request->start_date);
        } elseif ($request->end_date) {
            $query->where('documents_stock.DsDate', '<=', $request->end_date);
        }

        // Filtre par nature
        if ($request->DsNature && $request->DsNature != 'all') {
            $query->where('documents_stock.DsNature', $request->DsNature);
        }

        $documents_stocks = $query->orderBy('documents_stock.DsDate', 'desc')
            ->orderBy('documents_stock.DsNumero', 'desc')
            ->select('documents_stock.*',
                'user_creer.name as nom_createur',
                'user_modifier.name as nom_modificateur')
            ->get();

        return view('documents-stock.list', compact('documents_stocks'));
    }


    /**
     * Print a document.
     */
    public function print(string $DsNumero)
    {
        $document = DB::table('documents_stock')
            ->leftJoin('users AS user_creer', 'user_creer.id', '=', 'documents_stock.DsCreePar')
            ->where('documents_stock.DsNumero', $DsNumero)
            ->where('documents_stock.DsEntite', Auth::user()->entite)
            ->select('documents_stock.*', 'user_creer.name as nom_createur')
            ->first();

        if (!$document) {
            return redirect()->route('documents-stock.index')->with('alert-error', 'Document non trouvé.');
        }

        // Récupérer les lignes du document (à adapter selon votre structure)
        // $lignes = DB::table('documents_stock_lignes')
        //     ->where('DslDocument', $DsNumero)
        //     ->get();

        return view('documents-stock.print', compact('document'));
    }


    public function show_info_article($MsArticle)
    {
        $article = DB::table('articles')
            ->where('articles.ArNumero',$MsArticle)
            ->first();

        $str ='';



        $str.='
        <input type="hidden" id="prix_max" value="'.$article->ArPrixAchatMax.'" readonly/>
        <input type="hidden" id="qte_stock" value="'.$article->ArQteStock.'" readonly/>


        <div class="col-xl-3 col-md-3">
            <div class="form-group">
                <label for="">Code article</label>
                <input type="text" class="form-control" value="'.$article->ArCode.'" readonly/>
            </div>
        </div>

        <div class="col-xl-3 col-md-3">
            <div class="form-group">
                <label for="">Nom article</label>
                <input type="text" class="form-control" value="'.$article->ArNom.'" readonly/>
            </div>
        </div>';

        if($article->ArType == 'EPI') {
            $str.='<div class="col-xl-3 col-md-3">
                <div class="form-group">
                    <label for="">Type article</label>
                    <input type="text" class="form-control" value="EPI" readonly/>
                </div>
            </div>';
        } elseif($article->ArType == 'MB') {
             $str.='<div class="col-xl-3 col-md-3">
                <div class="form-group">
                    <label for="">Type article</label>
                    <input type="text" class="form-control" value="Matériels de bureau" readonly/>
                </div>
            </div>';
        } elseif($article->ArType == 'PM') {
             $str.='<div class="col-xl-3 col-md-3">
                <div class="form-group">
                    <label for="">Type article</label>
                    <input type="text" class="form-control" value="Pièces de moto" readonly/>
                </div>
            </div>';
        } elseif($article->ArType == 'PV') {
             $str.='<div class="col-xl-3 col-md-3">
                <div class="form-group">
                    <label for="">Type article</label>
                    <input type="text" class="form-control" value="Pièces de véhicules" readonly/>
                </div>
            </div>';
        } elseif($article->ArType == 'MC') {
             $str.='<div class="col-xl-3 col-md-3">
                <div class="form-group">
                    <label for="">Type article</label>
                    <input type="text" class="form-control" value="Matériels de chantier" readonly/>
                </div>
            </div>';
        } else {
            $str.='<div class="col-xl-3 col-md-3">
                <div class="form-group">
                    <label for="">Type article</label>
                    <input type="text" class="form-control" value="Consommables informatiques" readonly/>
                </div>
            </div>';
        }

        if($article->ArIsCoulNoirJaunOn == 'Y') {
            $str.='<div class="col-xl-3 col-md-3">
                <div class="form-group">
                    <label for="">Jaune/Noire activée ?</label>
                    <input type="text" class="form-control" value="Oui" readonly/>
                </div>
            </div>';
        } else {
            $str.='<div class="col-xl-3 col-md-3">
                <div class="form-group">
                    <label for="">Jaune/Noire activée ?</label>
                    <input type="text" class="form-control" value="Non" readonly/>
                </div>
            </div>';
        }


        $str.='<div class="col-xl-3 col-md-3">
            <div class="form-group">
                <label for="">Prix article conseillé</label>
                <input type="text" class="form-control" value="'.currency_xaf($article->ArPrixAchatCons).'" readonly/>
            </div>
        </div>

        <div class="col-xl-3 col-md-3">
            <div class="form-group">
                <label for="">Prix max article </label>
                <input type="text" class="form-control" value="'.currency_xaf($article->ArPrixAchatMax).'" readonly/>
            </div>
        </div>

        <div class="col-xl-3 col-md-3">
            <div class="form-group">
                <label for="">Qte stock article</label>
                <input type="text" class="form-control" value="'.$article->ArQteStock.'" readonly/>
            </div>
        </div>

        <div class="col-xl-3 col-md-3">
            <div class="form-group">
                <label for="">Seuil stock article</label>
                <input type="text" class="form-control" value="'.$article->ArStockSeuil.'" readonly/>
            </div>
        </div>';

        $str.='';

        echo $str;
    }

    public function show_info_fournisseur($DsFournBenef)
    {
        $fournisseur = DB::table('fournisseurs')
            ->leftJoin('entites', 'entites.EnNumero', '=', 'fournisseurs.FoEntite')
            ->where('fournisseurs.FoNumero',$DsFournBenef)
            ->first();

        $cart_articles_entree  = Cart::instance('entree_stock')->content();
        $mt_total = 0;

        foreach($cart_articles_entree as $item) {
            $mt_total=$item->options->MsPrixArticle*$item->options->MsQuantite;
        }

        if($fournisseur->FoIsTVAOn == 'Y') {
            if(!empty($fournisseur->EnTxTV)) {
                $mt_ht = (($mt_total*$fournisseur->EnTxTV)/100);
                $mt_ttc = $mt_total + $mt_ht;
            } else {
                $mt_ht = $mt_total;
                $mt_ttc = $mt_total;
            }
        } else {
            $mt_ht = $mt_total;
            $mt_ttc = $mt_total;
        }

        $str ='';

        $str.='
        <input type="hidden" id="DsMontantHT" name="DsMontantHT" value="'.$mt_ht.'" readonly/>
        <input type="hidden" id="DsMontantTTC" name="DsMontantTTC" value="'.$mt_ttc.'" readonly/>

        <div class="col-xl-6 col-md-6">
            <div class="form-group">
                <label for="DsMontantHT">Montant HT de la facture</label>
                <input type="text" class="form-control" value="'.currency_xaf($mt_ht).'" placeholder="Montant HT" readonly/>
            </div>
        </div>

        <div class="col-xl-6 col-md-6">
            <div class="form-group">
                <label for="DsMontantTTC">Montant TTC de la facture</label>
                <input type="text" class="form-control" value="'.currency_xaf($mt_ttc).'" placeholder="Montant TTC" readonly/>
            </div>
        </div>';

        $str.='';

        echo $str;
    }
}
