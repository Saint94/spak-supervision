<?php

namespace App\Http\Controllers;

use App\Models\OperationSuperv;
use App\Models\SiteSuperviseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class SupervisionSiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-d');

        if(Auth::user()->type == 'S') {

            $supervision_sites = DB::table('operations_superv')
                ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
                ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
                ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
                ->where('operations_superv.OsSuperviseur',Auth::user()->personnel)
                //->whereDate('operations_superv.OsDate','>=',$start_date)
                //->whereDate('operations_superv.OsDate','<=',$end_date)
                ->where('operations_superv.OsEntite',Auth::user()->entite)
                ->orderBy('operations_superv.OsDate','asc')
                ->get();

            $superviseurs = DB::table('personnels')
                ->whereIn('personnels.PeStatut', ['SG','SA','CT'])
                ->where('personnels.PeNumero',Auth::user()->personnel)
                ->where('personnels.PeEntite', Auth::user()->entite)
                ->orderBy('personnels.PeNom', 'asc')
                ->first();

            $villes = DB::table('villes')
                ->leftJoin('personnels','personnels.PeNumero','=','villes.ViRespOper')
                ->where('villes.ViNumero',$superviseurs->PeVille)
                ->where('villes.ViEntite',Auth::user()->entite)
                ->orderBy('villes.ViNom','asc')
                ->first();

            $site_superviseur = DB::table('sites_superviseur')
                ->where('sites_superviseur.SsSuperv',$superviseurs->PeNumero)
                ->orderBy('sites_superviseur.SsNumero','desc')
                ->first();

            if($site_superviseur->SsMode == 'Z') {
                $zones = DB::table('zones')
                    ->where('zones.ZoNumero',$site_superviseur->SsSiteClient)
                    ->first();

                $ZoNumero = $zones->ZoNumero;
                $ZoNom = $zones->ZoNom;

            } else {
                $zones = DB::table('sites_client')
                    ->where('sites_client.ScNumero',$site_superviseur->SsSiteClient)
                    ->first();

                $ZoNumero = $zones->ScNumero;
                $ZoNom = $zones->ScNomSite;
            }

        } elseif(Auth::user()->type == 'T') {

            $supervision_sites = DB::table('operations_superv')
                ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
                ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
                ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
                ->where('operations_superv.OsSuperviseur',Auth::user()->personnel)
                ->whereDate('operations_superv.OsDate','>=',$start_date)
                ->whereDate('operations_superv.OsDate','<=',$end_date)
                ->where('operations_superv.OsEntite',Auth::user()->entite)
                ->orderBy('operations_superv.OsDate','asc')
                ->get();

            $superviseurs = DB::table('personnels')
                ->whereIn('personnels.PeStatut', ['SG','SA','CT'])
                ->where('personnels.PeNumero',Auth::user()->personnel)
                ->where('personnels.PeEntite', Auth::user()->entite)
                ->orderBy('personnels.PeNom', 'asc')
                ->first();

            $villes = DB::table('villes')
                ->leftJoin('personnels','personnels.PeNumero','=','villes.ViRespOper')
                ->where('villes.ViNumero',$superviseurs->PeVille)
                ->where('villes.ViEntite',Auth::user()->entite)
                ->orderBy('villes.ViNom','asc')
                ->first();

            $site_superviseur = DB::table('sites_superviseur')
                ->where('sites_superviseur.SsSuperv',$superviseurs->PeNumero)
                ->orderBy('sites_superviseur.SsNumero','desc')
                ->first();

            if($site_superviseur->SsMode == 'Z') {
                $zones = DB::table('zones')
                    ->where('zones.ZoNumero',$site_superviseur->SsSiteClient)
                    ->first();

                $ZoNumero = $zones->ZoNumero;
                $ZoNom = $zones->ZoNom;

            } else {
                $zones = DB::table('sites_client')
                    ->where('sites_client.ScNumero',$site_superviseur->SsSiteClient)
                    ->first();

                $ZoNumero = $zones->ScNumero;
                $ZoNom = $zones->ScNomSite;
            }

        } else {

            $supervision_sites = DB::table('operations_superv')
                ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
                ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
                ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
                ->whereDate('operations_superv.OsDate','>=',$start_date)
                ->whereDate('operations_superv.OsDate','<=',$end_date)
                ->where('operations_superv.OsEntite',Auth::user()->entite)
                ->orderBy('operations_superv.OsDate','asc')
                ->get();


            $superviseurs = DB::table('personnels')
                ->whereIn('personnels.PeStatut', ['SG','SA','CT'])
                ->where('personnels.PeEntite', Auth::user()->entite)
                ->orderBy('personnels.PeNom', 'asc')
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

            $ZoNumero = "";
            $ZoNom = "";

        }

        return view('supervision-sites.list',compact(
            'supervision_sites','superviseurs','villes','zones','ZoNumero','ZoNom'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*$supervision_site = DB::table('operations_superv')
            ->where('operations_superv.OsSuperviseur',$request->OsSuperviseur)
            ->where('operations_superv.OsFaction',$request->OsFaction)
            ->where('operations_superv.OsDate',$request->OsDate)
            ->where('operations_superv.OsEntite',Auth::user()->entite)
            ->first();

        if(empty($supervision_site)) {*/

            DB::beginTransaction();

            try{
                // Do something and save to the db...
                $supervision_site = new OperationSuperv();
                $supervision_site->OsEntite = Auth::user()->entite;
                $supervision_site->OsDate = $request->OsDate;
                $supervision_site->OsVille = $request->OsVille;
                $supervision_site->OsZone = $request->OsZone;
                $supervision_site->OsSuperviseur = $request->OsSuperviseur;
                $supervision_site->OsFaction = $request->OsFaction;
                $supervision_site->OsHeureDebut = $request->OsHeureDebut;
                $supervision_site->OsIsSupervSigne = $request->OsIsSupervSigne;
                $supervision_site->OsHeureFin = NULL;
                $supervision_site->OsIsRetourFiche = NULL;
                $supervision_site->OsDateHFiche = NULL;
                $supervision_site->OsStatut = 'I';
                $supervision_site->OsDetails = $request->OsDetails;
                $supervision_site->save();
                DB::commit();
            }
            catch (\Throwable $th){
                // An error occured; cancel the transaction...
                DB::rollback();
                // and throw the error again.
                throw $th;
            }

            return redirect()->route('supervision-sites.index')->with('alert', 'Supervision site créée avec succès.');
        /*}
        else {
            return redirect()->route('supervision-sites.index')->with('alert-error', 'Cette supervision existe déjà.');
        }*/

    }

    /**
     * Display the specified resource.
     */
    public function show(string $OsNumero)
    {
        return DB::table('operations_superv')
            ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
            ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
            ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
            ->leftJoin('quartiers','quartiers.QuZone','=','zones.ZoNumero')
            ->where('operations_superv.OsNumero',$OsNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $OsNumero)
    {
        $supervision_site = DB::table('operations_superv')
            ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
            ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
            ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
            ->leftJoin('quartiers','quartiers.QuZone','=','zones.ZoNumero')
            ->where('operations_superv.OsNumero',$OsNumero)
            ->first();

        return response()->json($supervision_site);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $OsNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('operations_superv')
            ->where('operations_superv.OsNumero',$OsNumero)
            ->update([
                'OsDate' => $request->OsDate,
                'OsVille' => $request->OsVille,
                'OsZone' => $request->OsZone,
                'OsSuperviseur' => $request->OsSuperviseur,
                'OsFaction' => $request->OsFaction,
                'OsHeureDebut' => $request->OsHeureDebut,
                //'OsHeureFin' => $request->OsHeureFin,
                //'OsIsRetourFiche' => $request->OsIsRetourFiche,
                //'OsStatut' => $request->OsStatut,
                'OsDetails' => $request->OsDetails
            ]);
            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('supervision-sites.index')->with('alert', 'Site superviseur modifié avec succès.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit_fermer(string $OsNumero)
    {
        $supervision_site = DB::table('operations_superv')
            ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
            ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
            ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
            ->leftJoin('quartiers','quartiers.QuZone','=','zones.ZoNumero')
            ->where('operations_superv.OsNumero',$OsNumero)
            ->first();

        return response()->json($supervision_site);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_fermer(Request $request, string $OsNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('operations_superv')
            ->where('operations_superv.OsNumero',$OsNumero)
            ->update([
                'OsHeureFin' => $request->OsHeureFin,
                'OsIsRetourFiche' => $request->OsIsRetourFiche,
                'OsDateHFiche' => $request->OsDateHFiche,
                'OsSuperviseur' => $request->OsSuperviseur,
                'OsFaction' => $request->OsFaction,
                'OsHeureDebut' => $request->OsHeureDebut,
                'OsDetails' => $request->OsDetails
            ]);
            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('supervision-sites.index')->with('alert', 'Opération clôturée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function generatePDF(Request $request)
    {
        if($request->superviseur_site == 'all' && $request->ville_site == 'all' && $request->zone_site == 'all')
        {
            $supervision_sites = DB::table('operations_superv')
                ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
                ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
                ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
                ->whereDate('operations_superv.OsDate','>=',$request->start_date)
                ->whereDate('operations_superv.OsDate','<=',$request->end_date)
                ->where('operations_superv.OsEntite',Auth::user()->entite)
                ->orderBy('operations_superv.OsDate','asc')
            ->paginate(50);
        }
        else
        {
            // Handle other cases where not all parameters are 'all'
            $query = DB::table('operations_superv')
                ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
                ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
                ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone');

            // Apply filters based on the request parameters
            if($request->superviseur_site != 'all') {
                $query->where('operations_superv.OsSuperviseur',$request->superviseur_site)
                    ->whereDate('operations_superv.OsDate','>=',$request->start_date)
                    ->whereDate('operations_superv.OsDate','<=',$request->end_date)
                    ->where('operations_superv.OsEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->ville_site != 'all') {
                $query->where('operations_superv.OsVille',$request->ville_site)
                    ->whereDate('operations_superv.OsDate','>=',$request->start_date)
                    ->whereDate('operations_superv.OsDate','<=',$request->end_date)
                    ->where('operations_superv.OsEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->zone_site != 'all') {
                $query->where('operations_superv.OsZone',$request->zone_site)
                    ->whereDate('operations_superv.OsDate','>=',$request->start_date)
                    ->whereDate('operations_superv.OsDate','<=',$request->end_date)
                    ->where('operations_superv.OsEntite',Auth::user()->entite);
            }

            // Execute the query with the applied filters
            $supervision_sites = $query->orderBy('operations_superv.OsDate','asc')->paginate(50);
        }

        $start_date =  $request->start_date;
        $end_date =  $request->end_date;
        $superviseur_site = $request->superviseur_site;
        $ville_site = $request->ville_site;
        $zone_site = $request->zone_site;

        $entite = DB::table('entites')
            ->where('entites.EnNumero',Auth::user()->entite)
            ->first();

        $pdf = PDF::loadView('supervision-sites.pdf', compact(
            'supervision_sites','start_date','end_date','superviseur_site',
            'ville_site','zone_site','entite'
        ))->setPaper('a4', 'landscape');

        return  $pdf->stream("Liste-Rondes.pdf", array("Attachment" => false));
        exit(0);
    }


    public function filtre(Request $request)
    {
        if($request->superviseur_site == 'all' && $request->ville_site == 'all' && $request->zone_site == 'all')
        {
            if(in_array(Auth::user()->type, ['S', 'T'])) {

                $supervision_sites = DB::table('operations_superv')
                    ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
                    ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
                    ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
                    ->where('operations_superv.OsSuperviseur',Auth::user()->personnel)
                    ->whereDate('operations_superv.OsDate','>=',$request->start_date)
                    ->whereDate('operations_superv.OsDate','<=',$request->end_date)
                    ->where('operations_superv.OsEntite',Auth::user()->entite)
                    ->orderBy('operations_superv.OsDate','asc')
                    ->get();

            } else {

                $supervision_sites = DB::table('operations_superv')
                    ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
                    ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
                    ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
                    ->whereDate('operations_superv.OsDate','>=',$request->start_date)
                    ->whereDate('operations_superv.OsDate','<=',$request->end_date)
                    ->where('operations_superv.OsEntite',Auth::user()->entite)
                    ->orderBy('operations_superv.OsDate','asc')
                    ->get();
            }

        }
        else
        {
            if(in_array(Auth::user()->type, ['S', 'T'])) {
                // Handle other cases where not all parameters are 'all'
                $query = DB::table('operations_superv')
                    ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
                    ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
                    ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
                    ->where('operations_superv.OsSuperviseur',Auth::user()->personnel);

            } else {
                // Handle other cases where not all parameters are 'all'
                $query = DB::table('operations_superv')
                    ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
                    ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
                    ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone');
            }


            // Apply filters based on the request parameters
            if($request->superviseur_site != 'all') {
                $query->where('operations_superv.OsSuperviseur',$request->superviseur_site)
                    ->whereDate('operations_superv.OsDate','>=',$request->start_date)
                    ->whereDate('operations_superv.OsDate','<=',$request->end_date)
                    ->where('operations_superv.OsEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->ville_site != 'all') {
                $query->where('operations_superv.OsVille',$request->ville_site)
                    ->whereDate('operations_superv.OsDate','>=',$request->start_date)
                    ->whereDate('operations_superv.OsDate','<=',$request->end_date)
                    ->where('operations_superv.OsEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->zone_site != 'all') {
                $query->where('operations_superv.OsZone',$request->zone_site)
                    ->whereDate('operations_superv.OsDate','>=',$request->start_date)
                    ->whereDate('operations_superv.OsDate','<=',$request->end_date)
                    ->where('operations_superv.OsEntite',Auth::user()->entite);
            }

            // Execute the query with the applied filters
            $supervision_sites = $query->orderBy('operations_superv.OsDate','asc')->get();
        }

        $start_date =  $request->start_date;
        $end_date =  $request->end_date;
        $superviseur_site = $request->superviseur_site;
        $ville_site = $request->ville_site;
        $zone_site = $request->zone_site;

        if(Auth::user()->type =='S') {
            $superviseurs = DB::table('personnels')
                ->whereIn('personnels.PeStatut', ['SG','SA','CT'])
                ->where('personnels.PeNumero',Auth::user()->personnel)
                ->where('personnels.PeEntite', Auth::user()->entite)
                ->orderBy('personnels.PeNom', 'asc')
                ->first();

            $villes = DB::table('villes')
                ->leftJoin('personnels','personnels.PeNumero','=','villes.ViRespOper')
                ->where('villes.ViNumero',$superviseurs->PeVille)
                ->where('villes.ViEntite',Auth::user()->entite)
                ->orderBy('villes.ViNom','asc')
                ->first();

            $site_superviseur = DB::table('sites_superviseur')
                ->where('sites_superviseur.SsSuperv',$superviseurs->PeNumero)
                ->orderBy('sites_superviseur.SsNumero','desc')
                ->first();

            if($site_superviseur->SsMode == 'Z') {
                $zones = DB::table('zones')
                    ->where('zones.ZoNumero',$site_superviseur->SsSiteClient)
                    ->first();

                $ZoNumero = $zones->ZoNumero;
                $ZoNom = $zones->ZoNom;

            } else {
                $zones = DB::table('sites_client')
                    ->where('sites_client.ScNumero',$site_superviseur->SsSiteClient)
                    ->first();

                $ZoNumero = $zones->ScNumero;
                $ZoNom = $zones->ScNomSite;
            }

        } elseif(Auth::user()->type =='T') {
            $superviseurs = DB::table('personnels')
                ->whereIn('personnels.PeStatut', ['SG','SA','CT'])
                ->where('personnels.PeNumero',Auth::user()->personnel)
                ->where('personnels.PeEntite', Auth::user()->entite)
                ->orderBy('personnels.PeNom', 'asc')
                ->first();

            $villes = DB::table('villes')
                ->leftJoin('personnels','personnels.PeNumero','=','villes.ViRespOper')
                ->where('villes.ViNumero',$superviseurs->PeVille)
                ->where('villes.ViEntite',Auth::user()->entite)
                ->orderBy('villes.ViNom','asc')
                ->first();

            $site_superviseur = DB::table('sites_superviseur')
                ->where('sites_superviseur.SsSuperv',$superviseurs->PeNumero)
                ->orderBy('sites_superviseur.SsNumero','desc')
                ->first();

            if($site_superviseur->SsMode == 'Z') {
                $zones = DB::table('zones')
                    ->where('zones.ZoNumero',$site_superviseur->SsSiteClient)
                    ->first();

                $ZoNumero = $zones->ZoNumero;
                $ZoNom = $zones->ZoNom;

            } else {
                $zones = DB::table('sites_client')
                    ->where('sites_client.ScNumero',$site_superviseur->SsSiteClient)
                    ->first();

                $ZoNumero = $zones->ScNumero;
                $ZoNom = $zones->ScNomSite;
            }

        } else {
            $superviseurs = DB::table('personnels')
                ->whereIn('personnels.PeStatut', ['SG','SA','CT'])
                ->where('personnels.PeEntite', Auth::user()->entite)
                ->orderBy('personnels.PeNom', 'asc')
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

            $ZoNumero = "";
            $ZoNom = "";
        }

        return view('supervision-sites.filtre',compact(
            'supervision_sites','superviseurs','villes',
            'zones','start_date','end_date','superviseur_site',
            'ville_site','zone_site','ZoNumero','ZoNom'
        ));
    }



     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_avertir_ronde($OsSuperviseur,$OsDate,$OsFaction)
    {
        $supervision_site = DB::table('operations_superv')
            ->where('operations_superv.OsSuperviseur',$OsSuperviseur)
            ->where('operations_superv.OsDate',$OsDate)
            ->where('operations_superv.OsFaction',$OsFaction)
            //->where('operations_superv.OsEntite',Auth::user()->entite)
            ->first();

        $str ='';

        if($supervision_site) {
            $str.='<span style="color:red;font-size:16px">🚨 Une ronde avec les mêmes paramètres existent déjà!</span>';
        } else {
            $str.='<span></span>';
        }

        $str.='';

        echo $str;
    }



    /**
     * Remove the specified resource from storage.
    */
    public function sites(string $OsNumero)
    {
        $OsNumero = decrypter($OsNumero);

        $supervision_site = DB::table('operations_superv')
            ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
            ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
            ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
            ->leftJoin('quartiers','quartiers.QuZone','=','zones.ZoNumero')
            ->where('operations_superv.OsNumero',$OsNumero)
            ->first();

        // 1) Récupérer la valeur de SsIsAllSites depuis sites_superviseur
        $isAllSites = DB::table('sites_superviseur')
            ->where('sites_superviseur.SsSuperv', $supervision_site->OsSuperviseur)
            ->where('sites_superviseur.SsEntite', Auth::user()->entite)
            ->value('sites_superviseur.SsIsAllSites');


        // 2) Brancher selon la valeur
        if($isAllSites == 'Y') {
            // Cas 1 : tous les sites du superviseur (requête existante)
            $sites_superviseur = DB::table('sites_superviseur')
                ->leftJoin('personnels', 'personnels.PeNumero', '=', 'sites_superviseur.SsSuperv')
                ->leftJoin('sites_client', 'sites_client.ScNumero', '=', 'sites_superviseur.SsSiteClient')
                ->where('sites_superviseur.SsSuperv', $supervision_site->OsSuperviseur)
                ->where('sites_superviseur.SsDateDebut',$supervision_site->OsDate)
                ->where('sites_superviseur.SsEntite', Auth::user()->entite)
                ->get();
        } else {
            // Cas 2 : sites limités via sites_agent_sup_ctr
            $sites_superviseur = DB::table('sites_agent_sup_ctr')
                ->leftJoin('personnels', 'personnels.PeNumero', '=', 'sites_agent_sup_ctr.SaSupervCtrl')
                ->leftJoin('sites_client', 'sites_client.ScNumero', '=', 'sites_agent_sup_ctr.SaSiteClt')
                ->where('sites_agent_sup_ctr.SaSupervCtrl', $supervision_site->OsSuperviseur)
                ->where('sites_agent_sup_ctr.SaDateDebut',$supervision_site->OsDate)
                ->where('sites_agent_sup_ctr.SaEntite', Auth::user()->entite)
                ->get();
        }

     //   dd($isAllSites,$sites_superviseur);


        /* Pour les filtres */
        $clients = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->orderBy('clients.ClDesign','asc')
            ->get();

        $sites_client = DB::table('sites_client')
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->orderBy('sites_client.ScNomSite','asc')
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
        /* Pour les filtres */

        $sites_client_1 = DB::table('sites_client')
            ->whereNotIn('sites_client.ScNumero', function($query) {
                $query->select('sites_superviseur.SsSiteClient')
                    ->from('sites_superviseur');
                })
            ->where('sites_client.ScIsOn','Y')
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->orderBy('sites_client.ScNomSite','asc')
            ->get();

        return view('supervision-sites.sites',compact(
            'OsNumero','supervision_site','sites_superviseur','clients','sites_client',
            'villes','zones','quartiers','sites_client_1'
        ));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store_site_superviseur(Request $request)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            $site_superviseur = new SiteSuperviseur();
            $site_superviseur->SsEntite = Auth::user()->entite;
            $site_superviseur->SsSuperv = $request->SsSuperv;
            $site_superviseur->SsSiteClient = $request->SsSiteClient;
            $site_superviseur->SsDetails = $request->SsDetails;
            $site_superviseur->SsFaction = $request->SsFaction;
            $site_superviseur->SsIsOn = $request->SsIsOn;
            $site_superviseur->SsUserCree = Auth::user()->id;
            $site_superviseur->save();
            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('sites-superviseur.index')->with('alert', 'Site superviseur crée avec succès.');

    }

    /**
     * Display the specified resource.
     */
    public function show_site_superviseur(string $SsNumero)
    {
        return DB::table('sites_superviseur')
            ->leftJoin('personnels','personnels.PeNumero','=','sites_superviseur.SsSuperv')
            ->leftJoin('sites_client','sites_client.ScNumero','=','sites_superviseur.SsSiteClient')
            ->where('sites_superviseur.SsNumero',$SsNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit_site_superviseur(string $SsNumero)
    {
        $site_superviseur = DB::table('sites_superviseur')
            ->where('sites_superviseur.SsNumero',$SsNumero)
            ->first();

        return response()->json($site_superviseur);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_site_superviseur(Request $request, string $SsNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('sites_superviseur')
            ->where('sites_superviseur.SsNumero',$SsNumero)
            ->update([
                'SsSiteClient' => $request->SsSiteClient,
                'SsFaction' => $request->SsFaction,
                'SsIsOn' => $request->SsIsOn,
                'SsDetails' => $request->SsDetails
            ]);
            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('sites-superviseur.index')->with('alert', 'Site superviseur modifié avec succès.');
    }


    public function desactiver_site_superviseur($SsNumero)
    {
        DB::table('sites_superviseur')
            ->where('sites_superviseur.SsNumero',$SsNumero)
            ->update([
                'sites_superviseur.SsIsOn' => 'N',
                'sites_superviseur.SsDateFin' => date('Y-m-d H:i:s')
            ]);
    }


    /**
     * Remove the specified resource from storage.
    */
    public function operations(string $OsNumero)
    {
        $OsNumero = decrypter($OsNumero);

        $start_date = date('Y-m-01');
        $end_date = date('Y-m-d');

        $supervision_site = DB::table('operations_superv')
            ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
            ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
            ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
            ->where('operations_superv.OsNumero',$OsNumero)
            ->first();

        $pointage_agents = DB::table('sites_oper_superv')
                ->select([
                'sites_oper_superv.*',
                'agent_pgme.PeNumero as agent_pgme_numero',
                'agent_pgme.PeNom as agent_pgme_nom',
                'agent_pgme.PePrenom as agent_pgme_prenom',
                'agent_pgme.PeTel1 as agent_pgme_tel1',
                'agent_pgme.PeTel2 as agent_pgme_tel2',
                'agent_oper.PeNumero as agent_oper_numero',
                'agent_oper.PeNom as agent_oper_nom',
                'agent_oper.PePrenom as agent_oper_prenom',
                'agent_oper.PeTel1 as agent_oper_tel1',
                'agent_oper.PeTel2 as agent_oper_tel2',
                'sites_client.*',
                'villes.*',
                'zones.*'
                //'quartiers.*'
            ])
            ->leftJoin('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
            ->leftJoin('personnels AS agent_pgme', 'agent_pgme.PeNumero', '=', 'sites_oper_superv.SoAgentPgme') // Alias pour l'agent Pgme
            ->leftJoin('personnels AS agent_oper', 'agent_oper.PeNumero', '=', 'sites_oper_superv.SoAgentOper') // Alias pour l'agent Oper
            ->leftJoin('sites_client', 'sites_client.ScNumero', '=', 'sites_oper_superv.SoSite')
            ->leftJoin('villes', 'villes.ViNumero', '=', 'operations_superv.OsVille')
            ->leftJoin('zones', 'zones.ZoNumero', '=', 'operations_superv.OsZone')
            ->where('sites_oper_superv.SoOperSuperv',$OsNumero)
            ->whereDate('operations_superv.OsDate','>=',$start_date)
            ->whereDate('operations_superv.OsDate','<=',$end_date)
            ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
            ->get();


        /* Pour les filtres */
        $superviseurs = DB::table('personnels')
            ->whereIn('personnels.PeStatut', ['SG', 'SA', 'CT'])
            ->where('personnels.PeEntite', Auth::user()->entite)
            ->orderBy('personnels.PeMatric', 'asc')
            ->get();

        $clients = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->orderBy('clients.ClDesign','asc')
            ->get();

        $sites_client = DB::table('sites_client')
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->orderBy('sites_client.ScNomSite','asc')
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
        /* Pour les filtres */



        return view('pointage-agents.list',compact(
            'OsNumero','pointage_agents','supervision_site',
            'superviseurs','clients','sites_client',
            'villes','zones','quartiers'
        ));
    }


    public function print(string $OsNumero)
    {
        $OsNumero = decrypter($OsNumero);

        $pointage_agents = DB::table('sites_oper_superv')
                ->select([
                'sites_oper_superv.*',
                'agent_pgme.PeNumero as agent_pgme_numero',
                'agent_pgme.PeNom as agent_pgme_nom',
                'agent_pgme.PePrenom as agent_pgme_prenom',
                'agent_pgme.PeTel1 as agent_pgme_tel1',
                'agent_pgme.PeTel2 as agent_pgme_tel2',
                'agent_oper.PeNumero as agent_oper_numero',
                'agent_oper.PeNom as agent_oper_nom',
                'agent_oper.PePrenom as agent_oper_prenom',
                'agent_oper.PeTel1 as agent_oper_tel1',
                'agent_oper.PeTel2 as agent_oper_tel2',
                'sites_client.*',
                'villes.*',
                'zones.*'
            ])
            ->leftJoin('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
            ->leftJoin('personnels AS agent_pgme', 'agent_pgme.PeNumero', '=', 'sites_oper_superv.SoAgentPgme') // Alias pour l'agent Pgme
            ->leftJoin('personnels AS agent_oper', 'agent_oper.PeNumero', '=', 'sites_oper_superv.SoAgentOper') // Alias pour l'agent Oper
            ->leftJoin('sites_client', 'sites_client.ScNumero', '=', 'sites_oper_superv.SoSite')
            ->leftJoin('villes', 'villes.ViNumero', '=', 'operations_superv.OsVille')
            ->leftJoin('zones', 'zones.ZoNumero', '=', 'operations_superv.OsZone')
            ->where('sites_oper_superv.SoOperSuperv',$OsNumero)
            ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
            ->paginate(50);


        $supervision_site = DB::table('operations_superv')
            ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
            ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
            ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
            ->where('operations_superv.OsNumero',$OsNumero)
            ->first();


        $entite = DB::table('entites')
            ->where('entites.EnNumero',Auth::user()->entite)
            ->first();

        $pdf = PDF::loadView('supervision-sites.print', compact(
            'pointage_agents','supervision_site','entite','OsNumero'
        ))->setPaper('a4', 'landscape');

        return  $pdf->stream("Liste-Rondes.pdf", array("Attachment" => false));
        exit(0);
    }


     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show_ville_superviseur($OsSuperviseur)
    {
        $personnel = DB::table('personnels')
            ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
            ->where('personnels.PeNumero',$OsSuperviseur)
            ->first();

        $site_superviseur = DB::table('sites_superviseur')
            ->where('sites_superviseur.SsSuperv',$OsSuperviseur)
            ->orderBy('sites_superviseur.SsNumero','desc')
            ->first();

        $str ='';

        if($site_superviseur){

            $str.='<input type="hidden" class="form-control" id="OsVille" name="OsVille" value="'.$personnel->ViNumero.'"/>';

            $str.='
                <div class="col-xl-6 col-md-6">
                    <div class="form-group">
                        <label for="OsVille">Ville</label>
                    <input type="text" class="form-control" value="'.$personnel->ViNom.'" readonly/>
                    </div>
                </div>';

            if($site_superviseur->SsMode == 'Z') {
                $zone = DB::table('zones')
                    ->where('zones.ZoNumero',$site_superviseur->SsSiteClient)
                    ->first();

                $str.='
                <div class="col-xl-6 col-md-6">
                    <div class="form-group">
                        <label>Zone client</label>
                        <input type="text" class="form-control" value="'.$zone->ZoNom.'" readonly/>
                    </div>
                </div>';

            } else {
                $site_client = DB::table('sites_client')
                    ->where('sites_client.ScNumero',$site_superviseur->SsSiteClient)
                    ->first();

                $str.='
                <div class="col-xl-6 col-md-6">
                    <div class="form-group">
                        <label>Site client</label>
                        <input type="text" class="form-control" value="'.$site_client->ScNomSite.'" readonly/>
                    </div>
                </div>';
            }

        } else {
            $str.='
                <div class="col-xl-6 col-md-6">
                    <div class="form-group">
                        <label>Site client</label>
                        <input type="text" class="form-control" style="color: #bd4147;" value="Ce superviseur n\'es affecté à aucun site" readonly/>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6">
                    <div class="form-group">
                        <label for="OsVille">Ville</label>
                        <input type="text" class="form-control" style="color: #bd4147;" value="Veuillez renseigner la ville de ce personnel" readonly/>
                    </div>
                </div>';
        }

        $str.='';

        echo $str;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_zones_ville($OsVille)
    {
        $ville = DB::table('villes')
            ->where('villes.ViNumero',$OsVille)
            ->first();

        $zones = DB::table('zones')
            ->where('zones.ZoVille',$OsVille)
            ->get();

        $str ='';

        if($ville->ViIsZoneOn == 'Y') {
            $str.='
                <div class="form-group">
                    <label for="OsZone">Zone <abbr style="color: #bd4147;">(*)</abbr></label>
                    <select class="e3 form-control" id="OsZone" name="OsZone" required>
                        <option selected value="">Selectionner...</option>';
                        foreach($zones as $zone):
                            $str.='<option value="'.$zone->ZoNumero.'">'.$zone->ZoNom.'</option>';
                        endforeach;
                    $str.='</select>
                    <div class="invalid-feedback">
                        Ce champ est obligatoire.
                    </div>
                </div>';
        } else {
            $str.='
                <div class="form-group">
                    <label for="OsZone">Zone</label>
                    <input type="text" class="form-control" id="OsZone" name="OsZone" value="" readonly/>
                </div>';
        }

        $str.='
        <script>
            $(document).ready(function() {
                $(".e3").select2({
                    width: "100%"
                });
            });
        </script>';

        $str.='';

        echo $str;
    }

}
