<?php

namespace App\Http\Controllers;

use App\Models\ActionUser;
use App\Models\Ville;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;


class VilleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $villes = DB::table('villes')
            ->leftJoin('personnels','personnels.PeNumero','=','villes.ViRespOper')
            ->where('villes.ViEntite',Auth::user()->entite)
            ->orderBy('villes.ViNom','asc')
            ->get();

        $personnels = DB::table('personnels')
            ->orderBy('personnels.PeNumero','asc')
            ->get();

        $personnels_1 = DB::table('personnels')
            ->orderBy('personnels.PeNumero','asc')
            ->get();

        return view('villes.list',compact('villes','personnels','personnels_1'));
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
        $ville = DB::table('villes')
            ->where('villes.ViCode',$request->ViCode)
            ->where('villes.ViEntite',Auth::user()->entite)
            ->first();

        if(empty($ville)) {
            DB::beginTransaction();

            try{
                // Do something and save to the db...
                $ville = new Ville();
                $ville->ViEntite = Auth::user()->entite;
                $ville->ViCode = strtoupper($request->ViCode);
                $ville->ViNom = $request->ViNom;
                $ville->ViRespOper = $request->ViRespOper;
                $ville->ViIsZoneOn = $request->ViIsZoneOn;
                $ville->ViIsAllSiteOn = $request->ViIsAllSiteOn ?? 'N';
                $ville->ViIsOn = $request->ViIsOn;
                $ville->save();


            /* Action User */
                $action_user = new ActionUser();
                $action_user->AuEntite = Auth::user()->entite;
                $action_user->AuIDItem = $request->ViNumero;
                $action_user->AuTypeElt = 2;
                $action_user->AuTypeOper = 'A';
                $action_user->AuDateHOper = date('Y-m-d H:i:s');
                $action_user->AuUser = Auth::user()->id;
                $action_user->AuID1Open = $request->AuID1Open;
                $action_user->AuID2Open = $request->AuID2Open;
                $action_user->AuID3Open = $request->AuID3Open;
                $action_user->AuTextOuvert = $request->AuTextOuvert;
                $action_user->AuID1Ferm = $request->AuID1Ferms;
                $action_user->AuID2Ferm = $request->AuID2Ferm;
                $action_user->AuID3Ferm = $request->AuID3Ferm;
                $action_user->AuTextFerm = $request->AuTextFerm;
                $action_user->AuDateHFerm = $request->AuDateHFerm;
                $action_user->save();
            /* Action User */

                DB::commit();
            }
            catch (\Throwable $th){
                // An error occured; cancel the transaction...
                DB::rollback();
                // and throw the error again.
                throw $th;
            }

            return redirect()->route('villes.index')->with('alert', 'Ville créée avec succès.');
        }
        else {
            return redirect()->route('villes.index')->with('alert-error', 'Cette ville existe déjà.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $ViNumero)
    {
        return DB::table('villes')
            ->leftJoin('personnels','personnels.PeNumero','=','villes.ViRespOper')
            ->where('villes.ViNumero',$ViNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $ViNumero)
    {
        $ville = DB::table('villes')
            ->where('villes.ViNumero',$ViNumero)
            ->first();

        return response()->json($ville);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $ViNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('villes')
            ->where('villes.ViNumero',$ViNumero)
            ->update([
                'ViCode' => strtoupper($request->ViCode),
                'ViNom' => $request->ViNom,
                'ViRespOper' => $request->ViRespOper,
                'ViIsZoneOn' => $request->ViIsZoneOn,
                'ViIsAllSiteOn' => $request->ViIsAllSiteOn ?? 'N',
                'ViIsOn' => $request->ViIsOn
            ]);
            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('villes.index')->with('alert', 'Ville modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function activer($ViNumero)
    {
        DB::table('villes')->where('villes.ViNumero', $ViNumero)->update(['villes.ViIsOn' => 'Y']);
    }

    public function desactiver($ViNumero)
    {
        DB::table('villes')->where('villes.ViNumero', $ViNumero)->update(['villes.ViIsOn' => 'N']);
    }

    public function delete($ViNumero)
    {
        Ville::find($ViNumero)->delete();
    }

    public function generatePDF(Request $request)
    {
        $villes = DB::table('villes')
            ->leftJoin('personnels','personnels.PeNumero','=','villes.ViRespOper')
            ->where('villes.ViEntite',Auth::user()->entite)
            ->orderBy('villes.ViNom','asc')
            ->paginate(50);

        $entite = DB::table('entites')
            ->where('entites.EnNumero',Auth::user()->entite)
            ->first();

        $pdf = PDF::loadView('villes.pdf', compact('villes','entite'));

        return  $pdf->stream("Liste-villes.pdf", array("Attachment" => false));
        exit(0);
    }
}
