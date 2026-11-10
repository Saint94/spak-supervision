<?php

namespace App\Imports;

use App\Models\Personnel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;


class PersonnelImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Personnel([
            'PeMatric' => $row['PeMatric'] ?? null,
            'PeNom' => $row['PeNom'] ?? null,
            'PePrenom' => $row['PePrenom'] ?? null,
            'PeTypePiece' => $row['PeTypePiece'] ?? null,
            'PeDateEmission' => $row['PeDateEmission'] ?? null,
            'PeLieuEmission' => $row['PeLieuEmission'] ?? null,
            'PeDateExpire' => $row['PeDateExpire'] ?? null,
            'PeStatut' => $row['PeStatut'] ?? null,
            'PeIsSupervOn' => $row['PeIsSupervOn'] ?? 'N',
            'PeDateDebut' => $row['PeDateDebut'] ?? null,
            'PeTel1' => $row['PeTel1'] ?? null,
            'PeTel2' => $row['PeTel2'] ?? null,
            'PeEmail' => $row['PeEmail'] ?? null,
            'PeQuartier' => $row['PeQuartier'] ?? null,
            'PeDomicile' => $row['PeDomicile'] ?? null,
            'PeUserCree' => Auth::user()->id,
            'PeDateHCree' => date('Y-m-d H:i:s'),
            'PeUserModif' => null,
            'PeDateHModif' => null,
            'PeIsOn' => $row['PeIsOn'] ?? 'Y',
            'PeDateDepart' => null,
            'PeUserDepart' => null,
            'PeDetails'   => null,
            'PeEntite'   => Auth::user()->entite,
        ]);
    }


    /**
     * Règles de validation
     */
    public function rules(): array
    {
        return [
            '*.PeMatric' => 'required|unique: personnels,PeMatric',
            '*.PeNom' => 'required|string|max:255',
            '*.PeTypePiece' => 'required',
            '*.PeStatut' => 'required',
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    public function customValidationMessages()
    {
        return [
            'PeMatric.required' => 'le matricule est obligatoire',
            'PeMatric.unique' => 'le matricule existe déjà',
            'PeNom.required' => 'le nom est obligatoire',
            'PeTypePiece.required' => 'le type de pièce est obligatoire',
            'PeStatut.required' => 'le profil de pièce est obligatoire',
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
