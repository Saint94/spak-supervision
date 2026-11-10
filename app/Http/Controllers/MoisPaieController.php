<?php

namespace App\Http\Controllers;

use App\Models\MoisPaie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;


class MoisPaieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mois_paie = DB::table('mois_paie')
            ->leftJoin('users','users.id','=','mois_paie.MpCloturePar')
            ->where('mois_paie.MpEntite',Auth::user()->entite)
            ->orderBy('mois_paie.MpLibelle','asc')
            ->orderBy('mois_paie.MpLibelle','asc')
            ->get();

        $last_mois_paie = DB::table('mois_paie')
            ->where('mois_paie.MpEntite',Auth::user()->entite)
            ->orderBy('mois_paie.MpNumero','desc')
            ->first();

        return view('mois-paie.list',compact('mois_paie','last_mois_paie'));
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
        $mois_paie = DB::table('mois_paie')
            ->where('mois_paie.MpIdMois',$request->MpIdMois)
            ->where('mois_paie.MpAnnee',$request->MpAnnee)
            ->where('mois_paie.MpEntite',Auth::user()->entite)
            ->first();

        if(empty($mois_paie)) {
            DB::beginTransaction();

            try{
                // Do something and save to the db...
                $mois_paie = new MoisPaie();
                $mois_paie->MpEntite = Auth::user()->entite;
                $mois_paie->MpIdMois = $request->MpIdMois;
                $mois_paie->MpAnnee = $request->MpAnnee;
                $mois_paie->MpLibelle = $request->MpLibelle;
                $mois_paie->MpDateDebut = $request->MpDateDebut;
                $mois_paie->MpDateFin = $request->MpDateFin;
                $mois_paie->MpStatut = $request->MpStatut ?? 'I';
                $mois_paie->MpCreePar = Auth::user()->id;
                $mois_paie->MpDateHCree = date('Y-m-d H:i:s');
                $mois_paie->MpCloturePar = NULL;
                $mois_paie->MpDateHCloture = NULL;

                $mois_paie->MpNbPersonSecDeb = DB::table('personnels')
                                                ->where('personnels.PeNatureEmpl','S')
                                                ->where('personnels.PeEntite',Auth::user()->entite)
                                                ->count();

                $mois_paie->MpNbPersonSecFin = DB::table('personnels')
                                                ->where('personnels.PeNatureEmpl','S')
                                                ->where('personnels.PeEntite',Auth::user()->entite)
                                                ->count();

                $mois_paie->MpNbPersonAdmDeb = DB::table('personnels')
                                                ->where('personnels.PeNatureEmpl','A')
                                                ->where('personnels.PeEntite',Auth::user()->entite)
                                                ->count();

                $mois_paie->MpNbPersonAdmFin = DB::table('personnels')
                                                ->where('personnels.PeNatureEmpl','A')
                                                ->where('personnels.PeEntite',Auth::user()->entite)
                                                ->count();

                $mois_paie->MpObserv = $request->MpObserv;
                $mois_paie->save();
                DB::commit();
            }
            catch (\Throwable $th){
                // An error occured; cancel the transaction...
                DB::rollback();
                // and throw the error again.
                throw $th;
            }

            return redirect()->route('mois-paie.index')->with('alert', 'Mois de paie crée avec succès.');
        }
        else {
            return redirect()->route('mois-paie.index')->with('alert-error', 'Ce mois de paie existe déjà.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $MpNumero)
    {
        return DB::table('mois_paie')
            ->where('mois_paie.MpNumero',$MpNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $MpNumero)
    {
        $mois_paie = DB::table('mois_paie')
            ->where('mois_paie.MpNumero',$MpNumero)
            ->first();

        return response()->json($mois_paie);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $MpNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('mois_paie')
            ->where('mois_paie.MpNumero',$MpNumero)
            ->update([
                'MpIdMois' => $request->MpIdMois,
                'MpAnnee' => $request->MpAnnee,
                'MpLibelle' => $request->MpLibelle,
                'MpDateDebut' => $request->MpDateDebut,
                'MpDateFin' => $request->MpDateFin,
                'MpStatut' => $request->MpStatut,

                'MpNbPersonSecDeb' => DB::table('personnels')
                                        ->where('personnels.PeNatureEmpl','S')
                                        ->where('personnels.PeEntite',Auth::user()->entite)
                                        ->count(),

                'MpNbPersonSecFin' => DB::table('personnels')
                                        ->where('personnels.PeNatureEmpl','S')
                                        ->where('personnels.PeEntite',Auth::user()->entite)
                                        ->count(),

                'MpNbPersonAdmDeb' => DB::table('personnels')
                                        ->where('personnels.PeNatureEmpl','A')
                                        ->where('personnels.PeEntite',Auth::user()->entite)
                                        ->count(),

                'MpNbPersonAdmFin' => DB::table('personnels')
                                        ->where('personnels.PeNatureEmpl','A')
                                        ->where('personnels.PeEntite',Auth::user()->entite)
                                        ->count(),

                'MpObserv' => $request->MpObserv
            ]);
            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('mois-paie.index')->with('alert', 'Mois de paie modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function cloturer($MpNumero)
    {
        DB::table('mois_paie')
            ->where('mois_paie.MpNumero',$MpNumero)
            ->update([
                'mois_paie.SoIsOn' => 'Y'
            ]);
    }

    public function delete($MpNumero)
    {
        MoisPaie::find($MpNumero)->delete();
    }
}
