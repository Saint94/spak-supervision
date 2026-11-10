<?php

namespace App\Http\Controllers;

use App\Models\SecteurActivite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;


class SecteurActiviteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $secteurs_activites = DB::table('secteurs_activite')
            ->where('secteurs_activite.SaEntite',Auth::user()->entite)
            ->orderBy('secteurs_activite.SaNom','asc')
            ->get();

        return view('secteurs-activite.list',compact('secteurs_activites'));
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
        $secteur_activite = DB::table('secteurs_activite')
            ->where('secteurs_activite.SaNom',$request->SaNom)
            ->where('secteurs_activite.SaEntite',Auth::user()->entite)
            ->first();

        if(empty($secteur_activite)) {
            DB::beginTransaction();

            try{
                // Do something and save to the db...
                $secteur_activite = new SecteurActivite();
                $secteur_activite->SaEntite = Auth::user()->entite;
                $secteur_activite->SaNom = $request->SaNom;
                $secteur_activite->SaIsOn = $request->SaIsOn;
                $secteur_activite->save();


                DB::commit();
            }
            catch (\Throwable $th){
                // An error occured; cancel the transaction...
                DB::rollback();
                // and throw the error again.
                throw $th;
            }

            return redirect()->route('secteurs-activite.index')->with('alert', 'Secteur d\'activité crée avec succès.');
        }
        else {
            return redirect()->route('secteurs-activite.index')->with('alert-error', 'Ce secteur d\'activité existe déjà.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $SaNumero)
    {
        return DB::table('secteurs_activite')
            ->where('secteurs_activite.SaNumero',$SaNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $SaNumero)
    {
        $secteur_activite = DB::table('secteurs_activite')
            ->where('secteurs_activite.SaNumero',$SaNumero)
            ->first();

        return response()->json($secteur_activite);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $SaNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('secteurs_activite')
            ->where('secteurs_activite.SaNumero',$SaNumero)
            ->update([
                'SaNom' => $request->SaNom,
                'SaIsOn' => $request->SaIsOn
            ]);
            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('secteurs-activite.index')->with('alert', 'Secteur d\'activité modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function activer($SaNumero)
    {
        DB::table('secteurs_activite')->where('secteurs_activite.SaNumero', $SaNumero)->update(['secteurs_activite.SaIsOn' => 'Y']);
    }

    public function desactiver($SaNumero)
    {
        DB::table('secteurs_activite')->where('secteurs_activite.SaNumero', $SaNumero)->update(['secteurs_activite.SaIsOn' => 'N']);
    }

    public function delete($SaNumero)
    {
        SecteurActivite::find($SaNumero)->delete();
    }

    public function generatePDF(Request $request)
    {
        $secteurs_activite = DB::table('secteurs_activite')
            ->where('secteurs_activite.SaEntite',Auth::user()->entite)
            ->orderBy('secteurs_activite.SaNom','asc')
            ->paginate(50);

        $entite = DB::table('entites')
            ->where('entites.EnNumero',Auth::user()->entite)
            ->first();

        $pdf = PDF::loadView('secteurs-activite.pdf', compact('secteurs_activite','entite'));

        return  $pdf->stream("Liste-secteurs-activite.pdf", array("Attachment" => false));
        exit(0);
    }
}
