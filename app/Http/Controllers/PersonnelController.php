<?php

namespace App\Http\Controllers;

use App\Imports\PersonnelImport;
use App\Models\Personnel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Excel;

class PersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /*$lastPostings = DB::table('poster_vigile as pv1')
            ->join(DB::raw('
                (SELECT PvVigile, MAX(PvNumero) as max_id
                FROM poster_vigile
                GROUP BY PvVigile) as pv2
            '), function($join) {
                $join->on('pv1.PvVigile', '=', 'pv2.PvVigile')
                    ->on('pv1.PvNumero', '=', 'pv2.max_id');
            })
            ->where('pv1.PvEntite', Auth::user()->entite)
            ->get();

        for($i=0; $i <count($lastPostings) ; $i++) {
            if(condition) {

                DB::table('personnels')
                    ->where('personnels.PeNumero',$lastPostings[$i]->PvVigile)
                    ->update([
                        'personnels.PeSitePoste' => $lastPostings[$i]->PvSiteClient
                    ]);
            }
        }*/

        $personnels = DB::table('personnels')
            ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
            ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->leftJoin('entites','entites.EnNumero','=','personnels.PeEntite')
            ->leftJoin('poster_vigile','poster_vigile.PvVigile','=','personnels.PeNumero')
            ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->orderBy('personnels.PeMatric','asc')
            ->get();

        $villes = DB::table('villes')
            ->leftJoin('personnels','personnels.PeNumero','=','villes.ViRespOper')
            ->where('villes.ViEntite',Auth::user()->entite)
            ->orderBy('villes.ViNom','asc')
            ->get();

        $zones = DB::table('zones')
            ->leftJoin('villes','villes.ViNumero','=','zones.ZoVille')
            ->where('zones.ZoEntite',Auth::user()->entite)
            ->orderBy('zones.ZoNom','asc')
            ->get();

        $quartiers = DB::table('quartiers')
            ->leftJoin('villes','villes.ViNumero','=','quartiers.QuVille')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->where('quartiers.QuEntite',Auth::user()->entite)
            ->orderBy('quartiers.QuNom','asc')
            ->get();

        $sites_client = DB::table('sites_client')
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->orderBy('sites_client.ScNomSite','asc')
            ->get();

        return view('personnels.list',compact('personnels','villes','zones','quartiers','sites_client'));
    }


    /**
     * Display a listing of the resource.
    */
    public function controleurs()
    {
        $personnels = DB::table('personnels')
            ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
            ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->leftJoin('entites','entites.EnNumero','=','personnels.PeEntite')
            ->leftJoin('poster_vigile','poster_vigile.PvVigile','=','personnels.PeNumero')
            ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
            ->where('personnels.PeStatut','CT')
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->orderBy('personnels.PeMatric','asc')
            ->get();

        return view('personnels.controleurs',compact('personnels'));
    }

    /**
     * Display a listing of the resource.
    */
    public function superviseur()
    {
        $personnels = DB::table('personnels')
            ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
            ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->leftJoin('entites','entites.EnNumero','=','personnels.PeEntite')
            ->leftJoin('poster_vigile','poster_vigile.PvVigile','=','personnels.PeNumero')
            ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
            ->whereIn('personnels.PeStatut',['SG','SA'])
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->orderBy('personnels.PeMatric','asc')
            ->get();

        return view('personnels.superviseur',compact('personnels'));
    }

    /**
     * Display a listing of the resource.
    */
    public function commando()
    {
        $personnels = DB::table('personnels')
            ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
            ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->leftJoin('entites','entites.EnNumero','=','personnels.PeEntite')
            ->leftJoin('poster_vigile','poster_vigile.PvVigile','=','personnels.PeNumero')
            ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
            ->where('personnels.PeStatut','CM')
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->orderBy('personnels.PeMatric','asc')
            ->get();

        return view('personnels.commando',compact('personnels'));
    }


    /**
     * Display a listing of the resource.
    */
    public function chef_equipe()
    {
        $personnels = DB::table('personnels')
            ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
            ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->leftJoin('entites','entites.EnNumero','=','personnels.PeEntite')
            ->leftJoin('poster_vigile','poster_vigile.PvVigile','=','personnels.PeNumero')
            ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
            ->where('personnels.PeStatut','E')
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->orderBy('personnels.PeMatric','asc')
            ->get();

        return view('personnels.chef_equipe',compact('personnels'));
    }

    /**
     * Display a listing of the resource.
    */
    public function maitre_chien()
    {
        $personnels = DB::table('personnels')
            ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
            ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->leftJoin('entites','entites.EnNumero','=','personnels.PeEntite')
            ->leftJoin('poster_vigile','poster_vigile.PvVigile','=','personnels.PeNumero')
            ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
            ->where('personnels.PeStatut','C')
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->orderBy('personnels.PeMatric','asc')
            ->get();

        return view('personnels.maitre_chien',compact('personnels'));
    }


    /**
     * Display a listing of the resource.
    */
    public function agent_fixe()
    {
        $personnels = DB::table('personnels')
            ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
            ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->leftJoin('entites','entites.EnNumero','=','personnels.PeEntite')
            ->leftJoin('poster_vigile','poster_vigile.PvVigile','=','personnels.PeNumero')
            ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
            ->where('personnels.PeStatut','F')
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->orderBy('personnels.PeMatric','asc')
            ->get();

        return view('personnels.agent_fixe',compact('personnels'));
    }

    /**
     * Display a listing of the resource.
    */
    public function rondier()
    {
        $personnels = DB::table('personnels')
            ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
            ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->leftJoin('entites','entites.EnNumero','=','personnels.PeEntite')
            ->leftJoin('poster_vigile','poster_vigile.PvVigile','=','personnels.PeNumero')
            ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
            ->where('personnels.PeStatut','R')
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->orderBy('personnels.PeMatric','asc')
            ->get();

        return view('personnels.rondier',compact('personnels'));
    }


    /**
     * Display a listing of the resource.
    */
    public function stand_by()
    {
        $personnels = DB::table('personnels')
            ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
            ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->leftJoin('entites','entites.EnNumero','=','personnels.PeEntite')
            ->leftJoin('poster_vigile','poster_vigile.PvVigile','=','personnels.PeNumero')
            ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
            ->where('personnels.PeStatut','B')
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->orderBy('personnels.PeMatric','asc')
            ->get();

        return view('personnels.stand_by',compact('personnels'));
    }

    /**
     * Display a listing of the resource.
    */
    public function postulants()
    {
        $personnels = DB::table('personnels')
            ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
            ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->leftJoin('entites','entites.EnNumero','=','personnels.PeEntite')
            ->leftJoin('poster_vigile','poster_vigile.PvVigile','=','personnels.PeNumero')
            ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
            ->where('personnels.PeStatut','PS')
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->get();

        return view('personnels.postulants',compact('personnels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $villes = DB::table('villes')
            ->leftJoin('personnels','personnels.PeNumero','=','villes.ViRespOper')
            ->where('villes.ViEntite',Auth::user()->entite)
            ->orderBy('villes.ViNom','asc')
            ->get();

        $fonctions = DB::table('fonctions')
            ->where('fonctions.FnIsOn','Y')
            ->where('fonctions.FnEntite',Auth::user()->entite)
            ->orderBy('fonctions.FnNom','asc')
            ->get();

        return view('personnels.add',compact('villes','fonctions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $personnel = DB::table('personnels')
            ->where('personnels.PeMatric',$request->PeMatric)
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->first();

        if(empty($personnel)) {
            DB::beginTransaction();

            try{
                // Do something and save to the db...

                if($request->PeStatut == 'R') {
                    $PeIsRondier = 'Y';
                } else {
                    $PeIsRondier = 'N';
                }

                $personnel = new Personnel();
                $personnel->PeMatric = strtoupper($request->PeMatric);
                $personnel->PeNom = strtoupper($request->PeNom);
                $personnel->PePrenom = $request->PePrenom;
                $personnel->PeFonction = $request->PeFonction;
                $personnel->PeTypePiece = $request->PeTypePiece;
                $personnel->PeDateEmission = $request->PeDateEmission;
                $personnel->PeLieuEmission = $request->PeLieuEmission;
                $personnel->PeDateExpire = $request->PeDateExpire;
                $personnel->PeStatut = $request->PeStatut;
                $personnel->PeIsSupervOn = $request->PeIsSupervOn;
                $personnel->PeDateDebut = $request->PeDateDebut;
                $personnel->PeTel1 = $request->PeTel1;
                $personnel->PeTel2 = $request->PeTel2;
                $personnel->PeEmail = $request->PeEmail;
                $personnel->PeNumPieceID = $request->PeNumPieceID;
                $personnel->PeDateEmb = $request->PeDateEmb;
                $personnel->PeMaladie = $request->PeMaladie;
                $personnel->PeMaladieChron = $request->PeMaladieChron;
                $personnel->PeHopotalVisite = $request->PeHopotalVisite;
                $personnel->PeAptitudTechn = $request->PeAptitudTechn;
                $personnel->PePrefTravJN = $request->PePrefTravJN;
                $personnel->PeLastDiplom = $request->PeLastDiplom;
                $personnel->PeNivLect = $request->PeNivLect;
                $personnel->PeNivEcr = $request->PeNivEcr;
                $personnel->PeTaille = $request->PeTaille;
                $personnel->PePoids = $request->PePoids;
                $personnel->PeGabarit = $request->PeGabarit;
                $personnel->PeSpecialite = $request->PeSpecialite;
                $personnel->PePositionGPSLatX = $request->PePositionGPSLatX;
                $personnel->PePositionGPSLongY = $request->PePositionGPSLongY;
                $personnel->PeVillage = $request->PeVillage;
                $personnel->PeNomParrain = $request->PeNomParrain;
                $personnel->PeTel1Parr = $request->PeTel1Parr;
                $personnel->PeTel2Parr = $request->PeTel2Parr;
                $personnel->PeGPSLatXParr = $request->PeGPSLatXParr;
                $personnel->PeGPSLongYParr = $request->PeGPSLongYParr;
                $personnel->PeLienParrain = $request->PeLienParrain;
                $personnel->PeSitFam = $request->PeSitFam;
                $personnel->PeNomCompagne = $request->PeNomCompagne;
                $personnel->PeTel1Compagne = $request->PeTel1Compagne;
                $personnel->PeTel2Compagne = $request->PeTel2Compagne;
                $personnel->PeNbEnfants = $request->PeNbEnfants;
                $personnel->PeNomEcolEnfant = $request->PeNomEcolEnfant;
                $personnel->PePretenSal = $request->PePretenSal;
                $personnel->PeNatureEmpl = $request->PeNatureEmpl;
                $personnel->PeIsRondier = $PeIsRondier;
                $personnel->PeJourRepos = $request->PeJourRepos;
                $personnel->PeSitePoste =NULL;
                $personnel->PeIsAppliOn = $request->PeIsAppliOn;
                $personnel->PeAppAccesID = $request->PeAppAccesID;
                $personnel->PeAppAccessMDP = $request->PeAppAccessMDP;
                $personnel->PeEntite = Auth::user()->entite;
                $personnel->save();

                $poster_vigile =DB::table('poster_vigile')
                    ->where('poster_vigile.PvVigile',$personnel->PeNumero)
                    ->orderBy('poster_vigile.PvNumero','desc')
                    ->first();

                if($poster_vigile) {
                    DB::table('personnels')
                        ->where('personnels.PeNumero',$personnel->PeNumero)
                        ->update([
                            'personnels.PeSitePoste' => $poster_vigile->PvSiteClient
                        ]);
                }

                if(($personnel->PeStatut == 'SG' || $personnel->PeStatut == 'SA') && $personnel->PeIsAppliOn == 'Y') {

                    $user = new User();
                    $user->type = 'S';
                    $user->noms = strtoupper($personnel->PeNom);
                    $user->prenoms = $personnel->PePrenom;
                    $user->tel1 = $personnel->PeTel1 ;
                    $user->tel2 = $personnel->PeTel2;
                    $user->email = $personnel->PeEmail;
                    $user->fonction = NULL;
                    $user->username = $personnel->PeAppAccesID;
                    $user->password = Hash::make($personnel->PeAppAccessMDP);
                    $user->mot_de_passe = $personnel->PeAppAccessMDP;
                    $user->statut = 'Y';
                    $user->reset_password = 'N';
                    $user->entite = $personnel->PeEntite;
                    $user->personnel = $personnel->PeNumero;
                    $user->save();

                } else if(($personnel->PeStatut == 'CT') && $personnel->PeIsAppliOn == 'Y') {

                    $user = new User();
                    $user->type = 'T';
                    $user->noms = strtoupper($personnel->PeNom);
                    $user->prenoms = $personnel->PePrenom;
                    $user->tel1 = $personnel->PeTel1 ;
                    $user->tel2 = $personnel->PeTel2;
                    $user->email = $personnel->PeEmail;
                    $user->fonction = NULL;
                    $user->username = $personnel->PeAppAccesID;
                    $user->password = Hash::make($personnel->PeAppAccessMDP);
                    $user->mot_de_passe = $personnel->PeAppAccessMDP;
                    $user->statut = 'Y';
                    $user->reset_password = 'N';
                    $user->entite = $personnel->PeEntite;
                    $user->personnel = $personnel->PeNumero;
                    $user->save();

                } else {
                    /*------------------*/
                }


                DB::commit();
            }
            catch (\Throwable $th){
                // An error occured; cancel the transaction...
                DB::rollback();
                // and throw the error again.
                throw $th;
            }

            return redirect()->route('personnels.index')->with('alert', 'Personnel crée avec succès.');
        }
        else {
            return redirect()->route('personnels.index')->with('alert-error', 'Ce personnel existe déjà.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $PeNumero)
    {
        return DB::table('personnels')
            ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
            ->leftJoin('entites','entites.EnNumero','=','personnels.PeEntite')
            ->leftJoin('poster_vigile','poster_vigile.PvVigile','=','personnels.PeNumero')
            ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
            ->where('personnels.PeNumero',$PeNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $PeNumero)
    {
        $PeNumero = decrypter($PeNumero);

        $personnel = DB::table('personnels')
            ->where('personnels.PeNumero',$PeNumero)
            ->first();

        $villes = DB::table('villes')
            ->leftJoin('personnels','personnels.PeNumero','=','villes.ViRespOper')
            ->where('villes.ViEntite',Auth::user()->entite)
            ->orderBy('villes.ViNom','asc')
            ->get();

        $fonctions = DB::table('fonctions')
            ->where('fonctions.FnIsOn','Y')
            ->where('fonctions.FnEntite',Auth::user()->entite)
            ->orderBy('fonctions.FnNom','asc')
            ->get();

        $quartiers = DB::table('quartiers')
            ->where('quartiers.QuVille',$personnel->PeVille)
            ->where('quartiers.QuEntite',Auth::user()->entite)
            ->orderBy('quartiers.QuNom','asc')
            ->get();

        return view('personnels.edit',compact('personnel','villes','quartiers','fonctions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $PeNumero)
    {
        DB::beginTransaction();

        if($request->PeStatut == 'R') {
            $PeIsRondier = 'Y';
        } else {
            $PeIsRondier = 'N';
        }

        try{
            // Do something and save to the db...
            DB::table('personnels')
                ->where('personnels.PeNumero',$PeNumero)
                ->update([
                    'PeMatric' => strtoupper($request->PeMatric),
                    'PeNom' => strtoupper($request->PeNom),
                    'PePrenom' => $request->PePrenom,
                    'PeFonction' => $request->PeFonction,
                    'PeTypePiece' => $request->PeTypePiece,
                    'PeDateEmission' => $request->PeDateEmission,
                    'PeLieuEmission' => $request->PeLieuEmission,
                    'PeDateExpire' => $request->PeDateExpire,
                    'PeStatut' => $request->PeStatut,
                    'PeIsSupervOn' => $request->PeIsSupervOn,
                    'PeDateDebut' => $request->PeDateDebut,
                    'PeTel1' => $request->PeTel1,
                    'PeTel2' => $request->PeTel2,
                    'PeEmail' => $request->PeEmail,
                    'PeQuartier' => $request->PeQuartier,
                    'PeVille' => $request->PeVille,
                    'PeNumPieceID' => $request->PeNumPieceID,
                    'PeDateEmb' => $request->PeDateEmb,
                    'PeMaladie' => $request->PeMaladie,
                    'PeMaladieChron' => $request->PeMaladieChron,
                    'PeHopotalVisite' => $request->PeHopotalVisite,
                    'PeAptitudTechn' => $request->PeAptitudTechn,
                    'PePrefTravJN' => $request->PePrefTravJN,
                    'PeLastDiplom' => $request->PeLastDiplom,
                    'PeNivLect' => $request->PeNivLect,
                    'PeNivEcr' => $request->PeNivEcr,
                    'PeTaille' => $request->PeTaille,
                    'PePoids' => $request->PePoids,
                    'PeGabarit' => $request->PeGabarit,
                    'PeSpecialite' => $request->PeSpecialite,
                    'PePositionGPSLatX' => $request->PePositionGPSLatX,
                    'PePositionGPSLongY' => $request->PePositionGPSLongY,
                    'PeVillage' => $request->PeVillage,
                    'PeNomParrain' => $request->PeNomParrain,
                    'PeTel1Parr' => $request->PeTel1Parr,
                    'PeTel2Parr' => $request->PeTel2Parr,
                    'PeGPSLatXParr' => $request->PeGPSLatXParr,
                    'PeGPSLongYParr' => $request->PeGPSLongYParr,
                    'PeLienParrain' => $request->PeLienParrain,
                    'PeSitFam' => $request->PeSitFam,
                    'PeNomCompagne' => $request->PeNomCompagne,
                    'PeTel1Compagne' => $request->PeTel1Compagne,
                    'PeTel2Compagne' => $request->PeTel2Compagne,
                    'PeNbEnfants' => $request->PeNbEnfants,
                    'PeNomEcolEnfant' => $request->PeNomEcolEnfant,
                    'PePretenSal' => $request->PePretenSal,
                    'PeNatureEmpl' => $request->PeNatureEmpl,
                    'PeIsRondier' => $PeIsRondier,
                    'PeJourRepos' => $request->PeJourRepos,
                    'PeDomicile' => $request->PeDomicile,
                    'PeUserModif' => Auth::user()->id,
                    'PeDateHModif' => date('Y-m-d H:i:s'),
                    'PeIsOn' => $request->PeIsOn,
                    'PeIsAppliOn' => $request->PeIsAppliOn,
                    'PeAppAccesID' => $request->PeAppAccesID,
                    'PeAppAccessMDP' => $request->PeAppAccessMDP,
                    'PeDetails' => $request->PeDetails
                ]);

                $poster_vigile =DB::table('poster_vigile')
                    ->where('poster_vigile.PvVigile',$PeNumero)
                    ->orderBy('poster_vigile.PvNumero','desc')
                    ->first();

                if($poster_vigile) {
                    DB::table('personnels')
                        ->where('personnels.PeNumero',$PeNumero)
                        ->update([
                            'personnels.PeSitePoste' => $poster_vigile->PvSiteClient
                        ]);
                }

                if(($request->PeStatut == 'SG' || $request->PeStatut == 'SA') && $request->PeIsAppliOn == 'Y') {

                    DB::table('users')
                        ->where('personnel',$PeNumero)
                        ->update([
                            'noms' => strtoupper($request->PeNom),
                            'prenoms' => $request->PePrenom,
                            'tel1' => $request->PeTel1,
                            'tel2' => $request->PeTel2,
                            'email' => $request->PeEmail,
                            'fonction' => NULL,
                            'username' => $request->PeAppAccesID,
                            'password' => Hash::make($request->PeAppAccessMDP),
                            'mot_de_passe' => $request->PeAppAccessMDP,
                            'reset_password' => 'N'
                        ]);

                } else if(($request->PeStatut == 'CT') && $request->PeIsAppliOn == 'Y') {

                    DB::table('users')
                        ->where('personnel',$PeNumero)
                        ->update([
                            'noms' => strtoupper($request->PeNom),
                            'prenoms' => $request->PePrenom,
                            'tel1' => $request->PeTel1,
                            'tel2' => $request->PeTel2,
                            'email' => $request->PeEmail,
                            'fonction' => NULL,
                            'username' => $request->PeAppAccesID,
                            'password' => Hash::make($request->PeAppAccessMDP),
                            'mot_de_passe' => $request->PeAppAccessMDP,
                            'reset_password' => 'N'
                        ]);

                } else {
                    /*------------------*/
                }




            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('personnels.index')->with('alert', 'Personnel modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function activer($PeNumero)
    {
        DB::table('personnels')->where('personnels.PeNumero', $PeNumero)->update(['personnels.PeIsOn' => 'Y']);
    }

    public function desactiver($PeNumero)
    {
        DB::table('personnels')->where('personnels.PeNumero', $PeNumero)->update(['personnels.PeIsOn' => 'N']);
        DB::table('rondier_sites')->where('rondier_sites.RtRondier', $PeNumero)->delete();

    }

    public function delete($id)
    {
        Personnel::find($id)->delete();
    }


    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('file');

            // Option 1: Import simple
            Excel::import(new PersonnelImport, $file);
            return back()->with('alert', 'Import réussi !');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = "Ligne {$failure->row()}: {$failure->errors()[0]}";
            }

            return back()->with('alert-error', $errors);

        } catch (\Exception $e) {
            Log::error('Import error: ' . $e->getMessage());
            return back()->with('alert-error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }


    /*public function generatePDF(Request $request)
    {
        if($request->PeNumero == 'all' && $request->ViNumero == 'all' && $request->ZoNumero == 'all' && $request->QuNumero == 'all' && $request->ScNumero == 'all' && $request->ClNumero == 'all'  && $request->faction_site == 'all')
        {
            $sites_superviseur = DB::table('sites_superviseur')
                ->leftJoin('personnels','personnels.PeNumero','=','sites_superviseur.SsSuperv')
                ->leftJoin('sites_client','sites_client.ScNumero','=','sites_superviseur.SsSiteClient')
                ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
                ->where('sites_superviseur.SsEntite',Auth::user()->entite)
                ->whereDate('sites_superviseur.SsDateDebut','>=',$request->start_date)
                ->whereDate('sites_superviseur.SsDateDebut','<=',$request->end_date)
                ->orderBy('sites_superviseur.SsDateHCree','asc')
                ->get();
        }
        else
        {
            // Handle other cases where not all parameters are 'all'
            $query = DB::table('sites_superviseur')
                ->leftJoin('personnels','personnels.PeNumero','=','sites_superviseur.SsSuperv')
                ->leftJoin('sites_client','sites_client.ScNumero','=','sites_superviseur.SsSiteClient')
                ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
                ->leftJoin('quartiers','quartiers.QuNumero','=','sites_client.ScQuartier')
                ->leftJoin('zones','zones.ZoNumero','=','sites_client.ScZone')
                ->leftJoin('villes','villes.ViNumero','=','zones.ZoVille');

            // Apply filters based on the request parameters
            if($request->PeNumero != 'all') {
                $query->where('sites_superviseur.SsSuperv',$request->PeNumero)
                    ->where('sites_superviseur.SsEntite',Auth::user()->entite)
                    ->whereDate('sites_superviseur.SsDateDebut','>=',$request->start_date)
                    ->whereDate('sites_superviseur.SsDateDebut','<=',$request->end_date);
            }

            // Apply filters based on the request parameters
            if($request->ViNumero != 'all') {
                $query->where('villes.ViNumero',$request->ViNumero)
                    ->where('sites_superviseur.SsEntite',Auth::user()->entite)
                    ->whereDate('sites_superviseur.SsDateDebut','>=',$request->start_date)
                    ->whereDate('sites_superviseur.SsDateDebut','<=',$request->end_date);
            }

            // Apply filters based on the request parameters
            if($request->ZoNumero != 'all') {
                $query->where('zones.ZoNumero',$request->ZoNumero)
                    ->where('sites_superviseur.SsEntite',Auth::user()->entite)
                    ->whereDate('sites_superviseur.SsDateDebut','>=',$request->start_date)
                    ->whereDate('sites_superviseur.SsDateDebut','<=',$request->end_date);
            }

            // Apply filters based on the request parameters
            if($request->QuNumero != 'all') {
                $query->where('quartiers.QuNumero',$request->QuNumero)
                    ->where('sites_superviseur.SsEntite',Auth::user()->entite)
                    ->whereDate('sites_superviseur.SsDateDebut','>=',$request->start_date)
                    ->whereDate('sites_superviseur.SsDateDebut','<=',$request->end_date);
            }

            // Apply filters based on the request parameters
            if($request->ScNumero != 'all') {
                $query->where('sites_client.ScNumero',$request->ScNumero)
                    ->where('sites_superviseur.SsEntite',Auth::user()->entite)
                    ->whereDate('sites_superviseur.SsDateDebut','>=',$request->start_date)
                    ->whereDate('sites_superviseur.SsDateDebut','<=',$request->end_date);
            }

            // Apply filters based on the request parameters
            if($request->ClNumero != 'all') {
                $query->where('clients.ScNumero',$request->ClNumero)
                    ->where('sites_superviseur.SsEntite',Auth::user()->entite)
                    ->whereDate('sites_superviseur.SsDateDebut','>=',$request->start_date)
                    ->whereDate('sites_superviseur.SsDateDebut','<=',$request->end_date);
            }

            // Apply filters based on the request parameters
            if($request->faction_site != 'all') {
                $query->where('sites_superviseur.SsFaction',$request->faction_site)
                    ->where('sites_superviseur.SsEntite',Auth::user()->entite)
                    ->whereDate('sites_superviseur.SsDateDebut','>=',$request->start_date)
                    ->whereDate('sites_superviseur.SsDateDebut','<=',$request->end_date);
            }

            // Execute the query with the applied filters
            $sites_superviseur = $query->orderBy('sites_superviseur.SsDateHCree','asc')->get();
        }

        $start_date =  $request->start_date;
        $end_date =  $request->end_date;
        $PeNumero = $request->PeNumero;
        $ViNumero = $request->ViNumero;
        $ZoNumero = $request->ZoNumero;
        $QuNumero = $request->QuNumero;
        $ScNumero = $request->ScNumero;
        $ClNumero = $request->ClNumero;
        $faction_site = $request->faction_site;

        $entite = DB::table('entites')
            ->where('entites.EnNumero',Auth::user()->entite)
            ->first();

        $pdf = PDF::loadView('sites-superviseur.pdf', compact(
            'sites_superviseur','PeNumero','ViNumero','ZoNumero','QuNumero','ScNumero',
            'start_date','end_date','ClNumero','faction_site','entite'
        ));

        return  $pdf->stream("Liste-Sites-Supervises.pdf", array("Attachment" => false));
        exit(0);
    }*/


    public function filtre(Request $request)
    {
        if($request->ville_personnel == 'all' && $request->zone_personnel == 'all' && $request->quartier_personnel == 'all' && $request->site_personnel == 'all')
        {
            $personnels = DB::table('personnels')
                ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
                ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
                ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
                ->leftJoin('entites','entites.EnNumero','=','personnels.PeEntite')
                ->leftJoin('poster_vigile','poster_vigile.PvVigile','=','personnels.PeNumero')
                ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
                ->where('personnels.PeEntite',Auth::user()->entite)
                ->orderBy('personnels.PeMatric','asc')
                ->get();
        }
        else
        {
            // Handle other cases where not all parameters are 'all'
            $query = DB::table('personnels')
                ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
                ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
                ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
                ->leftJoin('entites','entites.EnNumero','=','personnels.PeEntite')
                ->leftJoin('poster_vigile','poster_vigile.PvVigile','=','personnels.PeNumero')
                ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient');

            // Apply filters based on the request parameters
            if($request->ville_personnel != 'all') {
                $query->where('personnels.PeVille',$request->ville_personnel)
                    ->where('personnels.PeEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->zone_personnel != 'all') {
                $query->where('zones.ZoNumero',$request->zone_personnel)
                    ->where('personnels.PeEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->quartier_personnel != 'all') {
                $query->where('personnels.PeQuartier',$request->quartier_personnel)
                    ->where('personnels.PeEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->site_personnel != 'all') {
                $query->where('sites_client.ScNumero',$request->site_personnel)
                    ->where('personnels.PeEntite',Auth::user()->entite);
            }

            // Execute the query with the applied filters
            $personnels = $query->orderBy('personnels.PeMatric','asc')->get();

        }

        $ville_personnel = $request->ville_personnel;
        $zone_personnel = $request->zone_personnel;
        $quartier_personnel = $request->quartier_personnel;
        $site_personnel = $request->site_personnel;

       $villes = DB::table('villes')
            ->leftJoin('personnels','personnels.PeNumero','=','villes.ViRespOper')
            ->where('villes.ViEntite',Auth::user()->entite)
            ->orderBy('villes.ViNom','asc')
            ->get();

        $zones = DB::table('zones')
            ->leftJoin('villes','villes.ViNumero','=','zones.ZoVille')
            ->where('zones.ZoEntite',Auth::user()->entite)
            ->orderBy('zones.ZoNom','asc')
            ->get();

        $quartiers = DB::table('quartiers')
            ->leftJoin('villes','villes.ViNumero','=','quartiers.QuVille')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->where('quartiers.QuEntite',Auth::user()->entite)
            ->orderBy('quartiers.QuNom','asc')
            ->get();

        $sites_client = DB::table('sites_client')
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->orderBy('sites_client.ScNomSite','asc')
            ->get();

        return view('personnels.filtre',compact(
            'personnels','villes','zones','quartiers','sites_client',
            'ville_personnel','zone_personnel','quartier_personnel',
            'site_personnel'
        ));
    }


    public function show_quartiers($PeVille)
    {
        $quartiers = DB::table('quartiers')
            ->where('quartiers.QuVille',$PeVille)
            ->get();

        $str ='';

        $str.='
        <div class="form-group">
            <label for="PeQuartier">Quartier</label>
            <select class="e9 form-control" id="PeQuartier" name="PeQuartier">
                <option selected value="">Selectionner...</option>';
                foreach($quartiers as $quartier):
                    $str.='<option value="'.$quartier->QuNumero.'">'.$quartier->QuNom.'</option>';
                endforeach;
            $str.='</select>
            <div class="invalid-feedback">
                Ce champ est obligatoire.
            </div>
        </div>';

        $str.='
        <script>
            $(document).ready(function() {
                $(".e9").select2();
            });
        </script>';

        $str.='';

        echo $str;
    }


    public function rondiers($PeNumero)
    {
        $start_date = date('Y-m-01', strtotime('-2 months'));
        $end_date=date('Y-m-d');

        $PeNumero = decrypter($PeNumero);

        $rondier = DB::table('personnels')
            ->join('poster_vigile','poster_vigile.PvVigile','=','personnels.PeNumero')
            ->where('personnels.PeNumero',$PeNumero)
            ->first();

        $personnels = DB::table('personnels')
            ->join('poster_vigile','poster_vigile.PvVigile','=','personnels.PeNumero')
            ->where('personnels.PeStatut','R')
            ->where('personnels.PeNumero','!=',$PeNumero)
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->orderBy('personnels.PeMatric','asc')
            ->get();

        $sites_client = DB::table('sites_client')
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->get();

        // Supposons que le jour de repos est stocké dans une colonne 'jour_repos' (ex: 1 pour lundi, 2 pour mardi, etc.)
        $jourRepos = $rondier->PeJourRepos ?? null;

        // Définir les jours
        $tous_les_jours = [
            "1" => "Lundi",
            "2" => "Mardi",
            "3" => "Mercredi",
            "4" => "Jeudi",
            "5" => "Vendredi",
            "6" => "Samedi",
            "7" => "Dimanche"
        ];

        // Filtrer les jours disponibles
        $jours_disponibles = $tous_les_jours;

        if($jourRepos !== null && $jourRepos !== '') {
            unset($jours_disponibles[$jourRepos]);
        }

        $jours_repos = DB::table('rondier_sites')
            ->leftJoin('personnels','personnels.PeNumero','=','rondier_sites.RtPersonnel')
            ->leftJoin('sites_client','sites_client.ScNumero','=','rondier_sites.RtSiteClient')
            ->where('rondier_sites.RtRondier',$PeNumero)
            ->whereDate('rondier_sites.RtDateDebut','>=',$start_date)
            ->whereDate('rondier_sites.RtDateDebut','<=',$end_date)
            ->where('rondier_sites.RtEntite',Auth::user()->entite)
            ->get();

        return view('personnels.rondiers.list',compact('PeNumero','personnels','sites_client','rondier','jours_disponibles','jours_repos'));
    }
}
