<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ContratSite;

class ContratSiteController extends Controller
{

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
        $contrat_site = DB::table('contrat_site')
            ->where('contrat_site.CsContratClient',$request->CsContratClient)
            ->where('contrat_site.CsSiteClient',$request->CsSiteClient)
            ->where('contrat_site.CsEntite',Auth::user()->entite)
            ->first();

        if(empty($contrat_site)) {
            DB::beginTransaction();

            $sommes = DB::table('contrat_site')
                ->select(
                    DB::raw('COALESCE(SUM(CsNbreAgentJr), 0) as agent_jr'),
                    DB::raw('COALESCE(SUM(CsNbreAgentNu), 0) as agent_nt'),
                    DB::raw('COALESCE(SUM(CsNbreMCJr), 0) as mc_jr'),
                    DB::raw('COALESCE(SUM(CsNbreMCNu), 0) as mc_nt'),
                    DB::raw('COALESCE(SUM(CsNbSupervJr), 0) as superv_jr'),
                    DB::raw('COALESCE(SUM(CsNbSupervNu), 0) as superv_nt'),
                    DB::raw('COALESCE(SUM(CsNbComdoJr), 0) as comdo_jr'),
                    DB::raw('COALESCE(SUM(CsNbComdoNu), 0) as comdo_nt'),
                    DB::raw('COALESCE(SUM(CsNbreOperVideo), 0) oper_video'),
                    DB::raw('COALESCE(SUM(CsNbLocatVidSurv), 0) as loc_video'),
                    DB::raw('COALESCE(SUM(CsNbInstVidSurv), 0) as install_video')
                )
                ->where('CsContratClient', $request->CsContratClient)
                ->where('CsIsOn','Y')
                ->first();

            // Prendre le plus récent (contrat ou avenant)
            $plafonds = DB::table('avenants_contrat')
                ->leftJoin('clients','clients.ClNumero','=','avenants_contrat.AcClient')
                ->where('avenants_contrat.AcNumero',$request->CsContratClient)
                ->orderBy('avenants_contrat.AcNumero','desc')
                ->first();

            //Calculer les nouveaux totaux avec les valeurs à ajouter/modifier
            $nouveauxTotaux = [
                'agent_jr' => $sommes->agent_jr + ($request->CsNbreAgentJr ?? 0),
                'agent_nt' => $sommes->agent_nt + ($request->CsNbreAgentNu ?? 0),
                'mc_jr' => $sommes->mc_jr + ($request->CsNbreMCJr ?? 0),
                'mc_nt' => $sommes->mc_nt + ($request->CsNbreMCNu ?? 0),
                'superv_jr' => $sommes->superv_jr + ($request->CsNbSupervJr ?? 0),
                'superv_nt' => $sommes->superv_nt + ($request->CsNbSupervNu ?? 0),
                'comdo_jr' => $sommes->comdo_jr + ($request->CsNbComdoJr ?? 0),
                'comdo_nt' => $sommes->comdo_nt + ($request->CsNbComdoNu ?? 0),
                'oper_video' => $sommes->oper_video + ($request->CsNbreOperVideo ?? 0),
                'loc_video' => $sommes->loc_video + ($request->CsNbLocatVidSurv ?? 0),
                'install_video' => $sommes->install_video + ($request->CsNbInstVidSurv ?? 0)
            ];

            //Vérifier les plafonds
            $verifications = [
                'agent_jr' => $nouveauxTotaux['agent_jr'] <= ($plafonds->AcNbAgentJr ?? 0),
                'agent_nt' => $nouveauxTotaux['agent_nt'] <= ($plafonds->AcNbAgentNu ?? 0),
                'mc_jr' => $nouveauxTotaux['mc_jr'] <= ($plafonds->AcNbreMCJour ?? 0),
                'mc_nt' => $nouveauxTotaux['mc_nt'] <= ($plafonds->AcNbreMCNuit ?? 0),
                'superv_jr' => $nouveauxTotaux['superv_jr'] <= ($plafonds->AcNbSupervJr ?? 0),
                'superv_nt' => $nouveauxTotaux['superv_nt'] <= ($plafonds->AcNbSupervNu ?? 0),
                'comdo_jr' => $nouveauxTotaux['comdo_jr'] <= ($plafonds->AcNbComdoJr ?? 0),
                'comdo_nt' => $nouveauxTotaux['comdo_nt'] <= ($plafonds->AcNbComdoNu ?? 0),
                'oper_video' => $nouveauxTotaux['oper_video'] <= ($plafonds->AcNbreOperVideo ?? 0),
                'loc_video' => $nouveauxTotaux['loc_video'] <= ($plafonds->AcNbLocatVidSurv ?? 0),
                'install_video' => $nouveauxTotaux['install_video'] <= ($plafonds->AcNbInstVidSurv ?? 0)
            ];

            if(in_array(false, $verifications)) {
                if(!$verifications['agent_jr']) {
                    return redirect()->back()->with('alert-error', "Le nombre d'agents nuit ".$nouveauxTotaux['agent_jr']." dépasse la limite de ".$plafonds->AcNbAgentJr ."");
                }
                if(!$verifications['agent_nt']) {
                    return redirect()->back()->with('alert-error', "Le nombre d'agents jour ".$nouveauxTotaux['agent_nt']." dépasse la limite de ".$plafonds->AcNbAgentNu."");
                }
                if(!$verifications['mc_jr']) {
                    return redirect()->back()->with('alert-error', "Le nombre de maîtres chien jour ".$nouveauxTotaux['mc_jr']." dépasse la limite de ".$plafonds->AcNbreMCJour."");
                }
                if(!$verifications['mc_nt']) {
                    return redirect()->back()->with('alert-error', "Le nombre de maîtres chien nuit ".$nouveauxTotaux['mc_nt']." dépasse la limite de ".$plafonds->AcNbreMCNuit."");
                }
                if(!$verifications['superv_jr']) {
                    return redirect()->back()->with('alert-error', "Le nombre de superviseurs jour ".$nouveauxTotaux['superv_jr']." dépasse la limite de ".$plafonds->AcNbSupervJr."");
                }
                if(!$verifications['superv_nt']) {
                    return redirect()->back()->with('alert-error', "Le nombre de superviseurs nuit ".$nouveauxTotaux['superv_nt']." dépasse la limite de ".$plafonds->AcNbSupervNu."");
                }
                if(!$verifications['comdo_jr']) {
                    return redirect()->back()->with('alert-error', "Le nombre de cmdo jour ".$nouveauxTotaux['comdo_jr']." dépasse la limite de ".$plafonds->AcNbComdoJr."");
                }
                if(!$verifications['comdo_nt']) {
                    return redirect()->back()->with('alert-error', "Le nombre de cmdo nuit ".$nouveauxTotaux['comdo_nt']." dépasse la limite de ".$plafonds->AcNbComdoNu."");
                }

                if(!$verifications['oper_video']) {
                    return redirect()->back()->with('alert-error', "Le nombre des opérateurs vidéo ".$nouveauxTotaux['oper_video']." dépasse la limite de ".$plafonds->AcNbreOperVideo."");
                }
                if(!$verifications['loc_video']) {
                    return redirect()->back()->with('alert-error', "Le nombre de location vidéo ".$nouveauxTotaux['loc_video']." dépasse la limite de ".$plafonds->AcNbLocatVidSurv."");
                }
                if(!$verifications['install_video']) {
                    return redirect()->back()->with('alert-error', "Le nombre d'installation de vidéo surveillance ".$nouveauxTotaux['install_video']." dépasse la limite de ".$plafonds->AcNbInstVidSurv."");
                }
            } else {
                    try{
                        // Do something and save to the db...
                        $contrat_site = new ContratSite();
                        $contrat_site->CsEntite = Auth::user()->entite;
                        $contrat_site->CsContratClient = $request->CsContratClient;
                        $contrat_site->CsSiteClient = $request->CsSiteClient;
                        $contrat_site->CsNbreAgentJr = $request->CsNbreAgentJr;
                        $contrat_site->CsNbreAgentNu = $request->CsNbreAgentNu;
                        $contrat_site->CsNbreMCJr = $request->CsNbreMCJr;
                        $contrat_site->CsNbreMCNu = $request->CsNbreMCNu;
                        $contrat_site->CsNbSupervJr = $request->CsNbSupervJr;
                        $contrat_site->CsNbSupervNu = $request->CsNbSupervNu;
                        $contrat_site->CsNbComdoJr = $request->CsNbComdoJr;
                        $contrat_site->CsNbComdoNu = $request->CsNbComdoNu;
                        $contrat_site->CsNbreOperVideo = $request->CsNbreOperVideo;
                        $contrat_site->CsNbLocatVidSurv = $request->CsNbLocatVidSurv;
                        $contrat_site->CsNbInstVidSurv = $request->CsNbInstVidSurv;
                        $contrat_site->CsIsOn = $request->CsIsOn;

                        if($request->CsIsOn == 'Y') {
                            $contrat_site->CsDateOn = date('Y-m-d');
                            $contrat_site->CsDateOff = NULL;
                            $contrat_site->CsUserOn = Auth::user()->id;
                            $contrat_site->CsUserOff = NULL;
                        } else {
                            $contrat_site->CsDateOn = NULL;
                            $contrat_site->CsDateOff = date('Y-m-d');
                            $contrat_site->CsUserOn = NULL;
                            $contrat_site->CsUserOff = Auth::user()->id;
                        }

                        $contrat_site->CsDetails = $request->CsDetails;
                        $contrat_site->save();

                        DB::commit();
                    }
                    catch (\Throwable $th){
                        // An error occured; cancel the transaction...
                        DB::rollback();
                        // and throw the error again.
                        throw $th;
                    }

                    return redirect()->back()->with('alert', 'Contrat site crée avec succès.');
            }
        }
        else {
            return redirect()->back()->with('alert-error', 'Ce contrat existe déjà.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $CsNumero)
    {
        return DB::table('contrat_site')
            ->leftJoin('avenants_contrat','avenants_contrat.AcNumero','=','contrat_site.CsContratClient')
            ->leftJoin('sites_client','sites_client.ScNumero','=','contrat_site.CsSiteClient')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->where('contrat_site.CsNumero',$CsNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $CsNumero)
    {
        $contrat_site = DB::table('contrat_site')
            ->leftJoin('avenants_contrat','avenants_contrat.AcNumero','=','contrat_site.CsContratClient')
            ->leftJoin('sites_client','sites_client.ScNumero','=','contrat_site.CsSiteClient')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->where('contrat_site.CsNumero',$CsNumero)
            ->first();

        return response()->json($contrat_site);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $CsNumero)
    {
        DB::beginTransaction();

        $sommes = DB::table('contrat_site')
            ->select(
                DB::raw('COALESCE(SUM(CsNbreAgentJr), 0) as agent_jr'),
                DB::raw('COALESCE(SUM(CsNbreAgentNu), 0) as agent_nt'),
                DB::raw('COALESCE(SUM(CsNbreMCJr), 0) as mc_jr'),
                DB::raw('COALESCE(SUM(CsNbreMCNu), 0) as mc_nt'),
                DB::raw('COALESCE(SUM(CsNbSupervJr), 0) as superv_jr'),
                DB::raw('COALESCE(SUM(CsNbSupervNu), 0) as superv_nt'),
                DB::raw('COALESCE(SUM(CsNbComdoJr), 0) as comdo_jr'),
                DB::raw('COALESCE(SUM(CsNbComdoNu), 0) as comdo_nt'),
                DB::raw('COALESCE(SUM(CsNbreOperVideo), 0) oper_video'),
                DB::raw('COALESCE(SUM(CsNbLocatVidSurv), 0) as loc_video'),
                DB::raw('COALESCE(SUM(CsNbInstVidSurv), 0) as install_video')

            )
            ->where('CsContratClient', $request->CsContratClient)
            ->where('CsIsOn','Y')
            ->first();

            // Prendre le plus récent (contrat ou avenant)
            $plafonds = DB::table('avenants_contrat')
                ->leftJoin('clients','clients.ClNumero','=','avenants_contrat.AcClient')
                ->where('avenants_contrat.AcNumero',$request->CsContratClient)
                ->orderBy('avenants_contrat.AcNumero','desc')
                ->first();

            //Calculer les nouveaux totaux avec les valeurs à ajouter/modifier
            $nouveauxTotaux = [
                'agent_jr' => $sommes->agent_jr + ($request->CsNbreAgentJr ?? 0),
                'agent_nt' => $sommes->agent_nt + ($request->CsNbreAgentNu ?? 0),
                'mc_jr' => $sommes->mc_jr + ($request->CsNbreMCJr ?? 0),
                'mc_nt' => $sommes->mc_nt + ($request->CsNbreMCNu ?? 0),
                'superv_jr' => $sommes->superv_jr + ($request->CsNbSupervJr ?? 0),
                'superv_nt' => $sommes->superv_nt + ($request->CsNbSupervNu ?? 0),
                'comdo_jr' => $sommes->comdo_jr + ($request->CsNbComdoJr ?? 0),
                'comdo_nt' => $sommes->comdo_nt + ($request->CsNbComdoNu ?? 0),
                'oper_video' => $sommes->oper_video + ($request->CsNbreOperVideo ?? 0),
                'loc_video' => $sommes->loc_video + ($request->CsNbLocatVidSurv ?? 0),
                'install_video' => $sommes->install_video + ($request->CsNbInstVidSurv ?? 0)
            ];

            //Vérifier les plafonds
            $verifications = [
                'agent_jr' => $nouveauxTotaux['agent_jr'] <= ($plafonds->AcNbAgentJr ?? 0),
                'agent_nt' => $nouveauxTotaux['agent_nt'] <= ($plafonds->AcNbAgentNu ?? 0),
                'mc_jr' => $nouveauxTotaux['mc_jr'] <= ($plafonds->AcNbreMCJour ?? 0),
                'mc_nt' => $nouveauxTotaux['mc_nt'] <= ($plafonds->AcNbreMCNuit ?? 0),
                'superv_jr' => $nouveauxTotaux['superv_jr'] <= ($plafonds->AcNbSupervJr ?? 0),
                'superv_nt' => $nouveauxTotaux['superv_nt'] <= ($plafonds->AcNbSupervNu ?? 0),
                'comdo_jr' => $nouveauxTotaux['comdo_jr'] <= ($plafonds->AcNbComdoJr ?? 0),
                'comdo_nt' => $nouveauxTotaux['comdo_nt'] <= ($plafonds->AcNbComdoNu ?? 0),
                'oper_video' => $nouveauxTotaux['oper_video'] <= ($plafonds->AcNbreOperVideo ?? 0),
                'loc_video' => $nouveauxTotaux['loc_video'] <= ($plafonds->AcNbLocatVidSurv ?? 0),
                'install_video' => $nouveauxTotaux['install_video'] <= ($plafonds->AcNbInstVidSurv ?? 0)
            ];

            if(in_array(false, $verifications)) {
                if(!$verifications['agent_jr']) {
                    return redirect()->back()->with('alert-error', "Le nombre d'agents nuit ".$nouveauxTotaux['agent_jr']." dépasse la limite de ".$plafonds->AcNbAgentJr ."");
                }
                if(!$verifications['agent_nt']) {
                    return redirect()->back()->with('alert-error', "Le nombre d'agents jour ".$nouveauxTotaux['agent_nt']." dépasse la limite de ".$plafonds->AcNbAgentNu."");
                }
                if(!$verifications['mc_jr']) {
                    return redirect()->back()->with('alert-error', "Le nombre de maîtres chien jour ".$nouveauxTotaux['mc_jr']." dépasse la limite de ".$plafonds->AcNbreMCJour."");
                }
                if(!$verifications['mc_nt']) {
                    return redirect()->back()->with('alert-error', "Le nombre de maîtres chien nuit ".$nouveauxTotaux['mc_nt']." dépasse la limite de ".$plafonds->AcNbreMCNuit."");
                }
                if(!$verifications['superv_jr']) {
                    return redirect()->back()->with('alert-error', "Le nombre de superviseurs jour ".$nouveauxTotaux['superv_jr']." dépasse la limite de ".$plafonds->AcNbSupervJr."");
                }
                if(!$verifications['superv_nt']) {
                    return redirect()->back()->with('alert-error', "Le nombre de superviseurs nuit ".$nouveauxTotaux['superv_nt']." dépasse la limite de ".$plafonds->AcNbSupervNu."");
                }
                if(!$verifications['comdo_jr']) {
                    return redirect()->back()->with('alert-error', "Le nombre de cmdo jour ".$nouveauxTotaux['comdo_jr']." dépasse la limite de ".$plafonds->AcNbComdoJr."");
                }
                if(!$verifications['comdo_nt']) {
                    return redirect()->back()->with('alert-error', "Le nombre de cmdo nuit ".$nouveauxTotaux['comdo_nt']." dépasse la limite de ".$plafonds->AcNbComdoNu."");
                }

                if(!$verifications['oper_video']) {
                    return redirect()->back()->with('alert-error', "Le nombre des opérateurs vidéo ".$nouveauxTotaux['oper_video']." dépasse la limite de ".$plafonds->AcNbreOperVideo."");
                }
                if(!$verifications['loc_video']) {
                    return redirect()->back()->with('alert-error', "Le nombre de location vidéo ".$nouveauxTotaux['loc_video']." dépasse la limite de ".$plafonds->AcNbLocatVidSurv."");
                }
                if(!$verifications['install_video']) {
                    return redirect()->back()->with('alert-error', "Le nombre d'installation de vidéo surveillance ".$nouveauxTotaux['install_video']." dépasse la limite de ".$plafonds->AcNbInstVidSurv."");
                }
                
        } else {
            try{
                // Do something and save to the db...

                if($request->CsIsOn == 'Y') {

                    DB::table('contrat_site')
                        ->where('contrat_site.CsNumero',$CsNumero)
                        ->update([
                            //'CsSiteClient' => $request->CsSiteClient,
                            'CsNbreAgentJr' => $request->CsNbreAgentJr,
                            'CsNbreAgentNu' => $request->CsNbreAgentNu,
                            'CsNbreMCJr' => $request->CsNbreMCJr,
                            'CsNbreMCNu' => $request->CsNbreMCNu,
                            'CsNbSupervJr' => $request->CsNbSupervJr,
                            'CsNbSupervNu' => $request->CsNbSupervNu,
                            'CsNbComdoJr' => $request->CsNbComdoJr,
                            'CsNbComdoNu' => $request->CsNbComdoNu,
                            'CsNbreOperVideo' => $request->CsNbreOperVideo,
                            'CsNbLocatVidSurv' => $request->CsNbLocatVidSurv,
                            'CsNbInstVidSurv' => $request->CsNbInstVidSurv,

                            'CsIsOn' => 'Y',
                            'CsDateOn' => date('Y-m-d'),
                            'CsDateOff' => NULL,
                            'CsUserOn' => Auth::user()->id,
                            'CsUserOff' => NULL,
                            'CsDetails' => $request->CsDetails
                        ]);

                } else {

                    DB::table('contrat_site')
                        ->where('contrat_site.CsNumero',$CsNumero)
                        ->update([
                            //'CsSiteClient' => $request->CsSiteClient,
                            'CsNbreAgentJr' => $request->CsNbreAgentJr,
                            'CsNbreAgentNu' => $request->CsNbreAgentNu,
                            'CsNbreMCJr' => $request->CsNbreMCJr,
                            'CsNbreMCNu' => $request->CsNbreMCNu,
                            'CsNbSupervJr' => $request->CsNbSupervJr,
                            'CsNbSupervNu' => $request->CsNbSupervNu,
                            'CsNbComdoJr' => $request->CsNbComdoJr,
                            'CsNbComdoNu' => $request->CsNbComdoNu,
                            'CsNbreOperVideo' => $request->CsNbreOperVideo,
                            'CsNbLocatVidSurv' => $request->CsNbLocatVidSurv,
                            'CsNbInstVidSurv' => $request->CsNbInstVidSurv,

                            'CsIsOn' => 'N',
                            'CsDateOn' => NULL,
                            'CsDateOff' => date('Y-m-d'),
                            'CsUserOn' => NULL,
                            'CsUserOff' => Auth::user()->id,
                            'CsDetails' => $request->CsDetails
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

            return redirect()->back()->with('alert', 'Contrat site modifié avec succès.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $AcNumero)
    {
        //
    }
}
