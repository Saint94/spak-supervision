@extends('layout.app')

@section('title', 'Nouveau personnel')

@section('content')
    <section id="basic-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="basic-layout-form"><b>Création d'un nouveau personnel</b></h4>
                    </div>

                    <div class="card-content">
                        <div class="px-3">
                            <form method="POST" action="{{ route('personnels.store') }}" class="needs-validation" novalidate>
                                @csrf

                                <div class="form-body">
                                    <hr>
                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeMatric">Matricule <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" style="text-transform:uppercase;" class="form-control" id="PeMatric" name="PeMatric" value="{{ old('PeMatric') }}" placeholder="Matricule" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeNom">Noms <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" style="text-transform: uppercase;" class="form-control" id="PeNom" name="PeNom" value="{{ old('PeNom') }}" placeholder="Noms" required/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PePrenom">Prénoms</label>
                                                <input type="text" class="form-control" id="PePrenom" name="PePrenom" value="{{ old('PePrenom') }}" placeholder="Prénoms"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="PeFonction">Fonction</label>
                                                <select class="e1 form-control" id="PeFonction" name="PeFonction">
                                                    <option selected value="">Selectionner...</option>
                                                    @foreach($fonctions as $fonction)
                                                        <option value="{{ $fonction->FnNumero }}">{{ $fonction->FnNom }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="PeStatut">Qualité <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <select class="e2 form-control" id="PeStatut" name="PeStatut" required>
                                                    <option selected value="">Selectionner...</option>
                                                    <option value="F">Agent Fixe</option>
                                                    <option value="R">Rondier</option>
                                                    <option value="B">Stand Bye</option>
                                                    <option value="E">Chef d'Equipe</option>
                                                    <option value="C">Maître Chien</option>
                                                    <option value="SG">Superv. Général</option>
                                                    <option value="SA">Superv. Adjoint</option>
                                                    <option value="CM">Commando</option>
                                                    <option value="CT">Contrôleur</option>
                                                    <option value="PS">Postulant</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeTypePiece">Selectionner le type de pièce <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <select class="e3 form-control" id="PeTypePiece" name="PeTypePiece" required>
                                                    <option selected value="">Selectionner...</option>
                                                    <option value="CN">CNI</option>
                                                    <option value="PS">Passeport</option>
                                                    <option value="CC">Carte Consulaire</option>
                                                    <option value="AU">Aucune</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeNumPieceID">Numéro de la pièce</label>
                                                <input type="text" class="form-control" id="PeNumPieceID" name="PeNumPieceID" value="{{ old('PeNumPieceID') }}" placeholder="Numéro de la pièce"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeDateEmission">Date d'émission</label>
                                                <input type="date" class="form-control" id="PeDateEmission" name="PeDateEmission"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeLieuEmission">Lieu d'émission</label>
                                                <input type="text" class="form-control" id="PeLieuEmission" name="PeLieuEmission" value="{{ old('PeLieuEmission') }}" placeholder="Lieu d'émission"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeDateExpire">Date d'expiration</label>
                                                <input type="date" class="form-control" id="PeDateExpire" name="PeDateExpire"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeDateDebut">Date de début</label>
                                                <input type="date" class="form-control" id="PeDateDebut" name="PeDateDebut"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="PeTel1">N° de téléphone 1</label>
                                                <input type="text" class="form-control" id="PeTel1" name="PeTel1" value="{{ old('PeTel1') }}" placeholder="N° de téléphone 1"/>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="PeTel2">N° de téléphone 2</label>
                                                <input type="text" class="form-control" id="PeTel2" name="PeTel2" value="{{ old('PeTel2') }}" placeholder="N° de téléphone 2"/>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeEmail">Email</label>
                                                <input type="text" class="form-control" id="PeEmail" name="PeEmail" value="{{ old('PeEmail') }}" placeholder="Email"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeVille">Ville</label>
                                                <select class="e4 form-control" id="PeVille" name="PeVille">
                                                    <option selected value="">Selectionner...</option>
                                                    @foreach($villes as $ville)
                                                        <option value="{{ $ville->ViNumero }}">{{ $ville->ViNom }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-6 col-md-6" id="afficher_quartiers">
                                            <div class="form-group">
                                                <label for="PeQuartier">Quartier</label>
                                                 <select class="e5 form-control" id="PeQuartier" name="PeQuartier">
                                                    <option selected value="">Selectionner...</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeDateEmb">Date d’embauche</label>
                                                <input type="date" class="form-control" id="PeDateEmb" name="PeDateEmb" value="{{ old('PeDateEmb') }}" placeholder="Date d’embauche"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeMaladie">Maladie dont souffre le personnel</label>
                                                <input type="text" class="form-control" id="PeMaladie" name="PeMaladie" value="{{ old('PeMaladie') }}" placeholder="Maladie dont souffre le Personnel"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeMaladieChron">Maladie chronique</label>
                                                <input type="text" class="form-control" id="PeMaladieChron" name="PeMaladieChron" value="{{ old('PeMaladieChron') }}" placeholder="Maladie chronique"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeHopotalVisite">Hôpital ou est effectué les visites</label>
                                                <input type="text" class="form-control" id="PeHopotalVisite" name="PeHopotalVisite" value="{{ old('PeHopotalVisite') }}" placeholder="Hôpital ou est effectué les visites"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeAptitudTechn">Aptitudes technique</label>
                                                <input type="text" class="form-control" id="PeAptitudTechn" name="PeAptitudTechn" value="{{ old('PeAptitudTechn') }}" placeholder="Aptitudes technique"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label>Préférence du travail ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <div class="input-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline1" name="PePrefTravJN" class="custom-control-input" value="J" checked/>
                                                        <label class="custom-control-label" for="customRadioInline1">Jour</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline2" name="PePrefTravJN" class="custom-control-input" value="N"/>
                                                        <label class="custom-control-label" for="customRadioInline2">Nuit</label>
                                                    </div>
                                                </div>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeLastDiplom">Dernier diplôme</label>
                                                <input type="text" class="form-control" id="PeLastDiplom" name="PeLastDiplom" value="{{ old('PeLastDiplom') }}" placeholder="Dernier diplôme"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeNivLect">Selectionner le niveau de lecture</label>
                                                <select class="e6 form-control" id="PeNivLect" name="PeNivLect">
                                                    <option selected value="">Selectionner...</option>
                                                    <option value="A">Aucun</option>
                                                    <option value="D">Mediocre</option>
                                                    <option value="M">Moyen</option>
                                                    <option value="B">Bien</option>
                                                    <option value="T">Très bien</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeNivEcr">Selectionner le niveau de d'écriture</label>
                                                <select class="e7 form-control" id="PeNivEcr" name="PeNivEcr">
                                                    <option selected value="">Selectionner...</option>
                                                    <option value="A">Aucun</option>
                                                    <option value="D">Mediocre</option>
                                                    <option value="M">Moyen</option>
                                                    <option value="B">Bien</option>
                                                    <option value="T">Très bien</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeTaille">Taile du personnel</label>
                                                <input type="text" class="form-control" id="PeTaille" name="PeTaille" value="{{ old('PeTaille') }}" placeholder="Taile du personnel"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PePoids">Poids du personnel</label>
                                                <input type="text" class="form-control" id="PePoids" name="PePoids" value="{{ old('PePoids') }}" placeholder="Poids du personnel"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeGabarit">Selectionner le gabarit du personnel</label>
                                                <select class="e8 form-control" id="PeGabarit" name="PeGabarit">
                                                    <option selected value="">Selectionner...</option>
                                                    <option value="F">Faible</option>
                                                    <option value="M">Moyen</option>
                                                    <option value="B">Bon</option>
                                                    <option value="T">Très bon</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeSpecialite">Spécialité du personnel</label>
                                                <input type="text" class="form-control" id="PeSpecialite" name="PeSpecialite" value="{{ old('PeSpecialite') }}" placeholder="Spécialité du personnel"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PePositionGPSLatX">Position de la latitude X du personnel</label>
                                                <input type="text" class="form-control" id="PePositionGPSLatX" name="PePositionGPSLatX" value="{{ old('PePositionGPSLatX') }}" placeholder="Position de la latitude X du personnel"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PePositionGPSLongY">Position de la longitude Y du personnel</label>
                                                <input type="text" class="form-control" id="PePositionGPSLongY" name="PePositionGPSLongY" value="{{ old('PePositionGPSLongY') }}" placeholder="Position de la longitude Y du personnel"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeVillage">Nom du village</label>
                                                <input type="text" class="form-control" id="PeVillage" name="PeVillage" value="{{ old('PeVillage') }}" placeholder="Nom du village"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeNomParrain">Noms et Prénoms complet du parrain</label>
                                                <input type="text" class="form-control" id="PeNomParrain" name="PeNomParrain" value="{{ old('PeNomParrain') }}" placeholder="Noms et Prénoms complet du parrain"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="PeTel1Parr">Numéro de téléphone 1 du parrain</label>
                                                <input type="text" class="form-control" id="PeTel1Parr" name="PeTel1Parr" value="{{ old('PeTel1Parr') }}" placeholder="Numéro de téléphone 1 du parrain"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="PeTel2Parr">Numéro de téléphone 2 du parrain</label>
                                                <input type="text" class="form-control" id="PeTel2Parr" name="PeTel2Parr" value="{{ old('PeTel2Parr') }}" placeholder="Numéro de téléphone 2 du parrain"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeGPSLatXParr">Position de la latitude X du parrain</label>
                                                <input type="text" class="form-control" id="PeGPSLatXParr" name="PeGPSLatXParr" value="{{ old('PeGPSLatXParr') }}" placeholder="Position de la latitude X du parrain"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeGPSLongYParr">Position de la longitude Y du parrain</label>
                                                <input type="text" class="form-control" id="PeGPSLongYParr" name="PeGPSLongYParr" value="{{ old('PeGPSLongYParr') }}" placeholder="Position de la longitude Y du parrain"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeLienParrain">Filiation ou lien avec le parrain</label>
                                                <input type="text" class="form-control" id="PeLienParrain" name="PeLienParrain" value="{{ old('PeLienParrain') }}" placeholder="Filiation ou lien avec le parrain"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeSitFam">Selectionner la situation familiale</label>
                                                <select class="e9 form-control" id="PeSitFam" name="PeSitFam">
                                                    <option selected value="">Selectionner...</option>
                                                    <option value="C">Célibataire</option>
                                                    <option value="M">Marié</option>
                                                    <option value="U">Union Libre</option>
                                                    <option value="D">Divorcé</option>
                                                    <option value="V">Veuf(ve)</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeNomCompagne">Nom de la compagne ou conjoint</label>
                                                <input type="text" class="form-control" id="PeNomCompagne" name="PeNomCompagne" value="{{ old('PeNomCompagne') }}" placeholder="Nom de la compagne ou conjoint"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeNbEnfants">Nombre d'enfants</label>
                                                <input type="text" class="form-control" id="PeNbEnfants" name="PeNbEnfants" value="{{ old('PeNbEnfants') }}" placeholder="Nombre d'enfants"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeTel1Compagne">Numéro de téléphone 1 de la compagne</label>
                                                <input type="text" class="form-control" id="PeTel1Compagne" name="PeTel1Compagne" value="{{ old('PeTel1Compagne') }}" placeholder="Numéro de téléphone 1 de la compagne"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeTel2Compagne">Numéro de téléphone 2 de la compagne</label>
                                                <input type="text" class="form-control" id="PeTel2Compagne" name="PeTel2Compagne" value="{{ old('PeTel2Compagne') }}" placeholder="Numéro de téléphone 2 de la compagne"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeNomEcolEnfant">Nom de l'école des enfants du personnel</label>
                                                <input type="text" class="form-control" id="PeNomEcolEnfant" name="PeNomEcolEnfant" value="{{ old('PeNomEcolEnfant') }}" placeholder="Nom de l'école des enfants du personnel"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PePretenSal">Prétention salariale</label>
                                                <input type="text" class="form-control number" id="PePretenSal" name="PePretenSal" value="{{ old('PePretenSal') }}" placeholder="Prétention salariale"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeNatureEmpl">Sélectionner le statut du personnel <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <select class="e10 form-control" id="PeNatureEmpl" name="PeNatureEmpl" required>
                                                    <option selected value="">Selectionner...</option>
                                                    <option value="A">Personnel administratif</option>
                                                    <option value="S">Agent de sécurité</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeDomicile">Domicile</label>
                                                <input type="text" class="form-control" id="PeDomicile" name="PeDomicile" value="{{ old('PeDomicile') }}" placeholder="Domicile"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        
                                        <div class="col-xl-6 col-md-6" id="afficher_jours_repos">
                                            <div class="form-group">
                                                <label for="PeJourRepos">Selectionner le jour de repos</label>
                                                <select class="e11 form-control" id="PeJourRepos" name="PeJourRepos" required>
                                                    <option selected value="">Selectionner...</option>
                                                    <option value="1">Lundi</option>
                                                    <option value="2">Mardi</option>
                                                    <option value="3">Mercredi</option>
                                                    <option value="4">Jeudi</option>
                                                    <option value="5">Vendredi</option>
                                                    <option value="6">Samedi</option>
                                                    <option value="7">Dimanche</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label>Est Superviseur ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <div class="input-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline3" name="PeIsSupervOn" class="custom-control-input" value="Y" checked/>
                                                        <label class="custom-control-label" for="customRadioInline3">Oui</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline4" name="PeIsSupervOn" class="custom-control-input" value="N"/>
                                                        <label class="custom-control-label" for="customRadioInline4">Non</label>
                                                    </div>
                                                </div>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label>Est actif ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <div class="input-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline5" name="PeIsOn" class="custom-control-input" value="Y" checked/>
                                                        <label class="custom-control-label" for="customRadioInline5">Oui</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline6" name="PeIsOn" class="custom-control-input" value="N"/>
                                                        <label class="custom-control-label" for="customRadioInline6">Non</label>
                                                    </div>
                                                </div>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label>Est-il autorisé à se connecter à l'application ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <div class="input-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline7" name="PeIsAppliOn" class="custom-control-input" value="N" checked/>
                                                        <label class="custom-control-label" for="customRadioInline7">Non</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline8" name="PeIsAppliOn" class="custom-control-input" value="Y"/>
                                                        <label class="custom-control-label" for="customRadioInline8">Oui</label>
                                                    </div>
                                                </div>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="afficher_infos_users">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeAppAccesID">Code ou identifiant pour se connecter</label>
                                                <input type="text" class="form-control" id="PeAppAccesID" name="PeAppAccesID" value="{{ old('PeAppAccesID') }}" placeholder="Code ou identifiant pour se connecter"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeAppAccessMDP">Mot de passe pour se connecter</label>
                                                <input type="text" class="form-control" id="PeAppAccessMDP" name="PeAppAccessMDP" value="{{ old('PeAppAccessMDP') }}" placeholder="Mot de passe pour se connecter"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="text-right">
                                    <a href="{{ route('personnels.index') }}" class="btn btn-danger">Annuler <i class="fa fa-refresh position-right"></i></a>
                                    <button type="submit" class="btn btn-success">Enregistrer <i class="fa fa-thumbs-o-up position-right"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('add_js')
    <script>
        $(document).ready(function() {
            $(".e1").select2();
            $(".e2").select2();
            $(".e3").select2();
            $(".e4").select2();
            $(".e5").select2();
            $(".e6").select2();
            $(".e7").select2();
            $(".e8").select2();
            $(".e9").select2();
            $(".e10").select2();
            $(".e11").select2();
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#PeVille").change(function()
            {
                var PeVille =  $("#PeVille").val();

                $.get('{{ route("personnel.show_quartiers","") }}/'+PeVille, function(rep) {
                    $("#afficher_quartiers").html(rep);
                });
            });

            if($("#PeVille").val() != null) {
                var PeVille =  $("#PeVille").val();

                $.get('{{ route("personnel.show_quartiers","") }}/'+PeVille, function(rep) {
                    $("#afficher_quartiers").html(rep);
                });
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $("input[type=radio][name=PeIsAppliOn]").click(function()
            {
                if($(this).val() == "Y")
                {
                    var PeNom =  $("#PeNom").val();

                    $("#afficher_infos_users").show();
                    $("#PeAppAccesID").attr("required",true);
                    $("#PeAppAccessMDP").attr("required",true);
                    $("#PeAppAccesID").val(PeNom);
                }
                else
                {
                    $("#afficher_infos_users").hide();
                    $("#PeAppAccesID").attr("required",false);
                    $("#PeAppAccessMDP").attr("required",false);
                    $("#PeAppAccesID").val("");
                    $("#PeAppAccessMDP").val("");
                }
            });

            if($("input[name='PeIsAppliOn']:checked").val() == "Y")
            {
                var PeNom =  $("#PeNom").val();

                $("#afficher_infos_users").show();
                $("#PeAppAccesID").attr("required",true);
                $("#PeAppAccessMDP").attr("required",true);
                $("#PeAppAccesID").val(PeNom);
            }
            else
            {
                $("#afficher_infos_users").hide();
                $("#PeAppAccesID").attr("required",false);
                $("#PeAppAccessMDP").attr("required",false);
                $("#PeAppAccesID").val("");
                $("#PeAppAccessMDP").val("");
            }
        });
    </script>

    <script>
        $(document).ready(function() {

            $(".e1").select2();
            $(".e10").select2();

            // Fonction qui gère l'état des champs
            function toggleFields() {
                var statut = $('#PeStatut').val();
                var $matric = $('#PeMatric');
                var $jour_repos = $('#PeJourRepos');

                // Gestion du champ Matricule
                if (statut === 'R') {
                    // Désactiver et retirer l'obligation
                    $matric.prop('disabled', true)
                        .prop('required', false)
                        .val('');
                    $matric.removeClass('is-invalid');
                } else {
                    // Activer et rendre obligatoire
                    $matric.prop('disabled', false)
                        .prop('required', true);
                }

                // Gestion du champ Jour de repos
                if (statut === 'R') {
                    // Afficher le champ, le rendre obligatoire et le vider
                    $('#afficher_jours_repos').show(); // Afficher le conteneur
                    $jour_repos.prop('required', true)
                        .val(''); // Initialiser à vide
                    $jour_repos.removeClass('is-invalid');
                } else {
                    // Cacher le champ, retirer l'obligation et vider la valeur
                    $('#afficher_jours_repos').hide(); // Cacher le conteneur
                    $jour_repos.prop('required', false)
                        .val(''); // Vider la valeur
                    $jour_repos.removeClass('is-invalid');
                }
            }

            // Écouter le changement du select
            $('#PeStatut').on('change', function() {
                toggleFields();
            });

            // Exécuter la fonction au chargement pour initialiser l'état
            toggleFields();
        });
    </script>
@endsection
