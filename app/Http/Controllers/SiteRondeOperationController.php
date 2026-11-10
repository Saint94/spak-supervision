<?php

namespace App\Http\Controllers;

use App\Models\SiteRondeOper;
use App\Models\TemporaryDocPlanAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiteRondeOperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       //
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
        $site_ronde_oper = DB::table('sites_ronde_oper')
            ->where('sites_ronde_oper.SrRondeSuperv',$request->SrRondeSuperv)
            ->where('sites_ronde_oper.SrClient',$request->SrClient)
            ->where('sites_ronde_oper.SrSiteClient',$request->SrSiteClient)
            ->where('sites_ronde_oper.SrEntite',Auth::user()->entite)
            ->first();

        if(empty($site_ronde_oper)) {

            DB::beginTransaction();

            try{
                // Do something and save to the db...
                $site_ronde_oper = new SiteRondeOper();
                $site_ronde_oper->SrEntite = Auth::user()->entite;
                $site_ronde_oper->SrRondeSuperv = $request->SrRondeSuperv;
                $site_ronde_oper->SrClient = $request->SrClient;
                $site_ronde_oper->SrSiteClient = $request->SrSiteClient;
                $site_ronde_oper->SrDateDebut = $request->SrDateDebut;
                $site_ronde_oper->SrHeureDebut = $request->SrHeureDebut;
                $site_ronde_oper->SrDateFin = $request->SrDateFin ?? NULL;
                $site_ronde_oper->SrHeureFin = $request->SrHeureFin ?? NULL;
                $site_ronde_oper->SrTitre = $request->SrTitre;
                $site_ronde_oper->SrStatut = $request->SrStatut;
                $site_ronde_oper->SrTypeNonConform = $request->SrTypeNonConform;
                $site_ronde_oper->SrIsPlanAction = $request->SrIsPlanAction;
                $site_ronde_oper->SrDateLimPlanAct = $request->SrDateLimPlanAct ?? NULL;
                $site_ronde_oper->SrResumePlanAct = $request->SrResumePlanAct ?? NULL;
                $site_ronde_oper->SrIsPlanActionOk = $request->SrIsPlanActionOk;

                $temporaryPlanAction = TemporaryDocPlanAction::where('folder',$request->SrDocumPlanAct)->first();

                if($temporaryPlanAction)
                {
                    $site_ronde_oper->SrDocumPlanAct = $temporaryPlanAction->file;
                }

                $site_ronde_oper->save();

                DB::commit();
            }
            catch (\Throwable $th){
                // An error occured; cancel the transaction...
                DB::rollback();
                // and throw the error again.
                throw $th;
            }

            DB::table('temporary_doc_plan_action')->truncate();

            return redirect()->back()->with('alert', 'Site ronde crée avec succès.');
        }
        else {
            return redirect()->back()->with('alert-error', 'Ce site ronde existe déjà.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $SrNumero )
    {
        return DB::table('sites_ronde_oper')
            ->leftJoin('ronde_operations','ronde_operations.RoNumero','=','sites_ronde_oper.SrRondeSuperv')
            ->leftJoin('clients','clients.ClNumero','=','sites_ronde_oper.SrClient')
            ->leftJoin('sites_client','sites_client.ScNumero','=','sites_ronde_oper.SrSiteClient')
            ->where('sites_ronde_oper.SrNumero',$SrNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $SrNumero)
    {
        $site_ronde_oper = DB::table('sites_ronde_oper')
            ->where('sites_ronde_oper.SrNumero',$SrNumero)
            ->first();

        return response()->json($site_ronde_oper);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $SrNumero)
    {
        $temporaryPlanAction = TemporaryDocPlanAction::where('folder',$request->SrDocumPlanAct)->first();

        DB::beginTransaction();

        try{
           if($temporaryPlanAction)
            {
                DB::table('sites_ronde_oper')
                    ->where('sites_ronde_oper.SrNumero',$SrNumero)
                    ->update([
                        'SrClient' => $request->SrClient,
                        'SrSiteClient' => $request->SrSiteClient,
                        'SrDateDebut' => $request->SrDateDebut,
                        'SrHeureDebut' => $request->SrHeureDebut,
                        'SrDateFin' => $request->SrDateFin,
                        'SrHeureFin' => $request->SrHeureFin,
                        'SrTitre' => $request->SrTitre,
                        'SrStatut' => $request->SrStatut,
                        'SrDocumPlanAct' => $temporaryPlanAction->file,
                        'SrTypeNonConform' => $request->SrTypeNonConform,
                        'SrIsPlanAction' => $request->SrIsPlanAction,
                        'SrDateLimPlanAct' => $request->SrDateLimPlanAct,
                        'SrResumePlanAct' => $request->SrResumePlanAct,
                        'SrIsPlanActionOk' => $request->SrIsPlanActionOk
                    ]);
            }else {
                DB::table('sites_ronde_oper')
                    ->where('sites_ronde_oper.SrNumero',$SrNumero)
                    ->update([
                        'SrClient' => $request->SrClient,
                        'SrSiteClient' => $request->SrSiteClient,
                        'SrDateDebut' => $request->SrDateDebut,
                        'SrHeureDebut' => $request->SrHeureDebut,
                        'SrDateFin' => $request->SrDateFin,
                        'SrHeureFin' => $request->SrHeureFin,
                        'SrTitre' => $request->SrTitre,
                        'SrStatut' => $request->SrStatut,
                        'SrTypeNonConform' => $request->SrTypeNonConform,
                        'SrIsPlanAction' => $request->SrIsPlanAction,
                        'SrDateLimPlanAct' => $request->SrDateLimPlanAct,
                        'SrResumePlanAct' => $request->SrResumePlanAct,
                        'SrIsPlanActionOk' => $request->SrIsPlanActionOk
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

        DB::table('temporary_doc_plan_action')->truncate();

        return redirect()->back()->with('alert', 'Site ronde modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show_sites_client($SrClient)
    {
        $sites_client = DB::table('sites_client')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->leftJoin('villes','villes.ViNumero','=','sites_client.ScVille')
            ->leftJoin('zones','zones.ZoNumero','=','sites_client.ScZone')
            ->leftJoin('quartiers','quartiers.QuNumero','=','sites_client.ScQuartier')
            ->where('sites_client.ScClient',$SrClient)
            ->get();

        
        $str ='';

        $str.='
            <div class="form-group">
                <label for="SrSiteClient">Site client <abbr style="color: #bd4147;">(*)</abbr></label>
                <select class="e2 form-control" id="SrSiteClient" name="SrSiteClient" required>
                    <option selected value="">Selectionner...</option>';
                    foreach($sites_client as $site):
                        $str.='<option value="'.$site->ScNumero.'">'.$site->ScNomSite.'</option>';
                    endforeach;
                $str.='</select>
                <div class="invalid-feedback">
                    Ce champ est obligatoire.
                </div>
            </div>';
       

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
    public function show_sites_client_edit($SrClient)
    {
        $sites_client = DB::table('sites_client')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->leftJoin('villes','villes.ViNumero','=','sites_client.ScVille')
            ->leftJoin('zones','zones.ZoNumero','=','sites_client.ScZone')
            ->leftJoin('quartiers','quartiers.QuNumero','=','sites_client.ScQuartier')
            ->where('sites_client.ScClient',$SrClient)
            ->get();
        
        $str ='';

        $str.='
            <div class="form-group">
                <label for="SrSiteClient">Site client <abbr style="color: #bd4147;">(*)</abbr></label>
                <select class="e2 form-control" id="SrSiteClient" name="SrSiteClient" required>
                    <option selected value="">Selectionner...</option>';
                    foreach($sites_client as $site):
                        $str.='<option value="'.$site->ScNumero.'">'.$site->ScNomSite.'</option>';
                    endforeach;
                $str.='</select>
                <div class="invalid-feedback">
                    Ce champ est obligatoire.
                </div>
            </div>';
       

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


    public function filtre(Request $request)
    {
        if($request->site_client == 'all')
        {
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

}
