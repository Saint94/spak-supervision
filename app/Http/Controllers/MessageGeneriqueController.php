<?php

namespace App\Http\Controllers;

use App\Models\MessageGenerique;
use App\Models\TemporaryDocMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageGeneriqueController extends Controller
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
            ->orderBy('clients.ClDesign','asc')
            ->get();

        $messages_generique = DB::table('messages_generique')
            ->leftJoin('clients','clients.ClNumero','=','messages_generique.MgClient')
            ->whereDate('messages_generique.MgDate','>=',$start_date)
            ->whereDate('messages_generique.MgDate','<=',$end_date)
            ->where('messages_generique.MgEntite',Auth::user()->entite)
            ->get();

        return view('messages.list',compact('messages_generique','clients'));
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
            $message_generique = new MessageGenerique();
            $message_generique->MgEntite = Auth::user()->entite;
            $message_generique->MgType = $request->MgType;
            $message_generique->MgClient = $request->MgClient;
            $message_generique->MgDate = $request->MgDate;
            $message_generique->MgMontant = $request->MgMontant;
            $message_generique->MgDateLimite = $request->MgDateLimite;
            $message_generique->MgTitre = $request->MgTitre;
            $message_generique->MgTexteMsg = $request->MgTexteMsg;
            $message_generique->MgStatut = $request->MgStatut;
            $message_generique->MgUserCree = Auth::user()->id;
            $message_generique->MgUserModif = NULL;
            $message_generique->MgDateHCree = date('Y-m-d H:i:s');
            $message_generique->MgDateHModif = NULL;

            $temporaryDoc = TemporaryDocMessage::where('folder',$request->MgChemDocFile)->first();

            if($temporaryDoc)
            {
                $message_generique->MgChemDocFile = $temporaryDoc->file;
            }

            $message_generique->save();

            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        DB::table('temporary_doc_message')->truncate();
        return redirect()->route('messages-generique.index')->with('alert', 'Message générique crée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $MgNumero )
    {
        return DB::table('messages_generique')
            ->leftJoin('clients','clients.ClNumero','=','messages_generique.MgClient')
            ->where('messages_generique.MgNumero',$MgNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $MgNumero)
    {
        $message_generique = DB::table('messages_generique')
            ->leftJoin('clients','clients.ClNumero','=','messages_generique.MgClient')
            ->where('messages_generique.MgNumero',$MgNumero)
            ->first();

        return response()->json($message_generique);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $MgNumero)
    {
        $temporaryDoc = TemporaryDocMessage::where('folder',$request->MgChemDocFile)->first();

        DB::beginTransaction();

        try{
            // Do something and save to the db...
            if($temporaryDoc)
            {
                DB::table('messages_generique')
                ->where('messages_generique.MgNumero',$MgNumero)
                ->update([
                    'MgClient' => $request->MgClient,
                    'MgType' => $request->MgType,
                    'MgDate' => $request->MgDate,
                    'MgMontant' => $request->MgMontant,
                    'MgDateLimite' => $request->MgDateLimite,
                    'MgTitre' => $request->MgTitre,
                    'MgTexteMsg' => $request->MgTexteMsg,
                    'MgChemDocFile' => $temporaryDoc->file,
                    'MgStatut' => $request->MgStatut,
                    'MgUserModif' => $request->MgUserModif,
                    'MgDateHModif' => $request->MgDateHModif
                ]);
            } else {
                DB::table('messages_generique')
                ->where('messages_generique.MgNumero',$MgNumero)
                ->update([
                    'MgClient' => $request->MgClient,
                    'MgType' => $request->MgType,
                    'MgDate' => $request->MgDate,
                    'MgMontant' => $request->MgMontant,
                    'MgDateLimite' => $request->MgDateLimite,
                    'MgTitre' => $request->MgTitre,
                    'MgTexteMsg' => $request->MgTexteMsg,
                    'MgStatut' => $request->MgStatut,
                    'MgUserModif' => $request->MgUserModif,
                    'MgDateHModif' => $request->MgDateHModif
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

        DB::table('temporary_doc_message')->truncate();
        return redirect()->back()->with('alert', 'Message générique modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function activer($MgNumero)
    {
        DB::table('messages_generique')
            ->where('messages_generique.MgNumero',$MgNumero)
            ->update([
                'messages_generique.MgStatut' => 'Y'
            ]);
    }

    public function desactiver($MgNumero)
    {
        DB::table('messages_generique')
            ->where('messages_generique.MgNumero',$MgNumero)
            ->update([
                'messages_generique.MgStatut' => 'N'
            ]);
    }


    public function delete($MgNumero)
    {
        MessageGenerique::find($MgNumero)->delete();
    }


    public function filtre(Request $request)
    {
        $clients = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->orderBy('clients.ClDesign','asc')
            ->get();

        if($request->client_message == 'all' && $request->type_message == 'all')
        {
            $messages_generique = DB::table('messages_generique')
                ->leftJoin('clients','clients.ClNumero','=','messages_generique.MgClient')
                ->whereDate('messages_generique.MgDate','>=',$request->start_date)
                ->whereDate('messages_generique.MgDate','<=',$request->end_date)
                ->where('messages_generique.MgEntite',Auth::user()->entite)
                ->get();
        }
        else
        {
            // Handle other cases where not all parameters are 'all'
            $query = DB::table('messages_generique')
                ->leftJoin('clients','clients.ClNumero','=','messages_generique.MgClient');

            // Apply filters based on the request parameters
            if($request->client_message != 'all') {
                $query->where('messages_generique.MgClient',$request->client_message)
                    ->whereDate('messages_generique.MgDate','>=',$request->start_date)
                    ->whereDate('messages_generique.MgDate','<=',$request->end_date)
                    ->where('messages_generique.MgEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->type_message != 'all') {
                $query->where('messages_generique.MgType',$request->type_message)
                    ->whereDate('messages_generique.MgDate','>=',$request->start_date)
                    ->whereDate('messages_generique.MgDate','<=',$request->end_date)
                    ->where('messages_generique.MgEntite',Auth::user()->entite);
            }

            // Execute the query with the applied filters
            $messages_generique = $query->get();
        }

        $start_date =  $request->start_date;
        $end_date =  $request->end_date;
        $client_message = $request->client_message;
        $type_message = $request->type_message;

        return view('messages.filtre',compact(
            'start_date','end_date','client_message',
            'type_message','clients','messages_generique'));

    }

}
