<?php

namespace App\Http\Controllers;

use App\Models\PosterVigile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;


class PosterVigileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-d');

        $poster_vigiles = DB::table('poster_vigile')
            ->leftJoin('personnels','personnels.PeNumero','=','poster_vigile.PvVigile')
            ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
            ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->whereDate('poster_vigile.PvDateDebut','>=',$start_date)
            ->whereDate('poster_vigile.PvDateDebut','<=',$end_date)
            ->where('poster_vigile.PvEntite',Auth::user()->entite)
            ->orderBy('poster_vigile.PvDateDebut','asc')
            ->get();

        $personnels = DB::table('personnels')
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->where('personnels.PeIsOn','Y')
            ->where('personnels.PeStatut','!=','PS')
            ->orderBy('personnels.PeMatric','asc')
            ->get();

        $sites_client = DB::table('sites_client')
            ->where('sites_client.ScIsOn','Y')
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->orderBy('sites_client.ScNomSite','asc')
            ->get();

        $personnels_1 = DB::table('personnels')
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->where('personnels.PeIsOn','Y')
            ->where('personnels.PeStatut','!=','PS')
            ->orderBy('personnels.PeMatric','asc')
            ->get();

        $clients = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->orderBy('clients.ClDesign','asc')
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

        return view('poster-vigile.list',compact(
            'poster_vigiles','personnels','sites_client',
            'personnels_1','clients','villes','zones'
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
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            $poster_vigile = new PosterVigile();
            $poster_vigile->PvEntite = Auth::user()->entite;
            $poster_vigile->PvSiteClient = $request->PvSiteClient;
            $poster_vigile->PvVigile = $request->PvVigile;
            $poster_vigile->PvStatut = $request->PvStatut;
            $poster_vigile->PvDateDebut = $request->PvDateDebut;
            $poster_vigile->PvFaction = $request->PvFaction ?? 'J';
            $poster_vigile->PvDateFin = NULL;
            $poster_vigile->PvDetails = $request->PvDetails;
            $poster_vigile->PvIsOn = $request->PvIsOn;
            $poster_vigile->PvUserCree = Auth::user()->id;
            $poster_vigile->save();

            $poster_vigile_1 =DB::table('poster_vigile')
                ->where('poster_vigile.PvVigile',$poster_vigile->PvVigile)
                ->orderBy('poster_vigile.PvNumero','desc')
                ->first();

            if($poster_vigile_1) {
                DB::table('personnels')
                    ->where('personnels.PeNumero',$poster_vigile_1->PvVigile)
                    ->update([
                        'personnels.PeSitePoste' => $poster_vigile_1->PvSiteClient
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

        return redirect()->route('poster-vigiles.index')->with('alert', 'Poste vigile crée avec succès.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $PvNumero)
    {
        return DB::table('poster_vigile')
            ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
            ->leftJoin('personnels','personnels.PeNumero','=','poster_vigile.PvVigile')
            ->where('poster_vigile.PvNumero',$PvNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $PvNumero)
    {
        $poster_vigile =DB::table('poster_vigile')
            ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
            ->leftJoin('personnels','personnels.PeNumero','=','poster_vigile.PvVigile')
            ->where('poster_vigile.PvNumero',$PvNumero)
            ->first();

        return response()->json($poster_vigile);
    }


    /**
     * Show the form for redeployer_edit the specified resource.
     */
    public function redeployer_edit(string $PvNumero)
    {
        $poster_vigile =DB::table('poster_vigile')
            ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
            ->leftJoin('personnels','personnels.PeNumero','=','poster_vigile.PvVigile')
            ->where('poster_vigile.PvNumero',$PvNumero)
            ->first();

        return response()->json($poster_vigile);
    }


    /**
     * Show the form for fin_deploiement_edit the specified resource.
     */
    public function fin_deploiement_edit(string $PvNumero)
    {
        $poster_vigile =DB::table('poster_vigile')
            ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
            ->leftJoin('personnels','personnels.PeNumero','=','poster_vigile.PvVigile')
            ->where('poster_vigile.PvNumero',$PvNumero)
            ->first();

        return response()->json($poster_vigile);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $PvNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('poster_vigile')
            ->where('poster_vigile.PvNumero',$PvNumero)
            ->update([
                //'PvSiteClient' => $request->PvSiteClient,
                //'PvVigile' => $request->PvVigile,
                //'PvDateDebut' => $request->PvDateDebut,
                'PvFaction' => $request->PvFaction,
                'PvStatut' => $request->PvStatut,
                'PvIsOn' => $request->PvIsOn,
                'PvDetails' => $request->PvDetails
            ]);

            $poster_vigile_1 =DB::table('poster_vigile')
                ->where('poster_vigile.PvNumero',$PvNumero)
                ->orderBy('poster_vigile.PvNumero','desc')
                ->first();

            if($poster_vigile_1) {
                DB::table('personnels')
                    ->where('personnels.PeNumero',$poster_vigile_1->PvVigile)
                    ->update([
                        'personnels.PeSitePoste' => $poster_vigile_1->PvSiteClient
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

        return redirect()->route('poster-vigiles.index')->with('alert', 'Poste vigile modifié avec succès.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function redeployer_update(Request $request, string $PvNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('poster_vigile')
                ->where('poster_vigile.PvNumero',$PvNumero)
                ->update([
                    'PvDateFin' => $request->PvDateFin,
                    'PvDateOff' => date('Y-m-d H:i:s'),
                    'PvIsOn' => 'N',
                    'PvUserOff' => Auth::user()->id
                ]);

            $poster_vigile = new PosterVigile();
            $poster_vigile->PvEntite = Auth::user()->entite;
            $poster_vigile->PvSiteClient = $request->PvSiteClient;
            $poster_vigile->PvVigile = $request->PvVigile;
            $poster_vigile->PvStatut = $request->PvStatut;
            $poster_vigile->PvDateDebut = $request->PvDateDebut;
            $poster_vigile->PvDateFin = NULL;
            $poster_vigile->PvFaction = $request->PvFaction;
            $poster_vigile->PvDetails = $request->PvDetails;
            $poster_vigile->PvIsOn = 'Y';
            $poster_vigile->PvUserCree = Auth::user()->id;
            $poster_vigile->save();

            $poster_vigile_1 =DB::table('poster_vigile')
                ->where('poster_vigile.PvVigile',$poster_vigile->PvVigile)
                ->orderBy('poster_vigile.PvNumero','desc')
                ->first();

            if($poster_vigile_1) {
                DB::table('personnels')
                    ->where('personnels.PeNumero',$poster_vigile_1->PvVigile)
                    ->update([
                        'personnels.PeSitePoste' => $poster_vigile_1->PvSiteClient
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

        return redirect()->route('poster-vigiles.index')->with('alert', 'Poste vigile redéployer avec succès.');
    }


    /**
     * Update the specified resource in storage.
     */
    public function fin_deploiement_update(Request $request, string $PvNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('poster_vigile')
                ->where('poster_vigile.PvNumero',$PvNumero)
                ->update([
                    'PvDateFin' => $request->PvDateFin,
                    'PvDateOff' => date('Y-m-d H:i:s'),
                    'PvIsOn' => 'N',
                    'PvUserOff' => Auth::user()->id
                ]);

            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('poster-vigiles.index')->with('alert', 'Fin de dépoiement poste vigile avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function activer($PvNumero)
    {
        DB::table('poster_vigile')
            ->where('poster_vigile.PvNumero',$PvNumero)
            ->update([
                'poster_vigile.PvIsOn' => 'Y',
                'poster_vigile.PvDateFin' => NULL,
                'poster_vigile.PvDateOff' => NULL,
                'poster_vigile.PvUserOff' => NULL
            ]);
    }

    public function desactiver($PvNumero)
    {
        DB::table('poster_vigile')
            ->where('poster_vigile.PvNumero',$PvNumero)
            ->update([
                'poster_vigile.PvIsOn' => 'N',
                'poster_vigile.PvDateFin' => date('Y-m-d H:i:s'),
                'poster_vigile.PvDateOff' => date('Y-m-d H:i:s'),
                'poster_vigile.PvUserOff' => Auth::user()->id
            ]);
    }

    public function generatePDF(Request $request)
    {
        if($request->PvVigile == 'all' && $request->PvStatut == 'all' && $request->ville_posting == 'all' && $request->zone_posting == 'all' && $request->client_posting == 'all' && $request->site_posting == 'all' && $request->faction_site == 'all')
        {
            $poster_vigiles = DB::table('poster_vigile')
                ->leftJoin('personnels','personnels.PeNumero','=','poster_vigile.PvVigile')
                ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
                ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
                ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
                ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
                ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
                ->where('poster_vigile.PvEntite',Auth::user()->entite)
                ->whereDate('poster_vigile.PvDateDebut','>=',$request->start_date)
                ->whereDate('poster_vigile.PvDateDebut','<=',$request->end_date)
                ->orderBy('poster_vigile.PvDateDebut','asc')
                ->paginate(50);
        }
        else
        {
            // Handle other cases where not all parameters are 'all'
            $query = DB::table('poster_vigile')
            ->leftJoin('personnels','personnels.PeNumero','=','poster_vigile.PvVigile')
                ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
                ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
                ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
                ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
                ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone');

            // Apply filters based on the request parameters
            if($request->PvVigile != 'all') {
                $query->where('poster_vigile.PvVigile',$request->PvVigile)
                    ->where('poster_vigile.PvEntite',Auth::user()->entite)
                    ->whereDate('poster_vigile.PvDateDebut','>=',$request->start_date)
                    ->whereDate('poster_vigile.PvDateDebut','<=',$request->end_date);
            }

            // Apply filters based on the request parameters
            if($request->PvStatut != 'all') {
                $query->where('poster_vigile.PvStatut',$request->PvStatut)
                    ->where('poster_vigile.PvEntite',Auth::user()->entite)
                    ->whereDate('poster_vigile.PvDateDebut','>=',$request->start_date)
                    ->whereDate('poster_vigile.PvDateDebut','<=',$request->end_date);
            }


            // Apply filters based on the request parameters
            if($request->ville_posting != 'all') {
                $query->where('personnels.PeVille',$request->ville_posting)
                    ->where('poster_vigile.PvEntite',Auth::user()->entite)
                    ->whereDate('poster_vigile.PvDateDebut','>=',$request->start_date)
                    ->whereDate('poster_vigile.PvDateDebut','<=',$request->end_date);
            }

            // Apply filters based on the request parameters
            if($request->zone_posting != 'all') {
                $query->where('zones.ZoNumero',$request->zone_posting)
                    ->where('poster_vigile.PvEntite',Auth::user()->entite)
                    ->whereDate('poster_vigile.PvDateDebut','>=',$request->start_date)
                    ->whereDate('poster_vigile.PvDateDebut','<=',$request->end_date);
            }

            // Apply filters based on the request parameters
            if($request->client_posting != 'all') {
                $query->where('clients.ClNumero',$request->client_posting)
                    ->where('poster_vigile.PvEntite',Auth::user()->entite)
                    ->whereDate('poster_vigile.PvDateDebut','>=',$request->start_date)
                    ->whereDate('poster_vigile.PvDateDebut','<=',$request->end_date);
            }

            // Apply filters based on the request parameters
            if($request->site_posting != 'all') {
                $query->where('poster_vigile.PvSiteClient',$request->site_posting)
                    ->where('poster_vigile.PvEntite',Auth::user()->entite)
                    ->whereDate('poster_vigile.PvDateDebut','>=',$request->start_date)
                    ->whereDate('poster_vigile.PvDateDebut','<=',$request->end_date);
            }

            // Apply filters based on the request parameters
            if($request->faction_site != 'all') {
                $query->where('poster_vigile.PvFaction',$request->faction_site)
                    ->where('poster_vigile.PvEntite',Auth::user()->entite)
                    ->whereDate('poster_vigile.PvDateDebut','>=',$request->start_date)
                    ->whereDate('poster_vigile.PvDateDebut','<=',$request->end_date);
            }

            // Execute the query with the applied filters
            $poster_vigiles = $query->orderBy('poster_vigile.PvDateDebut','asc')->paginate(50);
        }


        $start_date =  $request->start_date;
        $end_date =  $request->end_date;
        $PvVigile = $request->PvVigile;
        $PvStatut = $request->PvStatut;
        $ville_posting = $request->ville_posting;
        $zone_posting = $request->zone_posting;
        $client_posting = $request->client_posting;
        $site_posting = $request->site_posting;
        $faction_site = $request->faction_site;

        $entite = DB::table('entites')
            ->where('entites.EnNumero',Auth::user()->entite)
            ->first();

        $pdf = PDF::loadView('poster-vigile.pdf', compact(
            'poster_vigiles','PvVigile','PvStatut','start_date','end_date',
            'ville_posting','zone_posting','client_posting',
            'site_posting','faction_site','entite'
        ));

        return  $pdf->stream("Liste-Postings.pdf", array("Attachment" => false));
        exit(0);
    }


    public function filtre(Request $request)
    {
        if($request->PvVigile == 'all' && $request->PvStatut == 'all' && $request->ville_posting == 'all' && $request->zone_posting == 'all' && $request->client_posting == 'all' && $request->site_posting == 'all' && $request->faction_site == 'all')
        {
            $poster_vigiles = DB::table('poster_vigile')
                ->leftJoin('personnels','personnels.PeNumero','=','poster_vigile.PvVigile')
                ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
                ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
                ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
                ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
                ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
                ->where('poster_vigile.PvEntite',Auth::user()->entite)
                ->whereDate('poster_vigile.PvDateDebut','>=',$request->start_date)
                ->whereDate('poster_vigile.PvDateDebut','<=',$request->end_date)
                ->orderBy('poster_vigile.PvDateDebut','asc')
                ->get();
        }
        else
        {
            // Handle other cases where not all parameters are 'all'
            $query = DB::table('poster_vigile')
                ->leftJoin('personnels','personnels.PeNumero','=','poster_vigile.PvVigile')
                ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
                ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
                ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
                ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
                ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone');

            // Apply filters based on the request parameters
            if($request->PvVigile != 'all') {
                $query->where('poster_vigile.PvVigile',$request->PvVigile)
                    ->where('poster_vigile.PvEntite',Auth::user()->entite)
                    ->whereDate('poster_vigile.PvDateDebut','>=',$request->start_date)
                    ->whereDate('poster_vigile.PvDateDebut','<=',$request->end_date);
            }

            // Apply filters based on the request parameters
            if($request->PvStatut != 'all') {
                $query->where('poster_vigile.PvStatut',$request->PvStatut)
                    ->where('poster_vigile.PvEntite',Auth::user()->entite)
                    ->whereDate('poster_vigile.PvDateDebut','>=',$request->start_date)
                    ->whereDate('poster_vigile.PvDateDebut','<=',$request->end_date);
            }


            // Apply filters based on the request parameters
            if($request->ville_posting != 'all') {
                $query->where('personnels.PeVille',$request->ville_posting)
                    ->where('poster_vigile.PvEntite',Auth::user()->entite)
                    ->whereDate('poster_vigile.PvDateDebut','>=',$request->start_date)
                    ->whereDate('poster_vigile.PvDateDebut','<=',$request->end_date);
            }

            // Apply filters based on the request parameters
            if($request->zone_posting != 'all') {
                $query->where('zones.ZoNumero',$request->zone_posting)
                    ->where('poster_vigile.PvEntite',Auth::user()->entite)
                    ->whereDate('poster_vigile.PvDateDebut','>=',$request->start_date)
                    ->whereDate('poster_vigile.PvDateDebut','<=',$request->end_date);
            }

            // Apply filters based on the request parameters
            if($request->client_posting != 'all') {
                $query->where('clients.ClNumero',$request->client_posting)
                    ->where('poster_vigile.PvEntite',Auth::user()->entite)
                    ->whereDate('poster_vigile.PvDateDebut','>=',$request->start_date)
                    ->whereDate('poster_vigile.PvDateDebut','<=',$request->end_date);
            }

            // Apply filters based on the request parameters
            if($request->site_posting != 'all') {
                $query->where('poster_vigile.PvSiteClient',$request->site_posting)
                    ->where('poster_vigile.PvEntite',Auth::user()->entite)
                    ->whereDate('poster_vigile.PvDateDebut','>=',$request->start_date)
                    ->whereDate('poster_vigile.PvDateDebut','<=',$request->end_date);
            }

             // Apply filters based on the request parameters
            if($request->faction_site != 'all') {
                $query->where('poster_vigile.PvFaction',$request->faction_site)
                    ->where('poster_vigile.PvEntite',Auth::user()->entite)
                    ->whereDate('poster_vigile.PvDateDebut','>=',$request->start_date)
                    ->whereDate('poster_vigile.PvDateDebut','<=',$request->end_date);
            }

            // Execute the query with the applied filters
            $poster_vigiles = $query->orderBy('poster_vigile.PvDateDebut','asc')->get();
        }


        $start_date =  $request->start_date;
        $end_date =  $request->end_date;
        $PvVigile = $request->PvVigile;
        $PvStatut = $request->PvStatut;
        $ville_posting = $request->ville_posting;
        $zone_posting = $request->zone_posting;
        $client_posting = $request->client_posting;
        $site_posting = $request->site_posting;
        $faction_site = $request->faction_site;

        $personnels = DB::table('personnels')
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->where('personnels.PeIsOn','Y')
            ->where('personnels.PeStatut','!=','PS')
            ->orderBy('personnels.PeMatric','asc')
            ->get();

        $personnels_1 = DB::table('personnels')
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->where('personnels.PeIsOn','Y')
            ->where('personnels.PeStatut','!=','PS')
            ->orderBy('personnels.PeMatric','asc')
            ->get();

        $clients = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->orderBy('clients.ClDesign','asc')
            ->get();

        $sites_client = DB::table('sites_client')
            ->where('sites_client.ScIsOn','Y')
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


        return view('poster-vigile.filtre',compact(
            'poster_vigiles','PvVigile','PvStatut','start_date','end_date',
            'sites_client','personnels','personnels_1',
            'clients','villes','zones','ville_posting','zone_posting',
            'client_posting','site_posting','faction_site'
        ));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_profil_vigile($PvVigile)
    {
        $personnel = DB::table('personnels')
            ->where('personnels.PeNumero',$PvVigile)
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->where('personnels.PeIsOn','Y')
            ->orderBy('personnels.PeMatric','asc')
            ->first();

        $str ='';

        $str.= '
        <div class="form-group">
            <label>Profil <abbr style="color: #bd4147;">(*)</abbr></label>
            <select class="e140 form-control" id="PvStatut" name="PvStatut" required>
                <option selected value="">Selectionner...</option>
                <option value="F"' .($personnel->PeStatut == 'F' ? ' selected="selected"' : ''). '>Agent Fixe</option>
                <option value="R"' .($personnel->PeStatut == 'R' ? ' selected="selected"' : ''). '>Rondier</option>
                <option value="B"' .($personnel->PeStatut == 'B' ? ' selected="selected"' : ''). '>Stand Bye</option>
                <option value="E"' .($personnel->PeStatut == 'E' ? ' selected="selected"' : ''). '>Chef d\'Equipe</option>
                <option value="E"' .($personnel->PeStatut == 'C' ? ' selected="selected"' : ''). '>Maître Chien</option>
                <option value="SG"' .($personnel->PeStatut == 'SG' ? ' selected="selected"' : ''). '>Superv. Général</option>
                <option value="SA"' .($personnel->PeStatut == 'SA' ? ' selected="selected"' : ''). '>Superv. Adjoint</option>
            </select>
            <div class="invalid-feedback">
                Ce champ est obligatoire.
            </div>
        </div>';

        $str.='
            <script>
                $(document).ready(function() {
                    $(".e140").select2({
                        width: "100%"
                    });
                });
            </script>
        ';

        $str.='';

        echo $str;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_profil_vigile_edit($PvVigile)
    {
        $personnel = DB::table('personnels')
            ->where('personnels.PeNumero',$PvVigile)
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->where('personnels.PeIsOn','Y')
            ->orderBy('personnels.PeMatric','asc')
            ->first();

        $str ='';

        $str.= '
        <div class="form-group">
            <label>Profil <abbr style="color: #bd4147;">(*)</abbr></label>
            <select class="e150 form-control PvStatut1" id="PvStatut" name="PvStatut" required>
                <option selected value="">Selectionner...</option>
                <option value="F"' .($personnel->PeStatut == 'F' ? ' selected="selected"' : ''). '>Agent Fixe</option>
                <option value="R"' .($personnel->PeStatut == 'R' ? ' selected="selected"' : ''). '>Rondier</option>
                <option value="B"' .($personnel->PeStatut == 'B' ? ' selected="selected"' : ''). '>Stand Bye</option>
                <option value="E"' .($personnel->PeStatut == 'E' ? ' selected="selected"' : ''). '>Chef d\'Equipe</option>
                <option value="E"' .($personnel->PeStatut == 'C' ? ' selected="selected"' : ''). '>Maître Chien</option>
                <option value="SG"' .($personnel->PeStatut == 'SG' ? ' selected="selected"' : ''). '>Superv. Général</option>
                <option value="SA"' .($personnel->PeStatut == 'SA' ? ' selected="selected"' : ''). '>Superv. Adjoint</option>
            </select>
            <div class="invalid-feedback">
                Ce champ est obligatoire.
            </div>
        </div>';

        $str.='
            <script>
                $(document).ready(function() {
                    $(".e150").select2({
                        width: "100%"
                    });
                });
            </script>
        ';

        $str.='';

        echo $str;
    }


    public function show_sites_vigile($PvVigile)
    {
        $personnel = DB::table('personnels')
            ->where('personnels.PeNumero',$PvVigile)
            ->where('personnels.PeIsOn','Y')
            ->first();

        $sites_client = DB::table('sites_client')
            ->where('sites_client.ScVille',$personnel->PeVille)
            ->where('sites_client.ScIsOn','Y')
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->orderBy('sites_client.ScNomSite','asc')
            ->get();

        $str ='';

        $str.='
        <div class="form-group">
            <label>Site client <abbr style="color: #bd4147;">(*)</abbr></label>
            <select class="e160 form-control" id="PvSiteClient" name="PvSiteClient" required>
                <option selected value="">Selectionner...</option>';
                foreach($sites_client as $site_client):
                    $str.='<option value="'.$site_client->ScNumero.'">'.$site_client->ScNomSite.'</option>';
                endforeach;
            $str.='</select>
            <div class="invalid-feedback">
                Ce champ est obligatoire.
            </div>
        </div>';

        $str.='
        <script>
            $(document).ready(function() {
                $(".e160").select2({
                    width: "100%"
                });
            });
        </script>';

        $str.='';

        echo $str;
    }

    public function show_sites_vigile_edit($PvVigile)
    {
        $personnel = DB::table('personnels')
            ->where('personnels.PeNumero',$PvVigile)
            ->where('personnels.PeIsOn','Y')
            ->first();

        $sites_client = DB::table('sites_client')
            ->where('sites_client.ScVille',$personnel->PeVille)
            ->where('sites_client.ScIsOn','Y')
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->orderBy('sites_client.ScNomSite','asc')
            ->get();

        $str ='';

        $str.='
        <div class="form-group">
            <label>Site client <abbr style="color: #bd4147;">(*)</abbr></label>
            <select class="e170 form-control PvSiteClient1" id="PvSiteClient" name="PvSiteClient" required>
                <option selected value="">Selectionner...</option>';
                foreach($sites_client as $site_client):
                    $str.='<option value="'.$site_client->ScNumero.'">'.$site_client->ScNomSite.'</option>';
                endforeach;
            $str.='</select>
            <div class="invalid-feedback">
                Ce champ est obligatoire.
            </div>
        </div>';

        $str.='
        <script>
            $(document).ready(function() {
                $(".e170").select2({
                    width: "100%"
                });
            });
        </script>';

        $str.='';

        echo $str;
    }


    public function show_nbre_vigile_mc_contrat($PvVigile,$PvSiteClient)
    {
        $personnel = DB::table('personnels')
            ->where('personnels.PeNumero',$PvVigile)
            ->first();

        $site_client = DB::table('sites_client')
            ->where('sites_client.ScNumero',$PvSiteClient)
            ->first();

        $last_contrat = DB::table('contrat_site')
            ->leftJoin('avenants_contrat','avenants_contrat.AcNumero','=','contrat_site.CsContratClient')
            ->leftJoin('sites_client','sites_client.ScNumero','=','contrat_site.CsSiteClient')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->where('avenants_contrat.AcClient',$site_client->ScClient)
            ->where('contrat_site.CsSiteClient',$PvSiteClient)
            ->where('contrat_site.CsEntite',Auth::user()->entite)
            ->orderByDesc('avenants_contrat.AcNumero')
            ->first();


        $str ='';

        if(!empty($last_contrat)) {
            if($personnel->PeStatut == 'C') {
                $str.='
                    <div class="col-xl-6 col-md-6">
                        <div class="form-group">
                            <label for="CsNbreMCJr">Nombre de maîtres chien par jour</label>
                            <input type="text" class="form-control" id="CsNbreMCJr" value="'.$last_contrat->CsNbreMCJr.'" readonly/>
                        </div>
                    </div>

                    <div class="col-xl-6 col-md-6">
                        <div class="form-group">
                            <label for="CsNbreMCNu">Nombre de maîtres chien par nuit</label>
                            <input type="text" class="form-control" id="CsNbreMCNu" value="'.$last_contrat->CsNbreMCNu.'" readonly/>
                        </div>
                    </div>';
            } else {
                $str.='
                    <div class="col-xl-6 col-md-6">
                        <div class="form-group">
                            <label for="CsNbreAgentJr">Nombre d\'agents jour</label>
                            <input type="text" class="form-control" id="CsNbreAgentJr" value="'.$last_contrat->CsNbreAgentJr.'" readonly/>
                        </div>
                    </div>

                    <div class="col-xl-6 col-md-6">
                        <div class="form-group">
                            <label for="CsNbreAgentNu">Nombre d\'agents nuit</label>
                            <input type="text" class="form-control" id="CsNbreAgentNu" value="'.$last_contrat->CsNbreAgentNu.'" readonly/>
                        </div>
                    </div>';
            }
        } else {
            if($personnel->PeStatut == 'C') {
                $str.='
                    <div class="col-xl-6 col-md-6">
                        <div class="form-group">
                            <label for="CsNbreMCJr">Nombre de maîtres chien par jour</label>
                            <input type="text" class="form-control" id="CsNbreMCJr" value="" readonly/>
                            <div style="color:red">
                                ⚠️ Aucun personnel affecté sur ce site !
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-md-6">
                        <div class="form-group">
                            <label for="CsNbreMCNu">Nombre de maîtres chien par nuit</label>
                            <input type="text" class="form-control" id="CsNbreMCNu" value="" readonly/>
                            <div style="color:red">
                                ⚠️ Aucun personnel affecté sur ce site !
                            </div>
                        </div>
                    </div>';
            } else {
                $str.='
                    <div class="col-xl-6 col-md-6">
                        <div class="form-group">
                            <label for="CsNbreAgentJr">Nombre d\'agents jour</label>
                            <input type="text" class="form-control" id="CsNbreAgentJr" value="" readonly/>
                            <div style="color:red">
                                ⚠️ Aucun personnel affecté sur ce site !
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-md-6">
                        <div class="form-group">
                            <label for="CsNbreAgentNu">Nombre d\'agents nuit</label>
                            <input type="text" class="form-control" id="CsNbreAgentNu" value="" readonly/>
                            <div style="color:red">
                                ⚠️ Aucun personnel affecté sur ce site !
                            </div>
                        </div>
                    </div>';
            }
        }

        $str.='
        <script>
            $(document).ready(function() {
                $(".e160").select2({
                    width: "100%"
                });
            });
        </script>';

        $str.='';

        echo $str;
    }


    public function show_nbre_vigile_mc_contrat_edit($PvVigile,$PvSiteClient)
    {
        $personnel = DB::table('personnels')
            ->where('personnels.PeNumero',$PvVigile)
            ->first();

        $site_client = DB::table('sites_client')
            ->where('sites_client.ScNumero',$PvSiteClient)
            ->first();

        $last_contrat = DB::table('contrat_site')
            ->leftJoin('avenants_contrat','avenants_contrat.AcNumero','=','contrat_site.CsContratClient')
            ->leftJoin('sites_client','sites_client.ScNumero','=','contrat_site.CsSiteClient')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->where('avenants_contrat.AcClient',$site_client->ScClient)
            ->where('contrat_site.CsSiteClient',$PvSiteClient)
            ->where('contrat_site.CsEntite',Auth::user()->entite)
            ->orderByDesc('avenants_contrat.AcNumero')
            ->first();


        $str ='';

        if(!empty($last_contrat)) {
            if($personnel->PeStatut == 'C') {
                $str.='
                    <div class="col-xl-6 col-md-6">
                        <div class="form-group">
                            <label for="CsNbreMCJr">Nombre de maîtres chien par jour</label>
                            <input type="text" class="form-control" id="CsNbreMCJr" value="'.$last_contrat->CsNbreMCJr.'" readonly/>
                        </div>
                    </div>

                    <div class="col-xl-6 col-md-6">
                        <div class="form-group">
                            <label for="CsNbreMCNu">Nombre de maîtres chien par nuit</label>
                            <input type="text" class="form-control" id="CsNbreMCNu" value="'.$last_contrat->CsNbreMCNu.'" readonly/>
                        </div>
                    </div>';
            } else {
                $str.='
                    <div class="col-xl-6 col-md-6">
                        <div class="form-group">
                            <label for="CsNbreAgentJr">Nombre d\'agents jour</label>
                            <input type="text" class="form-control" id="CsNbreAgentJr" value="'.$last_contrat->CsNbreAgentJr.'" readonly/>
                        </div>
                    </div>

                    <div class="col-xl-6 col-md-6">
                        <div class="form-group">
                            <label for="CsNbreAgentNu">Nombre d\'agents nuit</label>
                            <input type="text" class="form-control" id="CsNbreAgentNu" value="'.$last_contrat->CsNbreAgentNu.'" readonly/>
                        </div>
                    </div>';
            }
        } else {
            if($personnel->PeStatut == 'C') {
                $str.='
                    <div class="col-xl-6 col-md-6">
                        <div class="form-group">
                            <label for="CsNbreMCJr">Nombre de maîtres chien par jour</label>
                            <input type="text" class="form-control" id="CsNbreMCJr" value="" readonly/>
                            <div style="color:red">
                                ⚠️ Aucun personnel affecté sur ce site !
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-md-6">
                        <div class="form-group">
                            <label for="CsNbreMCNu">Nombre de maîtres chien par nuit</label>
                            <input type="text" class="form-control" id="CsNbreMCNu" value="" readonly/>
                            <div style="color:red">
                                ⚠️ Aucun personnel affecté sur ce site !
                            </div>
                        </div>
                    </div>';
            } else {
                $str.='
                    <div class="col-xl-6 col-md-6">
                        <div class="form-group">
                            <label for="CsNbreAgentJr">Nombre d\'agents jour</label>
                            <input type="text" class="form-control" id="CsNbreAgentJr" value="" readonly/>
                            <div style="color:red">
                                ⚠️ Aucun personnel affecté sur ce site !
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-md-6">
                        <div class="form-group">
                            <label for="CsNbreAgentNu">Nombre d\'agents nuit</label>
                            <input type="text" class="form-control" id="CsNbreAgentNu" value="" readonly/>
                            <div style="color:red">
                                ⚠️ Aucun personnel affecté sur ce site !
                            </div>
                        </div>
                    </div>';
            }
        }

        $str.='
        <script>
            $(document).ready(function() {
                $(".e160").select2({
                    width: "100%"
                });
            });
        </script>';

        $str.='';

        echo $str;
    }
}
