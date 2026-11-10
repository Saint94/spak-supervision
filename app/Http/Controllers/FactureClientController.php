<?php

namespace App\Http\Controllers;

use App\Models\FactureClient;
use App\Models\ReglementFact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class FactureClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $start_date = date('Y-m-d', strtotime('-3 months'));
        $end_date = date('Y-m-d');

        $clients = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->get();

        $factures = DB::table('factures_clients')
            ->leftJoin('clients','clients.ClNumero','=','factures_clients.FcClient')
            ->leftJoin('users','users.id','=','factures_clients.FcUserCree')
            ->whereDate('factures_clients.FcDate','>=',$start_date)
            ->whereDate('factures_clients.FcDate','<=',$end_date)
            ->where('factures_clients.FcEntite',Auth::user()->entite)
            ->get();


        $entite = DB::table('entites')
            ->where('entites.EnNumero', Auth::user()->entite)
            ->first();

        //Pour filtres
        $clients_f = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->get();

        return view('factures.list',compact(
            'factures','clients','clients_f','entite'
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

        try{
            // Do something and save to the db...
            $facture_client = new FactureClient();
            $facture_client->FcEntite = Auth::user()->entite;
            $facture_client->FcClient = $request->FcClient;
            $facture_client->FcNumFacture = $request->FcNumFacture;
            $facture_client->FcIsMoisPeriode = $request->FcIsMoisPeriode;
            $facture_client->FcDate = $request->FcDate;
            $facture_client->FcMois = $request->FcMois;
            $facture_client->FcAnnee = $request->FcAnnee;
            $facture_client->FcDateDebut = $request->FcDateDebut;
            $facture_client->FcDateFin = $request->FcDateFin;
            $facture_client->FcNbVigileJour = $request->FcNbVigileJour;
            $facture_client->FcNbVigileNuit = $request->FcNbVigileNuit;
            $facture_client->FcPrixUVigile = str_replace(' ', '',$request->FcPrixUVigile ?? '');
            $facture_client->FcNbreMCJour = $request->FcNbreMCJour;
            $facture_client->FcNbreMCNuit = $request->FcNbreMCNuit;
            $facture_client->FcPrixUMC = str_replace(' ', '',$request->FcPrixUMC ?? '');
            $facture_client->FcNbSupervJr = $request->FcNbSupervJr;
            $facture_client->FcNbSupervNu = $request->FcNbSupervNu;
            $facture_client->FcPrixUSuperv = str_replace(' ', '',$request->FcPrixUSuperv ?? '');
            $facture_client->FcNbComdoJr = $request->FcNbComdoJr;
            $facture_client->FcNbComdoNu = $request->FcNbComdoNu;
            $facture_client->FcPrixUComm = str_replace(' ', '',$request->FcPrixUComm ?? '');
            $facture_client->FcNbJourFact = $request->FcNbJourFact;
            $facture_client->FcNbNuitFact = $request->FcNbNuitFact;
            $facture_client->FcMontantHT = str_replace(' ', '',$request->FcMontantHT ?? '');
            $facture_client->FcMontantTTC = str_replace(' ', '',$request->FcMontantTTC ?? '');
            $facture_client->FcTotalPaye = str_replace(' ', '',$request->FcTotalPaye ?? '');
            $facture_client->FcMtSolde = str_replace(' ', '',$request->FcMtSolde ?? '');
            $facture_client->FcUserCree = Auth::user()->id;
            $facture_client->FcDetail = $request->FcDetail;
            $facture_client->FcDateHCree = date('Y-m-d H:i:s');

            if($request->FcTotalPaye >= $request->FcMontantTTC) {
                $facture_client->FcIsSolde = 'Y';
            } else {
                $facture_client->FcIsSolde = 'N';
            }

            $facture_client->save();

            if(str_replace(' ', '',$request->FcTotalPaye ?? '') > 0) {

                $reglement_client = new ReglementFact();
                $reglement_client->RfEntite = Auth::user()->entite;
                $reglement_client->RfFacture = $facture_client->FcNumero;
                $reglement_client->RfDate = $facture_client->FcDate;
                $reglement_client->RfMtAPayer = str_replace(' ', '',$facture_client->FcMtSolde ?? '');
                $reglement_client->RfMtPaye = str_replace(' ', '',$facture_client->FcTotalPaye ?? '');
                $reglement_client->RfModeRegl = 'E';
                $reglement_client->RfReferReglem = NULL;
                $reglement_client->RfNewSolde = (str_replace(' ', '',$facture_client->FcMtSolde ?? '') - str_replace(' ', '',$facture_client->FcTotalPaye ?? ''));
                $reglement_client->RfDetails = NULL;
                $reglement_client->RfIsValide = $request->RfIsValide;
                $reglement_client->RfUserCree = Auth::user()->id;
                $reglement_client->RfDateHCree = date('Y-m-d H:i:s');
                $reglement_client->save();

                $totals_reglements = DB::table('reglements_fact')
                    ->where('reglements_fact.RfFacture',$facture_client->FcNumero)
                    ->sum('reglements_fact.RfMtPaye');

                if($reglement_client->RfNewSolde == 0){
                    $FcIsSolde = 'Y';
                }else {
                    $FcIsSolde = 'N';
                }

                DB::table('factures_clients')
                    ->where('factures_clients.FcNumero',$facture_client->FcNumero)
                    ->update([
                        'FcTotalPaye' => $totals_reglements,
                        'FcMtSolde' => $reglement_client->RfNewSolde,
                        'FcIsSolde' => $FcIsSolde
                    ]);
            }


            $clients = DB::table('clients')
                ->where('clients.ClEntite',Auth::user()->entite)
                ->orderBy('clients.ClDesign','asc')
                ->get();

            if(!empty($clients))
            {
                for($i=0; $i<count($clients); $i++)
                {
                    $sommes = DB::table('factures_clients')
                        ->where('factures_clients.FcClient', $clients[$i]->ClNumero)
                        ->selectRaw('SUM(factures_clients.FcMontantTTC) as mt_total, SUM(factures_clients.FcTotalPaye) as total_paye')
                        ->first();

                    DB::table('clients')
                        ->where('clients.ClNumero',$clients[$i]->ClNumero)
                        ->update(['clients.ClMtImpayes' => ($sommes->mt_total-$sommes->total_paye)]);
                }

                for($i=0; $i<count($clients); $i++)
                {
                    $factures_impayees = DB::table('factures_clients')
                        ->select(
                            'factures_clients.*',
                            DB::raw('TIMESTAMPDIFF(MONTH, factures_clients.FcDate, CURDATE()) as mois_ecoules'),
                            DB::raw('DATEDIFF(CURDATE(), factures_clients.FcDate) as jours_ecoules'),
                            DB::raw('ROUND(DATEDIFF(CURDATE(), factures_clients.FcDate) / 30.44, 1) as mois_approx')
                        )
                        ->where('factures_clients.FcClient', $clients[$i]->ClNumero)
                        ->where('factures_clients.FcIsSolde', 'N')
                        ->orderBy('factures_clients.FcDate', 'asc') // La plus ancienne d'abord
                        ->first(); // Prendre la première (la plus ancienne)

                    if ($factures_impayees) {
                        DB::table('clients')
                            ->where('clients.ClNumero', $clients[$i]->ClNumero)
                            ->update([
                                'clients.ClNbMoisImp' => $factures_impayees->mois_ecoules
                            ]);
                    } else {
                        // Pas de facture impayée
                        DB::table('clients')
                            ->where('clients.ClNumero', $clients[$i]->ClNumero)
                            ->update(['clients.ClNbMoisImp' => 0]);
                    }
                }
            }

            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->back()->with('alert', 'Facture client enregistrée avec succès.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $FcNumero)
    {
        return DB::table('factures_clients')
            ->leftJoin('clients','clients.ClNumero','=','factures_clients.FcClient')
            ->leftJoin('users','users.id','=','factures_clients.FcUserCree')
            ->where('factures_clients.FcNumero',$FcNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $FcNumero)
    {
        $facture_client = DB::table('factures_clients')
            ->leftJoin('clients','clients.ClNumero','=','factures_clients.FcClient')
            ->leftJoin('users','users.id','=','factures_clients.FcUserCree')
            ->where('factures_clients.FcNumero',$FcNumero)
            ->first();

        return response()->json($facture_client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $FcNumero)
    {
        DB::beginTransaction();

        if($request->FcTotalPaye >= $request->FcMontantTTC) {
                $FcIsSolde = 'Y';
            } else {
                $FcIsSolde = 'N';
            }

        try{
            // Do something and save to the db...
            DB::table('factures_clients')
            ->where('factures_clients.FcNumero',$FcNumero)
            ->update([
                'FcNumFacture' => $request->FcNumFacture,
                'FcIsMoisPeriode' => $request->FcIsMoisPeriode,
                'FcDate' => $request->FcDate,
                'FcMois' => $request->FcMois,
                'FcAnnee' => $request->FcAnnee,
                'FcDateDebut' => $request->FcDateDebut,
                'FcDateFin' => $request->FcDateFin,
                'FcNbVigileJour' => $request->FcNbVigileJour,
                'FcNbVigileNuit' => $request->FcNbVigileNuit,
                'FcPrixUVigile' => str_replace(' ', '',$request->FcPrixUVigile ?? ''),
                'FcNbreMCJour' => $request->FcNbreMCJour,
                'FcNbreMCNuit' => $request->FcNbreMCNuit,
                'FcPrixUMC' => str_replace(' ', '',$request->FcPrixUMC ?? ''),
                'FcNbSupervJr' => $request->FcNbSupervJr,
                'FcNbSupervNu' => $request->FcNbSupervNu,
                'FcPrixUSuperv' => str_replace(' ', '',$request->FcPrixUSuperv ?? ''),
                'FcNbComdoJr' => $request->FcNbComdoJr,
                'FcNbComdoNu' => $request->FcNbComdoNu,
                'FcPrixUComm' => str_replace(' ', '',$request->FcPrixUComm ?? ''),
                'FcNbJourFact' => $request->FcNbJourFact,
                'FcNbNuitFact' => $request->FcNbNuitFact,
                'FcMontantHT' => str_replace(' ', '',$request->FcMontantHT ?? ''),
                'FcMontantTTC' => str_replace(' ', '',$request->FcMontantTTC ?? ''),
                'FcTotalPaye' => str_replace(' ', '',$request->FcTotalPaye ?? ''),
                'FcMtSolde' => str_replace(' ', '',$request->FcMtSolde ?? ''),
                'FcIsSolde' => $FcIsSolde,
                'FcDetail' => $request->FcDetail
            ]);

            if($request->FcTotalPaye > 0) {

                DB::table('reglements_fact')
                ->where('reglements_fact.RfFacture',$FcNumero)
                ->update([
                    'RfEntite' => Auth::user()->entite,
                    'RfDate' => $request->FcDate,
                    'RfMtAPayer' => str_replace(' ', '',$request->FcMtSolde ?? ''),
                    'RfMtPaye' => str_replace(' ', '',$request->FcTotalPaye ?? ''),
                    'RfModeRegl' => 'E',
                    'RfReferReglem' => NULL,
                    'RfNewSolde' => (str_replace(' ', '',$request->FcMtSolde ?? '') - str_replace(' ', '',$request->FcTotalPaye ?? '')),
                    'RfDetails' => NULL,
                    'RfIsValide' => 'Y',
                    'RfUserCree' => Auth::user()->id,
                    'RfDateHCree' => date('Y-m-d H:i:s')
                ]);

                $totals_reglements = DB::table('reglements_fact')
                    ->where('reglements_fact.RfFacture',$FcNumero)
                    ->sum('reglements_fact.RfMtPaye');

                if((str_replace(' ', '',$request->FcMtSolde ?? '') - str_replace(' ', '',$request->FcTotalPaye ?? '')) == 0){
                    $FcIsSolde = 'Y';
                }else {
                    $FcIsSolde = 'N';
                }

                DB::table('factures_clients')
                    ->where('factures_clients.FcNumero',$FcNumero)
                    ->update([
                        'FcTotalPaye' => $totals_reglements,
                        'FcMtSolde' => (str_replace(' ', '',$request->FcMtSolde ?? '') - str_replace(' ', '',$request->FcTotalPaye ?? '')),
                        'FcIsSolde' => $FcIsSolde
                    ]);
            }

            $clients = DB::table('clients')
                ->where('clients.ClEntite',Auth::user()->entite)
                ->orderBy('clients.ClDesign','asc')
                ->get();

            if(!empty($clients))
            {
                for($i=0; $i<count($clients); $i++)
                {
                    $sommes = DB::table('factures_clients')
                        ->where('factures_clients.FcClient', $clients[$i]->ClNumero)
                        ->selectRaw('SUM(factures_clients.FcMontantTTC) as mt_total, SUM(factures_clients.FcTotalPaye) as total_paye')
                        ->first();

                    DB::table('clients')
                        ->where('clients.ClNumero',$clients[$i]->ClNumero)
                        ->update(['clients.ClMtImpayes' => ($sommes->mt_total-$sommes->total_paye)]);
                }

                for($i=0; $i<count($clients); $i++)
                {
                    $factures_impayees = DB::table('factures_clients')
                        ->select(
                            'factures_clients.*',
                            DB::raw('TIMESTAMPDIFF(MONTH, factures_clients.FcDate, CURDATE()) as mois_ecoules'),
                            DB::raw('DATEDIFF(CURDATE(), factures_clients.FcDate) as jours_ecoules'),
                            DB::raw('ROUND(DATEDIFF(CURDATE(), factures_clients.FcDate) / 30.44, 1) as mois_approx')
                        )
                        ->where('factures_clients.FcClient', $clients[$i]->ClNumero)
                        ->where('factures_clients.FcIsSolde', 'N')
                        ->orderBy('factures_clients.FcDate', 'asc') // La plus ancienne d'abord
                        ->first(); // Prendre la première (la plus ancienne)

                    if ($factures_impayees) {
                        DB::table('clients')
                            ->where('clients.ClNumero', $clients[$i]->ClNumero)
                            ->update([
                                'clients.ClNbMoisImp' => $factures_impayees->mois_ecoules
                            ]);
                    } else {
                        // Pas de facture impayée
                        DB::table('clients')
                            ->where('clients.ClNumero', $clients[$i]->ClNumero)
                            ->update(['clients.ClNbMoisImp' => 0]);
                    }
                }
            }

            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->back()->with('alert', 'Facture client modifiée avec succès.');
    }

    public function filtre(Request $request)
    {
        $clients = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->get();

        if($request->numero_client == 'all' && $request->statut_facture == 'all')
        {
            $factures = DB::table('factures_clients')
                ->leftJoin('clients','clients.ClNumero','=','factures_clients.FcClient')
                ->leftJoin('users','users.id','=','factures_clients.FcUserCree')
                ->whereDate('factures_clients.FcDate','>=',$request->start_date)
                ->whereDate('factures_clients.FcDate','<=',$request->end_date)
                ->where('factures_clients.FcEntite',Auth::user()->entite)
                ->get();
        }
        else
        {
            // Handle other cases where not all parameters are 'all'
            $query = DB::table('factures_clients')
                ->leftJoin('clients','clients.ClNumero','=','factures_clients.FcClient')
                ->leftJoin('users','users.id','=','factures_clients.FcUserCree');

            // Apply filters based on the request parameters
            if($request->numero_client != 'all') {
                $query->where('factures_clients.FcClient',$request->numero_client)
                    ->whereDate('factures_clients.FcDate','>=',$request->start_date)
                    ->whereDate('factures_clients.FcDate','<=',$request->end_date)
                    ->where('factures_clients.FcEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->statut_facture != 'all') {
                $query->where('factures_clients.FcIsSolde',$request->statut_facture)
                    ->whereDate('factures_clients.FcDate','>=',$request->start_date)
                    ->whereDate('factures_clients.FcDate','<=',$request->end_date)
                    ->where('factures_clients.FcEntite',Auth::user()->entite);
            }

            // Execute the query with the applied filters
            $factures = $query->get();
        }

        $start_date =  $request->start_date;
        $end_date =  $request->end_date;
        $numero_client = $request->numero_client;
        $statut_facture = $request->statut_facture;

        $entite = DB::table('entites')
            ->where('entites.EnNumero', Auth::user()->entite)
            ->first();

        //Pour filtres
        $clients_f = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->get();

        return view('factures.filtre',compact(
            'start_date','end_date','numero_client',
            'statut_facture','factures','clients',
            'clients_f','entite'
        ));

    }

    public function show_info_client_contrat(string $FcClient)
    {
        $client = DB::table('clients')
            ->leftJoin('villes','villes.ViNumero','=','clients.ClVilleSiege')
            ->where('clients.ClNumero', $FcClient)
            ->where('clients.ClEntite', Auth::user()->entite)
            ->first();

        $contrat = DB::table('avenants_contrat')
            ->leftJoin('clients', 'clients.ClNumero', '=', 'avenants_contrat.AcClient')
            ->where('clients.ClNumero', $FcClient)
            ->where('avenants_contrat.AcIsOn', 'Y')
            ->orderBy('avenants_contrat.AcNumero', 'desc')
            ->first();

        $str = '';

        // Utilisation de la classe "cache-field" pour les champs cachés
        $str .= '
            <input type="hidden" class="form-control cache-field" id="cache_ViNom" value="' . $client->ViNom . '"/>
            <input type="hidden" class="form-control cache-field" id="cache_ClIsTVAOn" value="' . $client->ClIsTVAOn . '"/>
        ';

        if ($contrat) {
            $str .= '
                <input type="hidden" class="form-control cache-field" id="cache_FcDateDebut" value="' . $contrat->AcDateEffet . '"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcPrixUVigile" value="' . $contrat->AcPrixAgent . '"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcNbVigileJour" value="' . $contrat->AcNbAgentJr . '"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcNbVigileNuit" value="' . $contrat->AcNbAgentNu . '"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcPrixUMC" value="' . $contrat->AcPrixUMC . '"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcNbreMCJour" value="' . $contrat->AcNbreMCJour . '"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcNbreMCNuit" value="' . $contrat->AcNbreMCNuit . '"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcPrixUSuperv" value="' . $contrat->AcPrixUSuperv . '"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcNbSupervJr" value="' . $contrat->AcNbSupervJr . '"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcNbSupervNu" value="' . $contrat->AcNbSupervNu . '"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcPrixUComm" value="' . $contrat->AcPrixUComm . '"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcNbComdoJr" value="' . $contrat->AcNbComdoJr . '"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcNbComdoNu" value="' . $contrat->AcNbComdoNu . '"/>
            ';
        } else {
            $str .= '
                <input type="hidden" class="form-control cache-field" id="cache_FcDateDebut" value="' . date('Y-m-d') . '"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcPrixUVigile" value="0"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcNbVigileJour" value="0"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcNbVigileNuit" value="0"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcPrixUMC" value="0"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcNbreMCJour" value="0"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcNbreMCNuit" value="0"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcPrixUSuperv" value="0"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcNbSupervJr" value="0"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcNbSupervNu" value="0"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcPrixUComm" value="0"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcNbComdoJr" value="0"/>
                <input type="hidden" class="form-control cache-field" id="cache_FcNbComdoNu" value="0"/>
            ';
        }

        echo $str;
    }

    public function show_info_client_contrat_edit(string $FcClient)
    {
        $client = DB::table('clients')
            ->leftJoin('villes','villes.ViNumero','=','clients.ClVilleSiege')
            ->where('clients.ClNumero', $FcClient)
            ->where('clients.ClEntite', Auth::user()->entite)
            ->first();

        $contrat = DB::table('avenants_contrat')
            ->leftJoin('clients', 'clients.ClNumero', '=', 'avenants_contrat.AcClient')
            ->where('clients.ClNumero', $FcClient)
            ->where('avenants_contrat.AcIsOn', 'Y')
            ->orderBy('avenants_contrat.AcNumero', 'desc')
            ->first();

        $str = '';

        // Utilisation de la classe "cache-field" pour les champs cachés
        $str .= '
            <input type="hidden" class="form-control cache-field cache_ViNom" value="' . $client->ViNom . '"/>
            <input type="hidden" class="form-control cache-field cache_ClIsTVAOn" value="' . $client->ClIsTVAOn . '"/>
        ';

        if ($contrat) {
            $str .= '
                <input type="hidden" class="form-control cache-field cache_FcDateDebut" value="' . $contrat->AcDateEffet . '"/>
                <input type="hidden" class="form-control cache-field cache_FcPrixUVigile" value="' . $contrat->AcPrixAgent . '"/>
                <input type="hidden" class="form-control cache-field cache_FcNbVigileJour" value="' . $contrat->AcNbAgentJr . '"/>
                <input type="hidden" class="form-control cache-field cache_FcNbVigileNuit" value="' . $contrat->AcNbAgentNu . '"/>
                <input type="hidden" class="form-control cache-field cache_FcPrixUMC" value="' . $contrat->AcPrixUMC . '"/>
                <input type="hidden" class="form-control cache-field cache_FcNbreMCJour" value="' . $contrat->AcNbreMCJour . '"/>
                <input type="hidden" class="form-control cache-field cache_FcNbreMCNuit" value="' . $contrat->AcNbreMCNuit . '"/>
                <input type="hidden" class="form-control cache-field cache_FcPrixUSuperv" value="' . $contrat->AcPrixUSuperv . '"/>
                <input type="hidden" class="form-control cache-field cache_FcNbSupervJr" value="' . $contrat->AcNbSupervJr . '"/>
                <input type="hidden" class="form-control cache-field cache_FcNbSupervNu" value="' . $contrat->AcNbSupervNu . '"/>
                <input type="hidden" class="form-control cache-field cache_FcPrixUComm" value="' . $contrat->AcPrixUComm . '"/>
                <input type="hidden" class="form-control cache-field cache_FcNbComdoJr" value="' . $contrat->AcNbComdoJr . '"/>
                <input type="hidden" class="form-control cache-field cache_FcNbComdoNu" value="' . $contrat->AcNbComdoNu . '"/>
            ';
        } else {
            $str .= '
                <input type="hidden" class="form-control cache-field cache_FcDateDebut" value="' . date('Y-m-d') . '"/>
                <input type="hidden" class="form-control cache-field cache_FcPrixUVigile" value="0"/>
                <input type="hidden" class="form-control cache-field cache_FcNbVigileJour" value="0"/>
                <input type="hidden" class="form-control cache-field cache_FcNbVigileNuit" value="0"/>
                <input type="hidden" class="form-control cache-field cache_FcPrixUMC" value="0"/>
                <input type="hidden" class="form-control cache-field cache_FcNbreMCJour" value="0"/>
                <input type="hidden" class="form-control cache-field cache_FcNbreMCNuit" value="0"/>
                <input type="hidden" class="form-control cache-field cache_FcPrixUSuperv" value="0"/>
                <input type="hidden" class="form-control cache-field cache_FcNbSupervJr" value="0"/>
                <input type="hidden" class="form-control cache-field cache_FcNbSupervNu" value="0"/>
                <input type="hidden" class="form-control cache-field cache_FcPrixUComm" value="0"/>
                <input type="hidden" class="form-control cache-field cache_FcNbComdoJr" value="0"/>
                <input type="hidden" class="form-control cache-field cache_FcNbComdoNu" value="0"/>
            ';
        }

        echo $str;
    }
}
