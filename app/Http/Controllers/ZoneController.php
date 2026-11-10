<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;


class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $zones = DB::table('zones')
            ->leftJoin('villes','villes.ViNumero','=','zones.ZoVille')
            ->where('zones.ZoEntite',Auth::user()->entite)
            ->orderBy('zones.ZoNumero','asc')
            ->get();

        $villes = DB::table('villes')
            ->where('villes.ViIsZoneOn','Y')
            ->where('villes.ViEntite',Auth::user()->entite)
            ->orderBy('villes.ViNom','asc')
            ->get();

        $villes_1 = DB::table('villes')
            ->where('villes.ViEntite',Auth::user()->entite)
            ->orderBy('villes.ViNom','asc')
            ->get();

        return view('zones.list',compact('zones','villes','villes_1'));
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
        $zone = DB::table('zones')
            ->where('zones.ZoCode',$request->ZoCode)
            ->where('zones.ZoEntite',Auth::user()->entite)
            ->first();

        if(empty($zone)) {
            DB::beginTransaction();

            try{
                // Do something and save to the db...
                $zone = new Zone();
                $zone->ZoEntite = Auth::user()->entite;
                $zone->ZoCode = strtoupper($request->ZoCode);
                $zone->ZoNom = $request->ZoNom;
                $zone->ZoVille = $request->ZoVille;
                $zone->ZoIsOn = $request->ZoIsOn;
                $zone->ZoUser = Auth::user()->id;
                $zone->save();
                DB::commit();
            }
            catch (\Throwable $th){
                // An error occured; cancel the transaction...
                DB::rollback();
                // and throw the error again.
                throw $th;
            }

            return redirect()->route('zones.index')->with('alert', 'Zone créée avec succès.');
        }
        else {
            return redirect()->route('zones.index')->with('alert-error', 'Cette zone existe déjà.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $ZoNumero)
    {
        return DB::table('zones')
            ->leftJoin('villes','villes.ViNumero','=','zones.ZoVille')
            ->where('zones.ZoNumero',$ZoNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $ZoNumero)
    {
        $zone = DB::table('zones')
            ->where('zones.ZoNumero',$ZoNumero)
            ->first();

        return response()->json($zone);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $ZoNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('zones')
            ->where('zones.ZoNumero',$ZoNumero)
            ->update([
                'ZoCode' => strtoupper($request->ZoCode),
                'ZoNom' => $request->ZoNom,
                'ZoVille' => $request->ZoVille,
                'ZoIsOn' => $request->ZoIsOn
            ]);
            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('zones.index')->with('alert', 'Zone modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function activer($ZoNumero)
    {
        DB::table('zones')
            ->where('zones.ZoNumero',$ZoNumero)
            ->update([
                'zones.ZoIsOn' => 'Y',
                'ZoDateLastActiv' => date('Y-m-d H:i:s')
            ]);
    }

    public function desactiver($ZoNumero)
    {
        DB::table('zones')
            ->where('zones.ZoNumero',$ZoNumero)
            ->update([
                'zones.ZoIsOn' => 'N',
                'ZoDateOff' => date('Y-m-d H:i:s')
            ]);
    }

    public function delete($ZoNumero)
    {
        Zone::find($ZoNumero)->delete();
    }

    public function generatePDF(Request $request)
    {
        $zones = DB::table('zones')
            ->leftJoin('villes','villes.ViNumero','=','zones.ZoVille')
            ->where('zones.ZoEntite',Auth::user()->entite)
            ->orderBy('zones.ZoNumero','asc')
            ->paginate(50);

        $entite = DB::table('entites')
            ->where('entites.EnNumero',Auth::user()->entite)
            ->first();

        $pdf = PDF::loadView('zones.pdf', compact('zones','entite'));

        return  $pdf->stream("Liste-zones.pdf", array("Attachment" => false));
        exit(0);
    }
}
