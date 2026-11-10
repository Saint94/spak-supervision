<?php

namespace App\Http\Controllers;

use App\Models\ContactClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContactClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $clients = DB::table('clients')
            ->orderBy('clients.ClDesign')
            ->get();

        $sites_client = DB::table('sites_client')
            ->where('sites_client.ScEntite',Auth::user()->entite)
            ->get();

        $contacts_client = DB::table('contacts_client')
            ->leftJoin('clients','clients.ClNumero','=','contacts_client.CcClient')
            ->leftJoin('sites_client','sites_client.ScNumero','=','contacts_client.CcSiteContact')
            ->where('contacts_client.CcEntite',Auth::user()->entite)
            ->get();

        return view('contacts-client.list',compact('clients','sites_client','contacts_client'));
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
    public function show(string $CcNumero )
    {
        return DB::table('contacts_client')
            ->leftJoin('clients','clients.ClNumero','=','contacts_client.CcClient')
            ->where('contacts_client.CcNumero',$CcNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $CcNumero)
    {
        $contact_client = DB::table('contacts_client')
            ->where('contacts_client.CcNumero',$CcNumero)
            ->first();

        return response()->json($contact_client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $CcNumero)
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function activer($CcNumero)
    {
        DB::table('contacts_client')
            ->where('contacts_client.CcNumero',$CcNumero)
            ->update([
                'contacts_client.CcIsOn' => 'Y',
                'contacts_client.CcDateFin' => NULL
            ]);
    }

    public function desactiver($CcNumero)
    {
        DB::table('contacts_client')
            ->where('contacts_client.CcNumero',$CcNumero)
            ->update([
                'contacts_client.CcIsOn' => 'N',
                'contacts_client.CcDateFin' => date('Y-m-d H:i:s')
            ]);
    }


    public function delete($CcNumero)
    {
        ContactClient::find($CcNumero)->delete();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show_sites_client($CcClient)
    {
        $sites_client = DB::table('sites_client')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->leftJoin('villes','villes.ViNumero','=','sites_client.ScVille')
            ->leftJoin('zones','zones.ZoNumero','=','sites_client.ScZone')
            ->leftJoin('quartiers','quartiers.QuNumero','=','sites_client.ScQuartier')
            ->where('sites_client.ScClient',$CcClient)
            ->get();
        
        $str ='';

        $str.='
            <div class="form-group">
                <label for="CcSiteContact">Site client <abbr style="color: #bd4147;">(*)</abbr></label>
                <select class="e2 form-control" id="CcSiteContact" name="CcSiteContact" required>
                    <option selected value="">Selectionner...</option>';
                    foreach($sites_client as $site):
                        $str.='<option value="'.$site->ScNumero.'">'.$site->ScNomSite.'</option>';
                    endforeach;
                $str.='</select>
                <div class="invalid-feedback">
                    Ce champ est obligatoire.
                </div>
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show_sites_client_edit($CcClient)
    {
        $sites_client = DB::table('sites_client')
            ->leftJoin('clients','clients.ClNumero','=','sites_client.ScClient')
            ->leftJoin('villes','villes.ViNumero','=','sites_client.ScVille')
            ->leftJoin('zones','zones.ZoNumero','=','sites_client.ScZone')
            ->leftJoin('quartiers','quartiers.QuNumero','=','sites_client.ScQuartier')
            ->where('sites_client.ScClient',$CcClient)
            ->get();
        
        $str ='';

        $str.='
            <div class="form-group">
                <label for="CcSiteContact">Site client <abbr style="color: #bd4147;">(*)</abbr></label>
                <select class="e2 form-control" id="CcSiteContact" name="CcSiteContact" required>
                    <option selected value="">Selectionner...</option>';
                    foreach($sites_client as $site):
                        $str.='<option value="'.$site->ScNumero.'">'.$site->ScNomSite.'</option>';
                    endforeach;
                $str.='</select>
                <div class="invalid-feedback">
                    Ce champ est obligatoire.
                </div>
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
