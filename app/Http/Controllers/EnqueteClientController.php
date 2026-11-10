<?php

namespace App\Http\Controllers;

use App\Models\EnqueteClient;
use App\Models\TemporaryDocEnquete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnqueteClientController extends Controller
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

        $enquetes_client = DB::table('enquetes_client')
            ->leftJoin('clients','clients.ClNumero','=','enquetes_client.EcClient')
            ->whereDate('enquetes_client.EcDate','>=',$start_date)
            ->whereDate('enquetes_client.EcDate','<=',$end_date)
            ->where('enquetes_client.EcEntite',Auth::user()->entite)
            ->get();

        //Pour filtres
        $clients_f = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->get();

        return view('enquetes.list',compact('clients','enquetes_client','clients_f'));
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
            $enquete_client = new EnqueteClient();
            $enquete_client->EcEntite = Auth::user()->entite;
            $enquete_client->EcClient = $request->EcClient;
            $enquete_client->EcDate = $request->EcDate;
            $enquete_client->EcHeure = $request->EcHeure;
            $enquete_client->EcType = $request->EcType;
            $enquete_client->EcNivSatisf = $request->EcNivSatisf;
            $enquete_client->EcRemarques = $request->EcRemarques;
            $enquete_client->EcDateRapp = $request->EcDateRapp;

            $temporaryDoc = TemporaryDocEnquete::where('folder',$request->EcChemDocEnq)->first();

            if($temporaryDoc)
            {
                $enquete_client->EcChemDocEnq = $temporaryDoc->file;
            }

            $enquete_client->save();

            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        DB::table('temporary_doc_enquete')->truncate();
        return redirect()->route('enquetes-clients.index')->with('alert', 'Enquête client créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $EcNumero )
    {
        return DB::table('enquetes_client')
            ->leftJoin('clients','clients.ClNumero','=','enquetes_client.EcClient')
            ->where('enquetes_client.EcNumero',$EcNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $EcNumero)
    {
        $enquete_client = DB::table('enquetes_client')
            ->leftJoin('clients','clients.ClNumero','=','enquetes_client.EcClient')
            ->where('enquetes_client.EcNumero',$EcNumero)
            ->first();

        return response()->json($enquete_client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $EcNumero)
    {
        $temporaryDoc = TemporaryDocEnquete::where('folder',$request->EcChemDocEnq)->first();

        DB::beginTransaction();

        try{
            // Do something and save to the db...
            if($temporaryDoc)
            {
                DB::table('enquetes_client')
                ->where('enquetes_client.EcNumero',$EcNumero)
                ->update([
                    'EcClient' => $request->EcClient,
                    'EcDate' => $request->EcDate,
                    'EcHeure' => $request->EcHeure,
                    'EcType' => $request->EcType,
                    'EcNivSatisf' => $request->EcNivSatisf,
                    'EcChemDocEnq' => $temporaryDoc->file,
                    'EcDateRapp' => $request->EcDateRapp,
                    'EcRemarques' => $request->EcRemarques,
                ]);
            } else {
                DB::table('enquetes_client')
                ->where('enquetes_client.EcNumero',$EcNumero)
                ->update([
                    'EcClient' => $request->EcClient,
                    'EcDate' => $request->EcDate,
                    'EcHeure' => $request->EcHeure,
                    'EcType' => $request->EcType,
                    'EcNivSatisf' => $request->EcNivSatisf,
                    'EcRemarques' => $request->EcRemarques,
                    'EcDateRapp' => $request->EcDateRapp
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

        DB::table('temporary_doc_enquete')->truncate();
        return redirect()->route('enquetes-clients.index')->with('alert', 'Enquête client modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function delete($EcNumero)
    {
        EnqueteClient::find($EcNumero)->delete();
    }


    public function filtre(Request $request)
    {
        $clients = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->orderBy('clients.ClDesign','asc')
            ->get();

        if($request->numero_client == 'all')
        {
            $enquetes_client = DB::table('enquetes_client')
                ->leftJoin('clients','clients.ClNumero','=','enquetes_client.EcClient')
                ->whereDate('enquetes_client.EcDate','>=',$request->start_date)
                ->whereDate('enquetes_client.EcDate','<=',$request->end_date)
                ->where('enquetes_client.EcEntite',Auth::user()->entite)
                ->get();
        }
        else
        {
            // Handle other cases where not all parameters are 'all'
            $query = DB::table('enquetes_client')
                ->leftJoin('clients','clients.ClNumero','=','enquetes_client.EcClient');

            // Apply filters based on the request parameters
            if($request->numero_client != 'all') {
                $query->where('enquetes_client.EcClient',$request->numero_client)
                    ->whereDate('enquetes_client.EcDate','>=',$request->start_date)
                    ->whereDate('enquetes_client.EcDate','<=',$request->end_date)
                    ->where('enquetes_client.EcEntite',Auth::user()->entite);
            }

            // Execute the query with the applied filters
            $enquetes_client = $query->get();
        }

        $start_date =  $request->start_date;
        $end_date =  $request->end_date;
        $numero_client = $request->numero_client;

        //Pour filtres
        $clients_f = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->get();

        return view('enquetes.filtre',compact(
            'start_date','end_date','numero_client',
            'clients','clients_f','enquetes_client'));
    }

}
