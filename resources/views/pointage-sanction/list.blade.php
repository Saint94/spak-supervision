@extends('layout.app')

@section('title', 'Liste des pointages sanctions')

@section('content')

@include('layout.alert')

<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><b>Liste des pointages sanctions</b></h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard table-responsive">
                        <button type="button" class="btn btn-danger mb-2" data-toggle="modal" data-target="#create_sanction"><i class="ft-plus"></i>&nbsp;Nouvelle sanction</button>
                        <button type="button" class="btn btn-warning mb-2" data-toggle="modal" data-target="#create_observation"><i class="ft-plus"></i>&nbsp;Nouvelle observation</button>

                        <form action="{{ route('pointages-sanctions.filtre') }}" method="POST">
                            @csrf

                            <div class="row">
                               <div class="col-xl-4 col-md-4">
                                    <div class="form-group">
                                        <label for="">Période du</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control" value="{{ date('Y-m-d', strtotime('-3 months')) }}"/>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-4">
                                    <div class="form-group">
                                        <label for="">Au</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control" value="{{ date('Y-m-d') }}"/>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-4">
                                    <div class="form-group">
                                        <label for="type_personnel_pointage">Type de Personnel</label>
                                        <select id="type_personnel_pointage" name="type_personnel_pointage" class="form-control e1">
                                            <option selected="selected" value="all">Tous</option>
                                            <option value="A">Administratif</option>
                                            <option value="S">Agents de sécurité</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-4" id="afficher_personnels">
                                    <div class="form-group">
                                        <label for="personnel_pointage">Personnel</label>
                                        <select id="personnel_pointage" name="personnel_pointage" class="form-control e2">
                                            <option selected="selected" value="all">Tous</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-4">
                                    <div class="form-group">
                                        <label for="sanction_pointage">Sanctions/Observations</label>
                                        <select id="sanction_pointage" name="sanction_pointage" class="form-control e3">
                                            <option selected="selected" value="all">Tous</option>
                                            @foreach($types_sanctions_1 as $type_sanction_1)
                                                <option value="{{ $type_sanction_1->SoNumero }}">{{ $type_sanction_1->SoDesign }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-4">
                                    <div class="form-group">
                                        <label for="">Afficher</label><br>
                                        <button type="submit" class="btn btn-danger"><i class="fa ft-filter"></i> Afficher</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                            <thead>
                                <tr>
                                    <th>Personnel</th>
                                    <th>Profil</th>
                                    <th>Sanction</th>
                                    <th>Montant par défaut</th>
                                    <th>Mois de paie</th>
                                    <th>Période de paie</th>
                                    <th>Date effet</th>
                                    <th>Motif</th>
                                    <th>Montant validé</th>
                                    <th>Est-elle confirmée ?</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pointages_sanctions as $pointage_sanction)
                                    <tr>
                                        <td>{{ $pointage_sanction->PeNom.' '.$pointage_sanction->PePrenom }}</td>

                                        @if($pointage_sanction->PeStatut == 'F')
                                            <td>Agent Fixe</td>
                                        @elseif($pointage_sanction->PeStatut == 'R')
                                            <td>Rondier</td>
                                        @elseif($pointage_sanction->PeStatut == 'B')
                                            <td>Stand Bye</td>
                                        @elseif($pointage_sanction->PeStatut == 'E')
                                            <td>Chef d'Equipe</td>
                                        @elseif($pointage_sanction->PeStatut == 'C')
                                            <td>Maître Chien</td>
                                        @elseif($pointage_sanction->PeStatut == 'SG')
                                            <td>Superv. Général</td>
                                        @elseif($pointage_sanction->PeStatut == 'SA')
                                            <td>Superv. Adjoint</td>
                                        @elseif($pointage_sanction->PeStatut == 'CT')
                                            <td>Contrôleur</td>
                                        @elseif($pointage_sanction->PeStatut == 'CM')
                                            <td>Commando</td>
                                        @elseif($pointage_sanction->PeStatut == 'PS')
                                            <td>Postulant</td>
                                        @else
                                            <td></td>
                                        @endif

                                        <td>{{ $pointage_sanction->SoDesign }}</td>
                                        <td>{{ currency_xaf($pointage_sanction->SoMontantDef) }}</td>
                                        <td>{{ $pointage_sanction->MpLibelle }}</td>
                                        <td>{{ date('d/m/Y',strtotime($pointage_sanction->MpDateDebut)).' au '.date('d/m/Y',strtotime($pointage_sanction->MpDateFin)) }}</td>

                                        @if($pointage_sanction->PsDatEffet != null)
                                            <td>{{ date('d/m/Y',strtotime($pointage_sanction->PsDatEffet)) }}</td>
                                        @else
                                            <td></td>
                                        @endif

                                        <td>{{ $pointage_sanction->PsMotif }}</td>
                                        <td>{{ currency_xaf($pointage_sanction->PsMtValide) }}</td>

                                        @if($pointage_sanction->PsIsConfirme == 'Y')
                                            <td>Oui</td>
                                        @elseif($pointage_sanction->PsIsConfirme == 'N')
                                            <td>Non</td>
                                        @else
                                            <td>En attente</td>
                                        @endif

                                        <td style="text-align: center;">
                                            <a onclick="showItem('{{ $pointage_sanction->PsNumero  }}')" class="info p-0" id="view">
                                                <i class="ft-eye font-medium-3 mr-2"></i>
                                            </a>

                                            <a href="#" onclick="editItem('{{$pointage_sanction->PsNumero }}')" class="warning p-0" id="editor">
                                                <i class="ft-edit font-medium-3 mr-2"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Nouvelle Sanction -->
<div class="modal fade text-left" id="create_sanction" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <form method="POST" action="{{ route('pointages-sanctions.store') }}" class="needs-validation" novalidate>
                @csrf

                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">CRÉATION D'UNE SANCTION</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="PsPersonnel">Personnel <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e4 form-control" id="PsPersonnel" name="PsPersonnel" required>
                                    <option selected value="">Selectionner...</option>
                                    @foreach($personnels as $personnel)
                                        <option value="{{ $personnel->PeNumero }}">{{ $personnel->PeNom.' '.$personnel->PePrenom }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="PsSanctionObserv">Sanction <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e5 form-control" id="PsSanctionObserv" name="PsSanctionObserv" required>
                                    <option selected value="">Selectionner...</option>
                                    @foreach($types_sanctions as $type_sanction)
                                        <option value="{{ $type_sanction->SoNumero }}">{{ $type_sanction->SoDesign }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3" id="afficher_montant_defaut_sanction">
                            <div class="form-group">
                                <label for="PsMontant">Montant par défaut</label>
                                <input type="text" id="PsMontant" class="form-control" name="PsMontant" value="{{ old('PsMontant') }}" placeholder="Montant par défaut" readonly/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="PsDatEffet">Date effet <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="date" class="form-control" id="PsDatEffet" name="PsDatEffet" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6" id="afficher_mois_concerne">
                            <div class="form-group">
                                <label for="PsMoisPaie">Mois concerné <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="PsMoisPaie" class="form-control" name="PsMoisPaie" value="{{ old('PsMoisPaie') }}" placeholder="Mois concerné" readonly/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="PsMotif">Motif <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="form-control" id="PsMotif" name="PsMotif" value="{{ old('PsMotif') }}" placeholder="Motif" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="PsMtValide">Montant validé <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="number" class="number form-control" id="PsMtValide" name="PsMtValide" value="{{ old('PsMtValide') }}" placeholder="Montant validé" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label>Est-elle confirmée ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline1" name="PsIsConfirme" class="custom-control-input" value="Y" checked/>
                                        <label class="custom-control-label" for="customRadioInline1">Oui</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline2" name="PsIsConfirme" class="custom-control-input" value="N"/>
                                        <label class="custom-control-label" for="customRadioInline2">Non</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline3" name="PsIsConfirme" class="custom-control-input" value="A"/>
                                        <label class="custom-control-label" for="customRadioInline3">En attente</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="PsObserv">Remarques de cette sanction</label>
                                <textarea rows="2" class="form-control" id="PsObserv" name="PsObserv" placeholder="Remarques de cette sanction"></textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler <i class="fa fa-refresh position-right"></i></button>
                    <button type="submit" class="btn btn-success">Enregistrer <i class="fa fa-thumbs-o-up position-right"></i></button>
                </div>

            </form>

        </div>
    </div>
</div>
<!-- Nouvelle Sanction -->

<!-- Nouvelle Observation -->
<div class="modal fade text-left" id="create_observation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <form method="POST" action="{{ route('pointages-sanctions.store') }}" class="needs-validation" novalidate>
                @csrf

                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">CRÉATION D'UNE OBSERVATION</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="PsPersonnel">Personnel <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e6 form-control" id="PsPersonnel" name="PsPersonnel" required>
                                    <option selected value="">Selectionner...</option>
                                    @foreach($personnels as $personnel)
                                        <option value="{{ $personnel->PeNumero }}">{{ $personnel->PeNom.' '.$personnel->PePrenom }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="PsSanctionObserv">Observation <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e7 form-control" id="PsSanctionObserv" name="PsSanctionObserv" required>
                                    <option selected value="">Selectionner...</option>
                                    @foreach($types_observations as $type_observation)
                                        <option value="{{ $type_observation->SoNumero }}">{{ $type_observation->SoDesign }}</option>
                                    @endforeach
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
                                <label for="PsDatEffet">Date effet <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="date" class="form-control" id="PsDatEffet2" name="PsDatEffet" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6" id="afficher_mois_concerne_2">
                            <div class="form-group">
                                <label for="PsMoisPaie">Mois concerné <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="PsMoisPaie" class="form-control" name="PsMoisPaie" value="{{ old('PsMoisPaie') }}" placeholder="Mois concerné" readonly/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="PsMotif">Motif <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="form-control" id="PsMotif" name="PsMotif" value="{{ old('PsMotif') }}" placeholder="Motif" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label>Est-elle confirmée ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline1" name="PsIsConfirme" class="custom-control-input" value="Y" checked/>
                                        <label class="custom-control-label" for="customRadioInline1">Oui</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline2" name="PsIsConfirme" class="custom-control-input" value="N"/>
                                        <label class="custom-control-label" for="customRadioInline2">Non</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline3" name="PsIsConfirme" class="custom-control-input" value="A"/>
                                        <label class="custom-control-label" for="customRadioInline3">En attente</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <label for="PsObserv">Remarques de cette sanction</label>
                                <textarea rows="2" class="form-control" id="PsObserv" name="PsObserv" placeholder="Remarques de cette sanction"></textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler <i class="fa fa-refresh position-right"></i></button>
                    <button type="submit" class="btn btn-success">Enregistrer <i class="fa fa-thumbs-o-up position-right"></i></button>
                </div>

            </form>

        </div>
    </div>
</div>
<!-- Nouvelle Observation -->

<!-- MODIFIER -->
<div class="modal fade text-left" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <form method="POST" action="#" class="form-edit needs-validation" novalidate>
                @csrf
                <input type="hidden" name="_method" value="PUT">

                <div class="modal-header bg-info white">
                    <h5 class="modal-title" id="myModalLabel16">MODIFICATION D'UNE SANCTION</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="PsPersonnel">Personnel <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e8 form-control PsPersonnel" id="PsPersonnel" name="PsPersonnel" required>
                                    <option selected value="">Selectionner...</option>
                                    @foreach($personnels as $personnel)
                                        <option value="{{ $personnel->PeNumero }}">{{ $personnel->PeNom.' '.$personnel->PePrenom }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="PsSanctionObserv">Sanction/Obs <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e9 form-control PsSanctionObserv" id="PsSanctionObserv" name="PsSanctionObserv" required>
                                    <option selected value="">Selectionner...</option>
                                    @foreach($types_sanctions as $type_sanction)
                                        <option value="{{ $type_sanction->SoNumero }}">{{ $type_sanction->SoDesign }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3" id="afficher_montant_defaut_sanction_edit">
                            <div class="form-group">
                                <label for="PsMontant">Montant par défaut</label>
                                <input type="text" id="PsMontant" class="form-control" name="PsMontant" value="{{ old('PsMontant') }}" placeholder="Montant par défaut" readonly/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="PsDatEffet">Date effet <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="date" class="form-control PsDatEffet" id="PsDatEffet" name="PsDatEffet" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6" id="afficher_mois_concerne_edit">
                            <div class="form-group">
                                <label for="PsMoisPaie">Mois concerné <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="PsMoisPaie" class="form-control" name="PsMoisPaie" value="{{ old('PsMoisPaie') }}" placeholder="Mois concerné" readonly/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="PsMotif">Motif <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="form-control PsMotif" id="PsMotif" name="PsMotif" value="{{ old('PsMotif') }}" placeholder="Motif" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="PsMtValide">Montant validé <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="number" class="number form-control PsMtValide" id="PsMtValide" name="PsMtValide" value="{{ old('PsMtValide') }}" placeholder="Montant validé" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label>Est-elle confirmée ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline1" name="PsIsConfirme" class="custom-control-input PsIsConfirme_Y" value="Y"/>
                                        <label class="custom-control-label" for="customRadioInline1">Oui</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline2" name="PsIsConfirme" class="custom-control-input PsIsConfirme_N" value="N"/>
                                        <label class="custom-control-label" for="customRadioInline2">Non</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline3" name="PsIsConfirme" class="custom-control-input PsIsConfirme_A" value="A"/>
                                        <label class="custom-control-label" for="customRadioInline3">En attente</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="PsObserv">Remarques de cette sanction</label>
                                <textarea rows="2" class="form-control PsObserv" id="PsObserv" name="PsObserv" placeholder="Remarques de cette sanction"></textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler <i class="fa fa-refresh position-right"></i></button>
                    <button type="submit" class="btn btn-info">Modifier <i class="fa fa-thumbs-o-up position-right"></i></button>
                </div>

            </form>
        </div>
    </div>
</div>
<!-- MODIFIER -->



<!-- CONSULTER -->
<div class="modal fade text-left" id="xlarge" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h5 class="modal-title" id="myModalLabel16">INFORMATIONS SANCTION</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-xl-6">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <tbody>
                                    <tr>
                                        <th scope="row">Personnel</th>
                                        <td id="personnel"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Profil</th>
                                        <td id="profil"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Sanction</th>
                                        <td id="sanction"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Montant par défaut</th>
                                        <td id="mt_defaut"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Mois de paie</th>
                                        <td id="mois_paie"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-6">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <tbody>
                                    <tr>
                                        <th scope="row">Période de paie</th>
                                        <td id="periode_paie"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Date effet</th>
                                        <td id="date_effet"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Motif</th>
                                        <td id="motif"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Montant validé</th>
                                        <td id="mt_valide"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Est-elle confirmée ?</th>
                                        <td id="est_confirme"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ft-x position-left"></i> Fermer</button>
            </div>
        </div>
    </div>
</div>
<!-- CONSULTER -->

@endsection

@section('add_js')

    <script>
        tippy('#view', {
            content: "Consulter",
            placement: 'top',
        });

        tippy('#editor', {
            content: "Modifier",
            placement: 'bottom',
        });
    </script>

    <script>
        $(document).ready(function(){
            $("#create_sanction,#create_observation,#edit,#xlarge").modal({
                show:false,
                backdrop:'static'
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $(".e1,.e2,.e3").select2();

            $(".e4,.e5").select2({
                width: '100%',
                dropdownParent: $("#create_sanction")
            });

            $(".e6,.e7").select2({
                width: '100%',
                dropdownParent: $("#create_observation")
            });
        });
    </script>

    <script type="text/javascript">
        function showItem(PsNumero) {
            $.ajax({
                url: '{{ route("pointage-sanction.show", "") }}/' + PsNumero ,
                type: 'GET',
                success: function(data){

                    $('#personnel').html(data.PeNom+' '+data.PePrenom)
                    $('#sanction').html(data.SoDesign)
                    $('#mt_defaut').html(data.PsMontant)
                    $('#mois_paie').html(data.MpLibelle)
                    $('#periode_paie').html(data.MpDateDebut+' '+data.MpDateFin)
                    $('#date_effet').html(data.PsDatEffet)
                    $('#motif').html(data.PsMotif)
                    $('#mt_valide').html(data.PsMtValide)

                    if((data.PsIsConfirme) == 'Y')
                    {
                        $('#est_confirme').text('Oui');
                    }
                    else if((data.PsIsConfirme) == 'N')
                    {
                        $('#est_confirme').text('Non');
                    }
                    else
                    {
                        $('#est_confirme').text('En attente');
                    }


                    if((data.PeStatut) == 'F')
                    {
                        $('#profil').text('Agent Fixe');
                    }
                    else if((data.PeStatut) == 'R')
                    {
                        $('#profil').text('Rondier');
                    }
                    else if((data.PeStatut) == 'B')
                    {
                        $('#profil').text('Stand Bye');
                    }
                    else if((data.PeStatut) == 'E')
                    {
                        $('#profil').text('Chef d\'Equipe');
                    }
                    else if((data.PeStatut) == 'C')
                    {
                        $('#profil').text('Maître Chien');
                    }
                    else if((data.PeStatut) == 'SG')
                    {
                        $('#profil').text('Superv. Général');
                    }
                    else if((data.PeStatut) == 'SA')
                    {
                        $('#profil').text('Superv. Adjoint');
                    }
                    else if((data.PeStatut) == 'CT')
                    {
                        $('#profil').text('Contrôleur');
                    }
                    else if((data.PeStatut) == 'CM')
                    {
                        $('#profil').text('Commando');
                    }
                    else if((data.PeStatut) == 'PS')
                    {
                        $('#profil').text('Postulant');
                    }
                    else
                    {
                        $('#profil').text('');
                    }

                    $('#xlarge').modal('show').fadeIn('slow')
                },
                error: function(){
                    console.log("Something goes wrong")
                }
            })
        }
    </script>

    <script type="text/javascript">
        function editItem(PsNumero) {
            $.ajax({
                url: '{{ route("pointage-sanction.edit", "") }}/' + PsNumero ,
                type: 'GET',
                success: function(data){

                    var PsNumero =data.PsNumero ;

                    $(".form-edit").attr('action', '{{ route("pointage-sanction.update", "") }}/' + PsNumero );
                    var form=$(".form-edit").attr('action');

                    $(".PsSanctionObserv").val(data.PsSanctionObserv);
                    $(".PsPersonnel").val(data.PsPersonnel);
                    $(".PsDatEffet").val(data.PsDatEffet);
                    $(".PsMotif").val(data.PsMotif);
                    $(".PsObserv").val(data.PsObserv);
                    $(".PsMtValide").val(data.PsMtValide);

                    if((data.PsIsConfirme) =='Y')
                    {
                        $('.PsIsConfirme_Y').attr('checked',true);
                    }
                    else if((data.PsIsConfirme) =='N')
                    {
                        $('.PsIsConfirme_N').attr('checked',true);
                    }
                    else
                    {
                        $('.PsIsConfirme_A').attr('checked',true);
                    }


                     $(".PsSanctionObserv").change(function()
                    {
                        var PsSanctionObserv =  $(".PsSanctionObserv").val();

                        $.get('{{ route("pointage-sanction.show_mt_defaut_sanction_edit","") }}/'+PsSanctionObserv, function(rep) {
                            $("#afficher_montant_defaut_sanction_edit").html(rep);
                        });
                    });

                    if($(".PsSanctionObserv").val() != null) {
                        var PsSanctionObserv =  $(".PsSanctionObserv").val();

                        $.get('{{ route("pointage-sanction.show_mt_defaut_sanction_edit","") }}/'+PsSanctionObserv, function(rep) {
                            $("#afficher_montant_defaut_sanction_edit").html(rep);
                        });
                    }


                    function getMoisPaie(date) {
                        if (date && date.trim() !== '') {
                            $.get('{{ route("pointage-sanction.show_mois_paie_edit", "") }}/' + date, function(rep) {
                                $("#afficher_mois_concerne_edit").html(rep);
                            }).fail(function() {
                                $("#afficher_mois_concerne_edit").html('<span class="text-danger">Erreur lors du chargement du mois de paie</span>');
                            });
                        } else {
                            $("#afficher_mois_concerne_edit").html('<span class="text-muted">Veuillez sélectionner une date</span>');
                        }
                    }

                    // Événement change sur l'input date
                    $(".PsDatEffet").on('change', function() {
                        var PsDatEffet = $(this).val();
                        getMoisPaie(PsDatEffet);
                    });

                    // Événement input pour les modifications en temps réel (optionnel)
                    $(".PsDatEffet").on('input', function() {
                        var PsDatEffet = $(this).val();
                        if (PsDatEffet && PsDatEffet.trim() !== '') {
                            // Pour éviter trop de requêtes, on peut ajouter un délai
                            clearTimeout($(this).data('timer'));
                            $(this).data('timer', setTimeout(function() {
                                getMoisPaie(PsDatEffet);
                            }, 500));
                        }
                    });

                    // Initialisation au chargement de la page
                    var dateInitiale = $(".PsDatEffet").val();
                    if (dateInitiale && dateInitiale.trim() !== '') {
                        getMoisPaie(dateInitiale);
                    } else {
                        // Si aucune date n'est sélectionnée, on peut mettre la date du jour par défaut
                        var today = new Date().toISOString().split('T')[0];
                        $(".PsDatEffet").val(today);
                        getMoisPaie(today);
                    }


                    $(".e8").select2({
                        width: '100%',
                    });

                    $(".e9").select2({
                        width: '100%',
                    });

                    $('#edit').modal('show').fadeIn('slow')
                },
                error: function(){
                    console.log("Something goes wrong")
                }
            })
        }
    </script>


    <script type="text/javascript">
        $(document).ready(function() {
            $("#PsSanctionObserv").change(function()
            {
                var PsSanctionObserv =  $("#PsSanctionObserv").val();

                $.get('{{ route("pointage-sanction.show_mt_defaut_sanction","") }}/'+PsSanctionObserv, function(rep) {
                    $("#afficher_montant_defaut_sanction").html(rep);
                });
            });

            if($("#PsSanctionObserv").val() != null) {
                var PsSanctionObserv =  $("#PsSanctionObserv").val();

                $.get('{{ route("pointage-sanction.show_mt_defaut_sanction","") }}/'+PsSanctionObserv, function(rep) {
                    $("#afficher_montant_defaut_sanction").html(rep);
                });
            }
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            // Fonction pour récupérer et afficher le mois de paie
            function getMoisPaie(date) {
                if (date && date.trim() !== '') {
                    $.get('{{ route("pointage-sanction.show_mois_paie", "") }}/' + date, function(rep) {
                        $("#afficher_mois_concerne").html(rep);
                    }).fail(function() {
                        $("#afficher_mois_concerne").html('<span class="text-danger">Erreur lors du chargement du mois de paie</span>');
                    });
                } else {
                    $("#afficher_mois_concerne").html('<span class="text-muted">Veuillez sélectionner une date</span>');
                }
            }

            // Événement change sur l'input date
            $("#PsDatEffet").on('change', function() {
                var PsDatEffet = $(this).val();
                getMoisPaie(PsDatEffet);
            });

            // Événement input pour les modifications en temps réel (optionnel)
            $("#PsDatEffet").on('input', function() {
                var PsDatEffet = $(this).val();
                if (PsDatEffet && PsDatEffet.trim() !== '') {
                    // Pour éviter trop de requêtes, on peut ajouter un délai
                    clearTimeout($(this).data('timer'));
                    $(this).data('timer', setTimeout(function() {
                        getMoisPaie(PsDatEffet);
                    }, 500));
                }
            });

            // Initialisation au chargement de la page
            var dateInitiale = $("#PsDatEffet").val();
            if (dateInitiale && dateInitiale.trim() !== '') {
                getMoisPaie(dateInitiale);
            } else {
                // Si aucune date n'est sélectionnée, on peut mettre la date du jour par défaut
                var today = new Date().toISOString().split('T')[0];
                $("#PsDatEffet").val(today);
                getMoisPaie(today);
            }
        });
    </script>


    <script type="text/javascript">
        $(document).ready(function() {
            // Fonction pour récupérer et afficher le mois de paie
            function getMoisPaie(date) {
                if (date && date.trim() !== '') {
                    $.get('{{ route("pointage-sanction.show_mois_paie", "") }}/' + date, function(rep) {
                        $("#afficher_mois_concerne_2").html(rep);
                    }).fail(function() {
                        $("#afficher_mois_concerne_2").html('<span class="text-danger">Erreur lors du chargement du mois de paie</span>');
                    });
                } else {
                    $("#afficher_mois_concerne_2").html('<span class="text-muted">Veuillez sélectionner une date</span>');
                }
            }

            // Événement change sur l'input date
            $("#PsDatEffet2").on('change', function() {
                var PsDatEffet = $(this).val();
                getMoisPaie(PsDatEffet);
            });

            // Événement input pour les modifications en temps réel (optionnel)
            $("#PsDatEffet2").on('input', function() {
                var PsDatEffet = $(this).val();
                if (PsDatEffet && PsDatEffet.trim() !== '') {
                    // Pour éviter trop de requêtes, on peut ajouter un délai
                    clearTimeout($(this).data('timer'));
                    $(this).data('timer', setTimeout(function() {
                        getMoisPaie(PsDatEffet);
                    }, 500));
                }
            });

            // Initialisation au chargement de la page
            var dateInitiale = $("#PsDatEffet2").val();
            if (dateInitiale && dateInitiale.trim() !== '') {
                getMoisPaie(dateInitiale);
            } else {
                // Si aucune date n'est sélectionnée, on peut mettre la date du jour par défaut
                var today = new Date().toISOString().split('T')[0];
                $("#PsDatEffet2").val(today);
                getMoisPaie(today);
            }
        });
    </script>


    <script type="text/javascript">
        $(document).ready(function() {
            $("#type_personnel_pointage").change(function()
            {
                var type_personnel_pointage =  $("#type_personnel_pointage").val();

                $.get('{{ route("pointage-sanction.show_personnels","") }}/'+type_personnel_pointage, function(rep) {
                    $("#afficher_personnels").html(rep);
                });
            });

            if($("#type_personnel_pointage").val() != null) {
                var type_personnel_pointage =  $("#type_personnel_pointage").val();

                $.get('{{ route("pointage-sanction.show_personnels","") }}/'+type_personnel_pointage, function(rep) {
                    $("#afficher_personnels").html(rep);
                });
            }
        });
    </script>

@endsection

