<?php

namespace App\Http\Controllers;

use App\Models\OperationSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OperationSiteController extends Controller
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
            // 2. Récupération du fichier
            $file = $request->file('SoChemPhoto');

            // 3. Construction du nom du fichier
            // Nettoyage du nom agent (supprime accents, espaces, caractères spéciaux)
            $nomAgent = Str::slug($request->SoNomPrenAgent, '_'); // ex: "Jean Dupont" -> "jean_dupont"

            // Nom original de l'image sans extension
            $nomImage = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $nomImage = Str::slug($nomImage, '_');

            // Extension d'origine (jpeg, png, jpg)
            $extension = $file->getClientOriginalExtension();

            // Concaténation : nom_agent + timestamp + nom_image + extension
            // On ajoute un timestamp pour éviter les doublons si même agent + même nom image
            $nomComplet = $nomAgent . '_' . time() . '_' . $nomImage . '.' . $extension;

            // 4. Déplacement du fichier vers public/assets/img/pointages
            $destination = public_path('assets/img/pointages');

            // Créer le dossier s'il n'existe pas
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $nomComplet);



            // Do something and save to the db...
            $operation_site = new OperationSite();
            $operation_site->SoEntite = Auth::user()->entite;
            $operation_site->SoOperSuperv = $request->SoOperSuperv;
            $operation_site->SoSite = $request->SoSite;
            $operation_site->SoAgentPgme = $request->SoAgentPgme;
            $operation_site->SoIsPresent = $request->SoIsPresent;
            $operation_site->SoAgentOper = ($request->SoIsPresent == 'Y') ? $request->SoAgentPgme : $request->SoAgentOper;
            $operation_site->SoNomPrenAgent = $request->SoNomPrenAgent ?? NULL;
            $operation_site->SoIsAgentSigne = $request->SoIsAgentSigne;
            $operation_site->SoHeureArrivAg = $request->SoHeureArrivAg ?? NULL;
            $operation_site->SoHeureDepart = $request->SoHeureDepart ?? NULL;
            $operation_site->SoPresentation = $request->SoPresentation;
            $operation_site->SoObservSuperv = $request->SoObservSuperv;
            $operation_site->SoObservClt = $request->SoObservClt;
            $operation_site->SoStatut = 'S';
            $operation_site->SoIsPointageOK = 'Y' ?? $request->SoIsPointageOK;
            $operation_site->SoEcartPointage = isset($request->distance) ? str_replace(',', '.', $request->distance) : NULL;
            $operation_site->SoLatitudeX = isset($request->SoLatitudeX) ? str_replace(',', '.', $request->SoLatitudeX) : NULL;
            $operation_site->SoLongitudeY = isset($request->SoLongitudeY) ? str_replace(',', '.', $request->SoLongitudeY) : NULL;
            $operation_site->SoChemPhoto = $nomComplet;
            $operation_site->save();

            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('supervision-site.pointage-agents',crypter($request->SoOperSuperv))->with('alert', 'Pointage saisie avec succès.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $OsNumero)
    {
        return DB::table('operations_superv')
            ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
            ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
            ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
            ->leftJoin('quartiers','quartiers.QuZone','=','zones.ZoNumero')
            ->where('operations_superv.OsNumero',$OsNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $OsNumero)
    {
        $supervision_site = DB::table('operations_superv')
            ->where('operations_superv.OsNumero',$OsNumero)
            ->first();

        return response()->json($supervision_site);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $OsNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('operations_superv')
            ->where('operations_superv.OsNumero',$OsNumero)
            ->update([
                'OsDate' => $request->OsDate,
                'OsVille' => $request->OsVille,
                'OsZone' => $request->OsZone,
                'OsSuperviseur' => $request->OsSuperviseur,
                'OsFaction' => $request->OsFaction,
                'OsHeureDebut' => $request->OsHeureDebut,
                'OsHeureFin' => $request->OsHeureFin,
                'OsIsRetourFiche' => $request->OsIsRetourFiche,
                'OsStatut' => $request->OsStatut,
                'OsDetails' => $request->OsDetails
            ]);
            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('supervision-sites.index')->with('alert', 'Site superviseur modifié avec succès.');
    }


    public function filtre(Request $request)
    {
        $OsNumero = $request->OsNumero;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if($request->numero_client == 'all' && $request->site_client == 'all' && $request->ville_site == 'all' && $request->zone_site == 'all' && $request->quartier_site == 'all' && $request->pointage_site == 'all')
        {
            $pointage_agents = DB::table('sites_oper_superv')
                ->select([
                'sites_oper_superv.*',
                'agent_pgme.PeNumero as agent_pgme_numero',
                'agent_pgme.PeNom as agent_pgme_nom',
                'agent_pgme.PePrenom as agent_pgme_prenom',
                'agent_pgme.PeTel1 as agent_pgme_tel1',
                'agent_pgme.PeTel2 as agent_pgme_tel2',
                'agent_oper.PeNumero as agent_oper_numero',
                'agent_oper.PeNom as agent_oper_nom',
                'agent_oper.PePrenom as agent_oper_prenom',
                'agent_oper.PeTel1 as agent_oper_tel1',
                'agent_oper.PeTel2 as agent_oper_tel2',
                'sites_client.*',
                'villes.*',
                'zones.*'
            ])
            ->leftJoin('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
            ->leftJoin('personnels AS agent_pgme', 'agent_pgme.PeNumero', '=', 'sites_oper_superv.SoAgentPgme') // Alias pour l'agent Pgme
            ->leftJoin('personnels AS agent_oper', 'agent_oper.PeNumero', '=', 'sites_oper_superv.SoAgentOper') // Alias pour l'agent Oper
            ->leftJoin('sites_client', 'sites_client.ScNumero', '=', 'sites_oper_superv.SoSite')
            ->leftJoin('villes', 'villes.ViNumero', '=', 'operations_superv.OsVille')
            ->leftJoin('zones', 'zones.ZoNumero', '=', 'operations_superv.OsZone')
            ->where('sites_oper_superv.SoOperSuperv',$request->OsNumero)
            ->whereDate('operations_superv.OsDate','>=',$start_date)
            ->whereDate('operations_superv.OsDate','<=',$end_date)
            ->where('sites_oper_superv.SoEntite',Auth::user()->entite)
            ->get();
        }
        else
        {
            // Handle other cases where not all parameters are 'all'
            $query = DB::table('sites_oper_superv')
                ->select([
                'sites_oper_superv.*',
                'agent_pgme.PeNumero as agent_pgme_numero',
                'agent_pgme.PeNom as agent_pgme_nom',
                'agent_pgme.PePrenom as agent_pgme_prenom',
                'agent_pgme.PeTel1 as agent_pgme_tel1',
                'agent_pgme.PeTel2 as agent_pgme_tel2',
                'agent_oper.PeNumero as agent_oper_numero',
                'agent_oper.PeNom as agent_oper_nom',
                'agent_oper.PePrenom as agent_oper_prenom',
                'agent_oper.PeTel1 as agent_oper_tel1',
                'agent_oper.PeTel2 as agent_oper_tel2',
                'sites_client.*',
                'villes.*',
                'zones.*'
            ])
            ->leftJoin('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
            ->leftJoin('personnels AS agent_pgme', 'agent_pgme.PeNumero', '=', 'sites_oper_superv.SoAgentPgme') // Alias pour l'agent Pgme
            ->leftJoin('personnels AS agent_oper', 'agent_oper.PeNumero', '=', 'sites_oper_superv.SoAgentOper') // Alias pour l'agent Oper
            ->leftJoin('sites_client', 'sites_client.ScNumero', '=', 'sites_oper_superv.SoSite')
            ->leftJoin('villes', 'villes.ViNumero', '=', 'operations_superv.OsVille')
            ->leftJoin('zones', 'zones.ZoNumero', '=', 'operations_superv.OsZone');

            // Apply filters based on the request parameters
            if($request->superviseur_site != 'all') {
                $query->where('operations_superv.OsSuperviseur',$request->superviseur_site)
                    ->where('sites_oper_superv.SoOperSuperv',$request->OsNumero)
                    ->whereDate('operations_superv.OsDate','>=',$start_date)
                    ->whereDate('operations_superv.OsDate','<=',$end_date)
                    ->where('sites_oper_superv.SoEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->numero_client != 'all') {
                $query->where('clients.ClNumero',$request->numero_client)
                    ->where('sites_oper_superv.SoOperSuperv',$request->OsNumero)
                    ->whereDate('operations_superv.OsDate','>=',$start_date)
                    ->whereDate('operations_superv.OsDate','<=',$end_date)
                    ->where('sites_oper_superv.SoEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->site_client != 'all') {
                $query->where('sites_oper_superv.SoSite',$request->site_client)
                    ->where('sites_oper_superv.SoOperSuperv',$request->OsNumero)
                    ->whereDate('operations_superv.OsDate','>=',$start_date)
                    ->whereDate('operations_superv.OsDate','<=',$end_date)
                    ->where('sites_oper_superv.SoEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->ville_site != 'all') {
                $query->where('personnels.PeVille',$request->ville_site)
                    ->where('sites_oper_superv.SoOperSuperv',$request->OsNumero)
                    ->whereDate('operations_superv.OsDate','>=',$start_date)
                    ->whereDate('operations_superv.OsDate','<=',$end_date)
                    ->where('sites_oper_superv.SoEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->zone_site != 'all') {
                $query->where('zones.ZoNumero',$request->zone_site)
                    ->where('sites_oper_superv.SoOperSuperv',$request->OsNumero)
                    ->whereDate('operations_superv.OsDate','>=',$start_date)
                    ->whereDate('operations_superv.OsDate','<=',$end_date)
                    ->where('sites_oper_superv.SoEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->quartier_site != 'all') {
                $query->where('personnels.PeQuartier',$request->quartier_site)
                    ->where('sites_oper_superv.SoOperSuperv',$request->OsNumero)
                    ->whereDate('operations_superv.OsDate','>=',$start_date)
                    ->whereDate('operations_superv.OsDate','<=',$end_date)
                    ->where('sites_oper_superv.SoEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->pointage_site != 'all') {
                $query->where('sites_client.ScNumero',$request->pointage_site)
                    ->where('sites_oper_superv.SoOperSuperv',$request->OsNumero)
                    ->whereDate('operations_superv.OsDate','>=',$start_date)
                    ->whereDate('operations_superv.OsDate','<=',$end_date)
                    ->where('sites_oper_superv.SoEntite',Auth::user()->entite);
            }

            // Execute the query with the applied filters
            $pointage_agents = $query->get();
        }

        $superviseur_site =  $request->superviseur_site ?? 'all';
        $numero_client =  $request->numero_client ?? 'all';
        $site_client = $request->site_client ?? 'all';
        $ville_site = $request->ville_site ?? 'all';
        $zone_site = $request->zone_site ?? 'all';
        $quartier_site = $request->quartier_site ?? 'all';
        $pointage_site = $request->pointage_site ?? 'all';

        $supervision_site = DB::table('operations_superv')
            ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
            ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
            ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
            ->leftJoin('quartiers','quartiers.QuZone','=','zones.ZoNumero')
            ->where('operations_superv.OsNumero',$OsNumero)
            ->first();


        /* Pour les filtres */
        $superviseurs = DB::table('personnels')
            ->whereIn('personnels.PeStatut', ['SG', 'SA', 'CT'])
            ->where('personnels.PeEntite', Auth::user()->entite)
            ->where('personnels.PeIsOn','Y')
            ->orderBy('personnels.PeMatric', 'asc')
            ->get();


        $clients = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->orderBy('clients.ClDesign','asc')
            ->get();

        $sites_client = DB::table('sites_client')
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->orderBy('sites_client.ScNomSite','asc')
            ->get();

        $villes = DB::table('villes')
            ->leftJoin('personnels','personnels.PeNumero','=','villes.ViRespOper')
            ->where('villes.ViEntite',Auth::user()->entite)
            ->orderBy('villes.ViNom','asc')
            ->get();

        $zones = DB::table('zones')
            ->leftJoin('villes','villes.ViNumero','=','zones.ZoVille')
            ->where('zones.ZoEntite',Auth::user()->entite)
            ->orderBy('zones.ZoNom','asc')
            ->get();

        $quartiers = DB::table('quartiers')
            ->leftJoin('villes','villes.ViNumero','=','quartiers.QuVille')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->where('quartiers.QuEntite',Auth::user()->entite)
            ->orderBy('quartiers.QuNom','asc')
            ->get();
        /* Pour les filtres */

        return view('pointage-agents.filtre',compact(
            'OsNumero','pointage_agents','supervision_site',
            'superviseurs','clients','sites_client','villes',
            'zones','quartiers','OsNumero','start_date',
            'end_date','numero_client','site_client',
            'ville_site','zone_site','quartier_site',
            'pointage_site','superviseur_site'));
    }


    public function carte_google_maps(string $SoNumero)
    {
        $SoNumero = decrypter($SoNumero);

        $site_oper_superv = DB::table('sites_oper_superv')
            ->leftJoin('sites_client','sites_client.ScNumero','=','sites_oper_superv.SoSite')
            ->where('sites_oper_superv.SoNumero',$SoNumero)
            ->first();

        // Vérifier si les coordonnées existent
        if (!$site_oper_superv || !$site_oper_superv->ScLatitudeX || !$site_oper_superv->ScLongitudeY) {
            return view('pointage-agents.carte-google-maps', ['message' => 'Coordonnées non disponibles']);
        }

         return view('pointage-agents.carte-google-maps',compact('SoNumero','site_oper_superv'));
    }

    public function show_distance_pointage_site($SoSite,$latitude2,$longitude2)
    {
        $site_client = DB::table('sites_client')
            ->where('sites_client.ScNumero',$SoSite)
            ->first();

        $str ='';

        if(!empty($latitude2) && !empty($longitude2) && !empty($site_client->ScLatitudeX) && !empty($site_client->ScLongitudeY))
        {

            // Convertir les degrés en radians
            $lat1 = deg2rad($site_client->ScLatitudeX);
            $lon1 = deg2rad($site_client->ScLongitudeY);
            $lat2 = deg2rad(str_replace(',', '.',$latitude2));
            $lon2 = deg2rad(str_replace(',', '.',$longitude2));

            // Différences
            $dLat = $lat2 - $lat1;
            $dLon = $lon2 - $lon1;

            // Formule de Haversine
            $a = sin($dLat/2) * sin($dLat/2) +
                cos($lat1) * cos($lat2) *
                sin($dLon/2) * sin($dLon/2);

            $c = 2 * atan2(sqrt($a), sqrt(1-$a));

            // Distance en mètres
            $distance = round(6371000*$c, 2);

            if($distance <= $site_client->ScPrecision){
                $statut_pointage = "Conforme";
            } else {
                $statut_pointage = "Non Conforme";
            }


            if($distance) {
                $str.='
                <div class="col-xl-6 col-md-6">
                    <div class="form-group">
                        <label for="distance">Distance en mètres entre le site et point de pointage</label>
                        <input type="text" class="form-control" value="'.str_replace('.', ',',$distance).' m'.'" readonly/>
                        <input type="hidden" class="form-control" id="distance" name="distance" value="'.str_replace('.', ',',$distance).'" readonly/>
                        <div class="invalid-feedback">
                            Ce champ est obligatoire.
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6">
                    <div class="form-group">
                        <label for="statut_pointage">Statut du pointage</label>
                        <input type="text" class="form-control" id="statut_pointage" name="statut_pointage" value="'.$statut_pointage.'" readonly/>
                    </div>
                </div>';
            } else {

                $distance = "Aucune";

                $str.='
                <div class="col-xl-6 col-md-6">
                    <div class="form-group">
                        <label for="distance">Distance en mètres entre le site et point de pointage</label>
                        <input type="text" class="form-control" id="distance" name="distance" value="'.$distance.'" readonly/>
                        <div class="invalid-feedback">
                            Ce champ est obligatoire.
                        </div>
                        <div style="color:red">
                            Mettez à jour les coordonnées de ce site client !
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6">
                    <div class="form-group">
                        <label for="statut_pointage">Statut du pointage</label>
                        <input type="text" class="form-control" id="statut_pointage" name="statut_pointage" readonly/>
                    </div>
                </div>';
            }
        } else {

            // Distance en mètres
            $distance = "Aucune";

            $str.='
            <div class="col-xl-6 col-md-6">
                <div class="form-group">
                    <label for="distance">Distance en mètres entre le site et point de pointage</label>
                    <input type="text"  class="form-control" id="distance" name="distance" value="'.$distance.'" readonly/>
                    <div class="invalid-feedback">
                        Ce champ est obligatoire.
                    </div>
                    <div style="color:red">
                        Mettez à jour les coordonnées de ce site client !
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6">
                <div class="form-group">
                    <label for="statut_pointage">Statut du pointage</label>
                    <input type="text" class="form-control" id="statut_pointage" name="statut_pointage" readonly/>
                    <div class="invalid-feedback">
                        Ce champ est obligatoire.
                    </div>
                </div>
            </div>';
        }

        $str.='';

        echo $str;
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show_sites_zones($OsNumero)
    {
        if(Auth::user()->type == 'T') {

            $supervision_site = DB::table('operations_superv')
                ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
                ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
                ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
                ->where('operations_superv.OsNumero',$OsNumero)
                ->first();

            $str ='';

            if($supervision_site->ViNumero){

                if($supervision_site->ViIsAllSiteOn == 'Y') {

                    if($supervision_site->ViIsZoneOn == 'Y') {

                        $sites_supervises = DB::table('sites_client')
                            ->where('sites_client.ScZone',$supervision_site->ZoNumero)
                            ->where('sites_client.ScEntite',Auth::user()->entite)
                            ->orderBy('sites_client.ScNomSite','asc')
                            ->get();

                        $str.='
                            <div class="form-group">
                                <label for="SsSiteClient">Sites supervisés <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e7 form-control" id="SoSite" name="SoSite" required>
                                    <option selected value="">Selectionner...</option>';
                                    foreach($sites_supervises as $site_supervises):
                                        $str.='<option value="'.$site_supervises->ScNumero.'">'.$site_supervises->ScNomSite.'</option>';
                                    endforeach;
                                $str.='</select>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>';

                    } else {

                        $sites_supervises = DB::table('sites_client')
                            ->where('sites_client.ScVille',$supervision_site->ViNumero)
                            ->where('sites_client.ScEntite',Auth::user()->entite)
                            ->orderBy('sites_client.ScNomSite','asc')
                            ->get();

                        $str.='
                            <div class="form-group">
                                <label for="SsSiteClient">Sites supervisés <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e7 form-control" id="SoSite" name="SoSite" required>
                                    <option selected value="">Selectionner...</option>';
                                    foreach($sites_supervises as $site_supervises):
                                        $str.='<option value="'.$site_supervises->ScNumero.'">'.$site_supervises->ScNomSite.'</option>';
                                    endforeach;
                                $str.='</select>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>';
                    }

                } else {

                    $sites_supervises = DB::table('sites_superviseur')
                        ->leftJoin('sites_client','sites_client.ScNumero','=','sites_superviseur.SsSiteClient')
                        ->where('sites_superviseur.SsSuperv',$supervision_site-> OsSuperviseur)
                        ->where('sites_superviseur.SsIsOn','Y')
                        ->where('sites_superviseur.SsEntite',Auth::user()->entite)
                        ->orderBy('sites_client.ScNomSite','asc')
                        ->get();

                    $str.='
                        <div class="form-group">
                            <label for="SsSiteClient">Sites supervisés <abbr style="color: #bd4147;">(*)</abbr></label>
                            <select class="e7 form-control" id="SoSite" name="SoSite" required>
                                <option selected value="">Selectionner...</option>';
                                foreach($sites_supervises as $site_supervises):
                                    $str.='<option value="'.$site_supervises->ScNumero.'">'.$site_supervises->ScNomSite.'</option>';
                                endforeach;
                            $str.='</select>
                            <div class="invalid-feedback">
                                Ce champ est obligatoire.
                            </div>
                        </div>';
                }
            } else {
                $str.='
                <div class="form-group">
                    <label for="SsSiteClient">Sites supervisés <abbr style="color: #bd4147;">(*)</abbr></label>
                    <select class="e7 form-control" id="SoSite" name="SoSite" required>
                        <option selected value="">Selectionner...</option>
                    </select>
                    <div class="alert alert-danger mt-1">
                        <i class="fa fa-exclamation-triangle"></i> Veuillez renseigner dans un premier temps la ville de ce personnel
                    </div>
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

        } else {

            $supervision_site = DB::table('operations_superv')
                ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
                ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
                ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
                ->where('operations_superv.OsNumero',$OsNumero)
                ->first();

            $str ='';

            $site_supervise = DB::table('sites_superviseur')
                ->leftJoin('sites_client','sites_client.ScNumero','=','sites_superviseur.SsSiteClient')
                ->where('sites_superviseur.SsSuperv',$supervision_site-> OsSuperviseur)
                ->where('sites_superviseur.SsIsOn','Y')
                ->where('sites_superviseur.SsEntite',Auth::user()->entite)
                ->orderBy('sites_client.ScNomSite','asc')
                ->first();

                $str.='
                    <div class="form-group">
                        <label for="SsSiteClient">Site supervisé <abbr style="color: #bd4147;">(*)</abbr></label>
                        <input type="text" class="form-control" value="'.$site_supervise->ScNomSite.'" readonly/>
                        <input type="hidden" class="form-control" id="SoSite" name="SoSite" value="'.$site_supervise->ScNumero.'" readonly/>
                    </div>';

            $str.='';

            echo $str;
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show_sites_zones_edit($OsNumero)
    {
        if(Auth::user()->type == 'T') {

            $supervision_site = DB::table('operations_superv')
                ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
                ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
                ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
                ->where('operations_superv.OsNumero',$OsNumero)
                ->first();

            $str ='';

            if($supervision_site->ViNumero){

                if($supervision_site->ViIsAllSiteOn == 'Y') {

                    if($supervision_site->ViIsZoneOn == 'Y') {

                        $sites_supervises = DB::table('sites_client')
                            ->where('sites_client.ScZone',$supervision_site->ZoNumero)
                            ->where('sites_client.ScEntite',Auth::user()->entite)
                            ->orderBy('sites_client.ScNomSite','asc')
                            ->get();

                        $str.='
                            <div class="form-group">
                                <label for="SsSiteClient">Sites supervisés <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e7 form-control" id="SoSite" name="SoSite" required>
                                    <option selected value="">Selectionner...</option>';
                                    foreach($sites_supervises as $site_supervises):
                                        $str.='<option value="'.$site_supervises->ScNumero.'">'.$site_supervises->ScNomSite.'</option>';
                                    endforeach;
                                $str.='</select>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>';

                    } else {

                        $sites_supervises = DB::table('sites_client')
                            ->where('sites_client.ScVille',$supervision_site->ViNumero)
                            ->where('sites_client.ScEntite',Auth::user()->entite)
                            ->orderBy('sites_client.ScNomSite','asc')
                            ->get();

                        $str.='
                            <div class="form-group">
                                <label for="SsSiteClient">Sites supervisés <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e7 form-control" id="SoSite" name="SoSite" required>
                                    <option selected value="">Selectionner...</option>';
                                    foreach($sites_supervises as $site_supervises):
                                        $str.='<option value="'.$site_supervises->ScNumero.'">'.$site_supervises->ScNomSite.'</option>';
                                    endforeach;
                                $str.='</select>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>';
                    }

                } else {

                    $sites_supervises = DB::table('sites_superviseur')
                        ->leftJoin('sites_client','sites_client.ScNumero','=','sites_superviseur.SsSiteClient')
                        ->where('sites_superviseur.SsSuperv',$supervision_site-> OsSuperviseur)
                        ->where('sites_superviseur.SsIsOn','Y')
                        ->where('sites_superviseur.SsEntite',Auth::user()->entite)
                        ->orderBy('sites_client.ScNomSite','asc')
                        ->get();

                    $str.='
                        <div class="form-group">
                            <label for="SsSiteClient">Sites supervisés <abbr style="color: #bd4147;">(*)</abbr></label>
                            <select class="e7 form-control" id="SoSite" name="SoSite" required>
                                <option selected value="">Selectionner...</option>';
                                foreach($sites_supervises as $site_supervises):
                                    $str.='<option value="'.$site_supervises->ScNumero.'">'.$site_supervises->ScNomSite.'</option>';
                                endforeach;
                            $str.='</select>
                            <div class="invalid-feedback">
                                Ce champ est obligatoire.
                            </div>
                        </div>';
                }
            } else {
                $str.='
                <div class="form-group">
                    <label for="SsSiteClient">Sites supervisés <abbr style="color: #bd4147;">(*)</abbr></label>
                    <select class="e7 form-control" id="SoSite" name="SoSite" required>
                        <option selected value="">Selectionner...</option>
                    </select>
                    <div class="alert alert-danger mt-1">
                        <i class="fa fa-exclamation-triangle"></i> Veuillez renseigner dans un premier temps la ville de ce personnel
                    </div>
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

        } else {

            $supervision_site = DB::table('operations_superv')
                ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
                ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
                ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
                ->where('operations_superv.OsNumero',$OsNumero)
                ->first();

            $str ='';

            $site_supervise = DB::table('sites_superviseur')
                ->leftJoin('sites_client','sites_client.ScNumero','=','sites_superviseur.SsSiteClient')
                ->where('sites_superviseur.SsSuperv',$supervision_site-> OsSuperviseur)
                ->where('sites_superviseur.SsIsOn','Y')
                ->where('sites_superviseur.SsEntite',Auth::user()->entite)
                ->orderBy('sites_client.ScNomSite','asc')
                ->first();

                $str.='
                    <div class="form-group">
                        <label for="SsSiteClient">Site supervisé <abbr style="color: #bd4147;">(*)</abbr></label>
                        <input type="text" class="form-control" value="'.$site_supervise->ScNomSite.'" readonly/>
                        <input type="hidden" class="form-control" id="SoSite" name="SoSite" value="'.$site_supervise->ScNumero.'" readonly/>
                    </div>';

            $str.='';

            echo $str;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show_agents_programmes($OsNumero,$SoSite)
    {
        $supervision_site = DB::table('operations_superv')
            ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
            ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
            ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
            ->where('operations_superv.OsNumero',$OsNumero)
            ->first();

        $OsDate = $supervision_site->OsDate;
        $OsFaction = $supervision_site->OsFaction;

        $agents_programmes = DB::table('poster_vigile')
            ->leftJoin('personnels', 'personnels.PeNumero', '=', 'poster_vigile.PvVigile')
            ->where('poster_vigile.PvSiteClient',$SoSite)
            ->whereIn('personnels.PeStatut', ['F','R','B','SG','SA'])
            ->whereNotIn('personnels.PeNumero', function($query) use ($OsDate,$OsFaction) {
                $query->select('sites_oper_superv.SoAgentPgme')
                    ->from('sites_oper_superv')
                    ->leftJoin('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                    ->where('operations_superv.OsDate',$OsDate)
                    ->where('operations_superv.OsFaction',$OsFaction);})
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->where('personnels.PeIsOn','Y')
            ->orderBy('personnels.PeMatric','asc')
            ->get();

        $str ='';

        $str.='
            <div class="form-group">
                <label for="SoAgentPgme">Agents programmés <abbr style="color: #bd4147;">(*)</abbr></label>
                <select class="e8 form-control" id="SoAgentPgme" name="SoAgentPgme" required>
                    <option selected value="">Selectionner...</option>';
                    foreach($agents_programmes as $agent_programme):
                        $str.='<option value="'.$agent_programme->PeNumero.'">'.$agent_programme->PeMatric.' '.$agent_programme->PeNom.' '.$agent_programme->PePrenom.'</option>';
                    endforeach;
                $str.='</select>
                <div class="invalid-feedback">
                    Ce champ est obligatoire.
                </div>
            </div>';

        /*$str.='
        <script>
            $(document).ready(function() {
                $(".e8").select2({
                    width: "100%"
                });
            });
        </script>';*/

        $str.='';

        echo $str;
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show_agents_programmes_edit($OsNumero,$SoSite)
    {
        $supervision_site = DB::table('operations_superv')
            ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
            ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
            ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
            ->where('operations_superv.OsNumero',$OsNumero)
            ->first();

        $OsDate = $supervision_site->OsDate;
        $OsFaction = $supervision_site->OsFaction;

        $agents_programmes = DB::table('poster_vigile')
            ->leftJoin('personnels', 'personnels.PeNumero', '=', 'poster_vigile.PvVigile')
            ->where('poster_vigile.PvSiteClient',$SoSite)
            ->whereIn('personnels.PeStatut', ['F','R','B','SG','SA'])
            ->whereNotIn('personnels.PeNumero', function($query) use ($OsDate,$OsFaction) {
                $query->select('sites_oper_superv.SoAgentPgme')
                    ->from('sites_oper_superv')
                    ->leftJoin('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                    ->where('operations_superv.OsDate',$OsDate)
                    ->where('operations_superv.OsFaction',$OsFaction);})
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->where('personnels.PeIsOn','Y')
            ->orderBy('personnels.PeMatric','asc')
            ->get();

        $str ='';

        $str.='
            <div class="form-group">
                <label for="SoAgentPgme">Agents programmés <abbr style="color: #bd4147;">(*)</abbr></label>
                <select class="e8 form-control" id="SoAgentPgme" name="SoAgentPgme" required>
                    <option selected value="">Selectionner...</option>';
                    foreach($agents_programmes as $agent_programme):
                        $str.='<option value="'.$agent_programme->PeNumero.'">'.$agent_programme->PeMatric.' '.$agent_programme->PeNom.' '.$agent_programme->PePrenom.'</option>';
                    endforeach;
                $str.='</select>
                <div class="invalid-feedback">
                    Ce champ est obligatoire.
                </div>
            </div>';

        /*$str.='
        <script>
            $(document).ready(function() {
                $(".e8").select2({
                    width: "100%"
                });
            });
        </script>';*/

        $str.='';

        echo $str;
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show_agents_trouves1($SoAgentPgme)
    {
        $agent_trouve = DB::table('personnels')
            ->where('personnels.PeNumero',$SoAgentPgme)
            ->where('personnels.PeIsOn','Y')
            ->first();

        $str ='';

        $str.='
            <div class="form-group">
                <label for="SoAgentOper">Agent trouvé</label>
                <input type="hidden" class="form-control" id="SoAgentOper" name="SoAgentOper" value="'.$agent_trouve->PeNumero.'"/>
                <input type="text" class="form-control" value="'.$agent_trouve->PeNom.' '.$agent_trouve->PePrenom.'" readonly/>
            </div>';

        $str.='';

        echo $str;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show_agents_trouves1_edit($SoAgentPgme)
    {
        $agent_trouve = DB::table('personnels')
            ->where('personnels.PeNumero',$SoAgentPgme)
            ->where('personnels.PeIsOn','Y')
            ->first();

        $str ='';

        $str.='
            <div class="form-group">
                <label for="SoAgentOper">Agent trouvé</label>
                <input type="hidden" class="form-control" id="SoAgentOper" name="SoAgentOper" value="'.$agent_trouve->PeNumero.'"/>
                <input type="text" class="form-control" value="'.$agent_trouve->PeNom.' '.$agent_trouve->PePrenom.'" readonly/>
            </div>';

        $str.='';

        echo $str;
    }


     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show_agents_trouves2($OsNumero,$SoSite)
    {

        $supervision_site = DB::table('operations_superv')
            ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
            ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
            ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
            ->where('operations_superv.OsNumero',$OsNumero)
            ->first();

        $OsDate = $supervision_site->OsDate;
        $OsFaction = $supervision_site->OsFaction;

        $agents_trouves = DB::table('poster_vigile')
            ->leftJoin('personnels', 'personnels.PeNumero', '=', 'poster_vigile.PvVigile')
            ->where('personnels.PeVille',$supervision_site->PeVille)
            ->where('poster_vigile.PvSiteClient','!=',$SoSite)
            ->whereIn('personnels.PeStatut', ['F','R','B','SG','SA'])
            ->whereNotIn('personnels.PeNumero', function($query) use ($OsDate,$OsFaction) {
                $query->select('sites_oper_superv.SoAgentPgme')
                    ->from('sites_oper_superv')
                    ->leftJoin('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                    ->where('operations_superv.OsDate',$OsDate)
                    ->where('operations_superv.OsFaction',$OsFaction);})
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->where('personnels.PeIsOn','Y')
            ->orderBy('personnels.PeMatric','asc')
            ->get();

        $str ='';

        $str.='
            <div class="form-group">
                <label for="SoAgentPgme">Agents trouvés</label>
                <select class="e9 form-control" id="SoAgentOper" name="SoAgentOper">
                    <option selected value="">Selectionner...</option>';
                    foreach($agents_trouves as $agent_trouve):
                        $str.='<option value="'.$agent_trouve->PeNumero.'">'.$agent_trouve->PeMatric.' '.$agent_trouve->PeNom.' '.$agent_trouve->PePrenom.'</option>';
                    endforeach;
                $str.='</select>
                <div class="invalid-feedback">
                    Ce champ est obligatoire.
                </div>
            </div>';

        /*$str.='
        <script>
            $(document).ready(function() {
                $(".e9").select2({
                    width: "100%"
                });
            });
        </script>';*/

        $str.='';

        echo $str;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show_agents_trouves2_edit($OsNumero,$SoSite)
    {

        $supervision_site = DB::table('operations_superv')
            ->leftJoin('personnels','personnels.PeNumero','=','operations_superv.OsSuperviseur')
            ->leftJoin('villes','villes.ViNumero','=','operations_superv.OsVille')
            ->leftJoin('zones','zones.ZoNumero','=','operations_superv.OsZone')
            ->where('operations_superv.OsNumero',$OsNumero)
            ->first();

        $OsDate = $supervision_site->OsDate;
        $OsFaction = $supervision_site->OsFaction;

        $agents_trouves = DB::table('poster_vigile')
            ->leftJoin('personnels', 'personnels.PeNumero', '=', 'poster_vigile.PvVigile')
            ->where('personnels.PeVille',$supervision_site->PeVille)
            ->where('poster_vigile.PvSiteClient','!=',$SoSite)
            ->whereIn('personnels.PeStatut', ['F','R','B','SG','SA'])
            ->whereNotIn('personnels.PeNumero', function($query) use ($OsDate,$OsFaction) {
                $query->select('sites_oper_superv.SoAgentPgme')
                    ->from('sites_oper_superv')
                    ->leftJoin('operations_superv', 'operations_superv.OsNumero', '=', 'sites_oper_superv.SoOperSuperv')
                    ->where('operations_superv.OsDate',$OsDate)
                    ->where('operations_superv.OsFaction',$OsFaction);})
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->where('personnels.PeIsOn','Y')
            ->orderBy('personnels.PeMatric','asc')
            ->get();

        $str ='';

        $str.='
            <div class="form-group">
                <label for="SoAgentPgme">Agents trouvés</label>
                <select class="e9 form-control" id="SoAgentOper" name="SoAgentOper">
                    <option selected value="">Selectionner...</option>';
                    foreach($agents_trouves as $agent_trouve):
                        $str.='<option value="'.$agent_trouve->PeNumero.'">'.$agent_trouve->PeMatric.' '.$agent_trouve->PeNom.' '.$agent_trouve->PePrenom.'</option>';
                    endforeach;
                $str.='</select>
                <div class="invalid-feedback">
                    Ce champ est obligatoire.
                </div>
            </div>';

        /*$str.='
        <script>
            $(document).ready(function() {
                $(".e9").select2({
                    width: "100%"
                });
            });
        </script>';*/

        $str.='';

        echo $str;
    }

    public function show_agent_trouve($SoAgentOper)
    {
        $agent_trouve = DB::table('personnels')
            ->where('personnels.PeNumero',$SoAgentOper)
            ->where('personnels.PeIsOn','Y')
            ->first();

       $str ='';

       if($agent_trouve) {
            $str.='
                <div class="form-group">
                    <label for="SoNomPrenAgent">Noms et Prénoms agent</label>
                    <input type="text" class="form-control" id="SoNomPrenAgent" name="SoNomPrenAgent" value="'.$agent_trouve->PeNom.' '.$agent_trouve->PePrenom.'" placeholder="Noms et Prénoms agent" readonly/>
                    <div class="invalid-feedback">
                        Ce champ est obligatoire.
                    </div>
                </div>';
       } else {
            $str.='
                <div class="form-group">
                    <label for="SoNomPrenAgent">Noms et Prénoms agent</label>
                    <input type="text" class="form-control" id="SoNomPrenAgent" name="SoNomPrenAgent" value="" placeholder="Noms et Prénoms agent"/>
                    <div class="invalid-feedback">
                        Ce champ est obligatoire.
                    </div>
                </div>';
       }

        $str.='';

        echo $str;
    }

}
