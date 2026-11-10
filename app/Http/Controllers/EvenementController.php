<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\TemporaryDocEvenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class EvenementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $evenements = DB::table('evenements')
            ->leftJoin('clients','clients.ClNumero','=','evenements.EvClient')
            ->leftJoin('users as user_rappel','user_rappel.id','=','evenements.EvUserRapp')
            ->leftJoin('users as user_creer','user_creer.id','=','evenements.EvUserCree')
            ->where('evenements.EvEntite', Auth::user()->entite)
            ->orderBy('evenements.EvType','asc')
            ->select('evenements.*',
                'clients.*',
                'user_rappel.noms as user_rappel_noms',
                'user_rappel.prenoms as user_rappel_prenoms',
                'user_creer.noms as user_creer_noms',
                'user_creer.prenoms as user_creer_prenoms'
            )->get();

        return view('suivi-client.list',compact('evenements'));
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

        $users = DB::table('users')
            ->whereNotIn('users.type',['A'])
            ->where('users.entite', Auth::user()->entite)
            ->orderBy('users.noms', 'asc')
            ->orderBy('users.prenoms', 'asc')
            ->get();

        return view('suivi-client.add',compact('clients','users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        $EvDateHNextRapp = strtotime(str_replace('/', '-', $request->EvDateHNextRapp));
        $EvDateHNextRappFormate = date('Y-m-d', $EvDateHNextRapp);


        try{
            // Do something and save to the db...
            $evenement = new Evenement();
            $evenement->EvEntite = Auth::user()->entite;
            $evenement->EvClient = $request->EvClient;
            $evenement->EvType = $request->EvType;
            $evenement->EvTitre = $request->EvTitre;
            $evenement->EvDate = $request->EvDate;
            $evenement->EvHeure = $request->EvHeure;
            $evenement->EvDateHRappel = $request->EvDateHRappel.' '.$request->EvHRappel;
            $evenement->EvDateFinRappel = $request->EvDateFinRappel;
            $evenement->EvFreqRapAprXJr = $request->EvFreqRapAprXJr;
            $evenement->EvNbRapByJr = $request->EvNbRapByJr;
            $evenement->EvTmpEcartRappels = $request->EvTmpEcartRappels;
            $evenement->EvDateHNextRapp = $EvDateHNextRappFormate.' '.$request->EvHNextRapp;
            $evenement->EvTitreRapp = $request->EvTitreRapp ?? NULL;
            $evenement->EvMessageRapp = $request->EvMessageRapp ?? NULL;
            $evenement->EvIsSonOn = $request->EvIsSonOn ?? 'Y';
            $evenement->EvIsOnly1User = $request->EvIsOnly1User;
            $evenement->EvUserRapp = $request->EvUserRapp ?? NULL;
            $evenement->EvUserCree = Auth::user()->id;
            $evenement->EvIsOn = $request->EvIsOn;

            $temporaryDoc = TemporaryDocEvenement::where('folder',$request->EvChemRapport)->first();

            if($temporaryDoc)
            {
                $evenement->EvChemRapport = $temporaryDoc->file;
            }

            $evenement->save();

            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        DB::table('temporary_doc_evenement')->truncate();
        return redirect()->route('suivi-clients.index')->with('alert', 'Évènement client crée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $EvNumero)
    {
        return DB::table('evenements')
            ->leftJoin('clients','clients.ClNumero','=','evenements.EvClient')
            ->leftJoin('users as user_rappel','user_rappel.id','=','evenements.EvUserRapp')
            ->leftJoin('users as user_creer','user_creer.id','=','evenements.EvUserCree')
            ->where('evenements.EvEntite', Auth::user()->entite)
            ->orderBy('evenements.EvType','asc')
            ->select('evenements.*',
                'clients.*',
                'user_rappel.noms as user_rappel_noms',
                'user_rappel.prenoms as user_rappel_prenoms',
                'user_creer.noms as user_creer_noms',
                'user_creer.prenoms as user_creer_prenoms'
            )
            ->where('evenements.EvNumero',$EvNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $EvNumero)
    {
        $EvNumero = decrypter($EvNumero);

        $evenement = DB::table('evenements')
            ->leftJoin('clients','clients.ClNumero','=','evenements.EvClient')
            ->leftJoin('users as user_rappel','user_rappel.id','=','evenements.EvUserRapp')
            ->leftJoin('users as user_creer','user_creer.id','=','evenements.EvUserCree')
            ->where('evenements.EvEntite', Auth::user()->entite)
            ->orderBy('evenements.EvType','asc')
            ->select('evenements.*',
                'clients.*',
                'user_rappel.noms as user_rappel_noms',
                'user_rappel.prenoms as user_rappel_prenoms',
                'user_creer.noms as user_creer_noms',
                'user_creer.prenoms as user_creer_prenoms'
            )
            ->where('evenements.EvNumero',$EvNumero)
            ->first();

        $clients = DB::table('clients')
            ->where('clients.ClEntite', Auth::user()->entite)
            ->orderBy('clients.ClDesign', 'asc')
            ->get();

        $users = DB::table('users')
            ->whereNotIn('users.type',['A'])
            ->where('users.entite', Auth::user()->entite)
            ->orderBy('users.noms', 'asc')
            ->orderBy('users.prenoms', 'asc')
            ->get();


        return view('suivi-client.edit',compact('evenement','clients','users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $EvNumero)
    {
        $temporaryDoc = TemporaryDocEvenement::where('folder',$request->EvChemRapport)->first();

        DB::beginTransaction();

        try{
            // Do something and save to the db...

            if($temporaryDoc){
                DB::table('evenements')
                ->where('evenements.EvNumero',$EvNumero)
                ->update([
                    'EvClient' => $request->EvClient,
                    'EvType' => $request->EvType,
                    'EvTitre' => $request->EvTitre,
                    'EvDate' => $request->EvDate,
                    'EvHeure' => $request->EvHeure,
                    'EvDateHRappel' => $request->EvDateHRappel.' '.$request->EvHRappel,
                    'EvDateFinRappel' => $request->EvDateFinRappel,
                    'EvFreqRapAprXJr' => $request->EvFreqRapAprXJr,
                    'EvNbRapByJr' => $request->EvNbRapByJr,
                    'EvTmpEcartRappels' => $request->EvTmpEcartRappels,
                    'EvDateHNextRapp' => $request->EvDateHNextRapp.' '.$request->EvHNextRapp,
                    'EvTitreRapp' => $request->EvTitreRapp ?? NULL,
                    'EvMessageRapp' => $request->EvMessageRapp ?? NULL,
                    'EvIsSonOn' => $request->EvIsSonOn ?? 'Y',
                    'EvIsOnly1User' => $request->EvIsOnly1User ?? NULL,
                    'EvUserRapp' => $request->EvUserRapp ?? NULL,
                    'EvUserCree' => Auth::user()->id,
                    'EvChemRapport' => $temporaryDoc->file,
                    'EvIsOn' => $request->EvIsOn
                ]);
            } else {
                DB::table('evenements')
                ->where('evenements.EvNumero',$EvNumero)
                ->update([
                    'EvClient' => $request->EvClient,
                    'EvType' => $request->EvType,
                    'EvTitre' => $request->EvTitre,
                    'EvDate' => $request->EvDate,
                    'EvHeure' => $request->EvHeure,
                    'EvDateHRappel' => $request->EvDateHRappel.' '.$request->EvHRappel,
                    'EvDateFinRappel' => $request->EvDateFinRappel,
                    'EvFreqRapAprXJr' => $request->EvFreqRapAprXJr,
                    'EvNbRapByJr' => $request->EvNbRapByJr,
                    'EvTmpEcartRappels' => $request->EvTmpEcartRappels,
                    'EvDateHNextRapp' => $request->EvDateHNextRapp.' '.$request->EvHNextRapp,
                    'EvTitreRapp' => $request->EvTitreRapp ?? NULL,
                    'EvMessageRapp' => $request->EvMessageRapp ?? NULL,
                    'EvIsSonOn' => $request->EvIsSonOn ?? 'Y',
                    'EvIsOnly1User' => $request->EvIsOnly1User ?? NULL,
                    'EvUserRapp' => $request->EvUserRapp ?? NULL,
                    'EvUserCree' => Auth::user()->id,
                    'EvIsOn' => $request->EvIsOn
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

        DB::table('temporary_doc_evenement')->truncate();
        return redirect()->route('suivi-clients.index')->with('alert', 'Évènement modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $AcNumero)
    {
        //
    }

    public function activer(string $EvNumero)
    {
        DB::table('evenements')
            ->where('evenements.EvNumero',$EvNumero)
            ->update([
                'evenements.EvIsOn' => 'Y'
            ]);
    }

    public function desactiver(string $EvNumero)
    {
        DB::table('evenements')
            ->where('evenements.EvNumero',$EvNumero)
            ->update([
                'evenements.EvIsOn' => 'N'
            ]);
    }

    public function delete(string $EvNumero)
    {
        Evenement::find($EvNumero)->delete();
    }



}
