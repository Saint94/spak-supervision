<?php

namespace App\Imports;

use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;


class ClientImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Client([
            'ClCode' => $row['ClCode'] ?? null,
            'ClDesign' => $row['ClDesign'] ?? null,
            'ClTel1' => $row['ClTel1'] ?? null,
            'ClTel2' => $row['ClTel2'] ?? null,
            'ClEmail' => $row['ClEmail'] ?? null,
            'ClDateDebut' => $row['ClDateDebut'] ?? null,
            'ClNbreSites' => $row['ClNbreSites'] ?? 0,
            'ClNbAgentJour' => $row['ClNbAgentJour'] ?? 0,
            'ClNbAgentNuit' => $row['ClNbAgentNuit'] ?? 0,
            'ClUserCree' => Auth::user()->id,
            'ClDateHCree' => date('Y-m-d H:i:s'),
            'ClIsOn' => $row['ClIsOn'] ?? 'Y',
            'ClDateOff' => null,
            'ClDetails' => null,
            'ClEntite'   => Auth::user()->entite,
        ]);
    }


    /**
     * Règles de validation
     */
    public function rules(): array
    {
        return [
            '*.ClCode' => 'required|unique: clients,ClCode',
            '*.ClDesign' => 'required|string|max:255',
            '*.ClIsOn' => 'required',
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    public function customValidationMessages()
    {
        return [
            'ClCode.required' => 'le code est obligatoire',
            'ClCode.unique' => 'le code existe déjà',
            'ClDesign.required' => 'le nom est obligatoire',
            'ClIsOn.required' => 'le statut est obligatoire',
        ];
    }

    /**
     * Taille du lot d'insertion
     */
    public function batchSize(): int
    {
        return 500;
    }

    /**
     * Taille du chunk pour la lecture
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * Nom des colonnes (si nécessaire)
     */
    public function headingRow(): int
    {
        return 1; // La première ligne contient les en-têtes
    }
}
