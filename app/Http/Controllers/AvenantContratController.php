<?php

namespace App\Http\Controllers;

use App\Models\AvenantContrat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;
use App\Imports\AvenantContratImport;
use App\Models\TemporaryDocContrat;

class AvenantContratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $avenants_contrat = DB::table('avenants_contrat')
            ->leftJoin('clients','clients.ClNumero','=','avenants_contrat.AcClient')
            ->where('avenants_contrat.AcEntite',Auth::user()->entite)
            ->orderBy('avenants_contrat.AcRefAven','asc')
            ->get();

        return view('avenants-contrat.list',compact('avenants_contrat'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = DB::table('clients')
            ->where('clients.ClEntite', Auth::user()->entite)
            ->orderBy('clients.ClDesign', 'asc')
            ->get();

        $entite = DB::table('entites')
            ->where('entites.EnNumero', Auth::user()->entite)
            ->first();


        return view('avenants-contrat.add',compact('clients','entite'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $count_avenants_contrat_client = DB::table('avenants_contrat')
            ->leftJoin('clients','clients.ClNumero','=','avenants_contrat.AcClient')
            ->where('avenants_contrat.AcClient',$request->AcClient)
            ->where('avenants_contrat.AcEntite',Auth::user()->entite)
            ->count();

        if($count_avenants_contrat_client >= 1) {
            $AcType = 'A';
        } else {
            $AcType = 'C';
        }

        DB::beginTransaction();

        try{
            // Do something and save to the db...
            $avenant_contrat = new AvenantContrat();
            $avenant_contrat->AcEntite = Auth::user()->entite;
            $avenant_contrat->AcClient = $request->AcClient;
            $avenant_contrat->AcDate = $request->AcDate;
            $avenant_contrat->AcRefAven = $request->AcRefAven;
            $avenant_contrat->AcType = $AcType;
            $avenant_contrat->AcDateEffet = $request->AcDateEffet;
            $avenant_contrat->AcDateFin = $request->AcDateFin;
            $avenant_contrat->AcPrixAgent = $request->AcPrixAgent;
            $avenant_contrat->AcNbAgentJr = $request->AcNbAgentJr;
            $avenant_contrat->AcNbAgentNu = $request->AcNbAgentNu;
            $avenant_contrat->AcPrixUMC = $request->AcPrixUMC;
            $avenant_contrat->AcNbreMCJour = $request->AcNbreMCJour;
            $avenant_contrat->AcNbreMCNuit = $request->AcNbreMCNuit;
            $avenant_contrat->AcPrixUSuperv = $request->AcPrixUSuperv;
            $avenant_contrat->AcNbSupervJr = $request->AcNbSupervJr;
            $avenant_contrat->AcNbSupervNu = $request->AcNbSupervNu;
            $avenant_contrat->AcPrixUComm = $request->AcPrixUComm;
            $avenant_contrat->AcNbComdoJr = $request->AcNbComdoJr;
            $avenant_contrat->AcNbComdoNu = $request->AcNbComdoNu;
            $avenant_contrat->AcNbreOperVideo = $request->AcNbreOperVideo;
            $avenant_contrat->AcPrixUOperVideo = $request->AcPrixUOperVideo;
            $avenant_contrat->AcNbLocatVidSurv = $request->AcNbLocatVidSurv;
            $avenant_contrat->AcPrixULocVidSurv = $request->AcPrixULocVidSurv;
            $avenant_contrat->AcNbInstVidSurv = $request->AcNbInstVidSurv;
            $avenant_contrat->AcPrixUInstVidSurv = $request->AcPrixUInstVidSurv;
            $avenant_contrat->AcUserCree = Auth::user()->id;
            $avenant_contrat->AcIsOn = $request->AcIsOn;
            $avenant_contrat->AcDetails = $request->ClDetails;

            $temporaryDoc = TemporaryDocContrat::where('folder',$request->AcChemDocContrat)->first();

            if($temporaryDoc)
            {
                $avenant_contrat->AcChemDocContrat = $temporaryDoc->file;
            }

            $avenant_contrat->save();

            DB::table('clients')
                ->where('clients.ClNumero',$avenant_contrat->AcClient)
                ->update([
                    'clients.ClNbAgentJour' => $avenant_contrat->AcNbAgentJr,
                    'clients.ClNbAgentNuit' => $avenant_contrat->AcNbAgentNu
                ]);

            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        DB::table('temporary_doc_contrat')->truncate();
        return redirect()->route('avenants-contrat.index')->with('alert', 'Avenant contrat crée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $AcNumero)
    {
        return DB::table('avenants_contrat')
            ->leftJoin('clients','clients.ClNumero','=','avenants_contrat.AcClient')
            ->where('avenants_contrat.AcNumero',$AcNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $AcNumero)
    {
        $AcNumero = decrypter($AcNumero);

        $avenant_contrat = DB::table('avenants_contrat')
            ->leftJoin('clients','clients.ClNumero','=','avenants_contrat.AcClient')
            ->where('avenants_contrat.AcNumero',$AcNumero)
            ->first();

        $clients = DB::table('clients')
            ->where('clients.ClEntite',Auth::user()->entite)
            ->orderBy('clients.ClDesign','asc')
            ->get();

        $entite = DB::table('entites')
            ->where('entites.EnNumero', Auth::user()->entite)
            ->first();

        return view('avenants-contrat.edit',compact('avenant_contrat','clients','entite'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $AcNumero)
    {
        $temporaryDoc = TemporaryDocContrat::where('folder',$request->AcChemDocContrat)->first();

        DB::beginTransaction();

        try{
            // Do something and save to the db...

            if($temporaryDoc)
            {
                DB::table('avenants_contrat')
                    ->where('avenants_contrat.AcNumero',$AcNumero)
                    ->update([
                        //'AcClient' => $request->AcClient,
                        'AcDate' => $request->AcDate,
                        'AcRefAven' => $request->AcRefAven,
                        'AcType' => $request->AcType,
                        'AcDateEffet' => $request->AcDateEffet,
                        'AcDateFin' => $request->AcDateFin,
                        'AcPrixAgent' => $request->AcPrixAgent,
                        //'AcNbAgentJr' => $request->AcNbAgentJr,
                        //'AcNbAgentNu' => $request->AcNbAgentNu,
                        'AcPrixUMC' => $request->AcPrixUMC,
                        'AcNbreMCJour' => $request->AcNbreMCJour,
                        'AcNbreMCNuit' => $request->AcNbreMCNuit,
                        'AcPrixUSuperv' => $request->AcPrixUSuperv,
                        'AcNbSupervJr' => $request->AcNbSupervJr,
                        'AcNbSupervNu' => $request->AcNbSupervNu,
                        'AcPrixUComm' => $request->AcPrixUComm,
                        'AcNbComdoJr' => $request->AcNbComdoJr,
                        'AcNbComdoNu' => $request->AcNbComdoNu,
                        'AcNbreOperVideo' => $request->AcNbreOperVideo,
                        'AcPrixUOperVideo' => $request->AcPrixUOperVideo,
                        'AcNbLocatVidSurv' => $request->AcNbLocatVidSurv,
                        'AcPrixULocVidSurv' => $request->AcPrixULocVidSurv,
                        'AcNbInstVidSurv' => $request->AcNbInstVidSurv,
                        'AcPrixUInstVidSurv' => $request->AcPrixUInstVidSurv,
                        'AcChemDocContrat' => $temporaryDoc->file,
                        'AcIsOn' => $request->AcIsOn,
                        'AcDetails' => $request->AcDetails
                    ]);

                DB::table('clients')
                    ->where('clients.ClNumero',$request->AcClient)
                    ->update([
                        'clients.ClNbAgentJour' => $request->AcNbAgentJr,
                        'clients.ClNbAgentNuit' => $request->AcNbAgentNu
                    ]);
            } else {
                DB::table('avenants_contrat')
                    ->where('avenants_contrat.AcNumero',$AcNumero)
                    ->update([
                        //'AcClient' => $request->AcClient,
                        'AcDate' => $request->AcDate,
                        'AcRefAven' => $request->AcRefAven,
                        'AcType' => $request->AcType,
                        'AcDateEffet' => $request->AcDateEffet,
                        'AcDateFin' => $request->AcDateFin,
                        'AcPrixAgent' => $request->AcPrixAgent,
                        //'AcNbAgentJr' => $request->AcNbAgentJr,
                        //'AcNbAgentNu' => $request->AcNbAgentNu,
                        'AcPrixUMC' => $request->AcPrixUMC,
                        'AcNbreMCJour' => $request->AcNbreMCJour,
                        'AcNbreMCNuit' => $request->AcNbreMCNuit,
                        'AcPrixUSuperv' => $request->AcPrixUSuperv,
                        'AcNbSupervJr' => $request->AcNbSupervJr,
                        'AcNbSupervNu' => $request->AcNbSupervNu,
                        'AcPrixUComm' => $request->AcPrixUComm,
                        'AcNbComdoJr' => $request->AcNbComdoJr,
                        'AcNbComdoNu' => $request->AcNbComdoNu,
                        'AcNbreOperVideo' => $request->AcNbreOperVideo,
                        'AcPrixUOperVideo' => $request->AcPrixUOperVideo,
                        'AcNbLocatVidSurv' => $request->AcNbLocatVidSurv,
                        'AcPrixULocVidSurv' => $request->AcPrixULocVidSurv,
                        'AcNbInstVidSurv' => $request->AcNbInstVidSurv,
                        'AcPrixUInstVidSurv' => $request->AcPrixUInstVidSurv,
                        'AcIsOn' => $request->AcIsOn,
                        'AcDetails' => $request->AcDetails
                    ]);

                DB::table('clients')
                    ->where('clients.ClNumero',$request->AcClient)
                    ->update([
                        'clients.ClNbAgentJour' => $request->AcNbAgentJr,
                        'clients.ClNbAgentNuit' => $request->AcNbAgentNu
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

        DB::table('temporary_doc_contrat')->truncate();
        return redirect()->route('avenants-contrat.index')->with('alert', 'Avenant contrat modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $AcNumero)
    {
        //
    }

    public function activer($AcNumero)
    {
        DB::table('avenants_contrat')
            ->where('avenants_contrat.AcNumero',$AcNumero)
            ->update([
                'avenants_contrat.AcIsOn' => 'Y'
            ]);
    }

    public function desactiver($AcNumero)
    {
        DB::table('avenants_contrat')
            ->where('avenants_contrat.AcNumero',$AcNumero)
            ->update([
                'avenants_contrat.AcIsOn' => 'N',
                'avenants_contrat.AcDateOff' => date('Y-m-d H:i:s')
            ]);
    }

    public function delete($AcNumero)
    {
        AvenantContrat::find($AcNumero)->delete();
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_last_contrat_client($AcClient)
    {
        $last_contrat = DB::table('avenants_contrat')
            ->leftJoin('clients','clients.ClNumero','=','avenants_contrat.AcClient')
            ->where('avenants_contrat.AcClient',$AcClient)
            ->orderByDesc('avenants_contrat.AcNumero')
            ->first();

        $str ='';

        if($last_contrat) {
            $str.='
                <div class="row">
                    <div class="col-xl-3 col-md-3">
                        <div class="form-group">
                            <label for="">Référence</label>
                            <input type="text" class="form-control" value="'.$last_contrat->AcRefAven.'" readonly/>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-3">
                        <div class="form-group">
                            <label for="">Date de saisie</label>
                            <input type="text" class="form-control" value="'.date('d/m/Y',strtotime($last_contrat->AcDate)).'" readonly/>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-3">
                        <div class="form-group">
                            <label for="">Date effet</label>
                            <input type="text" class="form-control" value="'.date('d/m/Y',strtotime($last_contrat->AcDateEffet)).'" readonly/>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-3">
                        <div class="form-group">
                            <label for="">Date de fin</label>';
                            if($last_contrat->AcDateFin == null) {
                                $str.='<input type="text" class="form-control" readonly/>';
                            } else {
                                $str.='<input type="text" class="form-control" value="'.date('d/m/Y',strtotime($last_contrat->AcDateFin)).'" readonly/>';
                            }
                        $str.='</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-3 col-md-3">
                        <div class="form-group">
                            <label for="">Prix agent</label>
                            <input type="text" class="form-control" value="'.currency_xaf($last_contrat->AcPrixAgent).'" readonly/>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-3">
                        <div class="form-group">
                            <label for="">Nombre d\'agents jour</label>
                            <input type="text" class="form-control" value="'.$last_contrat->AcNbAgentJr.'" readonly/>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-3">
                        <div class="form-group">
                            <label for="">Nbre d\'agents nuit</label>
                            <input type="text" class="form-control" value="'.$last_contrat->AcNbAgentNu.'" readonly/>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-3">
                        <div class="form-group">
                            <label for="">Prix maître chien</label>
                            <input type="text" class="form-control" value="'.currency_xaf($last_contrat->AcPrixUMC).'" readonly/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-3 col-md-3">
                        <div class="form-group">
                            <label for="">Nbre maîtres chien jour</label>
                            <input type="text" class="form-control" value="'.$last_contrat->AcNbreMCJour.'" readonly/>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-3">
                        <div class="form-group">
                            <label for="">Nbre maîtres chien nuit</label>
                            <input type="text" class="form-control" value="'.$last_contrat->AcNbreMCNuit.'" readonly/>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-3">
                        <div class="form-group">
                            <label for="">Prix superviseur</label>
                            <input type="text" class="form-control" value="'.currency_xaf($last_contrat->AcPrixUSuperv).'" readonly/>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-3">
                        <div class="form-group">
                            <label for="">Nbre superviseurs jour</label>
                            <input type="text" class="form-control" value="'.$last_contrat->AcNbSupervJr.'" readonly/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-3 col-md-3">
                        <div class="form-group">
                            <label for="">Nbre superviseurs nuit</label>
                            <input type="text" class="form-control" value="'.$last_contrat->AcNbSupervNu.'" readonly/>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-3">
                        <div class="form-group">
                            <label for="">Prix commando</label>
                            <input type="text" class="form-control" value="'.currency_xaf($last_contrat->AcPrixUComm).'" readonly/>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-3">
                        <div class="form-group">
                            <label for="">Nbre commando jour</label>
                            <input type="text" class="form-control" value="'.$last_contrat->AcNbComdoJr.'" readonly/>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-3">
                        <div class="form-group">
                            <label for="">Nbre commando nuit</label>
                            <input type="text" class="form-control" value="'.$last_contrat->AcNbComdoNu.'" readonly/>
                        </div>
                    </div>
                </div>

                <hr/>';

            $str.='
                <script>
                    $(document).ready(function() {
                        $(".e2").select2();
                    });
                </script>
            ';
        } else {
            # code...
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
    public function show_ref_contrat_client($ClNumero)
    {
        $contrat = DB::table('clients')
            ->where('clients.ClNumero',$ClNumero)
            ->first();

        $annee = date('y');
        $prefixe = $annee.'/';

        $dernier = DB::table('avenants_contrat')
            ->where('avenants_contrat.AcRefAven', 'like', $prefixe.'%')
            ->selectRaw("MAX(CAST(SUBSTRING_INDEX(AcRefAven, '/', -1) AS UNSIGNED)) as max_num")
            ->lockForUpdate()
            ->value('max_num');

        $numero = ($dernier ?? 0) + 1;
        $xxxx   = str_pad($numero, 4, '0', STR_PAD_LEFT);

        $reference = date('y').'/'.date('m').'/'.$contrat->ClCode.'/C/'.$xxxx;

        $str ='';

        if($contrat) {
            $str.='
                <div class="form-group">
                    <label for="AcRefAven">Référence <abbr style="color: #bd4147;">(*)</abbr></label>
                    <input type="text" class="form-control" id="AcRefAven" name="AcRefAven" value="'.$reference.'" placeholder="Référence" readonly/>
                    <div class="invalid-feedback">
                        Ce champ est obligatoire.
                    </div>
                </div>';
        } else {
            # code...
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
    public function show_ref_contrat_client_edit($ClNumero)
    {
        $contrat = DB::table('clients')
            ->where('clients.ClNumero',$ClNumero)
            ->first();

        $annee = date('y');
        $prefixe = $annee.'/';

        $dernier = DB::table('avenants_contrat')
            ->where('avenants_contrat.AcRefAven', 'like', $prefixe.'%')
            ->selectRaw("MAX(CAST(SUBSTRING_INDEX(AcRefAven, '/', -1) AS UNSIGNED)) as max_num")
            ->lockForUpdate()
            ->value('max_num');

        $numero = ($dernier ?? 0) + 1;
        $xxxx   = str_pad($numero, 4, '0', STR_PAD_LEFT);

        $reference = date('y').'/'.date('m').'/'.$contrat->ClCode.'/AV/'.$xxxx;

        $str ='';

        if($contrat) {
            $str.='
                <div class="form-group">
                    <label for="AcRefAven">Référence <abbr style="color: #bd4147;">(*)</abbr></label>
                    <input type="text" class="form-control" id="AcRefAven" name="AcRefAven" value="'.$reference.'" placeholder="Référence" readonly/>
                    <div class="invalid-feedback">
                        Ce champ est obligatoire.
                    </div>
                </div>';
        } else {
            # code...
        }


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
            Excel::import(new AvenantContratImport, $file);
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

    public function document($AcNumero)
    {
        $AcNumero = decrypter($AcNumero);

        $avenant_contrat = DB::table('avenants_contrat')
            ->leftJoin('clients','clients.ClNumero','=','avenants_contrat.AcClient')
            ->where('avenants_contrat.AcNumero',$AcNumero)
            ->first();

        return view('avenants-contrat.file',compact('AcNumero','avenant_contrat'));
    }

    /**
     * Display a listing of the resource.
     */
    public function personnel_contrat($AcNumero)
    {
        $AcNumero = decrypter($AcNumero);

        $avenant_contrat = DB::table('avenants_contrat')
            ->leftJoin('clients','clients.ClNumero','=','avenants_contrat.AcClient')
            ->where('avenants_contrat.AcNumero',$AcNumero)
            ->first();

        $sites_client = DB::table('sites_client')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->whereNotIn('sites_client.ScNumero', function($query) {
                $query->select('contrat_site.CsSiteClient')
                ->from('contrat_site');
            })
            ->where('sites_client.ScClient',$avenant_contrat->AcClient)
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->get();

        $personnels = DB::table('poster_vigile')
            ->leftJoin('personnels','personnels.PeNumero','=','poster_vigile.PvVigile')
            ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
            ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->whereIn('sites_client.ScNumero', function($query) use ($AcNumero) {
                $query->select('contrat_site.CsSiteClient')
                    ->from('contrat_site')
                    ->where('contrat_site.CsContratClient', $AcNumero);
            })
            ->where('poster_vigile.PvEntite',Auth::user()->entite)
            ->orderBy('poster_vigile.PvDateDebut','asc')
            ->get();

        return view('avenants-contrat.personnel-contrat.list',compact('personnels','sites_client','avenant_contrat','AcNumero'));
    }


    public function filtre_personnel_contrat(Request $request)
    {
        $AcNumero = decrypter($request->AcNumero);

        $avenant_contrat = DB::table('avenants_contrat')
            ->leftJoin('clients','clients.ClNumero','=','avenants_contrat.AcClient')
            ->where('avenants_contrat.AcNumero',$AcNumero)
            ->first();

        $sites_client = DB::table('sites_client')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->whereNotIn('sites_client.ScNumero', function($query) {
                $query->select('contrat_site.CsSiteClient')
                ->from('contrat_site');
            })
            ->where('sites_client.ScClient',$avenant_contrat->AcClient)
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->get();

        if($request->site_personnel == 'all' && $request->profil_personnel == 'all')
        {
            $personnels = DB::table('poster_vigile')
                ->leftJoin('personnels','personnels.PeNumero','=','poster_vigile.PvVigile')
                ->leftJoin('sites_client','sites_client.ScNumero','=','poster_vigile.PvSiteClient')
                ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
                ->leftJoin('villes','villes.ViNumero','=','personnels.PeVille')
                ->leftJoin('quartiers','quartiers.QuNumero','=','personnels.PeQuartier')
                ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
                ->whereIn('sites_client.ScNumero', function($query) use ($AcNumero) {
                    $query->select('contrat_site.CsSiteClient')
                        ->from('contrat_site')
                        ->where('contrat_site.CsContratClient', $AcNumero);
                })
                ->where('poster_vigile.PvEntite',Auth::user()->entite)
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
                ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
                ->whereIn('sites_client.ScNumero', function($query) use ($AcNumero) {
                    $query->select('contrat_site.CsSiteClient')
                        ->from('contrat_site')
                        ->where('contrat_site.CsContratClient', $AcNumero);
                });

            // Apply filters based on the request parameters
            if($request->site_personnel != 'all') {
                $query->where('sites_client.ScNumero',$request->site_personnel)
                    ->where('poster_vigile.PvEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->profil_personnel != 'all') {
                $query->where('personnels.PeStatut',$request->profil_personnel)
                    ->where('poster_vigile.PvEntite',Auth::user()->entite);
            }

            // Execute the query with the applied filters
            $personnels = $query->orderBy('poster_vigile.PvDateDebut','asc')->get();
        }


        $site_personnel =  $request->site_personnel;
        $profil_personnel =  $request->profil_personnel;

        return view('avenants-contrat.personnel-contrat.list',compact(
            'personnels','sites_client','avenant_contrat','AcNumero'
            ,'site_personnel','profil_personnel'));

    }



    /**
     * Display a listing of the resource.
     */
    public function sites_contrat($AcNumero)
    {
        $AcNumero = decrypter($AcNumero);

        $avenant_contrat = DB::table('avenants_contrat')
            ->leftJoin('clients','clients.ClNumero','=','avenants_contrat.AcClient')
            ->where('avenants_contrat.AcNumero',$AcNumero)
            ->first();

        $sites_client = DB::table('sites_client')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->whereNotIn('sites_client.ScNumero', function($query) {
                $query->select('contrat_site.CsSiteClient')
                ->from('contrat_site');
            })
            ->where('sites_client.ScClient',$avenant_contrat->AcClient)
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->get();

        $sites_contrats = DB::table('contrat_site')
            ->leftJoin('sites_client','sites_client.ScNumero','=','contrat_site.CsSiteClient')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->leftJoin('villes','villes.ViNumero','=','sites_client.ScVille')
            ->leftJoin('quartiers','quartiers.QuNumero','=','sites_client.ScQuartier')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->where('contrat_site.CsContratClient', $AcNumero)
            ->where('contrat_site.CsEntite',Auth::user()->entite)
            ->orderBy('sites_client.ScNomSite','asc')
            ->get();

        //Pour filtre
        $sites_client_f = DB::table('sites_client')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->whereNotIn('sites_client.ScNumero', function($query) {
                $query->select('contrat_site.CsSiteClient')
                ->from('contrat_site');
            })
            ->where('sites_client.ScClient',$avenant_contrat->AcClient)
            ->where('sites_client.ScEntite',Auth::user()->entite)
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

        return view('avenants-contrat.sites-contrat.list',compact('sites_contrats','sites_client','sites_client_f','villes_f','zones_f','quartiers_f','avenant_contrat','AcNumero'));
    }

    /**
     * Display a listing of the resource.
     */
    public function filtre_sites_contrat(Request $request)
    {
        $AcNumero = decrypter($request->AcNumero);

        $avenant_contrat = DB::table('avenants_contrat')
            ->leftJoin('clients','clients.ClNumero','=','avenants_contrat.AcClient')
            ->where('avenants_contrat.AcNumero',$AcNumero)
            ->first();

        $sites_client = DB::table('sites_client')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->whereNotIn('sites_client.ScNumero', function($query) {
                $query->select('contrat_site.CsSiteClient')
                ->from('contrat_site');
            })
            ->where('sites_client.ScClient',$avenant_contrat->AcClient)
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->get();

        if($request->site_personnel == 'all' && $request->ville_site == 'all' && $request->zone_site == 'all' && $request->quartier_site == 'all')
        {
            $sites_contrats = DB::table('contrat_site')
                ->leftJoin('sites_client','sites_client.ScNumero','=','contrat_site.CsSiteClient')
                ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
                ->leftJoin('villes','villes.ViNumero','=','sites_client.ScVille')
                ->leftJoin('quartiers','quartiers.QuNumero','=','sites_client.ScQuartier')
                ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
                ->where('contrat_site.CsContratClient', $AcNumero)
                ->where('contrat_site.CsEntite',Auth::user()->entite)
                ->orderBy('sites_client.ScNomSite','asc')
                ->get();
        }
        else
        {
            // Handle other cases where not all parameters are 'all'
            $query = DB::table('contrat_site')
                ->leftJoin('sites_client','sites_client.ScNumero','=','contrat_site.CsSiteClient')
                ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
                ->leftJoin('villes','villes.ViNumero','=','sites_client.ScVille')
                ->leftJoin('quartiers','quartiers.QuNumero','=','sites_client.ScQuartier')
                ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
                ->where('contrat_site.CsContratClient', $AcNumero);

            // Apply filters based on the request parameters
            if($request->site_personnel != 'all') {
                $query->where('sites_client.ScNumero',$request->site_personnel)
                ->where('contrat_site.CsEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->ville_site != 'all') {
                $query->where('sites_client.ScVille',$request->ville_site)
                    ->where('contrat_site.CsEntite',Auth::user()->entite);
            }


            // Apply filters based on the request parameters
            if($request->zone_site != 'all') {
                $query->where('sites_client.ScZone',$request->zone_site)
                    ->where('contrat_site.CsEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->quartier_site != 'all') {
                $query->where('sites_client.ScQuartier',$request->quartier_site)
                    ->where('contrat_site.CsEntite',Auth::user()->entite);
            }

            // Execute the query with the applied filters
            $sites_contrats = $query->orderBy('sites_client.ScNomSite','asc')->get();
        }


        $site_personnel =  $request->site_personnel;
        $ville_site =  $request->ville_site;
        $zone_site = $request->zone_site;
        $quartier_site = $request->quartier_site;

        //Pour filtre
        $sites_client_f = DB::table('sites_client')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->whereNotIn('sites_client.ScNumero', function($query) {
                $query->select('contrat_site.CsSiteClient')
                ->from('contrat_site');
            })
            ->where('sites_client.ScClient',$avenant_contrat->AcClient)
            ->where('sites_client.ScEntite',Auth::user()->entite)
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

        return view('avenants-contrat.sites-contrat.filtre',compact(
            'sites_contrats','sites_client','sites_client_f',
            'villes_f','zones_f','quartiers_f','avenant_contrat',
            'AcNumero','site_personnel','ville_site','zone_site',
            'quartier_site'));
    }


}
