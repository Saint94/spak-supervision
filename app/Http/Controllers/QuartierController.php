<?php

namespace App\Http\Controllers;

use App\Models\Quartier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;


class QuartierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quartiers = DB::table('quartiers')
            ->leftJoin('villes','villes.ViNumero','=','quartiers.QuVille')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->where('quartiers.QuEntite',Auth::user()->entite)
            ->orderBy('quartiers.QuNom','asc')
            ->get();

        $villes = DB::table('villes')
            ->where('villes.ViEntite',Auth::user()->entite)
            ->orderBy('villes.ViNom','asc')
            ->get();

        $zones_1 = DB::table('zones')
            ->where('zones.ZoEntite',Auth::user()->entite)
            ->orderBy('zones.ZoNom','asc')
            ->get();

        $villes_1 = DB::table('villes')
            ->where('villes.ViEntite',Auth::user()->entite)
            ->orderBy('villes.ViNom','asc')
            ->get();

        return view('quartiers.list',compact('quartiers','villes','zones_1','villes_1'));
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
        $quartier = DB::table('quartiers')
            ->leftJoin('villes','villes.ViNumero','=','quartiers.QuVille')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->where('quartiers.QuNom',$request->QuNom)
            ->where('quartiers.QuEntite',Auth::user()->entite)
            ->first();

        if(empty($quartier)) {
            DB::beginTransaction();

            try{
                // Do something and save to the db...
                $quartier = new Quartier();
                $quartier->QuEntite = Auth::user()->entite;
                $quartier->QuNom = $request->QuNom;
                $quartier->QuVille = $request->QuVille;
                $quartier->QuZone = $request->QuZone;
                $quartier->QuIsOn = $request->QuIsOn;
                $quartier->save();
                DB::commit();
            }
            catch (\Throwable $th){
                // An error occured; cancel the transaction...
                DB::rollback();
                // and throw the error again.
                throw $th;
            }

            return redirect()->route('quartiers.index')->with('alert', 'Quartier crée avec succès.');
        }
        else {
            return redirect()->route('quartiers.index')->with('alert-error', 'Ce quartier existe déjà.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $QuNumero)
    {
        return DB::table('quartiers')
            ->leftJoin('villes','villes.ViNumero','=','quartiers.QuVille')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->where('quartiers.QuNumero',$QuNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $QuNumero)
    {
        $zone = DB::table('quartiers')
            ->where('quartiers.QuNumero',$QuNumero)
            ->first();

        return response()->json($zone);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $QuNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('quartiers')
            ->where('quartiers.QuNumero',$QuNumero)
            ->update([
                'QuNom' => $request->QuNom,
                'QuVille' => $request->QuVille,
                'QuZone' => $request->QuZone,
                'QuIsOn' => $request->QuIsOn
            ]);
            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('quartiers.index')->with('alert', 'Quartier modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function activer($QuNumero)
    {
        DB::table('quartiers')
            ->where('quartiers.QuNumero',$QuNumero)
            ->update([
                'quartiers.QuIsOn' => 'Y'
            ]);
    }

    public function desactiver($QuNumero)
    {
        DB::table('quartiers')
            ->where('quartiers.QuNumero',$QuNumero)
            ->update([
                'quartiers.QuIsOn' => 'N',
                'QuDateOff' => date('Y-m-d H:i:s')
            ]);
    }

    public function delete($QuNumero)
    {
        Quartier::find($QuNumero)->delete();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show_zones($QuVille)
    {
        $ville = DB::table('villes')
            ->where('villes.ViNumero',$QuVille)
            ->first();

        $zones = DB::table('zones')
            ->where('zones.ZoVille',$QuVille)
            ->get();

        $str ='';

        if($ville->ViIsZoneOn == 'Y') {
            $str.='
                <div class="form-group">
                    <label for="ScQuartier">Zone <abbr style="color: #bd4147;">(*)</abbr></label>
                    <select class="e2 form-control" id="QuZone" name="QuZone" required>
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
                    <label for="QuZone">Zone <abbr style="color: #bd4147;">(*)</abbr></label>
                    <input type="text" class="form-control" readonly/>
                </div>';
        }

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


    public function generatePDF(Request $request)
    {
        $quartiers = DB::table('quartiers')
            ->leftJoin('villes','villes.ViNumero','=','quartiers.QuVille')
            ->leftJoin('zones','zones.ZoNumero','=','quartiers.QuZone')
            ->where('quartiers.QuEntite',Auth::user()->entite)
            ->orderBy('quartiers.QuNom','asc')
            ->paginate(50);

        $entite = DB::table('entites')
            ->where('entites.EnNumero',Auth::user()->entite)
            ->first();

        $pdf = PDF::loadView('quartiers.pdf', compact('quartiers','entite'));

        return  $pdf->stream("Liste-quartiers.pdf", array("Attachment" => false));
        exit(0);
    }
}
