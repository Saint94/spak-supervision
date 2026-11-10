<?php

namespace App\Http\Controllers;

use App\Models\RondeOperation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class RondeOperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-d');


        $rondes_operations = DB::table('ronde_operations')
            ->leftJoin('users as resp1', 'resp1.id', '=', 'ronde_operations.RoResponsable1')
            ->leftJoin('users as resp2', 'resp2.id', '=', 'ronde_operations.RoResponsable2')
            ->leftJoin('users as resp3', 'resp3.id', '=', 'ronde_operations.RoResponsable3')
            ->where('ronde_operations.RoEntite', Auth::user()->entite)
            ->whereDate('ronde_operations.RoDate', '>=', $start_date)
            ->whereDate('ronde_operations.RoDate', '<=', $end_date)
            ->orderBy('ronde_operations.RoDate', 'asc')
            ->select(
                'ronde_operations.*',
                // Responsable 1 : toujours présent normalement
                DB::raw("TRIM(CONCAT(IFNULL(resp1.noms, ''), ' ', IFNULL(resp1.prenoms, ''))) as responsable1_name"),
                // Responsable 2 : peut être vide → renvoie NULL si vide
                DB::raw("NULLIF(TRIM(CONCAT(IFNULL(resp2.noms, ''), ' ', IFNULL(resp2.prenoms, ''))), '') as responsable2_name"),
                // Responsable 3 : peut être vide → renvoie NULL si vide
                DB::raw("NULLIF(TRIM(CONCAT(IFNULL(resp3.noms, ''), ' ', IFNULL(resp3.prenoms, ''))), '') as responsable3_name")
            )
            ->get();


        $responsables_operations = DB::table('users')
            ->whereIn('users.type', ['R'])
            ->where('users.entite', Auth::user()->entite)
            ->orderBy('users.noms', 'asc')
            ->get();

        return view('ronde-operations.list',compact(
            'rondes_operations','responsables_operations'
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
            $ronde_operation = new RondeOperation();
            $ronde_operation->RoEntite = Auth::user()->entite;
            $ronde_operation->RoDate = date('Y-m-d');
            $ronde_operation->RoDateDebut = $request->RoDateDebut;
            $ronde_operation->RoHeureDebut = $request->RoHeureDebut;
            $ronde_operation->RoDateFin = NULL;
            $ronde_operation->RoHeureFin = NULL;
            $ronde_operation->RoResponsable1 = $request->RoResponsable1 ?? NULL;
            $ronde_operation->RoResponsable2 = $request->RoResponsable2 ?? NULL;
            $ronde_operation->RoResponsable3 = $request->RoResponsable3 ?? NULL;
            $ronde_operation->RoStatut = 'I';
            $ronde_operation->RoObjectif = $request->RoObjectif  ?? NULL;
            $ronde_operation->RoPlanAction = $request->RoPlanAction  ?? NULL;

            $ronde_operation->save();
            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('ronde-operations.index')->with('alert', 'Ronde responsable opération créée avec succès.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $RoNumero)
    {
        return DB::table('ronde_operations')
            ->leftJoin('users as resp1', 'resp1.id', '=', 'ronde_operations.RoResponsable1')
            ->leftJoin('users as resp2', 'resp2.id', '=', 'ronde_operations.RoResponsable2')
            ->leftJoin('users as resp3', 'resp3.id', '=', 'ronde_operations.RoResponsable3')
            ->select(
                'ronde_operations.*',
                // Responsable 1 : toujours présent normalement
                DB::raw("TRIM(CONCAT(IFNULL(resp1.noms, ''), ' ', IFNULL(resp1.prenoms, ''))) as responsable1_name"),
                // Responsable 2 : peut être vide → renvoie NULL si vide
                DB::raw("NULLIF(TRIM(CONCAT(IFNULL(resp2.noms, ''), ' ', IFNULL(resp2.prenoms, ''))), '') as responsable2_name"),
                // Responsable 3 : peut être vide → renvoie NULL si vide
                DB::raw("NULLIF(TRIM(CONCAT(IFNULL(resp3.noms, ''), ' ', IFNULL(resp3.prenoms, ''))), '') as responsable3_name")
            )
            ->where('ronde_operations.RoNumero',$RoNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $RoNumero)
    {
        $ronde_operation = DB::table('ronde_operations')
            ->where('ronde_operations.RoNumero',$RoNumero)
            ->first();

        return response()->json($ronde_operation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $RoNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('ronde_operations')
            ->where('ronde_operations.RoNumero',$RoNumero)
            ->update([
                'RoDateDebut' => $request->RoDateDebut,
                'RoHeureDebut' => $request->RoHeureDebut,
                'RoResponsable1' => $request->RoResponsable1,
                'RoResponsable2' => $request->RoResponsable2,
                'RoResponsable3' => $request->RoResponsable3,
                'RoObjectif' => $request->RoObjectif,
                'RoPlanAction' => $request->RoPlanAction
            ]);
            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('ronde-operations.index')->with('alert', 'Ronde opération modifié avec succès.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit_fermer(string $RoNumero)
    {
        $ronde_operation = DB::table('ronde_operations')
            ->leftJoin('users as resp1', 'resp1.id', '=', 'ronde_operations.RoResponsable1')
            ->leftJoin('users as resp2', 'resp2.id', '=', 'ronde_operations.RoResponsable2')
            ->leftJoin('users as resp3', 'resp3.id', '=', 'ronde_operations.RoResponsable3')
            ->select(
                'ronde_operations.*',
                // Responsable 1 : toujours présent normalement
                DB::raw("TRIM(CONCAT(IFNULL(resp1.noms, ''), ' ', IFNULL(resp1.prenoms, ''))) as responsable1_name"),
                // Responsable 2 : peut être vide → renvoie NULL si vide
                DB::raw("NULLIF(TRIM(CONCAT(IFNULL(resp2.noms, ''), ' ', IFNULL(resp2.prenoms, ''))), '') as responsable2_name"),
                // Responsable 3 : peut être vide → renvoie NULL si vide
                DB::raw("NULLIF(TRIM(CONCAT(IFNULL(resp3.noms, ''), ' ', IFNULL(resp3.prenoms, ''))), '') as responsable3_name")
            )
            ->where('ronde_operations.RoNumero',$RoNumero)
            ->first();

        return response()->json($ronde_operation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_fermer(Request $request, string $RoNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('ronde_operations')
            ->where('ronde_operations.RoNumero',$RoNumero)
            ->update([
                'RoDateFin' => $request->RoDateFin,
                'RoHeureFin' => $request->RoHeureFin,
                'RoStatut' => $request->RoStatut
            ]);
            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('ronde-operations.index')->with('alert', 'Ronde opération clôturée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function generatePDF(Request $request)
    {
        $rondes_operations = DB::table('ronde_operations')
            ->leftJoin('users as resp1', 'resp1.id', '=', 'ronde_operations.RoResponsable1')
            ->leftJoin('users as resp2', 'resp2.id', '=', 'ronde_operations.RoResponsable2')
            ->leftJoin('users as resp3', 'resp3.id', '=', 'ronde_operations.RoResponsable3')
            ->where('ronde_operations.RoEntite', Auth::user()->entite)
            ->whereDate('ronde_operations.RoDate', '>=', $request->start_date)
            ->whereDate('ronde_operations.RoDate', '<=', $request->end_date)
            ->orderBy('ronde_operations.RoDate', 'asc')
            ->select(
                'ronde_operations.*',
                // Responsable 1 : toujours présent normalement
                DB::raw("TRIM(CONCAT(IFNULL(resp1.noms, ''), ' ', IFNULL(resp1.prenoms, ''))) as responsable1_name"),
                // Responsable 2 : peut être vide → renvoie NULL si vide
                DB::raw("NULLIF(TRIM(CONCAT(IFNULL(resp2.noms, ''), ' ', IFNULL(resp2.prenoms, ''))), '') as responsable2_name"),
                // Responsable 3 : peut être vide → renvoie NULL si vide
                DB::raw("NULLIF(TRIM(CONCAT(IFNULL(resp3.noms, ''), ' ', IFNULL(resp3.prenoms, ''))), '') as responsable3_name")
            )
            ->paginate(50);
        

        $start_date =  $request->start_date;
        $end_date =  $request->end_date;

        $responsables_operations = DB::table('users')
            ->whereIn('users.type', ['R'])
            ->where('users.entite', Auth::user()->entite)
            ->orderBy('users.noms', 'asc')
            ->get();

        $entite = DB::table('entites')
            ->where('entites.EnNumero',Auth::user()->entite)
            ->first();

        $pdf = PDF::loadView('ronde-operations.pdf', compact(
            'rondes_operations','start_date','end_date','responsables_operations','entite'
        ))->setPaper('a4', 'landscape');

        return  $pdf->stream("Liste-Rondes.pdf", array("Attachment" => false));
        exit(0);
    }


    public function filtre(Request $request)
    {
        $rondes_operations = DB::table('ronde_operations')
            ->leftJoin('users as resp1', 'resp1.id', '=', 'ronde_operations.RoResponsable1')
            ->leftJoin('users as resp2', 'resp2.id', '=', 'ronde_operations.RoResponsable2')
            ->leftJoin('users as resp3', 'resp3.id', '=', 'ronde_operations.RoResponsable3')
            ->where('ronde_operations.RoEntite', Auth::user()->entite)
            ->whereDate('ronde_operations.RoDate', '>=', $request->start_date)
            ->whereDate('ronde_operations.RoDate', '<=', $request->end_date)
            ->orderBy('ronde_operations.RoDate', 'asc')
            ->select(
                'ronde_operations.*',
                // Responsable 1 : toujours présent normalement
                DB::raw("TRIM(CONCAT(IFNULL(resp1.noms, ''), ' ', IFNULL(resp1.prenoms, ''))) as responsable1_name"),
                // Responsable 2 : peut être vide → renvoie NULL si vide
                DB::raw("NULLIF(TRIM(CONCAT(IFNULL(resp2.noms, ''), ' ', IFNULL(resp2.prenoms, ''))), '') as responsable2_name"),
                // Responsable 3 : peut être vide → renvoie NULL si vide
                DB::raw("NULLIF(TRIM(CONCAT(IFNULL(resp3.noms, ''), ' ', IFNULL(resp3.prenoms, ''))), '') as responsable3_name")
            )
            ->get();
        

        $start_date =  $request->start_date;
        $end_date =  $request->end_date;

        $responsables_operations = DB::table('users')
            ->whereIn('users.type', ['R'])
            ->where('users.entite', Auth::user()->entite)
            ->orderBy('users.noms', 'asc')
            ->get();

        return view('ronde-operations.filtre',compact(
            'rondes_operations','start_date','end_date','responsables_operations'
        ));
    }



    /**
     * Remove the specified resource from storage.
    */
    public function sites(string $RoNumero)
    {
        $RoNumero = decrypter($RoNumero);

        $ronde_operation = DB::table('ronde_operations')
            ->leftJoin('users as resp1', 'resp1.id', '=', 'ronde_operations.RoResponsable1')
            ->leftJoin('users as resp2', 'resp2.id', '=', 'ronde_operations.RoResponsable2')
            ->leftJoin('users as resp3', 'resp3.id', '=', 'ronde_operations.RoResponsable3')
            ->select(
                'ronde_operations.*',
                // Responsable 1 : toujours présent normalement
                DB::raw("TRIM(CONCAT(IFNULL(resp1.noms, ''), ' ', IFNULL(resp1.prenoms, ''))) as responsable1_name"),
                // Responsable 2 : peut être vide → renvoie NULL si vide
                DB::raw("NULLIF(TRIM(CONCAT(IFNULL(resp2.noms, ''), ' ', IFNULL(resp2.prenoms, ''))), '') as responsable2_name"),
                // Responsable 3 : peut être vide → renvoie NULL si vide
                DB::raw("NULLIF(TRIM(CONCAT(IFNULL(resp3.noms, ''), ' ', IFNULL(resp3.prenoms, ''))), '') as responsable3_name")
            )
            ->where('ronde_operations.RoNumero',$RoNumero)
            ->first();

        $sites_ronde_oper = DB::table('sites_ronde_oper')
            ->leftJoin('ronde_operations','ronde_operations.RoNumero','=','sites_ronde_oper.SrRondeSuperv')
            ->leftJoin('clients','clients.ClNumero','=','sites_ronde_oper.SrClient')
            ->leftJoin('sites_client','sites_client.ScNumero','=','sites_ronde_oper.SrSiteClient')
            ->where('sites_ronde_oper.SrRondeSuperv',$RoNumero)
            ->where('sites_ronde_oper.SrEntite',Auth::user()->entite)
            ->get();

        $clients = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->orderBy('clients.ClDesign','asc')
            ->get();

        $sites_client = DB::table('sites_client')
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->orderBy('sites_client.ScNomSite','asc')
            ->get();

        return view('ronde-operations.sites.list',compact(
            'RoNumero','ronde_operation','sites_ronde_oper','clients','sites_client'
        ));
    }


}
