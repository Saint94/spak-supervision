<?php

namespace App\Http\Controllers;

use App\Imports\SiteClientImport;
use App\Models\SiteClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\Log;

class SiteClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->orderBy('clients.ClDesign','asc')
            ->get();

        $villes = DB::table('villes')
            ->where('villes.ViEntite',Auth::user()->entite)
            ->orderBy('villes.ViNom','asc')
            ->get();

        $clients_1 = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->orderBy('clients.ClDesign','asc')
            ->get();

        $villes_1 = DB::table('villes')
            ->where('villes.ViEntite',Auth::user()->entite)
            ->orderBy('villes.ViNom','asc')
            ->get();

        $sites_client = DB::table('sites_client')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->leftJoin('villes','villes.ViNumero','=','sites_client.ScVille')
            ->leftJoin('zones','zones.ZoNumero','=','sites_client.ScZone')
            ->leftJoin('quartiers','quartiers.QuNumero','=','sites_client.ScQuartier')
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->get();

        //Pour filtre
        $clients_f = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->orderBy('clients.ClDesign','asc')
            ->get();

        $villes_f = DB::table('villes')
            ->leftJoin('personnels','personnels.PeNumero','=','villes.ViRespOper')
            ->where('villes.ViEntite',Auth::user()->entite)
            ->orderBy('villes.ViNom','asc')
            ->get();

        $zones_f = DB::table('zones')
            ->leftJoin('villes','villes.ViNumero','=','zones.ZoVille')
            ->where('zones.ZoEntite',Auth::user()->entite)
            ->orderBy('zones.ZoNom','asc')
            ->get();

        $quartiers_f = DB::table('quartiers')
            ->leftJoin('villes','villes.ViNumero','=','quartiers.QuVille')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->where('quartiers.QuEntite',Auth::user()->entite)
            ->orderBy('quartiers.QuNom','asc')
            ->get();

        return view('sites-client.list',compact(
            'sites_client','clients','villes','clients_1','villes_1',
            'clients_f','villes_f','zones_f','quartiers_f'
        ));
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
        $site_client = DB::table('sites_client')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->leftJoin('villes','villes.ViNumero','=','sites_client.ScVille')
            ->leftJoin('zones','zones.ZoNumero','=','sites_client.ScZone')
            ->leftJoin('quartiers','quartiers.QuNumero','=','sites_client.ScQuartier')
            ->where('sites_client.ScNomSite',$request->ScNomSite)
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->first();

        if(empty($site_client)) {
            DB::beginTransaction();

            try{
                // Do something and save to the db...
                $site_client = new SiteClient();
                $site_client->ScEntite = Auth::user()->entite;
                $site_client->ScClient = $request->ScClient;
                $site_client->ScNomSite = $request->ScNomSite;
                $site_client->ScVille = $request->ScVille;
                $site_client->ScZone = $request->ScZone;
                $site_client->ScQuartier = $request->ScQuartier;
                $site_client->ScPrecision = $request->ScPrecision;
                $site_client->ScLatitudeX = str_replace(',', '.',$request->ScLatitudeX);
                $site_client->ScLongitudeY = str_replace(',', '.',$request->ScLongitudeY);
                $site_client->ScIsMultiSuperv = $request->ScIsMultiSuperv;
                $site_client->ScIsVigile = $request->ScIsVigile ?? 'N';
                $site_client->ScNbVigileJr = $request->ScNbVigileJr ?? NULL;
                $site_client->ScNbVigileNu = $request->ScNbVigileNu ?? NULL;
                $site_client->ScIsMeChien = $request->ScIsMeChien ?? 'N';
                $site_client->ScNbMeChienJr = $request->ScNbMeChienJr ?? NULL;
                $site_client->ScNbMeChienNu = $request->ScNbMeChienNu ?? NULL;
                $site_client->ScIsCmdo = $request->ScIsCmdo ?? 'N';
                $site_client->ScNbCmdoJr = $request->ScNbCmdoJr ?? NULL;
                $site_client->ScNbCmdoNu = $request->ScNbCmdoNu ?? NULL;
                $site_client->ScIsSuperv = $request->ScIsSuperv ?? 'N';
                $site_client->ScNbSupervJr = $request->ScNbSupervJr ?? NULL;
                $site_client->ScNbSupervNu = $request->ScNbSupervNu ?? NULL;
                $site_client->ScIsOperVideo = $request->ScIsOperVideo ?? 'N';
                $site_client->ScIsLocatVideo = $request->ScIsLocatVideo ?? 'N';
                $site_client->ScIsInstVidSurv = $request->ScIsInstVidSurv ?? 'N';
                $site_client->ScIsOn = $request->ScIsOn;
                $site_client->ScUserCree = Auth::user()->id;
                $site_client->save();

                $count_sites_client = DB::table('sites_client')
                    ->where('sites_client.ScClient',$request->ScClient)
                    ->where('sites_client.ScEntite',Auth::user()->entite)
                    ->count();

                $count_nbre = DB::table('sites_client')
                    ->where('sites_client.ScClient',$request->ScClient)
                    ->where('sites_client.ScEntite', Auth::user()->entite)
                    ->selectRaw('
                        COALESCE(SUM(ScNbVigileJr), 0) as total_vigile_jr,
                        COALESCE(SUM(ScNbVigileNu), 0) as total_vigile_nu,
                        COALESCE(SUM(ScNbMeChienJr), 0) as total_me_chien_jr,
                        COALESCE(SUM(ScNbMeChienNu), 0) as total_me_chien_nu,
                        COALESCE(SUM(ScNbCmdoJr), 0) as total_cmdo_jr,
                        COALESCE(SUM(ScNbCmdoNu), 0) as total_cmdo_nu,
                        COALESCE(SUM(ScNbSupervJr), 0) as total_superv_jr,
                        COALESCE(SUM(ScNbSupervNu), 0) as total_superv_nu
                    ')
                    ->first();

                $flags = [
                    'is_vigile'   => ($count_nbre->total_vigile_jr + $count_nbre->total_vigile_nu) > 0 ? 'Y' : 'N',
                    'is_me_chien' => ($count_nbre->total_me_chien_jr + $count_nbre->total_me_chien_nu) > 0 ? 'Y' : 'N',
                    'is_cmdo'     => ($count_nbre->total_cmdo_jr +$count_nbre->total_cmdo_nu) > 0 ? 'Y' : 'N',
                    'is_superv'   => ($count_nbre->total_superv_jr +$count_nbre->total_superv_nu) > 0 ? 'Y' : 'N',
                ];


                DB::table('clients')
                    ->where('clients.ClNumero',$request->ScClient)
                    ->update([
                        'clients.ClNbreSites' => $count_sites_client,
                        'clients.ClIsVigileOn' => $flags['is_vigile'],
                        'clients.ClNbVigileJr' => $count_nbre->total_vigile_jr,
                        'clients.ClNbVigileNu' => $count_nbre->total_vigile_nu,
                        'clients.ClIsMeChienOn' => $flags['is_me_chien'],
                        'clients.ClNbMeChienJr' => $count_nbre->total_me_chien_jr,
                        'clients.ClNbMeChienNu' => $count_nbre->total_me_chien_nu,
                        'clients.ClIsCmdoOn' => $flags['is_cmdo'],
                        'clients.ClNbCmdoJr' => $count_nbre->total_cmdo_jr,
                        'clients.ClNbCmdoNu' => $count_nbre->total_cmdo_nu,
                        'clients.ClIsSupervOn' => $flags['is_superv'],
                        'clients.ClNbSupervJr' => $count_nbre->total_superv_jr,
                        'clients.ClNbSupervNu' => $count_nbre->total_superv_nu
                    ]);

                DB::commit();
            }

            catch (\Throwable $th){
                // An error occured; cancel the transaction...
                DB::rollback();
                // and throw the error again.
                throw $th;
            }

            return redirect()->route('sites-client.index')->with('alert', 'Site client crée avec succès.');
        }
        else {
            return redirect()->route('sites-client.index')->with('alert-error', 'Ce site client existe déjà.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $ScNumero )
    {
        return DB::table('sites_client')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->leftJoin('villes','villes.ViNumero','=','sites_client.ScVille')
            ->leftJoin('zones','zones.ZoNumero','=','sites_client.ScZone')
            ->leftJoin('quartiers','quartiers.QuNumero','=','sites_client.ScQuartier')
            ->where('sites_client.ScNumero',$ScNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $ScNumero)
    {
        $site_client = DB::table('sites_client')
            ->where('sites_client.ScNumero',$ScNumero)
            ->first();

        return response()->json($site_client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $ScNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('sites_client')
            ->where('sites_client.ScNumero',$ScNumero)
            ->update([
                'ScClient' => $request->ScClient,
                'ScNomSite' => $request->ScNomSite,
                'ScVille' => $request->ScVille,
                'ScZone' => $request->ScZone,
                'ScQuartier' => $request->ScQuartier,
                'ScPrecision' => $request->ScPrecision,
                'ScLatitudeX' => str_replace(',', '.',$request->ScLatitudeX),
                'ScLongitudeY' => str_replace(',', '.',$request->ScLongitudeY),
                'ScIsMultiSuperv' => $request->ScIsMultiSuperv,
                'ScIsVigile' => $request->ScIsVigile ?? 'N',
                'ScNbVigileJr' => $request->ScNbVigileJr ?? NULL,
                'ScNbVigileNu' => $request->ScNbVigileNu ?? NULL,
                'ScIsMeChien' => $request->ScIsMeChien ?? 'N',
                'ScNbMeChienJr' => $request->ScNbMeChienJr ?? NULL,
                'ScNbMeChienNu' => $request->ScNbMeChienNu ?? NULL,
                'ScIsCmdo' => $request->ScIsCmdo ?? 'N',
                'ScNbCmdoJr' => $request->ScNbCmdoJr ?? NULL,
                'ScNbCmdoNu' => $request->ScNbCmdoNu ?? NULL,
                'ScIsSuperv' => $request->ScIsSuperv ?? 'N',
                'ScNbSupervJr' => $request->ScNbSupervJr ?? NULL,
                'ScNbSupervNu' => $request->ScNbSupervNu ?? NULL,
                'ScIsOperVideo' => $request->ScIsOperVideo ?? 'N',
                'ScIsLocatVideo' => $request->ScIsLocatVideo ?? 'N',
                'ScIsInstVidSurv' => $request->ScIsInstVidSurv ?? 'N',
                'ScIsOn' => $request->ScIsOn
            ]);

            $count_sites_client = DB::table('sites_client')
                ->where('sites_client.ScClient',$request->ScClient)
                ->where('sites_client.ScEntite',Auth::user()->entite)
                ->count();

            $count_nbre = DB::table('sites_client')
                    ->where('sites_client.ScClient', $request->ScClient)
                    ->where('sites_client.ScEntite', Auth::user()->entite)
                    ->selectRaw('
                        COALESCE(SUM(ScNbVigileJr), 0) as total_vigile_jr,
                        COALESCE(SUM(ScNbVigileNu), 0) as total_vigile_nu,
                        COALESCE(SUM(ScNbMeChienJr), 0) as total_me_chien_jr,
                        COALESCE(SUM(ScNbMeChienNu), 0) as total_me_chien_nu,
                        COALESCE(SUM(ScNbCmdoJr), 0) as total_cmdo_jr,
                        COALESCE(SUM(ScNbCmdoNu), 0) as total_cmdo_nu,
                        COALESCE(SUM(ScNbSupervJr), 0) as total_superv_jr,
                        COALESCE(SUM(ScNbSupervNu), 0) as total_superv_nu
                    ')
                    ->first();

                $flags = [
                    'is_vigile'   => ($count_nbre->total_vigile_jr + $count_nbre->total_vigile_nu) > 0 ? 'Y' : 'N',
                    'is_me_chien' => ($count_nbre->total_me_chien_jr + $count_nbre->total_me_chien_nu) > 0 ? 'Y' : 'N',
                    'is_cmdo'     => ($count_nbre->total_cmdo_jr +$count_nbre->total_cmdo_nu) > 0 ? 'Y' : 'N',
                    'is_superv'   => ($count_nbre->total_superv_jr +$count_nbre->total_superv_nu) > 0 ? 'Y' : 'N',
                ];


                DB::table('clients')
                    ->where('clients.ClNumero',$request->ScClient)
                    ->update([
                        'clients.ClNbreSites' => $count_sites_client,
                        'clients.ClIsVigileOn' => $flags['is_vigile'],
                        'clients.ClNbVigileJr' => $count_nbre->total_vigile_jr,
                        'clients.ClNbVigileNu' => $count_nbre->total_vigile_nu,
                        'clients.ClIsMeChienOn' => $flags['is_me_chien'],
                        'clients.ClNbMeChienJr' => $count_nbre->total_me_chien_jr,
                        'clients.ClNbMeChienNu' => $count_nbre->total_me_chien_nu,
                        'clients.ClIsCmdoOn' => $flags['is_cmdo'],
                        'clients.ClNbCmdoJr' => $count_nbre->total_cmdo_jr,
                        'clients.ClNbCmdoNu' => $count_nbre->total_cmdo_nu,
                        'clients.ClIsSupervOn' => $flags['is_superv'],
                        'clients.ClNbSupervJr' => $count_nbre->total_superv_jr,
                        'clients.ClNbSupervNu' => $count_nbre->total_superv_nu
                    ]);



            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('sites-client.index')->with('alert', 'Site client modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function activer($ScNumero)
    {
        DB::table('sites_client')
            ->where('sites_client.ScNumero',$ScNumero)
            ->update([
                'sites_client.ScIsOn' => 'Y'
            ]);
    }

    public function desactiver($ScNumero)
    {
        DB::table('sites_client')
            ->where('sites_client.ScNumero',$ScNumero)
            ->update([
                'sites_client.ScIsOn' => 'N',
                'ScDateHOff' => date('Y-m-d H:i:s')
            ]);
    }

    public function delete($ScNumero)
    {
        SiteClient::find($ScNumero)->delete();
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show_zones($ScVille)
    {
        $ville = DB::table('villes')
            ->where('villes.ViNumero',$ScVille)
            ->first();

        $zones = DB::table('zones')
            ->where('zones.ZoVille',$ScVille)
            ->get();

        $str ='';

        if($ville->ViIsZoneOn == 'Y') {
            $str.='
                <div class="form-group">
                    <label for="ScQuartier">Zone <abbr style="color: #bd4147;">(*)</abbr></label>
                    <select class="e7 form-control" id="ScZone" name="ScZone" required>
                        <option selected value="">Selectionner...</option>';
                        foreach($zones as $zone):
                            $str.='<option value="'.$zone->ZoNumero.'">'.$zone->ZoNom.'</option>';
                    endforeach;
                    $str.='</select>
                    <div class="invalid-feedback">
                        Ce champ est obligatoire.
                    </div>
                </div>';
        } else {
            $str.='
                <div class="form-group">
                    <label for="ScZone">Zone</label>
                    <input type="text" class="form-control" id="ScZone" name="ScZone" readonly/>
                </div>';
        }

        $str.='
        <script>
            $(document).ready(function() {
                $(".e7").select2({
                    width: "100%"
                });
            });
        </script>';

        $str.='';

        echo $str;
    }

    public function show_quartiers($ScZone)
    {
        $quartiers = DB::table('quartiers')
            ->where('quartiers.QuZone',$ScZone)
            ->get();

        $str ='';

        $str.='
        <div class="form-group">
            <label for="ScQuartier">Quartier</label>
            <select class="e8 form-control" id="ScQuartier" name="ScQuartier">
                <option selected value="">Selectionner...</option>';
                foreach($quartiers as $quartier):
                    $str.='<option value="'.$quartier->QuNumero.'">'.$quartier->QuNom.'</option>';
                endforeach;
            $str.='</select>
            <div class="invalid-feedback">
                Ce champ est obligatoire.
            </div>
        </div>';

        $str.='
        <script>
            $(document).ready(function() {
                $(".e8").select2({
                    width: "100%"
                });
            });
        </script>';

        $str.='';

        echo $str;
    }

    public function show_zones_edit($ScVille)
    {
        $ville = DB::table('villes')
            ->where('villes.ViNumero',$ScVille)
            ->first();

        $zones = DB::table('zones')
            ->where('zones.ZoVille',$ScVille)
            ->get();

        $str ='';

        if($ville->ViIsZoneOn == 'Y') {
            $str.='
                <div class="form-group">
                    <label for="ScZone">Zone <abbr style="color: #bd4147;">(*)</abbr></label>
                    <select class="form-control ScZone" id="ScZone" name="ScZone" required>
                        <option selected value="">Selectionner...</option>';
                        foreach($zones as $zone):
                            $str.='<option value="'.$zone->ZoNumero.'">'.$zone->ZoNom.'</option>';
                    endforeach;
                    $str.='</select>
                    <div class="invalid-feedback">
                        Ce champ est obligatoire.
                    </div>
                </div>';
        } else {
            $str.='
                <div class="form-group">
                    <label for="ScZone">Zone</label>
                    <input type="text" class="form-control" id="ScZone" name="ScZone" readonly/>
                </div>';
        }


        $str.='';

        echo $str;
    }

    public function show_quartiers_edit($ScZone)
    {
        $quartiers = DB::table('quartiers')
            ->where('quartiers.QuZone',$ScZone)
            ->get();

        $str ='';

        $str.='
        <div class="form-group">
            <label for="ScQuartier_edit">Quartier</label>
            <select class="form-control ScQuartier" id="ScQuartier_edit" name="ScQuartier">
                <option selected value="">Selectionner...</option>';
                foreach($quartiers as $quartier):
                    $str.='<option value="'.$quartier->QuNumero.'">'.$quartier->QuNom.'</option>';
                endforeach;
            $str.='</select>
            <div class="invalid-feedback">
                Ce champ est obligatoire.
            </div>
        </div>';

        $str.='';

        echo $str;
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('file');

            // Option 1: Import simple
            Excel::import(new SiteClientImport, $file);
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

    public function updateGps(Request $request, $id)
    {
        $request->validate([
            'ScLatitudeX' => 'required|string',
            'ScLongitudeY' => 'required|string',
            'ScPrecision' => 'nullable|numeric'
        ]);

        $site = SiteClient::findOrFail($id);

        // Remplacer les virgules par des points si nécessaire
        $latitude = str_replace(',', '.', $request->ScLatitudeX);
        $longitude = str_replace(',', '.', $request->ScLongitudeY);

        $site->ScLatitudeX = $latitude;
        $site->ScLongitudeY = $longitude;

        if ($request->has('ScPrecision') && $request->ScPrecision) {
            $site->ScPrecision = $request->ScPrecision;
        }

        $site->save();

        return response()->json([
            'success' => true,
            'message' => 'Coordonnées GPS mises à jour avec succès',
            'data' => $site
        ]);
    }

    public function filtre(Request $request)
    {
        $clients = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->orderBy('clients.ClDesign','asc')
            ->get();

        $villes = DB::table('villes')
            ->where('villes.ViEntite',Auth::user()->entite)
            ->orderBy('villes.ViNom','asc')
            ->get();

        $clients_1 = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->orderBy('clients.ClDesign','asc')
            ->get();

        $villes_1 = DB::table('villes')
            ->where('villes.ViEntite',Auth::user()->entite)
            ->orderBy('villes.ViNom','asc')
            ->get();

        $sites_client = DB::table('sites_client')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->leftJoin('villes','villes.ViNumero','=','sites_client.ScVille')
            ->leftJoin('zones','zones.ZoNumero','=','sites_client.ScZone')
            ->leftJoin('quartiers','quartiers.QuNumero','=','sites_client.ScQuartier')
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->get();

        if($request->numero_client == 'all' && $request->ville_site == 'all' && $request->zone_site == 'all' && $request->quartier_site == 'all')
        {
            $sites_client = DB::table('sites_client')
                ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
                ->leftJoin('villes','villes.ViNumero','=','sites_client.ScVille')
                ->leftJoin('zones','zones.ZoNumero','=','sites_client.ScZone')
                ->leftJoin('quartiers','quartiers.QuNumero','=','sites_client.ScQuartier')
                ->where('sites_client.ScEntite',Auth::user()->entite)
                ->get();
        }
        else
        {
            // Handle other cases where not all parameters are 'all'
            $query = DB::table('sites_client')
                ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
                ->leftJoin('villes','villes.ViNumero','=','sites_client.ScVille')
                ->leftJoin('zones','zones.ZoNumero','=','sites_client.ScZone')
                ->leftJoin('quartiers','quartiers.QuNumero','=','sites_client.ScQuartier');

            // Apply filters based on the request parameters
            if($request->numero_client != 'all') {
                $query->where('sites_client.ScClient',$request->numero_client)
                    ->where('sites_client.ScEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->ville_site != 'all') {
                $query->where('sites_client.ScVille',$request->ville_site)
                    ->where('sites_client.ScEntite',Auth::user()->entite);
            }


            // Apply filters based on the request parameters
            if($request->zone_site != 'all') {
                $query->where('sites_client.ScZone',$request->zone_site)
                    ->where('sites_client.ScEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->quartier_site != 'all') {
                $query->where('sites_client.ScQuartier',$request->quartier_site)
                    ->where('sites_client.ScEntite',Auth::user()->entite);
            }

            // Execute the query with the applied filters
            $sites_client = $query->get();
        }


        $numero_client =  $request->numero_client;
        $ville_site =  $request->ville_site;
        $zone_site = $request->zone_site;
        $quartier_site = $request->quartier_site;

        //Pour filtre
        $clients_f = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->orderBy('clients.ClDesign','asc')
            ->get();

        $villes_f = DB::table('villes')
            ->leftJoin('personnels','personnels.PeNumero','=','villes.ViRespOper')
            ->where('villes.ViEntite',Auth::user()->entite)
            ->orderBy('villes.ViNom','asc')
            ->get();

        $zones_f = DB::table('zones')
            ->leftJoin('villes','villes.ViNumero','=','zones.ZoVille')
            ->where('zones.ZoEntite',Auth::user()->entite)
            ->orderBy('zones.ZoNom','asc')
            ->get();

        $quartiers_f = DB::table('quartiers')
            ->leftJoin('villes','villes.ViNumero','=','quartiers.QuVille')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->where('quartiers.QuEntite',Auth::user()->entite)
            ->orderBy('quartiers.QuNom','asc')
            ->get();


        return view('sites-client.filtre',compact(
            'sites_client','clients','villes','clients_1','villes_1',
            'clients_f','villes_f','zones_f','quartiers_f',
            'numero_client','ville_site','zone_site','quartier_site'
        ));
    }

}
