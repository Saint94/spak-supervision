@extends('layout.app')

@section('title', 'Modifier le personnel')

@section('content')
    <section id="basic-form-layouts">

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title" id="basic-layout-form"><b>Modification du personnel {{ $personnel->PeNom.' '.$personnel->PePrenom }}</b></h4>
                        <br>
                        <span style="color: red;font-size:20px">un personnel désactivé ne peut pas être posté sur un site, ni sélectionné dans Agent Trouvé</span>
                    </div>

                    <div class="card-content">
                        <div class="px-3">
                            <form method="POST" action="{{ route('personnels.update', $personnel->PeNumero ) }}" class="needs-validation" novalidate>
                                @csrf
                                <input type="hidden" name="_method" value="PUT">

                                <div class="form-body">
                                    <hr>
                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeMatric">Matricule <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" style="text-transform:uppercase;" class="form-control" id="PeMatric" name="PeMatric" value="{{ $personnel->PeMatric }}" placeholder="Matricule" readonly/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeNom">Noms <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <input type="text" style="text-transform: uppercase;" class="form-control" id="PeNom" name="PeNom" value="{{ $personnel->PeNom }}" placeholder="Noms" readonly/>
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
                                                <input type="text" class="form-control" id="PePrenom" name="PePrenom" value="{{ $personnel->PePrenom }}" placeholder="Prénoms" readonly/>
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
                                                        <option value="{{ $fonction->FnNumero }}" {{ $personnel->PeFonction == $fonction->FnNumero ? 'selected="selected"':'' }}>{{ $fonction->FnNom }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                          <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="PeStatut">Qualité  <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <select class="e2 form-control" id="PeStatut" name="PeStatut" required>
                                                    <option selected value="">Selectionner...</option>
                                                    <option value="F" {{ $personnel->PeStatut == 'F' ? 'selected="selected"':'' }}>Agent Fixe</option>
                                                    <option value="R" {{ $personnel->PeStatut == 'R' ? 'selected="selected"':'' }}>Rondier</option>
                                                    <option value="B" {{ $personnel->PeStatut == 'B' ? 'selected="selected"':'' }}>Stand Bye</option>
                                                    <option value="C" {{ $personnel->PeStatut == 'E' ? 'selected="selected"':'' }}>Chef d'Equipe</option>
                                                    <option value="M" {{ $personnel->PeStatut == 'C' ? 'selected="selected"':'' }}>Maître Chien</option>
                                                    <option value="SG" {{ $personnel->PeStatut == 'SG' ? 'selected="selected"':'' }}>Superv. Général</option>
                                                    <option value="SA" {{ $personnel->PeStatut == 'SA' ? 'selected="selected"':'' }}>Superv. Adjoint</option>
                                                    <option value="CM" {{ $personnel->PeStatut == 'CM' ? 'selected="selected"':'' }}>Commando</option>
                                                    <option value="CT" {{ $personnel->PeStatut == 'CT' ? 'selected="selected"':'' }}>Contrôleur</option>
                                                    <option value="PS" {{ $personnel->PeStatut == 'PS' ? 'selected="selected"':'' }}>Postulant</option>
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
                                                    <option value="CN" {{ $personnel->PeTypePiece == "CN" ? 'selected="selected"':'' }}>CNI</option>
                                                    <option value="PS" {{ $personnel->PeTypePiece == "PS" ? 'selected="selected"':'' }}>Passeport</option>
                                                    <option value="CC" {{ $personnel->PeTypePiece == "CC" ? 'selected="selected"':'' }}>Carte Consulaire</option>
                                                    <option value="AU" {{ $personnel->PeTypePiece == "AU" ? 'selected="selected"':'' }}>Aucune</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeNumPieceID">Numéro de la pièce</label>
                                                <input type="text" class="form-control" id="PeNumPieceID" name="PeNumPieceID" value="{{ $personnel->PeNumPieceID }}" placeholder="Numéro de la pièce"/>
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
                                                <input type="date" class="form-control" id="PeDateEmission" name="PeDateEmission" value="{{ $personnel->PeDateEmission }}"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeLieuEmission">Lieu d'émission</label>
                                                <input type="text" class="form-control" id="PeLieuEmission" name="PeLieuEmission" value="{{ $personnel->PeLieuEmission }}" placeholder="Lieu d'émission"/>
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
                                                <input type="date" class="form-control" id="PeDateExpire" name="PeDateExpire" value="{{ $personnel->PeDateExpire }}"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeDateDebut">Date de début</label>
                                                <input type="date" class="form-control" id="PeDateDebut" name="PeDateDebut" value="{{ $personnel->PeDateDebut }}"/>
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
                                                <input type="text" class="form-control" id="PeTel1" name="PeTel1" value="{{ $personnel->PeTel1 }}" placeholder="N° de téléphone 1"/>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="PeTel2">N° de téléphone 2</label>
                                                <input type="text" class="form-control" id="PeTel2" name="PeTel2" value="{{ $personnel->PeTel2 }}" placeholder="N° de téléphone 2"/>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeEmail">Email</label>
                                                <input type="text" class="form-control" id="PeEmail" name="PeEmail" value="{{ $personnel->PeEmail }}" placeholder="Email"/>
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
                                                        <option value="{{ $ville->ViNumero }}" {{ $ville->ViNumero == $personnel->PeVille ? 'selected="selected"':'' }}>{{ $ville->ViNom }}</option>
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
                                                    @foreach($quartiers as $quartier)
                                                        <option value="{{ $quartier->QuNumero }}" {{ $quartier->QuNumero == $personnel->PeQuartier ? 'selected="selected"':'' }}>{{ $quartier->QuNom }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="PeDateEmb">Date d’embauche</label>
                                                <input type="date" class="form-control" id="PeDateEmb" name="PeDateEmb" value="{{ $personnel->PeDateEmb }}" placeholder="Date d’embauche" onchange="calculerAnciennete()" oninput="calculerAnciennete()"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="anciennete">Ancienneté</label>
                                                <input type="text" class="form-control" id="anciennete" name="anciennete" readonly placeholder="Calcul automatique"/>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeMaladie">Maladie dont souffre le personnel</label>
                                                <input type="text" class="form-control" id="PeMaladie" name="PeMaladie" value="{{ $personnel->PeMaladie }}" placeholder="Maladie dont souffre le Personnel"/>
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
                                                <input type="text" class="form-control" id="PeMaladieChron" name="PeMaladieChron" value="{{ $personnel->PeMaladieChron }}" placeholder="Maladie chronique"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeHopotalVisite">Hôpital ou est effectué les visites</label>
                                                <input type="text" class="form-control" id="PeHopotalVisite" name="PeHopotalVisite" value="{{ $personnel->PeHopotalVisite }}" placeholder="Hôpital ou est effectué les visites"/>
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
                                                <input type="text" class="form-control" id="PeAptitudTechn" name="PeAptitudTechn" value="{{ $personnel->PeAptitudTechn }}" placeholder="Aptitudes technique"/>
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
                                                        <input type="radio" id="customRadioInline1" name="PePrefTravJN" class="custom-control-input" value="J" {{ $personnel->PePrefTravJN == 'J' ? 'checked' : '' }}/>
                                                        <label class="custom-control-label" for="customRadioInline1">Jour</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline2" name="PePrefTravJN" class="custom-control-input" value="N" {{ $personnel->PePrefTravJN == 'N' ? 'checked' : '' }}/>
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
                                                <input type="text" class="form-control" id="PeLastDiplom" name="PeLastDiplom" value="{{ $personnel->PeLastDiplom }}" placeholder="Dernier diplôme"/>
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
                                                    <option value="A" {{ $personnel->PeNivLect == 'A' ? 'selected' : '' }}>Aucun</option>
                                                    <option value="D" {{ $personnel->PeNivLect == 'D' ? 'selected' : '' }}>Mediocre</option>
                                                    <option value="M" {{ $personnel->PeNivLect == 'M' ? 'selected' : '' }}>Moyen</option>
                                                    <option value="B" {{ $personnel->PeNivLect == 'B' ? 'selected' : '' }}>Bien</option>
                                                    <option value="T" {{ $personnel->PeNivLect == 'T' ? 'selected' : '' }}>Très bien</option>
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
                                                    <option value="A" {{ $personnel->PeNivEcr == 'A' ? 'selected' : '' }}>Aucun</option>
                                                    <option value="D" {{ $personnel->PeNivEcr == 'D' ? 'selected' : '' }}>Mediocre</option>
                                                    <option value="M" {{ $personnel->PeNivEcr == 'M' ? 'selected' : '' }}>Moyen</option>
                                                    <option value="B" {{ $personnel->PeNivEcr == 'B' ? 'selected' : '' }}>Bien</option>
                                                    <option value="T" {{ $personnel->PeNivEcr == 'T' ? 'selected' : '' }}>Très bien</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeTaille">Taille du personnel</label>
                                                <input type="text" class="form-control" id="PeTaille" name="PeTaille" value="{{ $personnel->PeTaille }}" placeholder="Taille du personnel"/>
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
                                                <input type="text" class="form-control" id="PePoids" name="PePoids" value="{{ $personnel->PePoids }}" placeholder="Poids du personnel"/>
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
                                                    <option value="F" {{ $personnel->PeGabarit == 'F' ? 'selected' : '' }}>Faible</option>
                                                    <option value="M" {{ $personnel->PeGabarit == 'M' ? 'selected' : '' }}>Moyen</option>
                                                    <option value="B" {{ $personnel->PeGabarit == 'B' ? 'selected' : '' }}>Bon</option>
                                                    <option value="T" {{ $personnel->PeGabarit == 'T' ? 'selected' : '' }}>Très bon</option>
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
                                                <input type="text" class="form-control" id="PeSpecialite" name="PeSpecialite" value="{{ $personnel->PeSpecialite }}" placeholder="Spécialité du personnel"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PePositionGPSLatX">Position de la latitude X du personnel</label>
                                                <input type="text" class="form-control" id="PePositionGPSLatX" name="PePositionGPSLatX" value="{{ $personnel->PePositionGPSLatX }}" placeholder="Position de la latitude X du personnel"/>
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
                                                <input type="text" class="form-control" id="PePositionGPSLongY" name="PePositionGPSLongY" value="{{ $personnel->PePositionGPSLongY }}" placeholder="Position de la longitude Y du personnel"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeVillage">Nom du village</label>
                                                <input type="text" class="form-control" id="PeVillage" name="PeVillage" value="{{ $personnel->PeVillage }}" placeholder="Nom du village"/>
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
                                                <input type="text" class="form-control" id="PeNomParrain" name="PeNomParrain" value="{{ $personnel->PeNomParrain }}" placeholder="Noms et Prénoms complet du parrain"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="PeTel1Parr">Numéro de téléphone 1 du parrain</label>
                                                <input type="text" class="form-control" id="PeTel1Parr" name="PeTel1Parr" value="{{ $personnel->PeTel1Parr }}" placeholder="Numéro de téléphone 1 du parrain"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="PeTel2Parr">Numéro de téléphone 2 du parrain</label>
                                                <input type="text" class="form-control" id="PeTel2Parr" name="PeTel2Parr" value="{{ $personnel->PeTel2Parr }}" placeholder="Numéro de téléphone 2 du parrain"/>
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
                                                <input type="text" class="form-control" id="PeGPSLatXParr" name="PeGPSLatXParr" value="{{ $personnel->PeGPSLatXParr }}" placeholder="Position de la latitude X du parrain"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeGPSLongYParr">Position de la longitude Y du parrain</label>
                                                <input type="text" class="form-control" id="PeGPSLongYParr" name="PeGPSLongYParr" value="{{ $personnel->PeGPSLongYParr }}" placeholder="Position de la longitude Y du parrain"/>
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
                                                <input type="text" class="form-control" id="PeLienParrain" name="PeLienParrain" value="{{ $personnel->PeLienParrain }}" placeholder="Filiation ou lien avec le parrain"/>
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
                                                    <option value="C" {{ $personnel->PeSitFam == 'C' ? 'selected' : '' }}>Célibataire</option>
                                                    <option value="M" {{ $personnel->PeSitFam == 'M' ? 'selected' : '' }}>Marié</option>
                                                    <option value="U" {{ $personnel->PeSitFam == 'U' ? 'selected' : '' }}>Union Libre</option>
                                                    <option value="D" {{ $personnel->PeSitFam == 'D' ? 'selected' : '' }}>Divorcé</option>
                                                    <option value="V" {{ $personnel->PeSitFam == 'V' ? 'selected' : '' }}>Veuf(ve)</option>
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
                                                <input type="text" class="form-control" id="PeNomCompagne" name="PeNomCompagne" value="{{ $personnel->PeNomCompagne }}" placeholder="Nom de la compagne ou conjoint"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeNbEnfants">Nombre d'enfants</label>
                                                <input type="text" class="form-control" id="PeNbEnfants" name="PeNbEnfants" value="{{ $personnel->PeNbEnfants }}" placeholder="Nombre d'enfants"/>
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
                                                <input type="text" class="form-control" id="PeTel1Compagne" name="PeTel1Compagne" value="{{ $personnel->PeTel1Compagne }}" placeholder="Numéro de téléphone 1 de la compagne"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeTel2Compagne">Numéro de téléphone 2 de la compagne</label>
                                                <input type="text" class="form-control" id="PeTel2Compagne" name="PeTel2Compagne" value="{{ $personnel->PeTel2Compagne }}" placeholder="Numéro de téléphone 2 de la compagne"/>
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
                                                <input type="text" class="form-control" id="PeNomEcolEnfant" name="PeNomEcolEnfant" value="{{ $personnel->PeNomEcolEnfant }}" placeholder="Nom de l'école des enfants du personnel"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PePretenSal">Prétention salariale</label>
                                                <input type="text" class="form-control number" id="PePretenSal" name="PePretenSal" value="{{ $personnel->PePretenSal }}" placeholder="Prétention salariale"/>
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
                                                    <option value="A" {{ $personnel->PeNatureEmpl == 'A' ? 'selected="selected"':'' }}>Personnel administratif</option>
                                                    <option value="S" {{ $personnel->PeNatureEmpl == 'S' ? 'selected="selected"':'' }}>Agent de sécurité</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeDomicile">Domicile</label>
                                                <input type="text" class="form-control" id="PeDomicile" name="PeDomicile" value="{{ $personnel->PeDomicile }}" placeholder="Domicile"/>
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
                                                    <option value="1" {{ $personnel->PeJourRepos == '1' ? 'selected="selected"':'' }}>Lundi</option>
                                                    <option value="2" {{ $personnel->PeJourRepos == '2' ? 'selected="selected"':'' }}>Mardi</option>
                                                    <option value="3" {{ $personnel->PeJourRepos == '3' ? 'selected="selected"':'' }}>Mercredi</option>
                                                    <option value="4" {{ $personnel->PeJourRepos == '4' ? 'selected="selected"':'' }}>Jeudi</option>
                                                    <option value="5" {{ $personnel->PeJourRepos == '5' ? 'selected="selected"':'' }}>Vendredi</option>
                                                    <option value="6" {{ $personnel->PeJourRepos == '6' ? 'selected="selected"':'' }}>Samedi</option>
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
                                                        <input type="radio" id="customRadioInline1" name="PeIsSupervOn" class="custom-control-input" value="Y" {{ $personnel->PeIsSupervOn=='Y'?'checked="checked"':'' }}/>
                                                        <label class="custom-control-label" for="customRadioInline1">Oui</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline2" name="PeIsSupervOn" class="custom-control-input" value="N" {{ $personnel->PeIsSupervOn=='N'?'checked="checked"':'' }}/>
                                                        <label class="custom-control-label" for="customRadioInline2">Non</label>
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
                                                        <input type="radio" id="customRadioInline3" name="PeIsOn" class="custom-control-input" value="Y" {{ $personnel->PeIsOn=='Y'?'checked="checked"':'' }}/>
                                                        <label class="custom-control-label" for="customRadioInline3">Oui</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline4" name="PeIsOn" class="custom-control-input" value="N" {{ $personnel->PeIsOn=='N'?'checked="checked"':'' }}/>
                                                        <label class="custom-control-label" for="customRadioInline4">Non</label>
                                                    </div>
                                                </div>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div id="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label>Est-il autorisé à se connecter à l'application ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                                <div class="input-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline5" name="PeIsAppliOn" class="custom-control-input" value="N" {{ $personnel->PeIsAppliOn=='N'?'checked="checked"':'' }}/>
                                                        <label class="custom-control-label" for="customRadioInline5">Non</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline6" name="PeIsAppliOn" class="custom-control-input" value="Y" {{ $personnel->PeIsAppliOn=='Y'?'checked="checked"':'' }}/>
                                                        <label class="custom-control-label" for="customRadioInline6">Oui</label>
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
                                                <input type="text" class="form-control" id="PeAppAccesID" name="PeAppAccesID" value="{{ $personnel->PeAppAccesID }}" placeholder="Code ou identifiant pour se connecter"/>
                                                <div class="invalid-feedback">
                                                    Ce champ est obligatoire.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label for="PeAppAccessMDP">Mot de passe pour se connecter</label>
                                                <input type="text" class="form-control" id="PeAppAccessMDP" name="PeAppAccessMDP" value="{{ $personnel->PeAppAccessMDP }}" placeholder="Mot de passe pour se connecter"/>
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

    <script>
        function calculerAnciennete() {
            const dateEmbauche = document.getElementById('PeDateEmb').value;
            const ancienneteInput = document.getElementById('anciennete');

            if (!dateEmbauche) {
                ancienneteInput.value = '';
                return;
            }

            const embauche = new Date(dateEmbauche);
            const aujourdhui = new Date();

            // Vérifier si la date est dans le futur
            if (embauche > aujourdhui) {
                ancienneteInput.value = 'La date d\'embauche ne peut pas être dans le futur';
                return;
            }

            // Calcul en années précises
            let diffEnAnnees = (aujourdhui - embauche) / (1000 * 60 * 60 * 24 * 365.25);

            // Calcul détaillé
            let ans = Math.floor(diffEnAnnees);
            let mois = Math.floor((diffEnAnnees - ans) * 12);
            let jours = Math.floor(((diffEnAnnees - ans) * 12 - mois) * 30.44);

            let resultat = ans + ' an' + (ans > 1 ? 's' : '') + ', ' + mois + ' mois, ' + jours + ' jour' + (jours > 1 ? 's' : '');

            ancienneteInput.value = resultat;
        }

        // Initialisation au chargement
        document.addEventListener('DOMContentLoaded', function() {
            calculerAnciennete();

            // Écouter les changements sur le champ date
            const dateInput = document.getElementById('PeDateEmb');
            if (dateInput) {
                // Ces événements déclenchent le calcul à chaque modification
                dateInput.addEventListener('change', calculerAnciennete);
                dateInput.addEventListener('input', calculerAnciennete);
                dateInput.addEventListener('blur', calculerAnciennete);
            }
        });

    </script>
@endsection
