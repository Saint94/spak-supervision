<?php

namespace App\Http\Controllers;

use App\Models\ReglementFact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ReglementFactureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-d');

        $clients = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->get();

        $reglements = DB::table('reglements_fact')
            ->leftJoin('factures_clients', 'factures_clients.FcNumero', '=', 'reglements_fact.RfFacture')
            ->leftJoin('clients', 'clients.ClNumero', '=', 'factures_clients.FcClient')
            ->leftJoin('users','users.id','=','factures_clients.FcUserCree')
            ->whereDate('reglements_fact.RfDate','>=',$start_date)
            ->whereDate('reglements_fact.RfDate','<=',$end_date)
            ->where('reglements_fact.RfEntite',Auth::user()->entite)
            ->get();

        //Pour filtres
        $clients_f = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->get();

        return view('reglements.list',compact('reglements','clients','clients_f'));
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
            $reglement_client = new ReglementFact();
            $reglement_client->RfEntite = Auth::user()->entite;
            $reglement_client->RfFacture = $request->RfFacture;
            $reglement_client->RfDate = $request->RfDate;
            $reglement_client->RfMtAPayer = $request->RfMtAPayer;
            $reglement_client->RfMtPaye = $request->RfMtPaye;
            $reglement_client->RfModeRegl = $request->RfModeRegl;
            $reglement_client->RfReferReglem = $request->RfReferReglem;
            $reglement_client->RfNewSolde = $request->RfNewSolde;
            $reglement_client->RfDetails = $request->RfDetails;
            $reglement_client->RfIsValide = $request->RfIsValide;
            $reglement_client->RfUserCree = Auth::user()->id;
            $reglement_client->RfDateHCree = date('Y-m-d H:i:s');
            $reglement_client->save();

            $totals_reglements = DB::table('reglements_fact')
                ->where('reglements_fact.RfFacture',$request->RfFacture)
                ->sum('reglements_fact.RfMtPaye');

            if($request->RfNewSolde == 0){
                $FcIsSolde = 'Y';
            }else {
                $FcIsSolde = 'N';
            }

            DB::table('factures_clients')
                ->where('factures_clients.FcNumero',$request->RfFacture)
                ->update([
                    'FcTotalPaye' => $totals_reglements,
                    'FcMtSolde' => $request->RfNewSolde,
                    'FcIsSolde' => $FcIsSolde
                ]);


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
            }

            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->back()->with('alert', 'Reglèment client enregistré avec succès.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $RfNumero)
    {
        return DB::table('reglements_fact')
            ->leftJoin('factures_clients', 'factures_clients.FcNumero', '=', 'reglements_fact.RfFacture')
            ->leftJoin('clients', 'clients.ClNumero', '=', 'factures_clients.FcClient')
            ->leftJoin('users','users.id','=','factures_clients.FcUserCree')
            ->where('reglements_fact.RfNumero',$RfNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $RfNumero )
    {
        $reglement_client = DB::table('reglements_fact')
            ->leftJoin('factures_clients', 'factures_clients.FcNumero', '=', 'reglements_fact.RfFacture')
            ->leftJoin('clients', 'clients.ClNumero', '=', 'factures_clients.FcClient')
            ->leftJoin('users','users.id','=','factures_clients.FcUserCree')
            ->where('reglements_fact.RfNumero',$RfNumero)
            ->first();

        return response()->json($reglement_client);
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
            /*DB::table('factures_clients')
            ->where('factures_clients.FcNumero',$FcNumero)
            ->update([
                'FcNumFacture' => $request->FcNumFacture,
                'FcDate' => $request->FcDate,
                'FcMois' => $request->FcMois,
                'FcAnnee' => $request->FcAnnee,
                'FcDateDebut' => $request->FcDateDebut,
                'FcDateFin' => $request->FcDateFin,
                'FcNbVigileJour' => $request->FcNbVigileJour,
                'FcNbVigileNuit' => $request->FcNbVigileNuit,
                'FcPrixUVigile' => $request->FcPrixUVigile,
                'FcNbJourFact' => $request->FcNbJourFact,
                'FcNbNuitFact' => $request->FcNbNuitFact,
                'FcMontantHT' => $request->FcMontantHT,
                'FcMontantTTC' => $request->FcMontantTTC,
                'FcTotalPaye' => $request->FcTotalPaye,
                'FcMtSolde' => $request->FcMtSolde,
                'FcIsSolde' => $FcIsSolde,
                'FcDetail' => $request->FcDetail
            ]);*/
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
            ->orderBy('clients.ClDesign','asc')
            ->get();

        if($request->numero_client == 'all')
        {
            $reglements = DB::table('reglements_fact')
                ->leftJoin('factures_clients', 'factures_clients.FcNumero', '=', 'reglements_fact.RfFacture')
                ->leftJoin('clients', 'clients.ClNumero', '=', 'factures_clients.FcClient')
                ->leftJoin('users','users.id','=','factures_clients.FcUserCree')
                ->whereDate('reglements_fact.RfDate','>=',$request->start_date)
                ->whereDate('reglements_fact.RfDate','<=',$request->end_date)
                ->where('reglements_fact.RfEntite',Auth::user()->entite)
                ->get();
        }
        else
        {
            // Handle other cases where not all parameters are 'all'
            $query = DB::table('reglements_fact')
                ->leftJoin('factures_clients', 'factures_clients.FcNumero', '=', 'reglements_fact.RfFacture')
                ->leftJoin('clients', 'clients.ClNumero', '=', 'factures_clients.FcClient')
                ->leftJoin('users','users.id','=','factures_clients.FcUserCree');

            // Apply filters based on the request parameters
            if($request->numero_client != 'all') {
                $query->where('factures_clients.FcClient',$request->numero_client)
                    ->whereDate('reglements_fact.RfDate','>=',$request->start_date)
                    ->whereDate('reglements_fact.RfDate','<=',$request->end_date)
                    ->where('reglements_fact.RfEntite',Auth::user()->entite);
            }

            // Execute the query with the applied filters
            $reglements = $query->get();
        }

        $start_date =  $request->start_date;
        $end_date =  $request->end_date;
        $numero_client = $request->numero_client;

        //Pour filtres
        $clients_f = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->get();

        return view('reglements.filtre',compact(
            'start_date','end_date','numero_client',
            'reglements','clients','clients_f'
        ));
    }


    public function show_factures_client(string $FcClient)
    {
        $factures = DB::table('factures_clients')
            ->leftJoin('clients','clients.ClNumero','=','factures_clients.FcClient')
            ->leftJoin('users','users.id','=','factures_clients.FcUserCree')
            ->where('factures_clients.FcIsSolde','N')
            ->where('factures_clients.FcClient',$FcClient)
            ->where('factures_clients.FcEntite',Auth::user()->entite)
            ->get();

            $str ='';

            $str.='
                <div class="form-group">
                    <label for="RfFacture">Factures non réglées</label>
                    <select id="RfFacture" name="RfFacture" class="form-control e30" required>
                        <option selected value="">Selectionner...</option>';
                        foreach($factures as $facture):
                            $str.='<option value="'.$facture->FcNumero.'">'.$facture->FcNumFacture.'</option>';
                        endforeach;
                    $str.='
                    </select>
                    <div class="invalid-feedback">
                        Ce champ est obligatoire.
                    </div>
                </div>';

        $str.='';

        $str.='
        <script>
            $(document).ready(function() {
                $(".e30").select2({
                    width: "100%"
                });
            });
        </script>';

        echo $str;
    }

    public function show_factures_client_edit(string $FcClient)
    {
        $factures = DB::table('factures_clients')
            ->leftJoin('clients','clients.ClNumero','=','factures_clients.FcClient')
            ->leftJoin('users','users.id','=','factures_clients.FcUserCree')
            ->where('factures_clients.FcIsSolde','N')
            ->where('factures_clients.FcClient',$FcClient)
            ->where('factures_clients.FcEntite',Auth::user()->entite)
            ->get();

            $str ='';

            $str.='
                <div class="form-group">
                    <label for="RfFacture">Factures non réglées</label>
                    <select id="RfFacture" name="RfFacture" class="form-control e30 RfFacture" required>
                        <option selected value="">Selectionner...</option>';
                        foreach($factures as $facture):
                            $str.='<option value="'.$facture->FcNumero.'">'.$facture->FcNumFacture.'</option>';
                        endforeach;
                    $str.='
                    </select>
                    <div class="invalid-feedback">
                        Ce champ est obligatoire.
                    </div>
                </div>';

        $str.='';

        $str.='
        <script>
            $(document).ready(function() {
                $(".e30").select2({
                    width: "100%"
                });
            });
        </script>';

        echo $str;
    }

    public function show_info_facture($RfFacture)
    {
        $facture = DB::table('factures_clients')
            ->where('factures_clients.FcNumero',$RfFacture)
            ->first();

        $str ='';

        if($facture) {
            $str.='
                <div class="form-group">
                    <label for="">Mt Solde à payer de la Facture</label>
                    <input type="text" class="form-control" value="'.currency_xaf($facture->FcMtSolde).'" readonly/>
                    <input type="hidden" class="form-control" id="RfMtAPayer" name="RfMtAPayer" value="'.$facture->FcMtSolde.'"/>
                </div>';
        } else {
            # code...
        }


        $str.='';

        echo $str;
    }

    public function show_info_facture_edit($RfFacture)
    {
        $facture = DB::table('factures_clients')
            ->where('factures_clients.FcNumero',$RfFacture)
            ->first();

        $str ='';

        if($facture) {
            $str.='
                <div class="form-group">
                    <label for="">Mt Solde à payer de la Facture</label>
                    <input type="text" class="form-control" value="'.currency_xaf($facture->FcMtSolde).'" readonly/>
                    <input type="hidden" class="form-control RfMtAPayer" id="RfMtAPayer" name="RfMtAPayer" value="'.$facture->FcMtSolde.'"/>
                </div>';
        } else {
            # code...
        }


        $str.='';

        echo $str;
    }
}
