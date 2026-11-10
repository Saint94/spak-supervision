<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = DB::table('users')
            ->leftJoin('personnels','personnels.PeNumero','=','users.personnel')
            ->leftJoin('entites','entites.EnNumero','=','users.entite')
            ->where('users.type','!=','A')
            ->orderBy('users.id','asc')
            ->get();

        return view('users.list',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mypassword = substr(uniqid('', true), -6);

        $entites = DB::table('entites')
            ->orderBy('entites.EnAbrge','asc')
            ->get();

        return view('users.add',compact('entites','mypassword'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            $user = new User();
            $user->type = $request->type;
            $user->noms = strtoupper($request->noms);
            $user->prenoms = $request->prenoms;
            $user->tel1 = $request->tel1;
            $user->tel2 = $request->tel2;
            $user->email = $request->email;
            $user->fonction = $request->fonction;
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->mot_de_passe = $request->password;
            $user->statut = 'Y';
            $user->reset_password = $request->reset_password;
            $user->entite = $request->entite;
            $user->personnel = $request->personnel;
            $user->save();

            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('users.index')->with('alert', 'Utilisateur crée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return DB::table('users')
            ->leftJoin('personnels','personnels.PeNumero','=','users.personnel')
            ->leftJoin('entites','entites.EnNumero','=','users.entite')
            ->where('users.id',$id)
            ->get();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $id = decrypter($id);

        $entites = DB::table('entites')
            ->orderBy('entites.EnAbrge','asc')
            ->get();

        $personnels = DB::table('personnels')
            ->whereIn('personnels.PeStatut', ['SG', 'SA','CT'])
            ->where('personnels.PeEntite', Auth::user()->entite)
            ->orderBy('personnels.PeNom', 'asc')
            ->get();

        $user = DB::table('users')
            ->leftJoin('personnels','personnels.PeNumero','=','users.personnel')
            ->leftJoin('entites','entites.EnNumero','=','users.entite')
            ->where('users.id',$id)
            ->first();

        return view('users.edit',compact('user','entites','personnels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            if(!empty($request->password)) {
                DB::table('users')
                    ->where('id',$id)
                    ->update([
                        'type' => $request->type,
                        'noms' => strtoupper($request->noms),
                        'prenoms' => $request->prenoms,
                        'tel1' => $request->tel1,
                        'tel2' => $request->tel2,
                        'email' => $request->email,
                        'fonction' => $request->fonction,
                        'username' => $request->username,
                        'password' => Hash::make($request->password),
                        'mot_de_passe' => $request->password,
                        'reset_password' => $request->reset_password,
                        'entite' => $request->entite,
                        'personnel' => $request->personnel
                    ]);
            } else {
                DB::table('users')
                    ->where('id',$id)
                    ->update([
                        'type' => $request->type,
                        'noms' => strtoupper($request->noms),
                        'prenoms' => $request->prenoms,
                        'tel1' => $request->tel1,
                        'tel2' => $request->tel2,
                        'email' => $request->email,
                        'fonction' => $request->fonction,
                        'username' => $request->username,
                        'reset_password' => $request->reset_password,
                        'entite' => $request->entite,
                        'personnel' => $request->personnel
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

        return redirect()->route('users.index')->with('alert', 'Utilisateur modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function activer($id)
    {
        DB::table('users')->where('users.id', $id)->update(['users.statut' => 'Y']);
    }

    public function desactiver($id)
    {
        DB::table('users')->where('users.id', $id)->update(['users.statut' => 'N']);
    }

    public function delete($id)
    {
        User::find($id)->delete();
    }

    public function show_personnels_users($type)
    {
        $str ='';

        if($type == 'S') {

            $superviseurs = DB::table('personnels')
                ->whereIn('personnels.PeStatut', ['SG', 'SA'])
                ->whereNotIn('personnels.PeNumero', function ($query) {
                    $query->select('entites.personnel')->from('entites')
                ;})
                ->where('personnels.PeEntite', Auth::user()->entite)
                ->orderBy('personnels.PeNom', 'asc')
                ->get();

            $str.='
                <div class="form-group">
                    <label for="personnel">Selectionner le personnel</label>
                    <select class="e2 form-control" id="personnel" name="personnel">
                        <option selected value="">Selectionner...</option>';
                        foreach($superviseurs as $superviseur):
                            $str.='<option value="'.$superviseur->PeNumero.'">'.$superviseur->PeNom.' '.$superviseur->PePrenom.'</option>';
                        endforeach;
                    $str.='</select>
                    <div class="invalid-feedback">
                        Ce champ est obligatoire.
                    </div>
                </div>';
            
        } else if($type == 'T') {

            $controleurs = DB::table('personnels')
                ->whereIn('personnels.PeStatut', ['CT'])
                ->whereNotIn('personnels.PeNumero', function ($query) {
                    $query->select('entites.personnel')->from('entites')
                ;})
                ->where('personnels.PeEntite', Auth::user()->entite)
                ->orderBy('personnels.PeNom', 'asc')
                ->get();

            $str.='
                <div class="form-group">
                    <label for="personnel">Selectionner le personnel</label>
                    <select class="e2 form-control" id="personnel" name="personnel">
                        <option selected value="">Selectionner...</option>';
                        foreach($controleurs as $controleur):
                            $str.='<option value="'.$controleur->PeNumero.'">'.$controleur->PeNom.' '.$controleur->PePrenom.'</option>';
                        endforeach;
                    $str.='</select>
                    <div class="invalid-feedback">
                        Ce champ est obligatoire.
                    </div>
                </div>';

        } else {
            $str.='
            <div class="form-group">
                <label for="personnel">Selectionner le personnel</label>
                <select class="e2 form-control" id="personnel" name="personnel">
                    <option selected value="">Selectionner...</option>
                </select>
                <div class="invalid-feedback">
                    Ce champ est obligatoire.
                </div>
            </div>';
        }
        

        $str.='
        <script>
            $(document).ready(function() {
                $(".e2").select2();
            });
        </script>';

        $str.='';

        echo $str;
    }
}
