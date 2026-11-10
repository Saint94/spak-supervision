<?php

namespace App\Http\Controllers;

use App\Models\Entite;
use App\Models\TemporaryLogoEntite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entites = DB::table('entites')
            ->leftJoin('personnels AS dg', 'dg.PeNumero', '=', 'entites.EnDG')
            ->leftJoin('personnels AS rt', 'rt.PeNumero', '=', 'entites.EnDT')
            ->leftJoin('personnels AS raf', 'raf.PeNumero', '=', 'entites.EnRAF')
            ->select(
                'entites.*',
                'dg.PeNumero  AS numero_dg',
                'dg.PeNom AS nom_dg',
                'dg.PePrenom AS prenom_dg',
                'rt.PeNumero  AS numero_rt',
                'rt.PeNom AS nom_rt',
                'rt.PePrenom AS prenom_rt',
                'raf.PeNumero  AS numero_raf',
                'raf.PeNom AS nom_raf',
                'raf.PePrenom AS prenom_raf'
            )
            ->orderBy('entites.EnNumero','asc')
            ->get();

        return view('entites.list',compact('entites'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $personnels_dg = DB::table('personnels')
            ->where('personnels.PeStatut','DG')
            ->orderBy('personnels.PeNumero','asc')
            ->get();

        $personnels_dt = DB::table('personnels')
            ->where('personnels.PeStatut','RT')
            ->orderBy('personnels.PeNumero','asc')
            ->get();

        $personnels_raf = DB::table('personnels')
            ->where('personnels.PeStatut','RAF')
            ->orderBy('personnels.PeNumero','asc')
            ->get();

        return view('entites.add',compact('personnels_dg','personnels_dt','personnels_raf'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $entite = DB::table('entites')
            ->where('entites.EnAbrge',$request->EnAbrge)
            ->first();

        if(empty($entite)) {
            DB::beginTransaction();

            try{
                // Do something and save to the db...
                $entite = new Entite();
                $entite->EnAbrge = $request->EnAbrge;
                $entite->EnNom = $request->EnNom;
                $entite->EnNomComplet = $request->EnNomComplet;
                $entite->EnBP = $request->EnBP;
                $entite->EnTel1 = $request->EnTel1;
                $entite->EnTel2 = $request->EnTel2;
                $entite->EnEmail = $request->EnEmail;
                $entite->EnSiteWeb = $request->EnSiteWeb;
                $entite->EnDG = $request->EnDG;
                $entite->EnDT = $request->EnDT;
                $entite->EnRAF = $request->EnRAF;
                $entite->EnDetails = $request->EnDetails;
                $entite->EnTxTVA = str_replace(',', '.',$request->EnTxTVA);
                $entite->EnNbMoisMaxImpClt = $request->EnNbMoisMaxImpClt ?? 0;
                $entite->EnPrixUMinAgentFixe = $request->EnPrixUMinAgentFixe ?? 0;
                $entite->EnPrixUMinMC = $request->EnPrixUMinMC ?? 0;
                $entite->EnPrixUMinSuperv = $request->EnPrixUMinSuperv ?? 0;
                $entite->EnPrixUMinComdo = $request->EnPrixUMinComdo ?? 0;

                $temporaryLogo = TemporaryLogoEntite::where('folder',$request->EnChemLogo)->first();

                if($temporaryLogo)
                {
                    $entite->EnChemLogo = $temporaryLogo->file;
                }

                $entite->save();

                DB::commit();
            }
            catch (\Throwable $th){
                // An error occured; cancel the transaction...
                DB::rollback();
                // and throw the error again.
                throw $th;
            }

            DB::table('temporary_logo_entite')->truncate();

            return redirect()->route('entites.index')->with('alert', 'Entité créée avec succès.');
        }
        else {
            return redirect()->route('entites.index')->with('alert-error', 'Ce entité existe déjà.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $EnNumero)
    {
        return DB::table('entites')
            ->leftJoin('personnels AS dg', 'dg.PeNumero', '=', 'entites.EnDG')
            ->leftJoin('personnels AS dt', 'dt.PeNumero', '=', 'entites.EnDT')
            ->leftJoin('personnels AS raf', 'raf.PeNumero', '=', 'entites.EnRAF')
            ->select(
                'entites.*',
                'dg.PeNumero  AS numero_dg',
                'dg.PeNom AS nom_dg',
                'dg.PePrenom AS prenom_dg',
                'dt.PeNumero  AS numero_dt',
                'dt.PeNom AS nom_dt',
                'dt.PePrenom AS prenom_dt',
                'raf.PeNumero  AS numero_raf',
                'raf.PeNom AS nom_raf',
                'raf.PePrenom AS prenom_raf'
            )
            ->where('entites.EnNumero',$EnNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $EnNumero)
    {
        $EnNumero = decrypter($EnNumero);

        $entite = DB::table('entites')
            ->where('entites.EnNumero',$EnNumero)
            ->first();

        $personnels_dg = DB::table('personnels')
            ->where('personnels.PeStatut','DG')
            ->orderBy('personnels.PeNumero','asc')
            ->get();

        $personnels_dt = DB::table('personnels')
            ->where('personnels.PeStatut','RT')
            ->orderBy('personnels.PeNumero','asc')
            ->get();

        $personnels_raf = DB::table('personnels')
            ->where('personnels.PeStatut','RAF')
            ->orderBy('personnels.PeNumero','asc')
            ->get();

        return view('entites.edit',compact('entite','personnels_dg','personnels_dt','personnels_raf'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $EnNumero)
    {
        $temporaryLogo = TemporaryLogoEntite::where('folder',$request->EnChemLogo)->first();

        DB::beginTransaction();

        try{
            // Do something and save to the db...
            if($temporaryLogo)
            {
                DB::table('entites')
                    ->where('entites.EnNumero',$EnNumero)
                    ->update([
                        'EnAbrge' => $request->EnAbrge,
                        'EnNom' => $request->EnNom,
                        'EnNomComplet' => $request->EnNomComplet,
                        'EnBP' => $request->EnBP,
                        'EnTel1' => $request->EnTel1,
                        'EnTel2' => $request->EnTel2,
                        'EnEmail' => $request->EnEmail,
                        'EnSiteWeb' => $request->EnSiteWeb,
                        'EnChemLogo' => $temporaryLogo->file,
                        'EnDG' => $request->EnDG,
                        'EnDT' => $request->EnDT,
                        'EnRAF' => $request->EnRAF,
                        'EnDetails' => $request->EnDetails,
                        'EnTxTVA' => str_replace(',', '.',$request->EnTxTVA),
                        'EnNbMoisMaxImpClt' => $request->EnNbMoisMaxImpClt,
                        'EnPrixUMinAgentFixe' => $request->EnPrixUMinAgentFixe ?? 0,
                        'EnPrixUMinMC' => $request->EnPrixUMinMC ?? 0,
                        'EnPrixUMinSuperv' => $request->EnPrixUMinSuperv ?? 0,
                        'EnPrixUMinComdo' => $request->EnPrixUMinComdo ?? 0
                    ]);
            }else {
                DB::table('entites')
                    ->where('entites.EnNumero',$EnNumero)
                    ->update([
                        'EnAbrge' => $request->EnAbrge,
                        'EnNom' => $request->EnNom,
                        'EnNomComplet' => $request->EnNomComplet,
                        'EnBP' => $request->EnBP,
                        'EnTel1' => $request->EnTel1,
                        'EnTel2' => $request->EnTel2,
                        'EnEmail' => $request->EnEmail,
                        'EnSiteWeb' => $request->EnSiteWeb,
                        'EnDG' => $request->EnDG,
                        'EnDT' => $request->EnDT,
                        'EnRAF' => $request->EnRAF,
                        'EnDetails' => $request->EnDetails,
                        'EnTxTVA' => str_replace(',', '.',$request->EnTxTVA),
                        'EnNbMoisMaxImpClt' => $request->EnNbMoisMaxImpClt,
                        'EnPrixUMinAgentFixe' => $request->EnPrixUMinAgentFixe ?? 0,
                        'EnPrixUMinMC' => $request->EnPrixUMinMC ?? 0,
                        'EnPrixUMinSuperv' => $request->EnPrixUMinSuperv ?? 0,
                        'EnPrixUMinComdo' => $request->EnPrixUMinComdo ?? 0
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


        DB::table('temporary_logo_entite')->truncate();

        return redirect()->route('entites.index')->with('alert', 'Entité modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function delete($EnNumero)
    {
        entite::find($EnNumero)->delete();
    }
}
