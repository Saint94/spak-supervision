<?php

namespace App\Http\Controllers;

use App\Imports\ClientImport;
use App\Models\Client;
use App\Models\ContactClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = DB::table('clients')
            ->leftJoin('villes','villes.ViNumero','=','clients.ClVilleSiege')
            ->leftJoin('quartiers','quartiers.QuNumero','=','clients.ClQuartier')
            ->leftJoin('secteurs_activite','secteurs_activite.SaNumero','=','clients.ClSectActivite')
            ->leftJoin('entites','entites.EnNumero','=','clients.ClEntite')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->orderBy('clients.ClDesign','asc')
            ->get();

        $secteurs_activites = DB::table('secteurs_activite')
            ->where('secteurs_activite.SaEntite',Auth::user()->entite)
            ->orderBy('secteurs_activite.SaNom','asc')
            ->get();

        $villes = DB::table('villes')
            ->where('villes.ViEntite',Auth::user()->entite)
            ->orderBy('villes.ViNom','asc')
            ->get();

        if(!empty($clients))
        {
            for($i=0; $i<count($clients); $i++)
            {
                $sommes = DB::table('factures_clients')
                    ->where('factures_clients.FcClient', $clients[$i]->ClNumero)
                    ->selectRaw('SUM(factures_clients.FcMontantTTC) as mt_total, SUM(factures_clients.FcTotalPaye) as total_paye')
                    ->first();

                DB::table('clients')
                    ->where('clients.ClNumero',$clients[$i]->ClNumero)
                    ->update(['clients.ClMtImpayes' => ($sommes->mt_total-$sommes->total_paye)]);
            }

            for($j=0; $j<count($clients); $j++)
            {
                $factures_impayees = DB::table('factures_clients')
                    ->select(
                        'factures_clients.*',
                        DB::raw('TIMESTAMPDIFF(MONTH, factures_clients.FcDate, CURDATE()) as mois_ecoules'),
                        DB::raw('DATEDIFF(CURDATE(), factures_clients.FcDate) as jours_ecoules'),
                        DB::raw('ROUND(DATEDIFF(CURDATE(), factures_clients.FcDate) / 30.44, 1) as mois_approx')
                    )
                    ->where('factures_clients.FcClient', $clients[$j]->ClNumero)
                    ->where('factures_clients.FcIsSolde', 'N')
                    ->orderBy('factures_clients.FcDate', 'asc') // La plus ancienne d'abord
                    ->first(); // Prendre la première (la plus ancienne)

                if ($factures_impayees) {
                    DB::table('clients')
                        ->where('clients.ClNumero', $clients[$j]->ClNumero)
                        ->update([
                            'clients.ClNbMoisImp' => $factures_impayees->mois_ecoules
                        ]);
                } else {
                    // Pas de facture impayée
                    DB::table('clients')
                        ->where('clients.ClNumero', $clients[$j]->ClNumero)
                        ->update(['clients.ClNbMoisImp' => 0]);
                }
            }

            for($k=0; $k<count($clients); $k++)
            {
                $count_sites = DB::table('sites_client')
                    ->where('sites_client.ScClient', $clients[$k]->ClNumero)
                    ->count();

                DB::table('clients')
                    ->where('clients.ClNumero',$clients[$k]->ClNumero)
                    ->update([
                        'clients.ClNbreSites' => $count_sites
                    ]);
            }
        }

        return view('clients.list',compact('clients','villes','secteurs_activites'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $secteurs_activites = DB::table('secteurs_activite')
            ->where('secteurs_activite.SaIsOn','Y')
            ->where('secteurs_activite.SaEntite',Auth::user()->entite)
            ->orderBy('secteurs_activite.SaNom','asc')
            ->get();

        $modes_paiement = DB::table('modes_paiement')
            ->where('modes_paiement.MpIsOn','Y')
            ->where('modes_paiement.MpEntite',Auth::user()->entite)
            ->orderBy('modes_paiement.MpNom','asc')
            ->get();

        $villes = DB::table('villes')
            ->where('villes.ViEntite',Auth::user()->entite)
            ->orderBy('villes.ViNom','asc')
            ->get();

        return view('clients.add',compact('villes','secteurs_activites','modes_paiement'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $client = DB::table('clients')
            ->where('clients.ClCode',$request->ClCode)
            ->where('clients.ClEntite',Auth::user()->entite)
            ->first();

        if(empty($client)) {
            DB::beginTransaction();

            try{
                // Do something and save to the db...
                $client = new Client();
                $client->ClEntite = Auth::user()->entite;
                $client->ClCode = strtoupper($request->ClCode);
                $client->ClDesign = $request->ClDesign;
                $client->ClTel1 = $request->ClTel1;
                $client->ClTel2 = $request->ClTel2;
                $client->ClEmail = $request->ClEmail;
                $client->ClDateDebut = $request->ClDateDebut;
                $client->ClVilleSiege = $request->ClVilleSiege;
                $client->ClQuartier = $request->ClQuartier;
                $client->ClSectActivite = $request->ClSectActivite;
                $client->ClNbreSites = 0;
                $client->ClNbAgentJour = 0;
                $client->ClNbAgentNuit = 0;
                $client->ClUserCree = Auth::user()->id;
                $client->ClIsOn = 'Y';
                $client->ClIsTVAOn = $request->ClIsTVAOn;
                $client->ClMtImpayes = 0;
                $client->ClNbMoisImp = 0;
                $client->ClDetails = $request->ClDetails;
                $client->ClContactlNom = $request->ClContactlNom;
                $client->ClModePaiem = $request->ClModePaiem;
                $client->ClTypeReglem = $request->ClTypeReglem ?? NULL;
                $client->ClJourReglem = $request->ClJourReglem ?? NULL;
                $client->save();

                $clients = DB::table('clients')
                    ->where('clients.ClEntite',Auth::user()->entite)
                    ->orderBy('clients.ClDesign','asc')
                    ->get();

                if(!empty($clients))
                {
                    for($i=0; $i<count($clients); $i++)
                    {
                        $sommes = DB::table('factures_clients')
                            ->where('factures_clients.FcClient', $clients[$i]->ClNumero)
                            ->selectRaw('SUM(factures_clients.FcMontantTTC) as mt_total, SUM(factures_clients.FcTotalPaye) as total_paye')
                            ->first();

                        DB::table('clients')
                            ->where('clients.ClNumero',$clients[$i]->ClNumero)
                            ->update(['clients.ClMtImpayes' => ($sommes->mt_total-$sommes->total_paye)]);
                    }

                    for($i=0; $i<count($clients); $i++)
                    {
                        $factures_impayees = DB::table('factures_clients')
                            ->select(
                                'factures_clients.*',
                                DB::raw('TIMESTAMPDIFF(MONTH, factures_clients.FcDate, CURDATE()) as mois_ecoules'),
                                DB::raw('DATEDIFF(CURDATE(), factures_clients.FcDate) as jours_ecoules'),
                                DB::raw('ROUND(DATEDIFF(CURDATE(), factures_clients.FcDate) / 30.44, 1) as mois_approx')
                            )
                            ->where('factures_clients.FcClient', $clients[$i]->ClNumero)
                            ->where('factures_clients.FcIsSolde', 'N')
                            ->orderBy('factures_clients.FcDate', 'asc') // La plus ancienne d'abord
                            ->first(); // Prendre la première (la plus ancienne)

                        if ($factures_impayees) {
                            DB::table('clients')
                                ->where('clients.ClNumero', $clients[$i]->ClNumero)
                                ->update([
                                    'clients.ClNbMoisImp' => $factures_impayees->mois_ecoules
                                ]);
                        } else {
                            // Pas de facture impayée
                            DB::table('clients')
                                ->where('clients.ClNumero', $clients[$i]->ClNumero)
                                ->update(['clients.ClNbMoisImp' => 0]);
                        }
                    }
                }

                DB::commit();
            }
            catch (\Throwable $th){
                // An error occured; cancel the transaction...
                DB::rollback();
                // and throw the error again.
                throw $th;
            }

            return redirect()->route('clients.index')->with('alert', 'Client crée avec succès.');
        }
        else {
            return redirect()->route('clients.index')->with('alert-error', 'Ce client existe déjà.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $ClNumero)
    {
        return DB::table('clients')
            ->leftJoin('villes','villes.ViNumero','=','clients.ClVilleSiege')
            ->leftJoin('secteurs_activite','secteurs_activite.SaNumero','=','clients.ClSectActivite')
            ->where('clients.ClNumero',$ClNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $ClNumero)
    {
        $ClNumero = decrypter($ClNumero);

        $secteurs_activites = DB::table('secteurs_activite')
            ->where('secteurs_activite.SaIsOn','Y')
            ->where('secteurs_activite.SaEntite',Auth::user()->entite)
            ->orderBy('secteurs_activite.SaNom','asc')
            ->get();

        $modes_paiement = DB::table('modes_paiement')
            ->where('modes_paiement.MpIsOn','Y')
            ->where('modes_paiement.MpEntite',Auth::user()->entite)
            ->orderBy('modes_paiement.MpNom','asc')
            ->get();

        $quartiers = DB::table('quartiers')
            ->where('quartiers.QuEntite',Auth::user()->entite)
            ->orderBy('quartiers.QuNom','asc')
            ->get();

        $villes = DB::table('villes')
            ->where('villes.ViEntite',Auth::user()->entite)
            ->orderBy('villes.ViNom','asc')
            ->get();

        $client = DB::table('clients')
            ->where('clients.ClNumero',$ClNumero)
            ->first();

        return view('clients.edit',compact('villes','client','secteurs_activites','quartiers','modes_paiement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $ClNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...

            DB::table('clients')
                ->where('clients.ClNumero',$ClNumero)
                ->update([
                    //'ClCode' => strtoupper($request->ClCode),
                    //'ClDesign' => $request->ClDesign,
                    'ClTel1' => $request->ClTel1,
                    'ClTel2' => $request->ClTel2,
                    'ClEmail' => $request->ClEmail,
                    'ClDateDebut' => $request->ClDateDebut,
                    'ClVilleSiege' => $request->ClVilleSiege,
                    'ClSectActivite' => $request->ClSectActivite,
                    'ClIsOn' => $request->ClIsOn,
                    'ClIsTVAOn' => $request->ClIsTVAOn,
                    'ClDetails' => $request->ClDetails,
                    'ClContactlNom' => $request->ClContactlNom,
                    'ClModePaiem' => $request->ClModePaiem,
                    'ClTypeReglem' => $request->ClTypeReglem,
                    'ClJourReglem' => $request->ClJourReglem,
                    'ClQuartier' => $request->ClQuartier
                ]);

            $clients = DB::table('clients')
                ->where('clients.ClEntite',Auth::user()->entite)
                ->orderBy('clients.ClDesign','asc')
                ->get();

            if(!empty($clients))
            {
                for($i=0; $i<count($clients); $i++)
                {
                    $sommes = DB::table('factures_clients')
                        ->where('factures_clients.FcClient', $clients[$i]->ClNumero)
                        ->selectRaw('SUM(factures_clients.FcMontantTTC) as mt_total, SUM(factures_clients.FcTotalPaye) as total_paye')
                        ->first();

                    DB::table('clients')
                        ->where('clients.ClNumero',$clients[$i]->ClNumero)
                        ->update(['clients.ClMtImpayes' => ($sommes->mt_total-$sommes->total_paye)]);
                }

                for($i=0; $i<count($clients); $i++)
                {
                    $factures_impayees = DB::table('factures_clients')
                        ->select(
                            'factures_clients.*',
                            DB::raw('TIMESTAMPDIFF(MONTH, factures_clients.FcDate, CURDATE()) as mois_ecoules'),
                            DB::raw('DATEDIFF(CURDATE(), factures_clients.FcDate) as jours_ecoules'),
                            DB::raw('ROUND(DATEDIFF(CURDATE(), factures_clients.FcDate) / 30.44, 1) as mois_approx')
                        )
                        ->where('factures_clients.FcClient', $clients[$i]->ClNumero)
                        ->where('factures_clients.FcIsSolde', 'N')
                        ->orderBy('factures_clients.FcDate', 'asc') // La plus ancienne d'abord
                        ->first(); // Prendre la première (la plus ancienne)

                    if ($factures_impayees) {
                        DB::table('clients')
                            ->where('clients.ClNumero', $clients[$i]->ClNumero)
                            ->update([
                                'clients.ClNbMoisImp' => $factures_impayees->mois_ecoulese
                            ]);
                    } else {
                        // Pas de facture impayée
                        DB::table('clients')
                            ->where('clients.ClNumero', $clients[$i]->ClNumero)
                            ->update(['clients.ClNbMoisImp' => 0]);
                    }
                }
            }

            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('clients.index')->with('alert', 'Client modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $ClNumero)
    {
        //
    }

    public function activer($ClNumero)
    {
        DB::table('clients')
            ->where('clients.ClNumero', $ClNumero)
            ->update([
                'clients.ClIsOn' => 'Y',
                'clients.ClDateOff' => NULL
            ]);;
    }

    public function desactiver($ClNumero)
    {
        DB::table('clients')
            ->where('clients.ClNumero', $ClNumero)
            ->update([
                'clients.ClIsOn' => 'N',
                'clients.ClDateOff' => date('Y-m-d H:i:s')
            ]);
    }

    public function delete($ClNumero)
    {
        Client::find($ClNumero)->delete();
    }


    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('file');

            // Option 1: Import simple
            Excel::import(new ClientImport, $file);
            return back()->with('alert', 'Import réussi !');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = "Ligne {$failure->row()}: {$failure->errors()[0]}";
            }

            return back()->with('alert-error', $errors);

        } catch (\Exception $e) {
            Log::error('Import error: ' . $e->getMessage());
            return back()->with('alert-error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
    */
    public function agents(string $ClNumero)
    {
        $ClNumero = decrypter($ClNumero);

        $client = DB::table('clients')
            ->where('clients.ClNumero',$ClNumero)
            ->first();

        $dernierPosting = DB::table('poster_vigile')
            ->leftJoin('sites_client', 'sites_client.ScNumero', '=', 'poster_vigile.PvSiteClient')
            ->where('sites_client.ScClient',$ClNumero)
            ->where('poster_vigile.PvIsOn','Y')
            ->where('poster_vigile.PvEntite', Auth::user()->entite)
            ->orderBy('poster_vigile.PvNumero','desc')
            ->first();

        if ($dernierPosting) {
            $personnels = DB::table('poster_vigile')
                ->leftJoin('personnels', 'personnels.PeNumero', '=', 'poster_vigile.PvVigile')
                ->leftJoin('sites_client', 'sites_client.ScNumero', '=', 'poster_vigile.PvSiteClient')
                ->where('sites_client.ScClient', $ClNumero)
                ->where('poster_vigile.PvEntite', Auth::user()->entite)
                ->where('poster_vigile.PvDateDebut', $dernierPosting->PvDateDebut)
                ->orderBy('poster_vigile.PvDateDebut', 'asc')
                ->get();
        } else {
            $personnels = collect(); // Collection vide
        }

        return view('clients.agents.list',compact('ClNumero','client','personnels'));
    }

    /**
     * Remove the specified resource from storage.
    */
    public function sites(string $ClNumero)
    {
        $ClNumero = decrypter($ClNumero);

        $client = DB::table('clients')
            ->where('clients.ClNumero',$ClNumero)
            ->first();

        $contrat = DB::table('avenants_contrat')
            ->leftJoin('clients','clients.ClNumero','=','avenants_contrat.AcClient')
            ->where('clients.ClNumero',$ClNumero)
            ->where('avenants_contrat.AcIsOn','Y')
            ->orderBy('avenants_contrat.AcNumero','desc')
            ->first();

        $sites_client = DB::table('sites_client')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->leftJoin('villes','villes.ViNumero','=','sites_client.ScVille')
            ->leftJoin('zones','zones.ZoNumero','=','sites_client.ScZone')
            ->leftJoin('quartiers','quartiers.QuNumero','=','sites_client.ScQuartier')
            ->where('sites_client.ScIsOn','Y')
            ->where('sites_client.ScClient',$ClNumero)
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->get();

        return view('clients.sites.list',compact('ClNumero','client','contrat','sites_client'));
    }



    /**
     * Remove the specified resource from storage.
    */
    public function contacts(string $ClNumero)
    {
        $ClNumero = decrypter($ClNumero);

        $client = DB::table('clients')
            ->where('clients.ClNumero',$ClNumero)
            ->first();

        $sites_client = DB::table('sites_client')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->leftJoin('villes','villes.ViNumero','=','sites_client.ScVille')
            ->leftJoin('zones','zones.ZoNumero','=','sites_client.ScZone')
            ->leftJoin('quartiers','quartiers.QuNumero','=','sites_client.ScQuartier')
            ->where('sites_client.ScIsOn','Y')
            ->where('sites_client.ScClient',$ClNumero)
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->get();

         $contacts_client = DB::table('contacts_client')
            ->leftJoin('clients','clients.ClNumero','=','contacts_client.CcClient')
            ->leftJoin('sites_client','sites_client.ScNumero','=','contacts_client.CcSiteContact')
            ->where('contacts_client.CcClient',$ClNumero)
            ->where('contacts_client.CcEntite',Auth::user()->entite)
            ->get();

        return view('clients.contacts.list',compact('ClNumero','client','sites_client','contacts_client'));
    }

    public function store_contact_client(Request $request)
    {
        $contact_client = DB::table('contacts_client')
            ->where('contacts_client.CcClient',$request->CcClient)
            ->where('contacts_client.CcNom',$request->CcNom)
            ->where('contacts_client.CcFonction',$request->CcFonction)
            ->first();

        if(empty($contact_client)) {

            DB::beginTransaction();

            try{
                // Do something and save to the db...
                $contact_client = new ContactClient();
                $contact_client->CcEntite = Auth::user()->entite;
                $contact_client->CcClient = $request->CcClient;
                $contact_client->CcSiteContact = $request->CcSiteContact;
                $contact_client->CcNom = $request->CcNom;
                $contact_client->CcFonction = $request->CcFonction;
                $contact_client->CcTel1 = $request->CcTel1;
                $contact_client->CcTel2 = $request->CcTel2;
                $contact_client->CcEMail = $request->CcEMail;
                $contact_client->CcIsOn = $request->CcIsOn;
                $contact_client->CcDateFin = $request->CcDateFin;
                $contact_client->save();

                DB::commit();
            }
            catch (\Throwable $th){
                // An error occured; cancel the transaction...
                DB::rollback();
                // and throw the error again.
                throw $th;
            }

            return redirect()->back()->with('alert', 'Contact client crée avec succès.');
        }
        else {
            return redirect()->back()->with('alert-error', 'Ce contact client existe déjà.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show_contact_client(string $CcNumero )
    {
        return DB::table('contacts_client')
            ->leftJoin('clients','clients.ClNumero','=','contacts_client.CcClient')
            ->where('contacts_client.CcNumero',$CcNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit_contact_client(string $CcNumero)
    {
        $contact_client = DB::table('contacts_client')
            ->where('contacts_client.CcNumero',$CcNumero)
            ->first();

        return response()->json($contact_client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_contact_client(Request $request, string $CcNumero)
    {
        DB::beginTransaction();
        try{
            // Do something and save to the db...
            DB::table('contacts_client')
            ->where('contacts_client.CcNumero',$CcNumero)
            ->update([
                'CcClient' => $request->CcClient,
                'CcSiteContact' => $request->CcSiteContact,
                'CcNom' => $request->CcNom,
                'CcFonction' => $request->CcFonction,
                'CcTel1' => $request->CcTel1,
                'CcTel2' => $request->CcTel2,
                'CcEMail' => $request->CcEMail,
                'CcIsOn' => $request->CcIsOn,
                'CcDateFin' => $request->CcDateFin
            ]);
            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->back()->with('alert', 'Conctact client modifié avec succès.');
    }

    
    public function activer_contact_client($CcNumero)
    {
        DB::table('contacts_client')
            ->where('contacts_client.CcNumero',$CcNumero)
            ->update([
                'contacts_client.CcIsOn' => 'Y',
                'contacts_client.CcDateFin' => NULL
            ]);
    }

    public function desactiver_contact_client($CcNumero)
    {
        DB::table('contacts_client')
            ->where('contacts_client.CcNumero',$CcNumero)
            ->update([
                'contacts_client.CcIsOn' => 'N',
                'contacts_client.CcDateFin' => date('Y-m-d H:i:s')
            ]);
    }


    public function delete_contact_client($CcNumero)
    {
        ContactClient::find($CcNumero)->delete();
    }



    public function filtre(Request $request)
    {
        if($request->statut_client == 'all' && $request->ville_client == 'all' && $request->secteur_activite_client == 'all')
        {
            $clients = DB::table('clients')
                ->leftJoin('villes','villes.ViNumero','=','clients.ClVilleSiege')
                ->leftJoin('secteurs_activite','secteurs_activite.SaNumero','=','clients.ClSectActivite')
                ->leftJoin('entites','entites.EnNumero','=','clients.ClEntite')
                ->where('clients.ClEntite',Auth::user()->entite)
                ->orderBy('clients.ClDesign','asc')
                ->get();
        }
        else
        {
            // Handle other cases where not all parameters are 'all'
            $query = DB::table('clients')
                ->leftJoin('villes','villes.ViNumero','=','clients.ClVilleSiege')
                ->leftJoin('entites','entites.EnNumero','=','clients.ClEntite');

            // Apply filters based on the request parameters
            if($request->statut_client != 'all') {
                $query->where('clients.ClIsOn',$request->statut_client)
                    ->where('clients.ClEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->ville_client != 'all') {
                $query->where('clients.ClVilleSiege',$request->ville_client)
                    ->where('clients.ClEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->secteur_activite_client != 'all') {
                $query->where('clients.ClSectActivite',$request->secteur_activite_client)
                    ->where('clients.ClEntite',Auth::user()->entite);
            }

            // Execute the query with the applied filters
            $clients = $query->orderBy('clients.ClDesign','asc')->get();
        }

        $villes = DB::table('villes')
            ->where('villes.ViEntite',Auth::user()->entite)
            ->orderBy('villes.ViNom','asc')
            ->get();

        $ville_client = $request->ville_client;
        $secteur_activite_client = $request->secteur_activite_client;
        $statut_client = $request->statut_client;

        $clients_1 = DB::table('clients')
            ->leftJoin('villes','villes.ViNumero','=','clients.ClVilleSiege')
            ->leftJoin('secteurs_activite','secteurs_activite.SaNumero','=','clients.ClSectActivite')
            ->leftJoin('entites','entites.EnNumero','=','clients.ClEntite')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->orderBy('clients.ClDesign','asc')
            ->get();

        if(!empty($clients_1))
        {
            for($i=0; $i<count($clients_1); $i++)
            {
                $sommes = DB::table('factures_clients')
                    ->where('factures_clients.FcClient', $clients_1[$i]->ClNumero)
                    ->selectRaw('SUM(factures_clients.FcMontantTTC) as mt_total, SUM(factures_clients.FcTotalPaye) as total_paye')
                    ->first();

                DB::table('clients')
                    ->where('clients.ClNumero',$clients_1[$i]->ClNumero)
                    ->update(['clients.ClMtImpayes' => ($sommes->mt_total-$sommes->total_paye)]);
            }

            for($i=0; $i<count($clients_1); $i++)
            {
                $factures_impayees = DB::table('factures_clients')
                    ->select(
                        'factures_clients.*',
                        DB::raw('TIMESTAMPDIFF(MONTH, factures_clients.FcDate, CURDATE()) as mois_ecoules'),
                        DB::raw('DATEDIFF(CURDATE(), factures_clients.FcDate) as jours_ecoules'),
                        DB::raw('ROUND(DATEDIFF(CURDATE(), factures_clients.FcDate) / 30.44, 1) as mois_approx')
                    )
                    ->where('factures_clients.FcClient', $clients_1[$i]->ClNumero)
                    ->where('factures_clients.FcIsSolde', 'N')
                    ->orderBy('factures_clients.FcDate', 'asc') // La plus ancienne d'abord
                    ->first(); // Prendre la première (la plus ancienne)

                if ($factures_impayees) {
                    DB::table('clients')
                        ->where('clients.ClNumero', $clients_1[$i]->ClNumero)
                        ->update([
                            'clients.ClNbMoisImp' => $factures_impayees->mois_ecoules
                        ]);
                } else {
                    // Pas de facture impayée
                    DB::table('clients')
                        ->where('clients.ClNumero', $clients_1[$i]->ClNumero)
                        ->update(['clients.ClNbMoisImp' => 0]);
                }
            }
        }

        return view('clients.filtre',compact('clients','statut_client','ville_client','secteur_activite_client','villes'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_quartiers($ClVilleSiege)
    {
        $quartiers = DB::table('quartiers')
            ->where('quartiers.QuVille',$ClVilleSiege)
            ->get();

        $str ='';

        $str.='

            <div class="form-group">
                <label for="ClQuartier">Quartier</label>
                <select class="e2 form-control" id="ClQuartier" name="ClQuartier">
                    <option selected value="">Selectionner...</option>';
                    foreach($quartiers as $quartier):
                        $str.='<option value="'.$quartier->QuNumero.'">'.$quartier->QuNom.'</option>';
                    endforeach;
                $str.='
                </select>
            </div>';

        $str.='
            <script>
                $(document).ready(function() {
                    $(".e2").select2({
                        width: "100%"
                    });
                });
            </script>';

        $str.='';

        echo $str;
    }
}
