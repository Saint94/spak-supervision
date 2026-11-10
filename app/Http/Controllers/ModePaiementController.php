<?php

namespace App\Http\Controllers;

use App\Models\ModePaiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;


class ModePaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modes_paiement = DB::table('modes_paiement')
            ->where('modes_paiement.MpEntite',Auth::user()->entite)
            ->orderBy('modes_paiement.MpNom','asc')
            ->get();

        return view('modes-paiement.list',compact('modes_paiement'));
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
        $mode_paiement = DB::table('modes_paiement')
            ->where('modes_paiement.MpNom',$request->MpNom)
            ->where('modes_paiement.MpEntite',Auth::user()->entite)
            ->first();

        if(empty($mode_paiement)) {
            DB::beginTransaction();

            try{
                // Do something and save to the db...
                $mode_paiement = new ModePaiement();
                $mode_paiement->MpEntite = Auth::user()->entite;
                $mode_paiement->MpNom = $request->MpNom;
                $mode_paiement->MpIsOn  = $request->MpIsOn ;
                $mode_paiement->save();

                DB::commit();
            }
            catch (\Throwable $th){
                // An error occured; cancel the transaction...
                DB::rollback();
                // and throw the error again.
                throw $th;
            }

            return redirect()->route('modes-paiement.index')->with('alert', 'Mode de paiement crée avec succès.');
        }
        else {
            return redirect()->route('modes-paiement.index')->with('alert-error', 'Ce mode de paiement existe déjà.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $MpNumero)
    {
        return DB::table('modes_paiement')
            ->where('modes_paiement.MpNumero',$MpNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $MpNumero)
    {
        $mode_paiement = DB::table('modes_paiement')
            ->where('modes_paiement.MpNumero',$MpNumero)
            ->first();

        return response()->json($mode_paiement);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $MpNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('modes_paiement')
            ->where('modes_paiement.MpNumero',$MpNumero)
            ->update([
                'MpNom' => $request->MpNom,
                'MpIsOn' => $request->MpIsOn
            ]);
            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('modes-paiement.index')->with('alert', 'Mode de paiement modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function activer($MpNumero)
    {
        DB::table('modes_paiement')->where('modes_paiement.MpNumero', $MpNumero)->update(['modes_paiement.MpIsOn' => 'Y']);
    }

    public function desactiver($MpNumero)
    {
        DB::table('modes_paiement')->where('modes_paiement.MpNumero', $MpNumero)->update(['modes_paiement.MpIsOn' => 'N']);
    }

    public function delete($MpNumero)
    {
        ModePaiement::find($MpNumero)->delete();
    }

    public function generatePDF(Request $request)
    {
        $modes_paiement = DB::table('modes_paiement')
            ->where('modes_paiement.MpEntite',Auth::user()->entite)
            ->orderBy('modes_paiement.MpNom','asc')
            ->paginate(50);

        $entite = DB::table('entites')
            ->where('entites.EnNumero',Auth::user()->entite)
            ->first();

        $pdf = PDF::loadView('modes-paiement.pdf', compact('modes_paiement','entite'));

        return  $pdf->stream("Liste-modes-paiement.pdf", array("Attachment" => false));
        exit(0);
    }
}
