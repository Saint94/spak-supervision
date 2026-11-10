<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fournisseurs = DB::table('fournisseurs')
            ->leftJoin('users AS user_creer', 'user_creer.id', '=', 'fournisseurs.FoCreePar')
            ->leftJoin('users AS user_modifier', 'user_modifier.id', '=', 'fournisseurs.FoUserModif')
            ->where('fournisseurs.FoEntite', Auth::user()->entite)
            ->orderBy('fournisseurs.FoNom', 'asc')
            ->get();

        return view('fournisseurs.list', compact('fournisseurs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('fournisseurs.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fournisseur = DB::table('fournisseurs')
            ->where('fournisseurs.FoCode', $request->FoCode)
            ->where('fournisseurs.FoEntite', Auth::user()->entite)
            ->first();

        if (empty($fournisseur)) {

            DB::beginTransaction();

            try {
                $fournisseur = new Fournisseur();
                $fournisseur->FoEntite = Auth::user()->entite;
                $fournisseur->FoCode = strtoupper($request->FoCode);
                $fournisseur->FoNom = $request->FoNom;
                $fournisseur->FoAdresse = $request->FoAdresse ?? null;
                $fournisseur->FoTel1 = $request->FoTel1 ?? null;
                $fournisseur->FoTel2 = $request->FoTel2 ?? null;
                $fournisseur->FoEmail = $request->FoEmail ?? null;
                $fournisseur->FoVille = $request->FoVille ?? null;
                $fournisseur->FoPays = $request->FoPays ?? null;
                $fournisseur->FoIsTVAOn = $request->FoIsTVAOn ?? 'N';
                $fournisseur->FoIsOn = $request->FoIsOn ?? 'Y';
                $fournisseur->FoCreePar = Auth::user()->id;
                $fournisseur->FoDateHCree = date('Y-m-d H:i:s');
                $fournisseur->FoUserModif = null;
                $fournisseur->FoDateHModif = null;

                $fournisseur->save();

                DB::commit();
            } catch (\Throwable $th) {
                DB::rollback();
                throw $th;
            }

            return redirect()->route('fournisseurs.index')->with('alert', 'Fournisseur créé avec succès.');
        } else {
            return redirect()->route('fournisseurs.index')->with('alert-error', 'Ce fournisseur existe déjà.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $FoNumero)
    {
        return DB::table('fournisseurs')
            ->leftJoin('users AS user_creer', 'user_creer.id', '=', 'fournisseurs.FoCreePar')
            ->leftJoin('users AS user_modifier', 'user_modifier.id', '=', 'fournisseurs.FoUserModif')
            ->where('fournisseurs.FoNumero', $FoNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $FoNumero)
    {
        $FoNumero = decrypter($FoNumero);

        $fournisseur = DB::table('fournisseurs')
            ->where('fournisseurs.FoNumero', $FoNumero)
            ->first();

        return view('fournisseurs.edit', compact('fournisseur'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $FoNumero)
    {
        DB::beginTransaction();

        try {
            DB::table('fournisseurs')
                ->where('fournisseurs.FoNumero', $FoNumero)
                ->update([
                    'FoCode' => strtoupper($request->FoCode),
                    'FoNom' => $request->FoNom,
                    'FoAdresse' => $request->FoAdresse,
                    'FoTel1' => $request->FoTel1,
                    'FoTel2' => $request->FoTel2,
                    'FoEmail' => $request->FoEmail,
                    'FoVille' => $request->FoVille,
                    'FoPays' => $request->FoPays,
                    'FoIsTVAOn' => $request->FoIsTVAOn,
                    'FoIsOn' => $request->FoIsOn,
                    'FoEntite' => $request->FoEntite,
                    'FoUserModif' => Auth::user()->id,
                    'FoDateHModif' => date('Y-m-d H:i:s')
                ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

        return redirect()->route('fournisseurs.index')->with('alert', 'Fournisseur modifié avec succès.');
    }

    /**
     * Activate a fournisseur.
     */
    public function activer(string $FoNumero)
    {
        DB::table('fournisseurs')
            ->where('fournisseurs.FoNumero', $FoNumero)
            ->update([
                'FoIsOn' => 'Y',
                'FoUserModif' => Auth::user()->id,
                'FoDateHModif' => date('Y-m-d H:i:s')
            ]);
    }

    /**
     * Deactivate a fournisseur.
     */
    public function desactiver(string $FoNumero)
    {
        DB::table('fournisseurs')
            ->where('fournisseurs.FoNumero', $FoNumero)
            ->update([
                'FoIsOn' => 'N',
                'FoUserModif' => Auth::user()->id,
                'FoDateHModif' => date('Y-m-d H:i:s')
            ]);
    }

    /**
     * Delete a fournisseur.
     */
    public function delete(string $FoNumero)
    {
        Fournisseur::find($FoNumero)->delete();
    }
}
