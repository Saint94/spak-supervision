<?php

namespace App\Http\Controllers;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartEntreeStockController extends Controller
{
    public function store(Request $request)
    {
        $MsArticle = $request->MsArticle;

        $article = DB::table('articles')
            ->where('articles.ArNumero',$request->MsArticle)
            ->first();

        $doublon = Cart::instance('entree_stock')->search(function ($cartItem, $rowId) use ($MsArticle) {
            return $cartItem->id == $MsArticle;
        });

        if($doublon->IsNotEmpty())
        {
            foreach($doublon as $db){}
            $rowId = $db->rowId;
            $qte = $request->MsQuantite;

            Cart::instance('entree_stock')->remove($rowId);

            $cart = Cart::instance('entree_stock')
                ->add(1,"Entree stock",1,1,
                [
                    'MsArticle' => $request->MsArticle,
                    'ArCode' => $article->ArCode,
                    'ArNom' => $article->ArNom,
                    'ArType' => $article->ArType,
                    'ArIsCoulNoirJaunOn' => $article->ArIsCoulNoirJaunOn,
                    'ArPrixAchatCons' => $article->ArPrixAchatCons,
                    'ArPrixAchatMax' => $article->ArPrixAchatMax,
                    'ArPhoto' => $article-> ArPhoto,
                    'ArQteStock' => $article->ArQteStock,
                    'ArStockSeuil'=>$article->ArStockSeuil,
                    'MsQuantite'=>$request->MsQuantite,
                    'MsSensMvm'=>$request->MsSensMvm,
                    'MsQteApres'=>$request->MsQteApres,
                    'MsPrixArticle'=>$request->MsPrixArticle
                ]);

            return response()->json($cart);
        }
        else
        {
            $cart = Cart::instance('entree_stock')
                ->add(1,"Entree stock",1,1,
                [
                    'MsArticle' => $request->MsArticle,
                    'ArCode' => $article->ArCode,
                    'ArNom' => $article->ArNom,
                    'ArType' => $article->ArType,
                    'ArIsCoulNoirJaunOn' => $article->ArIsCoulNoirJaunOn,
                    'ArPrixAchatCons' => $article->ArPrixAchatCons,
                    'ArPrixAchatMax' => $article->ArPrixAchatMax,
                    'ArPhoto' => $article-> ArPhoto,
                    'ArQteStock' => $article->ArQteStock,
                    'ArStockSeuil'=>$article->ArStockSeuil,
                    'MsQuantite'=>$request->MsQuantite,
                    'MsSensMvm'=>$request->MsSensMvm,
                    'MsQteApres'=>$request->MsQteApres,
                    'MsPrixArticle'=>$request->MsPrixArticle
                ]);

            return response()->json($cart);
        }

    }

    public function viderPanier()
    {
        Cart::instance('entree_stock')->destroy();
        return redirect()->back();
    }


    public function remove(Request $request)
    {
        $rowId = $request->rowId;
        $cart = Cart::instance('entree_stock')->remove($rowId);
        return response()->json($cart);
    }
}
