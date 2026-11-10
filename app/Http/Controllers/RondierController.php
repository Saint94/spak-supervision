<?php

namespace App\Http\Controllers;

use App\Models\RondierSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RondierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       //
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
            $rondier_site = new RondierSite();
            $rondier_site->RtEntite = Auth::user()->entite;
            $rondier_site->RtRondier = $request->RtRondier;
            $rondier_site->RtPersonnel = $request->RtPersonnel;
            $rondier_site->RtSiteClient = $request->RtSiteClient;
            $rondier_site->RtJourRepos = $request->RtJourRepos;
            $rondier_site->RtDateDebut = $request->RtDateDebut;
            $rondier_site->RtIsOn = $request->RtIsOn;
            $rondier_site->RtDateFin = $request->RtDateFin;
            $rondier_site->RtCreePar = Auth::user()->id;
            $rondier_site->RtDateHPgme = date('Y-m-d H:i');
            $rondier_site->RtUserFin = null;
            $rondier_site->RtDateHFin = null;
            $rondier_site->save();

            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('personnels.rondiers',crypter($request->RtRondier))->with('alert', 'Repos donné avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $RtNumero )
    {
        return DB::table('rondier_sites')
            ->leftJoin('personnels','personnels.PeNumero','=','rondier_sites.RtPersonnel')
            ->leftJoin('sites_client','sites_client.ScNumero','=','rondier_sites.RtSiteClient')
            ->where('rondier_sites.RtNumero',$RtNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $RtNumero)
    {
        $rondier_site = DB::table('rondier_sites')
            ->leftJoin('personnels','personnels.PeNumero','=','rondier_sites.RtPersonnel')
            ->leftJoin('sites_client','sites_client.ScNumero','=','rondier_sites.RtSiteClient')
            ->where('rondier_sites.RtNumero',$RtNumero)
            ->first();

        return response()->json($rondier_site);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $RtNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('rondier_sites')
            ->where('rondier_sites.RtNumero',$RtNumero)
            ->update([
                'RtPersonnel' => $request->RtPersonnel,
                'RtSiteClient' => $request->RtSiteClient,
                'RtJourRepos' => $request->RtJourRepos,
                'RtDateDebut' => $request->RtDateDebut,
                'RtIsOn' => $request->RtIsOn,
                'RtDateFin' => $request->RtDateFin
            ]);
            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->back()->with('alert', 'Repos modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function activer(string $RtNumero)
    {
        DB::table('rondier_sites')
            ->where('rondier_sites.RtNumero',$RtNumero)
            ->update([
                'rondier_sites.RtIsOn' => 'Y',
                'rondier_sites.RtDateHPgme' => date('Y-m-d H:i:s'),
                'rondier_sites.RtUserFin' => null,
                'rondier_sites.RtDateHFin' => null
            ]);
    }

    public function desactiver(string $RtNumero)
    {
        DB::table('rondier_sites')
            ->where('rondier_sites.RtNumero',$RtNumero)
            ->update([
                'rondier_sites.RtIsOn' => 'N',
                'rondier_sites.RtUserFin' => Auth::user()->id,
                'rondier_sites.RtDateHFin' => date('Y-m-d H:i:s'),
                'rondier_sites.RtDateHPgme' => null
            ]);
    }


    public function delete($RtNumero)
    {
        RondierSite::find($RtNumero)->delete();
    }

}
