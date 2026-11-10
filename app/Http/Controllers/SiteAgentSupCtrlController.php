<?php

namespace App\Http\Controllers;

use App\Models\Carriere;
use App\Models\SiteAgentSupCtrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiteAgentSupCtrlController extends Controller
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
        $site_agent_sup_ctr = DB::table('sites_agent_sup_ctr')
            ->where('sites_agent_sup_ctr.SaSupervCtrl',$request->SaSupervCtrl)
            ->where('sites_agent_sup_ctr.SaSiteClt',$request->SaSiteClt)
            ->where('sites_agent_sup_ctr.SaEntite',Auth::user()->entite)
            ->first();

        if(empty($site_agent_sup_ctr)) {

            DB::beginTransaction();

            try{
                // Do something and save to the db...
                $site_agent_sup_ctr = new SiteAgentSupCtrl();
                $site_agent_sup_ctr->SaEntite = Auth::user()->entite;
                $site_agent_sup_ctr->SaSupervCtrl = $request->SaSupervCtrl;
                $site_agent_sup_ctr->SaSiteClt = $request->SaSiteClt;
                $site_agent_sup_ctr->SaAgent = $request->SaAgent;
                $site_agent_sup_ctr->SaDateDebut = $request->SaDateDebut;
                $site_agent_sup_ctr->SaDateFin = $request->SaDateFin;
                $site_agent_sup_ctr->SaUser = Auth::user()->id;
                $site_agent_sup_ctr->SaDateH = date('Y-m-d H:i:s');
                $site_agent_sup_ctr->SaIsOn = $request->SaIsOn;
                $site_agent_sup_ctr->save();

                DB::commit();
            }
            catch (\Throwable $th){
                // An error occured; cancel the transaction...
                DB::rollback();
                // and throw the error again.
                throw $th;
            }

            return redirect()->back()->with('alert', 'Ajout site/agent créée avec succès.');
        }
        else {
            return redirect()->back()->with('alert-error', 'Ce site/agent existe déjà.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $SaNumero )
    {
        return DB::table('sites_agent_sup_ctr')
           ->leftJoin('personnels','personnels.PeNumero','=','sites_agent_sup_ctr.SaAgent')
            ->where('sites_agent_sup_ctr.SaNumero',$SaNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $SaNumero)
    {
        $site_agent_sup_ctr = DB::table('sites_agent_sup_ctr')
            ->where('sites_agent_sup_ctr.SaNumero',$SaNumero)
            ->first();

        return response()->json($site_agent_sup_ctr);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $SaNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('sites_agent_sup_ctr')
            ->where('sites_agent_sup_ctr.SaNumero',$SaNumero)
            ->update([
                'SaSiteClt' => $request->SaSiteClt,
                'SaAgent' => $request->SaAgent,
                'SaDateDebut' => $request->SaDateDebut,
                'SaDateFin' => $request->SaDateFin,
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

        return redirect()->back()->with('alert', 'Site/agent modifiée avec succès.');
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
        DB::table('sites_agent_sup_ctr')
            ->where('sites_agent_sup_ctr.SaNumero',$SaNumero)
            ->update([
                'sites_agent_sup_ctr.SaIsOn' => 'Y',
                'sites_agent_sup_ctr.SaDateFin' => NULL
            ]);
    }

    public function desactiver($SaNumero)
    {
        DB::table('sites_agent_sup_ctr')
            ->where('sites_agent_sup_ctr.SaNumero',$SaNumero)
            ->update([
                'sites_agent_sup_ctr.SaIsOn' => 'N',
                'sites_agent_sup_ctr.SaDateFin' => date('Y-m-d H:i:s')
            ]);
    }


    public function delete($SaNumero)
    {
        SiteAgentSupCtrl::find($SaNumero)->delete();
    }

}
