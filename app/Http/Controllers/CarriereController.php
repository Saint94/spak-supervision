<?php

namespace App\Http\Controllers;

use App\Models\Carriere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CarriereController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personnels = DB::table('personnels')
            ->get();

        $carrieres = DB::table('carrieres')
            ->leftJoin('personnels','personnels.PeNumero','=','carrieres.CaAgent')
            ->where('carrieres.CaEntite',Auth::user()->entite)
            ->get();

        return view('carrieres.list',compact('personnels','carrieres'));
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
            $carriere = new Carriere();
            $carriere->CaEntite = Auth::user()->entite;
            $carriere->CaAgent = $request->CaAgent;
            $carriere->CaDateDebut = $request->CaDateDebut;
            $carriere->CaDateFin = $request->CaDateFin;
            $carriere->CaPoste = $request->CaPoste;
            $carriere->CaVille = $request->CaVille;
            $carriere->save();

            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->back()->with('alert', 'Carrière créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $CaNumero )
    {
        return DB::table('carrieres')
           ->leftJoin('personnels','personnels.PeNumero','=','carrieres.CaAgent')
            ->where('carrieres.CaNumero',$CaNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $CaNumero)
    {
        $carriere = DB::table('carrieres')
            ->where('carrieres.CaNumero',$CaNumero)
            ->first();

        return response()->json($carriere);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $CaNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('carrieres')
            ->where('carrieres.CaNumero',$CaNumero)
            ->update([
                'CaAgent' => $request->CaAgent,
                'CaDateDebut' => $request->CaDateDebut,
                'CaDateFin' => $request->CaDateFin,
                'CaPoste' => $request->CaPoste,
                'CaVille' => $request->CaVille
            ]);
            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->back()->with('alert', 'Carrière modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function delete($CaNumero)
    {
        Carriere::find($CaNumero)->delete();
    }

}
