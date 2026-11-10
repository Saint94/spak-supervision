<?php

namespace App\Imports;

use App\Models\SiteClient;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;


class SiteClientImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new SiteClient([
            'ScClient' => $row['ScClient'] ?? null,
            'ScNomSite' => $row['ScNomSite'] ?? null,
            'ScZone' => $row['ScZone'] ?? null,
            'ScQuartier' => $row['ScQuartier'] ?? null,
            'ScUserCree' => Auth::user()->id,
            'ScDateHCree' => date('Y-m-d H:i:s'),
            'ScIsOn' => $row['ClIsOn'] ?? 'Y',
            'ScIsMultiSuperv' => $row['ScIsMultiSuperv'] ?? null,
            'ScPrecision' => $row['ScPrecision'] ?? null,
            'ScLatitudeX' => $row['ScLatitudeX'] ?? 0,
            'ScLongitudeY' => $row['ScLongitudeY'] ?? 0,
            'ScDateHOff' => null,
            'ScEntite'   => Auth::user()->entite,
        ]);
    }


    /**
     * Règles de validation
     */
    public function rules(): array
    {
        return [
            '*.ScClient' => 'required',
            '*.ScNomSite' => 'required',
            '*.ScZone' => 'required',
            '*.ScQuartier' => 'required',
            '*.ScLatitudeX' => 'required',
            '*.ScLongitudeY' => 'required',
            '*.ScIsOn' => 'required',
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    public function customValidationMessages()
    {
        return [
            'ScClient.required' => 'le client est obligatoire',
            'ScNomSite.required' => 'le nom du client est obligatoire',
            'ScZone.required' => 'la zone est obligatoire',
            'ScQuartier.required' => 'le quartier est obligatoire',
            'ScLatitudeX.required' => 'la latitude est obligatoire',
            'ScLongitudeY.required' => 'la longitude est obligatoire',
            'ScIsOn.required' => 'le statut est obligatoire',
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
