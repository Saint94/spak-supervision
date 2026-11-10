<?php

namespace App\Http\Controllers;

use App\Models\Fonction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;


class FonctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fonctions = DB::table('fonctions')
            ->where('fonctions.FnEntite',Auth::user()->entite)
            ->orderBy('fonctions.FnNom','asc')
            ->get();

        return view('fonctions.list',compact('fonctions'));
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
        $fonction = DB::table('fonctions')
            ->where('fonctions.FnCode',$request->FnCode)
            ->where('fonctions.FnEntite',Auth::user()->entite)
            ->first();

        if(empty($fonction)) {
            DB::beginTransaction();

            try{
                // Do something and save to the db...
                $fonction = new Fonction();
                $fonction->FnEntite = Auth::user()->entite;
                $fonction->FnCode = $request->FnCode;
                $fonction->FnNom = $request->FnNom;
                $fonction->FnIsOn = $request->FnIsOn;
                $fonction->save();


                DB::commit();
            }
            catch (\Throwable $th){
                // An error occured; cancel the transaction...
                DB::rollback();
                // and throw the error again.
                throw $th;
            }

            return redirect()->route('fonctions.index')->with('alert', 'Fonction créée avec succès.');
        }
        else {
            return redirect()->route('fonctions.index')->with('alert-error', 'Cette fonction existe déjà.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $FnNumero)
    {
        return DB::table('fonctions')
            ->where('fonctions.FnNumero',$FnNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $FnNumero)
    {
        $fonction = DB::table('fonctions')
            ->where('fonctions.FnNumero',$FnNumero)
            ->first();

        return response()->json($fonction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $FnNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('fonctions')
            ->where('fonctions.FnNumero',$FnNumero)
            ->update([
                'FnCode' => $request->FnCode,
                'FnNom' => $request->FnNom,
                'FnIsOn' => $request->FnIsOn
            ]);
            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('fonctions.index')->with('alert', 'Fonction modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function activer($FnNumero)
    {
        DB::table('fonctions')->where('fonctions.FnNumero', $FnNumero)->update(['fonctions.FnIsOn' => 'Y']);
    }

    public function desactiver($FnNumero)
    {
        DB::table('fonctions')->where('fonctions.FnNumero', $FnNumero)->update(['fonctions.FnIsOn' => 'N']);
    }

    public function delete($FnNumero)
    {
        Fonction::find($FnNumero)->delete();
    }

    public function generatePDF(Request $request)
    {
        $villes = DB::table('fonctions')
            ->where('fonctions.FnEntite',Auth::user()->entite)
            ->orderBy('fonctions.FnNom','asc')
            ->paginate(50);

        $entite = DB::table('entites')
            ->where('entites.EnNumero',Auth::user()->entite)
            ->first();

        $pdf = PDF::loadView('fonctions.pdf', compact('villes','entite'));

        return  $pdf->stream("Liste-fonctions.pdf", array("Attachment" => false));
        exit(0);
    }
}
