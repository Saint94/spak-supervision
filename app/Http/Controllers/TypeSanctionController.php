<?php

namespace App\Http\Controllers;

use App\Models\TypeSanction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;


class TypeSanctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types_sanctions = DB::table('sanctions')
            ->where('sanctions.SoEntite',Auth::user()->entite)
            ->orderBy('sanctions.SoDesign','asc')
            ->get();

        return view('types-sanction.list',compact('types_sanctions'));
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
        $type_sanction = DB::table('sanctions')
            ->where('sanctions.SoDesign',$request->SoDesign)
            ->where('sanctions.SoEntite',Auth::user()->entite)
            ->first();

        if(empty($type_sanction)) {
            DB::beginTransaction();

            try{
                // Do something and save to the db...
                $type_sanction = new TypeSanction();
                $type_sanction->SoEntite = Auth::user()->entite;
                $type_sanction->SoDesign = $request->SoDesign;
                $type_sanction->SoMontantDef = $request->SoMontantDef;
                $type_sanction->SoType = $request->SoType;
                $type_sanction->SoIsOn = $request->SoIsOn;
                $type_sanction->SoTypePerson = $request->SoTypePerson;
                $type_sanction->SoTypeProfil = $request->SoTypeProfil;
                $type_sanction->save();
                DB::commit();
            }
            catch (\Throwable $th){
                // An error occured; cancel the transaction...
                DB::rollback();
                // and throw the error again.
                throw $th;
            }

            return redirect()->route('types-sanction.index')->with('alert', 'Type de sanction crée avec succès.');
        }
        else {
            return redirect()->route('types-sanction.index')->with('alert-error', 'Ce type de sanction existe déjà.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $SoNumero)
    {
        return DB::table('sanctions')
            ->where('sanctions.SoNumero',$SoNumero)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $SoNumero)
    {
        $type_sanction = DB::table('sanctions')
            ->where('sanctions.SoNumero',$SoNumero)
            ->first();

        return response()->json($type_sanction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $SoNumero)
    {
        DB::beginTransaction();

        try{
            // Do something and save to the db...
            DB::table('sanctions')
            ->where('sanctions.SoNumero',$SoNumero)
            ->update([
                'SoDesign' => $request->SoDesign,
                'SoMontantDef' => $request->SoMontantDef,
                'SoType' => $request->SoType,
                'SoIsOn' => $request->SoIsOn,
                'SoTypePerson' => $request->SoTypePerson,
                'SoTypeProfil' => $request->SoTypeProfil
            ]);
            DB::commit();
        }
        catch (\Throwable $th){
            // An error occured; cancel the transaction...
            DB::rollback();
            // and throw the error again.
            throw $th;
        }

        return redirect()->route('types-sanction.index')->with('alert', 'Type de sanction modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function activer($SoNumero)
    {
        DB::table('sanctions')
            ->where('sanctions.SoNumero',$SoNumero)
            ->update([
                'sanctions.SoIsOn' => 'Y'
            ]);
    }

    public function desactiver($SoNumero)
    {
        DB::table('sanctions')
            ->where('sanctions.SoNumero',$SoNumero)
            ->update([
                'sanctions.SoIsOn' => 'N'
            ]);
    }

    public function delete($SoNumero)
    {
        TypeSanction::find($SoNumero)->delete();
    }
}
