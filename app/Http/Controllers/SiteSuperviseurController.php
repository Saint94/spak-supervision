<?php

namespace App\Http\Controllers;

use App\Models\SiteSuperviseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;


class SiteSuperviseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-d');

        $sites_superviseur = DB::table('sites_superviseur')
            ->leftJoin('personnels','personnels.PeNumero','=','sites_superviseur.SsSuperv')
            ->leftJoin('sites_client','sites_client.ScNumero','=','sites_superviseur.SsSiteClient')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
            ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->whereDate('sites_superviseur.SsDateDebut','>=',$start_date)
            ->whereDate('sites_superviseur.SsDateDebut','<=',$end_date)
            ->where('sites_superviseur.SsEntite',Auth::user()->entite)
            ->orderBy('sites_superviseur.SsDateDebut','asc')
            ->get();

        $superviseurs = DB::table('personnels')
            ->whereIn('personnels.PeStatut', ['SG', 'SA', 'CT'])
            ->where('personnels.PeEntite', Auth::user()->entite)
            ->orderBy('personnels.PeNom', 'asc')
            ->get();

        $clients = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->orderBy('clients.ClDesign','asc')
            ->get();

        $sites_client = DB::table('sites_client')
            ->leftJoin('sites_superviseur','sites_superviseur.SsSiteClient','=','sites_client.ScNumero')
            ->whereNotIn('sites_client.ScNumero', function($query) {
                $query->select('sites_superviseur.SsSiteClient')
                    ->from('sites_superviseur')
                    ->where('sites_superviseur.SsIsOn','Y')
                    ->where('sites_client.ScIsMultiSuperv','N')
                    ;
                })
            ->where('sites_client.ScIsOn','Y')
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->orderBy('sites_client.ScNomSite','asc')
            ->get();

        $superviseurs_1 = DB::table('personnels')
            ->whereIn('personnels.PeStatut', ['SG', 'SA', 'CT'])
            ->where('personnels.PeEntite', Auth::user()->entite)
            ->orderBy('personnels.PeMatric', 'asc')
            ->get();

        $sites_client_1 = DB::table('sites_client')
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

        return view('sites-superviseur.list',compact(
            'sites_superviseur','superviseurs','sites_client','superviseurs_1','sites_client_1',
            'villes','zones','quartiers','clients'
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
        DB::beginTransaction();

        $superviseur = DB::table('personnels')
            ->where('personnels.PeNumero',$request->SsSuperv)
            ->first();

        $ville = DB::table('villes')
            ->where('villes.ViNumero',$superviseur->PeVille)
            ->first();

            if($ville) {
                if($ville->ViIsAllSiteOn == 'Y') {

                    $sites_client = DB::table('sites_client')
                        ->where('sites_client.ScVille',$superviseur->PeVille)
                        ->get();

                    for($i=0; $i < count($sites_client) ; $i++) {

                        try{
                            // Do something and save to the db...
                            $site_superviseur = new SiteSuperviseur();
                            $site_superviseur->SsEntite = Auth::user()->entite;
                            $site_superviseur->SsSuperv = $request->SsSuperv;
                            $site_superviseur->SsMode = $request->SsMode;
                            $site_superviseur->SsIsAllSites = $request->SsIsAllSites ?? 'N';
                            $site_superviseur->SsSiteClient = $sites_client[$i]->ScNumero ?? NULL;
                            $site_superviseur->SsDateDebut = $request->SsDateDebut;
                            $site_superviseur->SsDetails = $request->SsDetails;
                            $site_superviseur->SsFaction = $request->SsFaction;
                            $site_superviseur->SsIsOn = $request->SsIsOn;
                            $site_superviseur->SsUserCree = Auth::user()->id;
                            $site_superviseur->SsDateHCree = date('Y-m-d H:i:s');
                            $site_superviseur->save();
                            DB::commit();
                        }
                        catch (\Throwable $th){
                            // An error occured; cancel the transaction...
                            DB::rollback();
                            // and throw the error again.
                            throw $th;
                        }

                        return redirect()->route('sites-superviseur.index')->with('alert', 'Sites affectés au superviseur avec succès.');
                    }

                } else {

                    if($ville->ViIsZoneOn == 'Y') {

                            try{
                            // Do something and save to the db...
                            $site_superviseur = new SiteSuperviseur();
                            $site_superviseur->SsEntite = Auth::user()->entite;
                            $site_superviseur->SsSuperv = $request->SsSuperv;
                            $site_superviseur->SsMode = $request->SsMode;
                            $site_superviseur->SsIsAllSites = $request->SsIsAllSites ?? 'N';
                            $site_superviseur->SsSiteClient = NULL;
                            $site_superviseur->SsDateDebut = $request->SsDateDebut;
                            $site_superviseur->SsDetails = $request->SsDetails;
                            $site_superviseur->SsFaction = $request->SsFaction;
                            $site_superviseur->SsIsOn = $request->SsIsOn;
                            $site_superviseur->SsUserCree = Auth::user()->id;
                            $site_superviseur->SsDateHCree = date('Y-m-d H:i:s');
                            $site_superviseur->save();
                            DB::commit();
                        }
                        catch (\Throwable $th){
                            // An error occured; cancel the transaction...
                            DB::rollback();
                            // and throw the error again.
                            throw $th;
                        }

                        return redirect()->route('sites-superviseur.index')->with('alert', 'Zone affectée au superviseur avec succès.');

                    } else {

                        $site_client = DB::table('sites_client')
                            ->where('sites_client.ScNumero',$request->SsSiteClient)
                            ->first();

                        if($site_client->ScIsMultiSuperv == 'N') {

                            $site_superviseur = DB::table('sites_superviseur')
                                ->where('sites_superviseur.SsSuperv',$request->SsSuperv)
                                ->where('sites_superviseur.SsSiteClient',$request->SsSiteClient)
                                ->where('sites_superviseur.SsEntite',Auth::user()->entite)
                                ->first();

                            if(empty($client)) {
                                try{
                                    // Do something and save to the db...
                                    $site_superviseur = new SiteSuperviseur();
                                    $site_superviseur->SsEntite = Auth::user()->entite;
                                    $site_superviseur->SsSuperv = $request->SsSuperv;
                                    $site_superviseur->SsMode = $request->SsMode;
                                    $site_superviseur->SsIsAllSites = $request->SsIsAllSites ?? 'N';
                                    $site_superviseur->SsSiteClient = NULL;
                                    $site_superviseur->SsDateDebut = $request->SsDateDebut;
                                    $site_superviseur->SsDetails = $request->SsDetails;
                                    $site_superviseur->SsFaction = $request->SsFaction;
                                    $site_superviseur->SsIsOn = $request->SsIsOn;
                                    $site_superviseur->SsUserCree = Auth::user()->id;
                                    $site_superviseur->SsDateHCree = date('Y-m-d H:i:s');
                                    $site_superviseur->save();
                                    DB::commit();
                                }
                                catch (\Throwable $th){
                                    // An error occured; cancel the transaction...
                                    DB::rollback();
                                    // and throw the error again.
                                    throw $th;
                                }

                                return redirect()->route('sites-superviseur.index')->with('alert', 'Site affecté au superviseur avec succès.');
                            }
                            else {
                                return redirect()->route('sites-superviseur.index')->with('alert-error', 'Ce site est déjà affecté à un superviseur.');
                            }
                        } else {
                            try{
                                    // Do something and save to the db...
                                    $site_superviseur = new SiteSuperviseur();
                                    $site_superviseur->SsEntite = Auth::user()->entite;
                                    $site_superviseur->SsSuperv = $request->SsSuperv;
                                    $site_superviseur->SsMode = $request->SsMode;
                                    $site_superviseur->SsIsAllSites = $request->SsIsAllSites ?? 'N';
                                    $site_superviseur->SsSiteClient = NULL;
                                    $site_superviseur->SsDateDebut = $request->SsDateDebut;
                                    $site_superviseur->SsDetails = $request->SsDetails;
                                    $site_superviseur->SsFaction = $request->SsFaction;
                                    $site_superviseur->SsIsOn = $request->SsIsOn;
                                    $site_superviseur->SsUserCree = Auth::user()->id;
                                    $site_superviseur->SsDateHCree = date('Y-m-d H:i:s');
                                    $site_superviseur->save();
                                    DB::commit();
                                }
                                catch (\Throwable $th){
                                    // An error occured; cancel the transaction...
                                    DB::rollback();
                                    // and throw the error again.
                                    throw $th;
                                }

                            return redirect()->route('sites-superviseur.index')->with('alert', 'Site affecté au superviseur avec succès.');
                        }
                    }
                }
            } else {
                return redirect()->route('sites-superviseur.index')->with('alert-error', 'Veuillez renseigner la ville de ce personnel.');
            }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $SsNumero)
    {
        return DB::table('sites_superviseur')
            ->leftJoin('personnels','personnels.PeNumero','=','sites_superviseur.SsSuperv')
            ->leftJoin('sites_client','sites_client.ScNumero','=','sites_superviseur.SsSiteClient')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
            ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->where('sites_superviseur.SsNumero',$SsNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $SsNumero)
    {
        $site_superviseur = DB::table('sites_superviseur')
            ->leftJoin('personnels','personnels.PeNumero','=','sites_superviseur.SsSuperv')
            ->leftJoin('sites_client','sites_client.ScNumero','=','sites_superviseur.SsSiteClient')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
            ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->where('sites_superviseur.SsNumero',$SsNumero)
            ->first();

        return response()->json($site_superviseur);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $SsNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('sites_superviseur')
            ->where('sites_superviseur.SsNumero',$SsNumero)
            ->update([
                'SsMode' => $request->SsMode,
                'SsIsAllSites' => $request->SsIsAllSites,
                'SsSuperv' => $request->SsSuperv,
                'SsSiteClient' => $request->SsSiteClient ?? NULL,
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


     /**
     * Show the form for editing the specified resource.
     */
    public function fin_supervision_edit(string $SsNumero)
    {
        $site_superviseur = DB::table('sites_superviseur')
            ->leftJoin('personnels','personnels.PeNumero','=','sites_superviseur.SsSuperv')
            ->leftJoin('sites_client','sites_client.ScNumero','=','sites_superviseur.SsSiteClient')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
            ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->where('sites_superviseur.SsNumero',$SsNumero)
            ->first();

        return response()->json($site_superviseur);
    }

    /**
     * Update the specified resource in storage.
     */
    public function fin_supervision_update(Request $request, string $SsNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('sites_superviseur')
            ->where('sites_superviseur.SsNumero',$SsNumero)
            ->update([
                'SsDateFin' => $request->SsDateFin,
                'SsIsOn' => 'N',
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

        return redirect()->route('sites-superviseur.index')->with('alert', 'Fin de supervision avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function activer($SsNumero)
    {
        DB::table('sites_superviseur')
            ->where('sites_superviseur.SsNumero',$SsNumero)
            ->update([
                'SsDateFin' => NULL,
                'SsIsOn' => 'Y'
            ]);
    }

    /*public function desactiver($SsNumero)
    {
        DB::table('sites_superviseur')
            ->where('sites_superviseur.SsNumero',$SsNumero)
            ->update([
                'sites_superviseur.SsIsOn' => 'N',
                'sites_superviseur.SsDateFin' => date('Y-m-d H:i:s')
            ]);
    }*/

    public function delete($SsNumero)
    {
        SiteSuperviseur::find($SsNumero)->delete();
    }


    public function generatePDF(Request $request)
    {
        if($request->PeNumero == 'all' && $request->ViNumero == 'all' && $request->ZoNumero == 'all' && $request->QuNumero == 'all' && $request->ScNumero == 'all' && $request->ClNumero == 'all'  && $request->faction_site == 'all')
        {
            $sites_superviseur = DB::table('sites_superviseur')
                ->leftJoin('personnels','personnels.PeNumero','=','sites_superviseur.SsSuperv')
                ->leftJoin('sites_client','sites_client.ScNumero','=','sites_superviseur.SsSiteClient')
                ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
                ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
                ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
                ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
                ->where('sites_superviseur.SsEntite',Auth::user()->entite)
                ->whereDate('sites_superviseur.SsDateDebut','>=',$request->start_date)
                ->whereDate('sites_superviseur.SsDateDebut','<=',$request->end_date)
                ->orderBy('sites_superviseur.SsDateHCree','asc')
                ->paginate(50);
        }
        else
        {
            // Handle other cases where not all parameters are 'all'
            $query = DB::table('sites_superviseur')
                ->leftJoin('personnels','personnels.PeNumero','=','sites_superviseur.SsSuperv')
                ->leftJoin('sites_client','sites_client.ScNumero','=','sites_superviseur.SsSiteClient')
                ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
                ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
                ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
                ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone');

            // Apply filters based on the request parameters
            if($request->PeNumero != 'all') {
                $query->where('sites_superviseur.SsSuperv',$request->PeNumero)
                    ->where('sites_superviseur.SsEntite',Auth::user()->entite)
                    ->whereDate('sites_superviseur.SsDateDebut','>=',$request->start_date)
                    ->whereDate('sites_superviseur.SsDateDebut','<=',$request->end_date);
            }

           // Apply filters based on the request parameters
            if($request->ViNumero != 'all') {
                $query->where('personnels.PeVille',$request->ViNumero)
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
                $query->where('personnels.PeQuartier',$request->QuNumero)
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
            $sites_superviseur = $query->orderBy('sites_superviseur.SsDateHCree','asc')->paginate(50);
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
    }


    public function filtre(Request $request)
    {
        if($request->PeNumero == 'all' && $request->ViNumero == 'all' && $request->ZoNumero == 'all' && $request->QuNumero == 'all' && $request->ScNumero == 'all' && $request->ClNumero == 'all'  && $request->faction_site == 'all')
        {
            $sites_superviseur = DB::table('sites_superviseur')
                ->leftJoin('personnels','personnels.PeNumero','=','sites_superviseur.SsSuperv')
                ->leftJoin('sites_client','sites_client.ScNumero','=','sites_superviseur.SsSiteClient')
                ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
                ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
                ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
                ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
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
                ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
                ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
                ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone');

            // Apply filters based on the request parameters
            if($request->PeNumero != 'all') {
                $query->where('sites_superviseur.SsSuperv',$request->PeNumero)
                    ->where('sites_superviseur.SsEntite',Auth::user()->entite)
                    ->whereDate('sites_superviseur.SsDateDebut','>=',$request->start_date)
                    ->whereDate('sites_superviseur.SsDateDebut','<=',$request->end_date);
            }

            // Apply filters based on the request parameters
            if($request->ViNumero != 'all') {
                $query->where('personnels.PeVille',$request->ViNumero)
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
                $query->where('personnels.PeQuartier',$request->QuNumero)
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

        $superviseurs = DB::table('personnels')
            ->whereIn('personnels.PeStatut', ['SG', 'SA', 'CT'])
            ->where('personnels.PeEntite', Auth::user()->entite)
            ->orderBy('personnels.PeMatric', 'asc')
            ->get();

        $sites_client = DB::table('sites_client')
            ->whereNotIn('sites_client.ScNumero', function($query) {
                $query->select('sites_superviseur.SsSiteClient')
                    ->from('sites_superviseur');
                })
            ->where('sites_client.ScIsOn','Y')
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->orderBy('sites_client.ScNomSite','asc')
            ->get();

       $superviseurs_1 = DB::table('personnels')
            ->whereIn('personnels.PeStatut', ['SG', 'SA', 'CT'])
            ->where('personnels.PeEntite', Auth::user()->entite)
            ->orderBy('personnels.PeMatric', 'asc')
            ->get();

        $clients = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->orderBy('clients.ClDesign','asc')
            ->get();

        $sites_client_1 = DB::table('sites_client')
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

        return view('sites-superviseur.filtre',compact(
            'sites_superviseur','PeNumero','ViNumero','ZoNumero','QuNumero','ScNumero',
            'start_date','end_date','superviseurs','sites_client','superviseurs_1',
            'sites_client_1','villes','zones','quartiers','clients','ClNumero','faction_site'
        ));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show_sites_zones($PeNumero)
    {
        $personnel = DB::table('personnels')
            ->where('personnels.PeNumero',$PeNumero)
            ->first();

        $str ='';

        $str.='<input type="hidden" class="form-control" id="statut" value="'.$personnel->PeStatut.'"/>';

        $ville = DB::table('villes')
            ->where('villes.ViNumero',$personnel->PeVille)
            ->first();

        $zones = DB::table('zones')
            ->where('zones.ZoVille',$personnel->PeVille)
            ->where('zones.ZoIsOn','Y')
            ->where('zones.ZoEntite',Auth::user()->entite)
            ->orderBy('zones.ZoNom','asc')
            ->get();

        if($ville){

            if($ville->ViIsAllSiteOn == 'Y') {

                $str.='<input type="hidden" class="form-control" id="statut" value="'.$personnel->PeStatut.'"/>
                        <input type="hidden" class="form-control" name="SsMode" value="S"/>
                        <input type="hidden" class="form-control" name="SsIsAllSites" value="Y"/>';

                $str.='
                    <div class="form-group">
                    <br>
                        <div class="alert alert-warning mt-1">
                            <i class="fa fa-exclamation-triangle"></i> Tous les sites sont à affecter au Superviseur
                        </div>
                    </div>';

            } else {

                if($ville->ViIsZoneOn == 'Y') {

                    $str.='
                        <input type="hidden" class="form-control" name="SsMode" value="Z"/>
                        <input type="hidden" class="form-control" name="SsIsAllSites" value="N"/>';

                    $str.='
                    <div class="form-group">
                        <label for="SsSiteClient">Zone <abbr style="color: #bd4147;">(*)</abbr></label>
                        <select class="e2 form-control" id="SsSiteClient" name="SsSiteClient" required>
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

                    $str.='<input type="hidden" class="form-control" name="SsMode" value="S"/>
                            <input type="hidden" class="form-control" name="SsIsAllSites" value="N"/>';

                    $str.='
                    <div class="form-group">
                    <br>
                        <div class="alert alert-warning mt-1">
                            <i class="fa fa-exclamation-triangle"></i> Contrôler que certains sites de la Ville
                        </div>
                    </div>';

                }
            }
        } else {
            $str.='
            <div class="form-group">
                <label for="SsSiteClient">Site Client <abbr style="color: #bd4147;">(*)</abbr></label>
                <select class="e2 form-control" id="SsSiteClient" name="SsSiteClient" required>
                    <option selected value="">Selectionner...</option>
                </select>
                <div class="alert alert-danger mt-1">
                    <i class="fa fa-exclamation-triangle"></i> Veuillez renseigner la ville de ce personnel
                </div>
            </div>';
        }

        $str.='
        <script>
            $(document).ready(function() {
                $(".e2").select2({
                    width: "100%"
                });
            });
        </script>';

        $str.='';

        echo $str;

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show_sites_zones_edit($PeNumero)
    {
        $personnel = DB::table('personnels')
            ->where('personnels.PeNumero',$PeNumero)
            ->first();

        $str ='';

        $str.='<input type="hidden" class="form-control" id="statut" value="'.$personnel->PeStatut.'"/>';

        $ville = DB::table('villes')
            ->where('villes.ViNumero',$personnel->PeVille)
            ->first();

        $zones = DB::table('zones')
            ->where('zones.ZoVille',$personnel->PeVille)
            ->where('zones.ZoIsOn','Y')
            ->where('zones.ZoEntite',Auth::user()->entite)
            ->orderBy('zones.ZoNom','asc')
            ->get();

        if($ville){

            if($ville->ViIsAllSiteOn == 'Y') {

                $str.='<input type="hidden" class="form-control" id="statut" value="'.$personnel->PeStatut.'"/>
                        <input type="hidden" class="form-control" name="SsMode" value="S"/>
                        <input type="hidden" class="form-control" name="SsIsAllSites" value="Y"/>';

                $str.='
                    <div class="form-group">
                    <br>
                        <div class="alert alert-warning mt-1">
                            <i class="fa fa-exclamation-triangle"></i> Tous les sites sont à affecter au Superviseur
                        </div>
                    </div>';

            } else {

                if($ville->ViIsZoneOn == 'Y') {

                    $str.='
                        <input type="hidden" class="form-control" name="SsMode" value="Z"/>
                        <input type="hidden" class="form-control" name="SsIsAllSites" value="N"/>';

                    $str.='
                    <div class="form-group">
                        <label for="SsSiteClient">Zone <abbr style="color: #bd4147;">(*)</abbr></label>
                        <select class="e2 form-control" id="SsSiteClient" name="SsSiteClient" required>
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

                    $str.='<input type="hidden" class="form-control" name="SsMode" value="S"/>
                            <input type="hidden" class="form-control" name="SsIsAllSites" value="N"/>';

                    $str.='
                    <div class="form-group">
                    <br>
                        <div class="alert alert-warning mt-1">
                            <i class="fa fa-exclamation-triangle"></i> Contrôler que certains sites de la Ville
                        </div>
                    </div>';

                }
            }
        } else {
            $str.='
            <div class="form-group">
                <label for="SsSiteClient">Site Client <abbr style="color: #bd4147;">(*)</abbr></label>
                <select class="e2 form-control" id="SsSiteClient" name="SsSiteClient" required>
                    <option selected value="">Selectionner...</option>
                </select>
                <div class="alert alert-danger mt-1">
                    <i class="fa fa-exclamation-triangle"></i> Veuillez renseigner la ville de ce personnel
                </div>
            </div>';
        }

        $str.='
        <script>
            $(document).ready(function() {
                $(".e2").select2({
                    width: "100%"
                });
            });
        </script>';

        $str.='';

        echo $str;

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function sites_supervises(string $SsNumero)
    {
        $SsNumero = decrypter($SsNumero);

        $site_superviseur = DB::table('sites_superviseur')
            ->leftJoin('personnels','personnels.PeNumero','=','sites_superviseur.SsSuperv')
            ->leftJoin('sites_client','sites_client.ScNumero','=','sites_superviseur.SsSiteClient')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
            ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->where('sites_superviseur.SsNumero',$SsNumero)
            ->first();

        $PeNumero = $site_superviseur->PeNumero;

        $sites_client = DB::table('sites_client')
            ->leftJoin('sites_superviseur','sites_superviseur.SsSiteClient','=','sites_client.ScNumero')
            ->whereNotIn('sites_client.ScNumero', function($query) use ($PeNumero) {
                $query->select('sites_superviseur.SsSiteClient')
                    ->from('sites_superviseur')
                    ->where('sites_superviseur.SsIsOn','Y')
                    ->where('sites_superviseur.SsSuperv',$PeNumero);
                })
            ->where('sites_client.ScIsOn','Y')
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->orderBy('sites_client.ScNomSite','asc')
            ->get();

        $agents = DB::table('personnels')
            ->whereNotIn('personnels.PeStatut', ['SG', 'SA', 'CT'])
            ->where('personnels.PeEntite', Auth::user()->entite)
            ->orderBy('personnels.PeNom', 'asc')
            ->get();

        $sites_supervises = DB::table('sites_agent_sup_ctr')
            ->leftJoin('personnels','personnels.PeNumero','=','sites_agent_sup_ctr.SaAgent')
            ->leftJoin('sites_client','sites_client.ScNumero','=','sites_agent_sup_ctr.SaSiteClt')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
            ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->where('sites_agent_sup_ctr.SaSupervCtrl',$site_superviseur->PeNumero)
            ->where('sites_agent_sup_ctr.SaEntite',Auth::user()->entite)
            ->get();

        return view('sites-superviseur.sites-supervises.list',compact('site_superviseur','sites_client','agents','sites_supervises'));
    }
}
