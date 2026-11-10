<?php

namespace App\Http\Controllers;

use App\Models\PointageSanction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;


class PointageSanctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $start_date = date('Y-m-d', strtotime('-3 months'));
        $end_date = date('Y-m-d');

        $personnels = DB::table('personnels')
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->get();

        $types_sanctions = DB::table('sanctions')
            ->where('sanctions.SoType','S')
            ->where('sanctions.SoEntite',Auth::user()->entite)
            ->orderBy('sanctions.SoDesign','asc')
            ->get();

        $types_observations = DB::table('sanctions')
            ->where('sanctions.SoEntite','O')
            ->where('sanctions.SoEntite',Auth::user()->entite)
            ->orderBy('sanctions.SoDesign','asc')
            ->get();

        $mois_paie = DB::table('mois_paie')
            ->leftJoin('users','users.id','=','mois_paie.MpCloturePar')
            ->where('mois_paie.MpStatut','E')
            ->where('mois_paie.MpEntite',Auth::user()->entite)
            ->orderBy('mois_paie.MpLibelle','asc')
            ->orderBy('mois_paie.MpLibelle','asc')
            ->get();

        $pointages_sanctions = DB::table('pointages_sanctions')
            ->leftJoin('personnels','personnels.PeNumero','=','pointages_sanctions.PsPersonnel')
            ->leftJoin('sanctions','sanctions.SoNumero','=','pointages_sanctions.PsSanctionObserv')
            ->leftJoin('mois_paie','mois_paie.MpNumero','=','pointages_sanctions.PsMoisPaie')
            ->where('pointages_sanctions.PsEntite',Auth::user()->entite)
            ->where(function($query) use ($start_date, $end_date) {
                $query->whereBetween('mois_paie.MpDateDebut', [$start_date, $end_date])
                    ->orWhereBetween('mois_paie.MpDateFin', [$start_date, $end_date])
                    ->orWhere(function($q) use ($start_date, $end_date) {
                        $q->where('mois_paie.MpDateDebut', '<=', $start_date)
                            ->where('mois_paie.MpDateFin', '>=', $end_date);
                    });
            })
            ->orderBy('pointages_sanctions.PsMoisPaie','asc')
            ->get();


        //Pour filtres
        $types_sanctions_1 = DB::table('sanctions')
            ->where('sanctions.SoEntite',Auth::user()->entite)
            ->orderBy('sanctions.SoDesign','asc')
            ->get();

        return view('pointage-sanction.list',compact('personnels','types_sanctions','types_observations','types_sanctions_1','mois_paie','pointages_sanctions'));
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
        $pointage_sanction = DB::table('pointages_sanctions')
            ->where('pointages_sanctions.PsSanctionObserv',$request->PsSanctionObserv)
            ->where('pointages_sanctions.PsPersonnel',$request->PsPersonnel)
            ->where('pointages_sanctions.PsMoisPaie',$request->PsMoisPaie)
            ->where('pointages_sanctions.PsEntite',Auth::user()->entite)
            ->first();

        if(empty($pointage_sanction)) {
            DB::beginTransaction();

            try{
                // Do something and save to the db...
                $pointage_sanction = new PointageSanction();
                $pointage_sanction->PsEntite = Auth::user()->entite;
                $pointage_sanction->PsSanctionObserv = $request->PsSanctionObserv;
                $pointage_sanction->PsPersonnel = $request->PsPersonnel;
                $pointage_sanction->PsMoisPaie = $request->PsMoisPaie;
                $pointage_sanction->PsDatEffet = $request->PsDatEffet;
                $pointage_sanction->PsMontant = $request->PsMontant;
                $pointage_sanction->PsMotif = $request->PsMotif;
                $pointage_sanction->PsObserv = $request->PsObserv;
                $pointage_sanction->PsIsConfirme = $request->PsIsConfirme;
                $pointage_sanction->PsMtValide = $request->PsMtValide;
                $pointage_sanction->PsUser = Auth::user()->id;
                $pointage_sanction->PsDateHOper = date('Y-m-d H:i:s');
                $pointage_sanction->save();
                DB::commit();
            }
            catch (\Throwable $th){
                // An error occured; cancel the transaction...
                DB::rollback();
                // and throw the error again.
                throw $th;
            }

            return redirect()->route('pointages-sanctions.index')->with('alert', 'Pointage de sanction crée avec succès.');
        }
        else {
            return redirect()->route('pointages-sanctions.index')->with('alert-error', 'Ce pointage de sanction existe déjà.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $PsNumero)
    {
        return DB::table('pointages_sanctions')
            ->leftJoin('personnels','personnels.PeNumero','=','pointages_sanctions.PsPersonnel')
            ->leftJoin('sanctions','sanctions.SoNumero','=','pointages_sanctions.PsSanctionObserv')
            ->leftJoin('mois_paie','mois_paie.MpNumero','=','pointages_sanctions.PsMoisPaie')
            ->where('pointages_sanctions.PsNumero',$PsNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $PsNumero)
    {
        $pointage_sanction = DB::table('pointages_sanctions')
            ->where('pointages_sanctions.PsNumero',$PsNumero)
            ->first();

        return response()->json($pointage_sanction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $PsNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('pointages_sanctions')
            ->where('pointages_sanctions.PsNumero',$PsNumero)
            ->update([
                'PsSanctionObserv' => $request->PsSanctionObserv,
                'PsPersonnel' => $request->PsPersonnel,
                'PsMoisPaie' => $request->PsMoisPaie,
                'PsDatEffet' => $request->PsDatEffet,
                'PsMontant' => $request->PsMontant,
                'PsMotif' => $request->PsMotif,
                'PsObserv' => $request->PsObserv,
                'PsIsConfirme' => $request->PsIsConfirme,
                'PsMtValide' => $request->PsMtValide
            ]);

            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('pointages-sanctions.index')->with('alert', 'Type de saonction modifié avec succès.');
    }



    public function delete($PsNumero)
    {
        PointageSanction::find($PsNumero)->delete();
    }


    public function filtre(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $personnels = DB::table('personnels')
            ->where('personnels.PeEntite',Auth::user()->entite)
            ->get();

        $types_sanctions = DB::table('sanctions')
            ->where('sanctions.SoType','S')
            ->where('sanctions.SoEntite',Auth::user()->entite)
            ->orderBy('sanctions.SoDesign','asc')
            ->get();

        $types_observations = DB::table('sanctions')
            ->where('sanctions.SoEntite','O')
            ->where('sanctions.SoEntite',Auth::user()->entite)
            ->orderBy('sanctions.SoDesign','asc')
            ->get();

        $mois_paie = DB::table('mois_paie')
            ->leftJoin('users','users.id','=','mois_paie.MpCloturePar')
            ->where('mois_paie.MpStatut','E')
            ->where('mois_paie.MpEntite',Auth::user()->entite)
            ->orderBy('mois_paie.MpLibelle','asc')
            ->orderBy('mois_paie.MpLibelle','asc')
            ->get();

        if($request->type_personnel_pointage == 'all' && $request->personnel_pointage == 'all' && $request->sanction_pointage == 'all')
        {
            $pointages_sanctions = DB::table('pointages_sanctions')
                ->leftJoin('personnels','personnels.PeNumero','=','pointages_sanctions.PsPersonnel')
                ->leftJoin('sanctions','sanctions.SoNumero','=','pointages_sanctions.PsSanctionObserv')
                ->leftJoin('mois_paie','mois_paie.MpNumero','=','pointages_sanctions.PsMoisPaie')
                ->where('pointages_sanctions.PsEntite',Auth::user()->entite)
                ->where(function($query) use ($start_date, $end_date) {
                    $query->whereBetween('mois_paie.MpDateDebut', [$start_date, $end_date])
                        ->orWhereBetween('mois_paie.MpDateFin', [$start_date, $end_date])
                        ->orWhere(function($q) use ($start_date, $end_date) {
                            $q->where('mois_paie.MpDateDebut', '<=', $start_date)
                                ->where('mois_paie.MpDateFin', '>=', $end_date);
                        });
                })
                ->where('pointages_sanctions.PsEntite',Auth::user()->entite)
                ->orderBy('pointages_sanctions.PsMoisPaie','asc')
                ->get();
        }
        else
        {
            // Handle other cases where not all parameters are 'all'
            $query = DB::table('pointages_sanctions')
                ->leftJoin('personnels','personnels.PeNumero','=','pointages_sanctions.PsPersonnel')
                ->leftJoin('sanctions','sanctions.SoNumero','=','pointages_sanctions.PsSanctionObserv')
                ->leftJoin('mois_paie','mois_paie.MpNumero','=','pointages_sanctions.PsMoisPaie')
                ->where(function($query) use ($start_date, $end_date) {
                    $query->whereBetween('mois_paie.MpDateDebut', [$start_date, $end_date])
                        ->orWhereBetween('mois_paie.MpDateFin', [$start_date, $end_date])
                        ->orWhere(function($q) use ($start_date, $end_date) {
                            $q->where('mois_paie.MpDateDebut', '<=', $start_date)
                                ->where('mois_paie.MpDateFin', '>=', $end_date);
                        });
                });

            // Apply filters based on the request parameters
            if($request->type_personnel_pointage != 'all') {
                $query->where('personnels.PeNatureEmpl',$request->type_personnel_pointage)
                    ->where('pointages_sanctions.PsEntite',Auth::user()->entite);
            }

            // Apply filters based on the request parameters
            if($request->personnel_pointage != 'all') {
                $query->where('pointages_sanctions.PsPersonnel',$request->personnel_pointage)
                    ->where('pointages_sanctions.PsEntite',Auth::user()->entite);
            }


            // Apply filters based on the request parameters
            if($request->sanction_pointage != 'all') {
                $query->where('pointages_sanctions.PsSanctionObserv',$request->sanction_pointage)
                    ->where('pointages_sanctions.PsEntite',Auth::user()->entite);
            }

            // Execute the query with the applied filters
            $pointages_sanctions = $query->get();
        }


        $type_personnel_pointage =  $request->type_personnel_pointage;
        $personnel_pointage =  $request->personnel_pointage;
        $sanction_pointage = $request->sanction_pointage;

        //Pour filtres
        $types_sanctions_1 = DB::table('sanctions')
            ->where('sanctions.SoEntite',Auth::user()->entite)
            ->orderBy('sanctions.SoDesign','asc')
            ->get();

        return view('pointage-sanction.filtre',compact(
            'personnels','types_sanctions','types_observations','types_sanctions_1',
            'mois_paie','pointages_sanctions','type_personnel_pointage','personnel_pointage',
            'sanction_pointage','start_date','end_date'
        ));
    }

   /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */

    public function show_mt_defaut_sanction(string $PsSanctionObserv)
    {
        $type_sanction = DB::table('sanctions')
            ->where('sanctions.SoNumero',$PsSanctionObserv)
            ->first();

        $str ='';

        $str.='
            <div class="form-group">
                <label for="PsMontant">Montant par défaut</label>
                <input type="hidden" id="PsMontant" class="form-control" name="PsMontant" value="'.$type_sanction->SoMontantDef.'" placeholder="Montant par défaut" readonly/>
                <input type="text" class="form-control" value="'.currency_xaf($type_sanction->SoMontantDef).'" placeholder="Montant par défaut" readonly/>
            </div>';

        $str.='';

        echo $str;
    }

    public function show_mt_defaut_sanction_edit(string $PsSanctionObserv)
    {
        $type_sanction = DB::table('sanctions')
            ->where('sanctions.SoNumero',$PsSanctionObserv)
            ->first();

        $str ='';

        $str.='
            <div class="form-group">
                <label for="PsMontant">Montant par défaut</label>
                <input type="hidden" id="PsMontant" class="form-control" name="PsMontant" value="'.$type_sanction->SoMontantDef.'" placeholder="Montant par défaut" readonly/>
                <input type="text" class="form-control" value="'.currency_xaf($type_sanction->SoMontantDef).'" placeholder="Montant par défaut" readonly/>
            </div>';

        $str.='';

        echo $str;
    }

    public function show_mois_paie(string $PsDatEffet)
    {
        $mois_paie = DB::table('mois_paie')
            ->whereDate('mois_paie.MpDateDebut', '<=', $PsDatEffet)
            ->whereDate('mois_paie.MpDateFin', '>=', $PsDatEffet)
            ->first();

       $str ='';

        $str.='
            <div class="form-group">
                <label for="PsMoisPaie">Mois concerné</label>
                <input type="hidden" id="PsMoisPaie" class="form-control" name="PsMoisPaie" value="'.$mois_paie->MpNumero.'" placeholder="Mois concerné" readonly/>
                <input type="text" class="form-control" value="'.$mois_paie->MpLibelle.'" placeholder="Mois concerné" readonly/>
            </div>';

        $str.='';

        echo $str;
    }

    public function show_mois_paie_edit(string $PsDatEffet)
    {
        $mois_paie = DB::table('mois_paie')
            ->whereDate('mois_paie.MpDateDebut', '<=', $PsDatEffet)
            ->whereDate('mois_paie.MpDateFin', '>=', $PsDatEffet)
            ->first();

       $str ='';

        $str.='
            <div class="form-group">
                <label for="PsMoisPaie">Mois concerné</label>
                <input type="hidden" id="PsMoisPaie" class="form-control" name="PsMoisPaie" value="'.$mois_paie->MpNumero.'" placeholder="Mois concerné" readonly/>
                <input type="text" class="form-control" value="'.$mois_paie->MpLibelle.'" placeholder="Mois concerné" readonly/>
            </div>';

        $str.='';

        echo $str;
    }


    public function show_personnels(string $type_personnel_pointage)
    {
        if($type_personnel_pointage == 'A') {
            $personnels = DB::table('personnels')
                ->where('personnels.PeNatureEmpl','A')
                ->where('personnels.PeEntite',Auth::user()->entite)
                ->get();

            $str ='';

            $str.='
                <div class="form-group">
                    <label for="personnel_pointage">Personnel</label>
                    <select id="personnel_pointage" name="personnel_pointage" class="form-control e20">
                        <option selected="selected" value="all">Tous</option>';
                        foreach($personnels as $personnel):
                            $str.='<option value="'.$personnel->PeNumero.'">'.$personnel->PeMatric.' '.$personnel->PeNom.' '.$personnel->PePrenom.'</option>';
                        endforeach;
                    $str.='</select>
                </div>';

        } elseif($type_personnel_pointage == 'S') {
            $personnels = DB::table('personnels')
                ->where('personnels.PeNatureEmpl','S')
                ->where('personnels.PeEntite',Auth::user()->entite)
                ->get();

            $str ='';

            $str.='
                <div class="form-group">
                    <label for="personnel_pointage">Personnel</label>
                    <select id="personnel_pointage" name="personnel_pointage" class="form-control e20">
                        <option selected="selected" value="all">Tous</option>';
                        foreach($personnels as $personnel):
                            $str.='<option value="'.$personnel->PeNumero.'">'.$personnel->PeMatric.' '.$personnel->PeNom.' '.$personnel->PePrenom.'</option>';
                        endforeach;
                    $str.='</select>
                </div>';
        } else {
            $personnels = DB::table('personnels')
                ->where('personnels.PeEntite',Auth::user()->entite)
                ->get();

            $str ='';

            $str.='
                <div class="form-group">
                    <label for="personnel_pointage">Personnel</label>
                    <select id="personnel_pointage" name="personnel_pointage" class="form-control e20">
                        <option selected="selected" value="all">Tous</option>';
                        foreach($personnels as $personnel):
                            $str.='<option value="'.$personnel->PeNumero.'">'.$personnel->PeMatric.' '.$personnel->PeNom.' '.$personnel->PePrenom.'</option>';
                        endforeach;
                    $str.='</select>
                </div>';
        }


        $str.='';

        $str.='
        <script>
            $(document).ready(function() {
                $(".e20").select2({
                    width: "100%"
                });
            });
        </script>';

        echo $str;
    }

}
