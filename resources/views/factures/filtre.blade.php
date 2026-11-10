@extends('layout.app')

@section('title', 'Liste des factures clients')

@section('content')

@include('layout.alert')

<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><b>Liste des factures clients</b></h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard table-responsive">
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create"><i class="ft-plus"></i>&nbsp;Ajouter une facture client</button>
                        <button type="button" class="btn btn-danger" onclick="imprimerAvecFiltres()"><i class="ft-printer"></i> Imprimer Liste Factures Client</button>

                        <input type="hidden" id="EnPrixUMinAgentFixe" value="{{ $entite->EnPrixUMinAgentFixe }}"/>
                        <input type="hidden" id="EnPrixUMinMC" value="{{ $entite->EnPrixUMinMC }}"/>
                        <input type="hidden" id="EnPrixUMinSuperv" value="{{ $entite->EnPrixUMinSuperv }}"/>
                        <input type="hidden" id="EnPrixUMinComdo" value="{{ $entite->EnPrixUMinComdo }}"/>

                        <form action="{{ route('factures-clients.filtre') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-xl-4 col-md-4">
                                    <div class="form-group">
                                        <label for="">Période du</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $start_date}}"/>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-4">
                                    <div class="form-group">
                                        <label for="">Au</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $end_date }}"/>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-4">
                                    <div class="form-group">
                                        <label for="numero_client">Clients</label>
                                        <select name="numero_client" class="form-control e1">
                                            <option selected="selected" value="all">Tous</option>
                                            @foreach($clients_f as $client_f)
                                                <option value="{{ $client_f->ClNumero }}" {{ $client_f->ClNumero == $numero_client ? 'selected' : '' }}>{{ $client_f->ClDesign }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-4">
                                    <div class="form-group">
                                        <label for="statut_facture">Statuts</label>
                                        <select name="statut_facture" class="form-control e2">
                                            <option selected="selected" value="all">Tous</option>
                                            <option value="Y" {{ $statut_facture == 'Y' ? 'selected' : '' }}>Facture soldées</option>
                                            <option value="N" {{ $statut_facture == 'N' ? 'selected' : '' }}>Facture non soldées</option>
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
                                    <th>Crée par</th>
                                    <th>Client</th>
                                    <th>Num Facture</th>
                                    <th>Date</th>
                                    <th>Mois</th>
                                    <th>Année</th>
                                    <th>Date début</th>
                                    <th>Date Fin</th>
                                    <th>Nbre vigiles Jour</th>
                                    <th>Nbre vigiles Nuit</th>
                                    <th>Mt Facturé par Vigile</th>
                                    <th>Nbre maîtres chien Jour</th>
                                    <th>Nbre maîtres chien Nuit</th>
                                    <th>Mt Facturé par maître chien</th>
                                    <th>Nbre superviseurs Jour</th>
                                    <th>Nbre superviseurs Nuit</th>
                                    <th>Mt Facturé par superviseur</th>
                                    <th>Nbre cmdo Jour</th>
                                    <th>Nbre cmdo Nuit</th>
                                    <th>Mt Facturé par cmdo</th>
                                    <th>Mt HT</th>
                                    <th>Mt TTC</th>
                                    <th>Mt Payé</th>
                                    <th>Mt Solde</th>
                                    <th>Soldé ?</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($factures as $facture)
                                    <tr>
                                        <td>{{ $facture->noms.' '.$facture->prenoms }}</td>
                                        <td>{{ $facture->ClDesign }}</td>
                                        <td>{{ $facture->FcNumFacture }}</td>

                                        @if($facture->FcDate != null)
                                            <td>{{ date('d/m/Y',strtotime($facture->FcDate)) }}</td>
                                        @else
                                            <td></td>
                                        @endif

                                        <td>{{ afficherMoisEnLettre($facture->FcMois) }}</td>
                                        <td>{{ $facture->FcAnnee }}</td>

                                        @if($facture->FcDateDebut != null)
                                            <td>{{ date('d/m/Y',strtotime($facture->FcDateDebut)) }}</td>
                                        @else
                                            <td></td>
                                        @endif

                                        @if($facture->FcDateFin != null)
                                            <td>{{ date('d/m/Y',strtotime($facture->FcDateFin)) }}</td>
                                        @else
                                            <td></td>
                                        @endif

                                        <td>{{ $facture->FcNbVigileJour }}</td>
                                        <td>{{ $facture->FcNbVigileNuit }}</td>
                                        <td>{{ currency_xaf($facture->FcPrixUVigile) }}</td>
                                        <td>{{ $facture->FcNbreMCJour }}</td>
                                        <td>{{ $facture->FcNbreMCNuit }}</td>
                                        <td>{{ currency_xaf($facture->FcPrixUVigile) }}</td>
                                        <td>{{ $facture->FcNbSupervJr }}</td>
                                        <td>{{ $facture->FcNbSupervNu }}</td>
                                        <td>{{ currency_xaf($facture->FcPrixUSuperv) }}</td>
                                        <td>{{ $facture->FcNbComdoJr }}</td>
                                        <td>{{ $facture->FcNbComdoNu }}</td>
                                        <td>{{ currency_xaf($facture->FcPrixUComm) }}</td>
                                        <td>{{ currency_xaf($facture->FcMontantHT) }}</td>
                                        <td>{{ currency_xaf($facture->FcMontantTTC) }}</td>
                                        <td>{{ currency_xaf($facture->FcTotalPaye) }}</td>
                                        <td>{{ currency_xaf($facture->FcMtSolde) }}</td>

                                        @if($facture->FcIsSolde == 'Y')
                                            <td><span class="badge light badge-success">Soldé</span></td>
                                        @elseif($facture->FcIsSolde == 'N')
                                            <td><span class="badge light badge-danger">Non Soldé</span></td>
                                        @else
                                            <td></td>
                                        @endif

                                        <td style="text-align:center;">
                                            <a onclick="showItem('{{ $facture->FcNumero }}')" class="info p-0" id="view">
                                                <i class="ft-eye font-medium-3 mr-2"></i>
                                            </a>

                                            <a href="#" onclick="editItem('{{$facture->FcNumero}}')" class="warning p-0" id="editor">
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

<!-- CREATE -->
<div class="modal fade text-left" id="create" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">

            <form method="POST" action="{{ route('factures-clients.store') }}" class="needs-validation" novalidate>
                @csrf

                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">CRÉATION D'UNE FACTURE CLIENT</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcClient">Client <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e3 form-control" id="FcClient" name="FcClient" required>
                                    <option selected value="">Selectionner...</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->ClNumero }}">{{ $client->ClDesign }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="">Ville siège</label>
                                <input type="text" class="form-control" id="ViNom" readonly/>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNumFacture">Numéro de la facture <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="form-control" id="FcNumFacture" name="FcNumFacture" value="{{ old('FcNumFacture') }}" placeholder="Numéro de la facture" required maxlength="20"/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNumFacture">Mois ou Période <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="modeMois" name="FcIsMoisPeriode" class="custom-control-input" value="M" checked/>
                                        <label class="custom-control-label" for="modeMois">Mois</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="modePeriode" name="FcIsMoisPeriode" class="custom-control-input" value="P"/>
                                        <label class="custom-control-label" for="modePeriode">Période</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="afficher_info_client_contrat"></div>

                    <div class="row">
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcDate">Date facture client <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="date" class="form-control" id="FcDate" name="FcDate" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3" id="moisBlock">
                            <div class="form-group">
                                <label for="FcMois">Mois facture client <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="form-control" id="FcMois" name="FcMois" required>
                                    <option selected value="">Selectionner...</option>
                                    @php
                                        $moisActuel = date('n');
                                        $anneeActuelle = date('Y');
                                    @endphp
                                    @foreach(range(1, 12) as $mois)
                                        @php
                                            $nomMois = date('F', mktime(0, 0, 0, $mois, 10));
                                            $estAncien = ($anneeActuelle == $anneeActuelle && $mois < $moisActuel - 3);
                                            $estFutur = ($anneeActuelle > $anneeActuelle || ($anneeActuelle == $anneeActuelle && $mois > $moisActuel));
                                        @endphp
                                        <option value="{{ $mois }}"
                                            data-ancien="{{ $estAncien ? 'true' : 'false' }}"
                                            data-futur="{{ $estFutur ? 'true' : 'false' }}"
                                            {{ $estFutur ? 'disabled' : '' }}>
                                            {{ ucfirst($nomMois) }}
                                            @if($estAncien)
                                                (Plus de 3 mois)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3" id="anneeBlock">
                            <div class="form-group">
                                <label for="FcAnnee">Année facture client <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="form-control" id="FcAnnee" name="FcAnnee" required>
                                    <option selected value="">Selectionner...</option>
                                    @for($i = date('Y') - 1; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcDateDebut">Date début facture client <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="date" class="form-control" id="FcDateDebut" name="FcDateDebut"
                                    value="{{ old('FcDateDebut', date('Y-m-01')) }}" required readonly/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcDateFin">Date fin facture client <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="date" class="form-control" id="FcDateFin" name="FcDateFin"
                                    value="{{ old('FcDateFin', date('Y-m-t')) }}" required readonly/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNbVigileJour">Nbre de vigiles Jour <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control" id="FcNbVigileJour" name="FcNbVigileJour" placeholder="Nbre de vigiles Jour" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNbVigileNuit">Nbre de vigiles Nuit <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control" id="FcNbVigileNuit" name="FcNbVigileNuit" placeholder="Nbre de vigiles Nuit" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcPrixUVigile">Montant par Vigile <abbr style="color: #bd4147;">(*)</abbr><small class="text-muted">(Minimum: {{ currency_xaf($entite->EnPrixUMinAgentFixe) }})</small></label>
                                <input type="text" class="number form-control" id="FcPrixUVigile" name="FcPrixUVigile" placeholder="Montant par vigile" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNbreMCJour">Nbre maîtres chien jour <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control" id="FcNbreMCJour" name="FcNbreMCJour" placeholder="Nbre maîtres chien jour" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNbreMCNuit">Nbre maîtres chien nuit <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control" id="FcNbreMCNuit" name="FcNbreMCNuit" placeholder="Nbre maîtres chien nuit" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcPrixUMC">Montant par maître chien <abbr style="color: #bd4147;">(*)</abbr><small class="text-muted">(Minimum: {{ currency_xaf($entite->EnPrixUMinMC) }})</small></label>
                                <input type="text" class="number form-control" id="FcPrixUMC" name="FcPrixUMC" placeholder="Montant par maître chien" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNbSupervJr">Nbre superviseurs jour <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control" id="FcNbSupervJr" name="FcNbSupervJr" placeholder="Nbre superviseurs jour" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNbSupervNu">Nbre superviseurs nuit <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control" id="FcNbSupervNu" name="FcNbSupervNu" placeholder="Nbre superviseurs nuit" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcPrixUSuperv">Montant par superviseur <abbr style="color: #bd4147;">(*)</abbr><small class="text-muted">(Minimum: {{ currency_xaf($entite->EnPrixUMinSuperv) }})</small></label>
                                <input type="text" class="number form-control" id="FcPrixUSuperv" name="FcPrixUSuperv" placeholder="Montant par superviseur" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNbComdoJr">Nbre commandos jour <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control" id="FcNbComdoJr" name="FcNbComdoJr" placeholder="Nbre commandos jour" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNbComdoNu">Nbre commandos nuit <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control" id="FcNbComdoNu" name="FcNbComdoNu" placeholder="Nbre commandos nuit" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcPrixUComm">Montant par commodo <abbr style="color: #bd4147;">(*)</abbr><small class="text-muted">(Minimum: {{ currency_xaf($entite->EnPrixUMinComdo) }})</small></label>
                                <input type="text" class="number form-control" id="FcPrixUComm" name="FcPrixUComm" placeholder="Montant par commodo" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNbJourFact">Nbre de Jours Facturés <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control" id="FcNbJourFact" name="FcNbJourFact" value="{{ old('FcNbJourFact') }}" placeholder="Nbre de Jours Facturés" required readonly/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNbNuitFact">Nbre de Nuits Facturés <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control" id="FcNbNuitFact" name="FcNbNuitFact" value="{{ old('FcNbNuitFact') }}" placeholder="Nbre de Nuits Facturés" required readonly/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <!-- Zone Montant HT (affichée conditionnellement) -->
                        <div class="col-xl-3 col-md-3" id="montantHTBlock" style="{{ $client->ClIsTVAOn ? '' : 'display: none;' }}">
                            <div class="form-group">
                                <label for="FcMontantHT">Montant HT facture <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="form-control montant-field" id="FcMontantHT" name="FcMontantHT" value="{{ old('FcMontantHT') }}" placeholder="Montant HT facture" readonly/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <!-- Zone Montant TTC (toujours affichée) -->
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcMontantTTC">Montant TTC facture <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="form-control montant-field" id="FcMontantTTC" name="FcMontantTTC" value="{{ old('FcMontantTTC') }}" placeholder="Montant TTC facture" required readonly/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <!-- Champ Montant Payé masqué -->
                        <input type="hidden" id="FcTotalPaye" name="FcTotalPaye" value="0"/>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcMtSolde">Montant Solde facture</label>
                                <input type="text" class="form-control montant-field" id="FcMtSoldeXAF" placeholder="Montant Solde facture client" readonly/>
                                <input type="hidden" class="form-control" id="FcMtSolde" name="FcMtSolde" placeholder="Montant Solde facture"/>
                            </div>
                        </div>

                        <!-- Variables cachées pour le statut TVA -->
                        <input type="hidden" id="ClIsTVAOn">
                        <input type="hidden" id="ClTxTVA" value="{{ $entite->EnTxTVA }}">
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <label for="FcDetail">Détails</label>
                                <textarea rows="2" class="form-control" id="FcDetail" name="FcDetail" placeholder="Commentaires"></textarea>
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
<!-- CREATE -->

<!-- MODIFIER -->
<div class="modal fade text-left" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">

            <form method="POST" action="#" class="form-edit needs-validation" novalidate>
                @csrf
                <input type="hidden" name="_method" value="PUT">

                <div class="modal-header bg-info white">
                    <h5 class="modal-title" id="myModalLabel16">MODIFICATION D'UNE FACTURE CLIENT</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcClient">Client <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e4 form-control FcClient" id="FcClient" name="FcClient" required>
                                    <option selected value="">Selectionner...</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->ClNumero }}">{{ $client->ClDesign }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="">Ville siège</label>
                                <input type="text" class="form-control" id="ViNom" readonly/>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNumFacture">Numéro de la facture <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="form-control FcNumFacture" id="FcNumFacture" name="FcNumFacture" placeholder="Numéro de la facture" required maxlength="20"/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcDate">Date facture client <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="date" class="form-control FcDate" id="FcDate" name="FcDate" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="afficher_info_client_contrat_edit"></div>

                    <div class="row">
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcMois">Mois facture client <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="form-control FcMois" id="FcMois" name="FcMois" required>
                                    <option value="">Selectionner...</option>
                                    @foreach(range(1, 12) as $mois)
                                        <option value="{{ $mois }}">{{ date('F', mktime(0, 0, 0, $mois, 10)) }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcAnnee">Année facture client <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="form-control FcAnnee" id="FcAnnee" name="FcAnnee" required>
                                    <option value="">Selectionner...</option>
                                    @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcDateDebut">Date début facture client <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="date" class="form-control FcDateDebut" id="FcDateDebut" name="FcDateDebut" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcDateFin">Date fin facture client <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="date" class="form-control FcDateFin" id="FcDateFin" name="FcDateFin" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNbVigileJour">Nbre de vigiles Jour <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control FcNbVigileJour" id="FcNbVigileJour" name="FcNbVigileJour" placeholder="Nbre de vigiles Jour" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNbVigileNuit">Nbre de vigiles Nuit <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control FcNbVigileNuit" id="FcNbVigileNuit" name="FcNbVigileNuit" placeholder="Nbre de vigiles Nuit" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcPrixUVigile">Mt Facturé par vigile <abbr style="color: #bd4147;">(*)</abbr><small class="text-muted">(Minimum: {{ currency_xaf($entite->EnPrixUMinAgentFixe) }})</small></label>
                                <input type="text" class="number form-control FcPrixUVigile" id="FcPrixUVigile" name="FcPrixUVigile" placeholder="Montant Facturé par vigile" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNbreMCJour">Nbre maîtres chien jour <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control FcNbreMCJour" id="FcNbreMCJour" name="FcNbreMCJour" placeholder="Nbre maîtres chien jour" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNbreMCNuit">Nbre maîtres chien nuit <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control" id="FcNbreMCNuit FcNbreMCNuit" name="FcNbreMCNuit" placeholder="Nbre maîtres chien nuit" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcPrixUMC">Montant par maître chien <abbr style="color: #bd4147;">(*)</abbr><small class="text-muted">(Minimum: {{ currency_xaf($entite->EnPrixUMinMC) }})</small></label>
                                <input type="text" class="number form-control FcPrixUMC" id="FcPrixUMC" name="FcPrixUMC" placeholder="Montant par maître chien" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNbSupervJr">Nbre superviseurs jour <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control FcNbSupervJr" id="FcNbSupervJr" name="FcNbSupervJr" placeholder="Nbre superviseurs jour" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNbSupervNu">Nbre superviseurs nuit <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control FcNbSupervNu" id="FcNbSupervNu" name="FcNbSupervNu" placeholder="Nbre superviseurs nuit" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcPrixUSuperv">Montant par superviseur <abbr style="color: #bd4147;">(*)</abbr><small class="text-muted">(Minimum: {{ currency_xaf($entite->EnPrixUMinSuperv) }})</small></label>
                                <input type="text" class="number form-control FcPrixUSuperv" id="FcPrixUSuperv" name="FcPrixUSuperv" placeholder="Montant par superviseur" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNbComdoJr">Nbre commandos jour <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control FcNbComdoJr" id="FcNbComdoJr" name="FcNbComdoJr" placeholder="Nbre commandos jour" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNbComdoNu">Nbre commandos nuit <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control FcNbComdoNu" id="FcNbComdoNu" name="FcNbComdoNu" placeholder="Nbre commandos nuit" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcPrixUComm">Montant par commodo <abbr style="color: #bd4147;">(*)</abbr><small class="text-muted">(Minimum: {{ currency_xaf($entite->EnPrixUMinComdo) }})</small></label>
                                <input type="text" class="number form-control FcPrixUComm" id="FcPrixUComm" name="FcPrixUComm" placeholder="Montant par commodo" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNbJourFact">Nbre de Jours Facturés <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control FcNbJourFact" id="FcNbJourFact" name="FcNbJourFact" placeholder="Nbre de Jours Facturés" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcNbNuitFact">Nbre de Nuits Facturés <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control FcNbNuitFact" id="FcNbNuitFact" name="FcNbNuitFact" placeholder="Nbre de Nuits Facturés" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcMontantHT">Montant HT facture <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control FcMontantHT" id="FcMontantHT" name="FcMontantHT" placeholder="Montant HT facture" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="FcMontantTTC">Montant TTC facture <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" class="number form-control FcMontantTTC" id="FcMontantTTC" name="FcMontantTTC" placeholder="Montant TTC facture" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="FcTotalPaye">Montant Payé facture</label>
                                <input type="text" class="number form-control FcTotalPaye" id="FcTotalPaye" name="FcTotalPaye" placeholder="Montant Payé facture"/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="FcMtSolde">Montant Solde facture</label>
                                <input type="text" class="form-control FcMtSoldeXAF" id="FcMtSoldeXAF" placeholder="Montant Solde facture client" readonly/>
                                <input type="hidden" class="form-control FcMtSolde" id="FcMtSolde" name="FcMtSolde"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <label for="FcDetail">Détails</label>
                                <textarea rows="2" class="form-control FcDetail" id="FcDetail" name="FcDetail" placeholder="Commentaires"></textarea>
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
                <h5 class="modal-title" id="myModalLabel16">INFORMATIONS FACTURE CLIENT</h5>
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
                                        <th scope="row">Client</th>
                                        <td id="client_facture"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Num Facture</th>
                                        <td id="num_facture"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Date Facture</th>
                                        <td id="date_facture"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Mois</th>
                                        <td id="mois_facture"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Année</th>
                                        <td id="annee_facture"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Date début</th>
                                        <td id="date_debut"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Date fin</th>
                                        <td id="date_fin"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Nbre de vigiles Jour</th>
                                        <td id="nbre_vigile_jour"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Nbre de vigiles Nuit</th>
                                        <td id="nbre_vigile_nuit"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Montant Facturé par Vigile</th>
                                        <td id="mt_facture_vigile"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Nbre de maîtres chien Jour</th>
                                        <td id="nbre_mc_jour"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Nbre de maîtres chien Nuit</th>
                                        <td id="nbre_mc_nuit"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Montant Facturé par maître chien</th>
                                        <td id="mt_facture_mc"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Nbre de superviseurs Jour</th>
                                        <td id="nbre_sup_jour"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Nbre de superviseurs Nuit</th>
                                        <td id="nbre_sup_nuit"></td>
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
                                        <th scope="row">Montant Facturé par superviseur</th>
                                        <td id="mt_facture_sup"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Nbre de cmdo Jour</th>
                                        <td id="nbre_comdo_jour"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Nbre de cmdo Nuit</th>
                                        <td id="nbre_comdo_nuit"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Montant Facturé par cmdo</th>
                                        <td id="mt_facture_comdo"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Nbre de Jours Facturés</th>
                                        <td id="nbre_jour_facture"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Nbre de Nuits Facturés</th>
                                        <td id="nbre_nuit_facture"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Mt HT</th>
                                        <td id="mt_ht"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Mt TTC</th>
                                        <td id="mt_ttc"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Mt Payé</th>
                                        <td id="mt_paye"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Mt Solde</th>
                                        <td id="mt_solde"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Statut</th>
                                        <td id="statut"></td>
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

    <script type="text/javascript">
        function remove_space(val){
            val=val.split(" ");
            val=val.join("");
            return val;
        }
        function is_number(char){
            var array=new Array("0","1","2","3","4","5","6","7","8","9",".",",","-","+");
            var trouve=false;
            var k=0;
            while (k<13 && trouve==false){
                if(char==array[k])
                trouve=true;
                k++;
            }
            return trouve;
        }

        function Check_number(nombre){
            trouve=false;
            i=0;
            if(nombre.length!=0){
                trouve=true;
                while(i<nombre.length && trouve==true){
                if(!is_number(nombre[i]))
                    trouve=false;
                i++;
                }
            }
            return trouve;
        }

        function reverseString(str){
            str = str.split(""); //convert 'jQuery' to array
            str = str.reverse(); //reverse 'jQuery' order
            str = str.join(""); //then join the reverse order values together
            return str;
        }

        function montant_xaf(str){
            str=str.toString();
            str=reverseString(str);
            var size=str.length;
            montant="";
            j=0;
            if(size!=0){
                for(i=0;i<size;i++){
                    j++;
                    montant+=str[i];
                    if(j<size && (i+1)%3==0){
                        montant+=" ";
                    }
                }
                    montant=reverseString(montant);
                }
            return montant;
        }
    </script>

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
            $("#create,#edit,#xlarge").modal({
                show:false,
                backdrop:'static'
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $(".e1,.e2").select2();

            $(".e3").select2({
                width: '100%',
                dropdownParent: $("#create")
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            function mettreAJourChamps() {
                // Récupérer les valeurs depuis les champs cachés (avec préfixe "cache_")
                var ViNom = $("#cache_ViNom").val();
                var ClIsTVAOn = $("#cache_ClIsTVAOn").val();

                var FcDateDebut = $("#cache_FcDateDebut").val();
                var FcPrixUVigile = $("#cache_FcPrixUVigile").val();
                var FcNbVigileJour = $("#cache_FcNbVigileJour").val();
                var FcNbVigileNuit = $("#cache_FcNbVigileNuit").val();
                var FcPrixUMC = $("#cache_FcPrixUMC").val();
                var FcNbreMCJour = $("#cache_FcNbreMCJour").val();
                var FcNbreMCNuit = $("#cache_FcNbreMCNuit").val();
                var FcPrixUSuperv = $("#cache_FcPrixUSuperv").val();
                var FcNbSupervJr = $("#cache_FcNbSupervJr").val();
                var FcNbSupervNu = $("#cache_FcNbSupervNu").val();
                var FcPrixUComm = $("#cache_FcPrixUComm").val();
                var FcNbComdoJr = $("#cache_FcNbComdoJr").val();
                var FcNbComdoNu = $("#cache_FcNbComdoNu").val();

                // Mettre à jour les champs du formulaire (sans préfixe)
                $("#ViNom").val(ViNom);
                $("#ClIsTVAOn").val(ClIsTVAOn);

                $("#FcDateDebut").val(FcDateDebut);
                $("#FcPrixUVigile").val(FcPrixUVigile);
                $("#FcNbVigileJour").val(FcNbVigileJour);
                $("#FcNbVigileNuit").val(FcNbVigileNuit);
                $("#FcPrixUMC").val(FcPrixUMC);
                $("#FcNbreMCJour").val(FcNbreMCJour);
                $("#FcNbreMCNuit").val(FcNbreMCNuit);
                $("#FcPrixUSuperv").val(FcPrixUSuperv);
                $("#FcNbSupervJr").val(FcNbSupervJr);
                $("#FcNbSupervNu").val(FcNbSupervNu);
                $("#FcPrixUComm").val(FcPrixUComm);
                $("#FcNbComdoJr").val(FcNbComdoJr);
                $("#FcNbComdoNu").val(FcNbComdoNu);
            }

            function chargerClient(FcClient) {
                if (!FcClient) return;

                $.get('{{ route("facture-client.show_info_client_contrat","") }}/' + FcClient, function(reponse) {
                    $("#afficher_info_client_contrat").html(reponse);
                    mettreAJourChamps();
                });
            }

            $("#FcClient").change(function() {
                chargerClient($(this).val());
            });

            if ($("#FcClient").val()) {
                chargerClient($("#FcClient").val());
            }
        });
    </script>

    <script type="text/javascript">
        function showItem(FcNumero) {
            $.ajax({
                url: '{{ route("facture-client.show", "") }}/' + FcNumero,
                type: 'GET',
                success: function(data){
                    $('#client_facture').html(data.ClDesign);
                    $('#num_facture').html(data.FcNumFacture);
                    $('#date_facture').html(data.FcDate);
                    $('#mois_facture').html(data.FcMois);
                    $('#annee_facture').html(data.FcAnnee);
                    $('#date_debut').html(data.FcDateDebut);
                    $('#date_fin').html(data.FcDateFin);
                    $('#nbre_vigile_jour').html(data.FcNbVigileJour);
                    $('#nbre_vigile_nuit').html(data.FcNbVigileNuit);
                    $('#mt_facture_vigile').html(data.FcPrixUVigile);
                    $('#nbre_mc_jour').html(data.FcNbreMCJour);
                    $('#nbre_mc_nuit').html(data.FcNbreMCNuit);
                    $('#mt_facture_mc').html(data.FcPrixUMC);
                    $('#nbre_sup_jour').html(data.FcNbSupervJr);
                    $('#nbre_sup_nuit').html(data.FcNbSupervNu);
                    $('#mt_facture_sup').html(data.FcPrixUSuperv);
                    $('#nbre_comdo_jour').html(data.FcNbreComdoJour);
                    $('#nbre_comdo_nuit').html(data.FcNbreComdoNuit);
                    $('#mt_facture_comdo').html(data.FcPrixUComdo);
                    $('#nbre_jour_facture').html(data.FcNbJourFact);
                    $('#nbre_nuit_facture').html(data.FcNbNuitFact);
                    $('#mt_ht').html(data.FcMontantHT);
                    $('#mt_ttc').html(data.FcMontantTTC);
                    $('#mt_paye').html(data.FcTotalPaye);
                    $('#mt_solde').html(data.FcMtSolde);

                    if((data.FcIsSolde) == 'Y')
                    {
                        $('#statut').text('Soldé');
                    }
                    else if((data.FcIsSolde) == 'N')
                    {
                        $('#statut').text('Non Soldé');
                    }
                    else
                    {
                        $('#statut').text('');
                    }

                    $('#xlarge').modal('show').fadeIn('slow');
                },
                error: function(){
                    console.log("Something goes wrong");
                }
            })
        }
    </script>

    <script type="text/javascript">
        function editItem(FcNumero) {
            $.ajax({
                url: '{{ route("facture-client.edit", "") }}/' + FcNumero,
                type: 'GET',
                success: function(data) {
                    // Récupérer les données de la facture
                    var facture = data.facture || data;

                    // Mettre à jour l'action du formulaire
                    $(".form-edit").attr('action', '{{ route("facture-client.update", "") }}/' + facture.FcNumero);

                    // Remplir les champs du formulaire
                    $("#FcClientDisplay").val(facture.client_nom || facture.ClDesign);
                    $(".FcClient").val(facture.FcClient);
                    $(".FcNumFacture").val(facture.FcNumFacture);
                    $(".FcDate").val(facture.FcDate);
                    $(".FcMois").val(facture.FcMois);
                    $(".FcAnnee").val(facture.FcAnnee);
                    $(".FcDateDebut").val(facture.FcDateDebut);
                    $(".FcDateFin").val(facture.FcDateFin);
                    $(".FcNbVigileJour").val(facture.FcNbVigileJour);
                    $(".FcNbVigileNuit").val(facture.FcNbVigileNuit);
                    $(".FcPrixUVigile").val(facture.FcPrixUVigile);
                    $('.FcNbreMCJour').val(data.FcNbreMCJour);
                    $('.FcNbreMCNuit').val(data.FcNbreMCNuit);
                    $('.FcPrixUMC').val(data.FcPrixUMC);
                    $('.FcNbSupervJr').val(data.FcNbSupervJr);
                    $('.FcNbSupervNu').val(data.FcNbSupervNu);
                    $('.FcPrixUSuperv').val(data.FcPrixUSuperv);
                    $(".FcNbreComdoJour").val(data.FcNbreComdoJour);
                    $(".FcNbreComdoNuit").val(data.FcNbreComdoNuit);
                    $(".FcPrixUComdo").val(data.FcPrixUComdo);
                    $(".FcNbJourFact").val(facture.FcNbJourFact);
                    $(".FcNbNuitFact").val(facture.FcNbNuitFact);
                    $(".FcMontantHT").val(facture.FcMontantHT);
                    $(".FcMontantTTC").val(facture.FcMontantTTC);
                    $(".FcTotalPaye").val(facture.FcTotalPaye);
                    $(".FcMtSolde").val(facture.FcMtSolde);

                    // Formater le solde avec séparateur de milliers
                    var soldeFormate = facture.FcMtSolde.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
                    $(".FcMtSoldeXAF").val(soldeFormate + ' FCFA');

                    $(".FcDetail").val(facture.FcDetail);


                    function mettreAJourChamps() {
                        // Récupérer les valeurs depuis les champs cachés (avec préfixe "cache_")
                        var ViNom = $(".cache_ViNom").val();
                        var ClIsTVAOn = $(".cache_ClIsTVAOn").val();

                        var FcDateDebut = $(".cache_FcDateDebut").val();
                        var FcPrixUVigile = $(".cache_FcPrixUVigile").val();
                        var FcNbVigileJour = $(".cache_FcNbVigileJour").val();
                        var FcNbVigileNuit = $(".cache_FcNbVigileNuit").val();
                        var FcPrixUMC = $(".cache_FcPrixUMC").val();
                        var FcNbreMCJour = $(".cache_FcNbreMCJour").val();
                        var FcNbreMCNuit = $(".cache_FcNbreMCNuit").val();
                        var FcPrixUSuperv = $(".cache_FcPrixUSuperv").val();
                        var FcNbSupervJr = $(".cache_FcNbSupervJr").val();
                        var FcNbSupervNu = $(".cache_FcNbSupervNu").val();
                        var FcPrixUComm = $(".cache_FcPrixUComm").val();
                        var FcNbComdoJr = $(".cache_FcNbComdoJr").val();
                        var FcNbComdoNu = $(".cache_FcNbComdoNu").val();

                        // Mettre à jour les champs du formulaire (sans préfixe)
                        $(".ViNom").val(ViNom);
                        $(".ClIsTVAOn").val(ClIsTVAOn);

                        $(".FcDateDebut").val(FcDateDebut);
                        $(".FcPrixUVigile").val(FcPrixUVigile);
                        $(".FcNbVigileJour").val(FcNbVigileJour);
                        $(".FcNbVigileNuit").val(FcNbVigileNuit);
                        $(".FcPrixUMC").val(FcPrixUMC);
                        $(".FcNbreMCJour").val(FcNbreMCJour);
                        $(".FcNbreMCNuit").val(FcNbreMCNuit);
                        $(".FcPrixUSuperv").val(FcPrixUSuperv);
                        $(".FcNbSupervJr").val(FcNbSupervJr);
                        $(".FcNbSupervNu").val(FcNbSupervNu);
                        $(".FcPrixUComm").val(FcPrixUComm);
                        $(".FcNbComdoJr").val(FcNbComdoJr);
                        $(".FcNbComdoNu").val(FcNbComdoNu);
                    }

                    function chargerClient(FcClient) {
                        if (!FcClient) return;

                        $.get('{{ route("facture-client.show_info_client_contrat_edit","") }}/' + FcClient, function(reponse) {
                            $("#afficher_info_client_contrat_edit").html(reponse);
                            mettreAJourChamps();
                        });
                    }

                    $(".FcClient").change(function() {
                        chargerClient($(this).val());
                    });

                    if ($(".FcClient").val()) {
                        chargerClient($(".FcClient").val());
                    }

                    $(".e4").select2({
                        width: '100%',
                    });

                    // Afficher le modal
                    $('#edit').modal('show').fadeIn('slow');
                },
                error: function(xhr, status, error) {
                    console.log("Erreur lors du chargement des données");
                    console.log(xhr.responseText);
                    alert("Impossible de charger les données de la facture");
                }
            });
        }
    </script>

    <script>
        // Fonction pour formater avec séparateur de milliers
        function formatMontant(valeur) {
            return valeur.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
        }

        // Attendre que le DOM soit chargé (version vanilla)
        document.addEventListener('DOMContentLoaded', function() {
            // ÉLÉMENTS POUR LA GESTION DU MOIS/ANNÉE
            const fcDateInput = document.getElementById('FcDate');
            const fcMoisSelect = document.getElementById('FcMois');
            const fcAnneeSelect = document.getElementById('FcAnnee');

            // Fonction pour obtenir le nom du mois en français
            function getMoisFrancais(moisIndex) {
                const mois = [
                    'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                    'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
                ];
                return mois[moisIndex];
            }

            // Fonction pour générer les options des années (3 dernières années)
            function genererOptionsAnnees(dateSelectionnee) {
                const date = new Date(dateSelectionnee);
                const anneeSelectionnee = date.getFullYear();

                // Vider le select
                fcAnneeSelect.innerHTML = '<option selected value="">Selectionner...</option>';

                // Générer les 3 dernières années (année courante et 2 précédentes)
                for (let i = 0; i < 3; i++) {
                    let annee = anneeSelectionnee - i;
                    let option = document.createElement('option');
                    option.value = annee;
                    option.textContent = annee;

                    // Sélectionner l'année courante par défaut
                    if (i === 0) {
                        option.selected = true;
                    }

                    fcAnneeSelect.appendChild(option);
                }
            }

            // Fonction pour générer les options des mois (TOUS les mois de l'année)
            function genererOptionsMois(dateSelectionnee) {
                const date = new Date(dateSelectionnee);
                const annee = date.getFullYear();
                const moisActuel = date.getMonth(); // 0-11
                const anneeActuelle = date.getFullYear();

                // Vider le select
                fcMoisSelect.innerHTML = '<option selected value="">Selectionner...</option>';

                // Générer les options pour TOUS les mois de l'année
                for(let i = 0; i < 12; i++) {
                    let moisValue = i + 1; // 1-12
                    let moisTexte = getMoisFrancais(i);

                    let option = document.createElement('option');
                    option.value = moisValue;
                    option.textContent = moisTexte;

                    // Ajouter des attributs de données pour la validation
                    let estFutur = (annee > anneeActuelle) || (annee === anneeActuelle && i > moisActuel);
                    let estAncien = (annee < anneeActuelle) || (annee === anneeActuelle && i < moisActuel - 3);

                    option.dataset.ancien = estAncien ? 'true' : 'false';
                    option.dataset.futur = estFutur ? 'true' : 'false';

                    // Désactiver les mois futurs
                    if(estFutur) {
                        option.disabled = true;
                    }

                    // Sélectionner le mois de la date choisie par défaut s'il n'est pas futur
                    if(i === moisActuel && !estFutur) {
                        option.selected = true;
                    }

                    fcMoisSelect.appendChild(option);
                }

                // Vérifier si le mois sélectionné est ancien (plus de 3 mois)
                verifierAncienneteMois(date);
            }

            // Fonction pour vérifier si le mois est antérieur à 3 mois
            function verifierAncienneteMois(dateSelectionnee) {
                const dateFacture = new Date(dateSelectionnee);
                const dateLimite = new Date();
                dateLimite.setMonth(dateLimite.getMonth() - 3);

                // Comparer les dates (ignorer l'heure)
                dateFacture.setHours(0, 0, 0, 0);
                dateLimite.setHours(0, 0, 0, 0);

                if(dateFacture < dateLimite) {
                    // Afficher une alerte discrète
                    const alerteDiv = document.createElement('div');
                    alerteDiv.className = 'alert alert-warning alert-dismissible fade show mt-2';
                    alerteDiv.setAttribute('role', 'alert');
                    alerteDiv.innerHTML = `
                        <i class="fas fa-exclamation-triangle"></i>
                        Attention : Cette facture date de plus de 3 mois.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    `;

                    // Supprimer l'alerte précédente si elle existe
                    const ancienneAlerte = document.getElementById('alerteMoisAncien');
                    if(ancienneAlerte) {
                        ancienneAlerte.remove();
                    }

                    alerteDiv.id = 'alerteMoisAncien';

                    // Insérer l'alerte après le select du mois
                    fcMoisSelect.closest('.form-group').appendChild(alerteDiv);

                    // Auto-destruction de l'alerte après 5 secondes
                    setTimeout(() => {
                        if(alerteDiv.parentNode) {
                            alerteDiv.remove();
                        }
                    }, 10000);
                } else {
                    // Supprimer l'alerte si elle existe
                    const ancienneAlerte = document.getElementById('alerteMoisAncien');
                    if (ancienneAlerte) {
                        ancienneAlerte.remove();
                    }
                }
            }

            // Fonction pour mettre à jour les deux selects
            function mettreAJourSelects(dateValue) {
                if(dateValue) {
                    genererOptionsAnnees(dateValue);
                    genererOptionsMois(dateValue);
                }
            }

            // Initialiser avec la date du jour
            if (fcDateInput && fcDateInput.value) {
                mettreAJourSelects(fcDateInput.value);
            }

            // Écouter les changements de date
            if(fcDateInput) {
                fcDateInput.addEventListener('change', function() {
                    if (this.value) {
                        mettreAJourSelects(this.value);
                    }
                });
            }

            // Permettre la modification manuelle du mois (libre)
            if(fcMoisSelect) {
                fcMoisSelect.addEventListener('change', function() {
                    if(fcDateInput && fcDateInput.value && this.value) {
                        const anneeSelect = fcAnneeSelect.value;
                        if(anneeSelect) {
                            const dateMoisSelectionne = new Date(parseInt(anneeSelect), parseInt(this.value) - 1, 1);
                            verifierAncienneteMois(dateMoisSelectionne);
                        }
                    }
                });
            }

            // Permettre la modification manuelle de l'année (libre)
            if(fcAnneeSelect) {
                fcAnneeSelect.addEventListener('change', function() {
                    if(fcDateInput && fcDateInput.value && this.value) {
                        const dateOriginale = new Date(fcDateInput.value);
                        const nouvelleDate = new Date(this.value, dateOriginale.getMonth(), 1);
                        genererOptionsMois(nouvelleDate.toISOString().split('T')[0]);
                    }
                });
            }
        });

        // Attendre que jQuery soit prêt (pour la partie jQuery)
        $(document).ready(function() {
            // Initialisation des variables
            var mode = 'M'; // Mode Mois par défaut
            var dateDebut = $('#FcDateDebut');
            var dateFin = $('#FcDateFin');
            var moisSelect = $('#FcMois');
            var anneeSelect = $('#FcAnnee');
            var nbJourFact = $('#FcNbJourFact');
            var nbNuitFact = $('#FcNbNuitFact');
            var prixUVigile = $('#FcPrixUVigile');
            var nbVigileJour = $('#FcNbVigileJour');
            var nbVigileNuit = $('#FcNbVigileNuit');
            var prixUMC = $('#FcPrixUMC');

            var nbMCJour = $('#FcNbreMCJour');
            var nbMCNuit = $('#FcNbreMCNuit');

            var prixComdo = $('#FcPrixUComdo');
            var nbComdoJour = $('#FcNbreComdoJour');
            var nbComdoNuit = $('#FcNbreComdoNuit');

            var prixSuperv = $('#FcPrixUSuperv');
            var nbSupervJour = $('#FcNbSupervJr');
            var nbSupervNuit = $('#FcNbSupervNu');

            var montantHT = $('#FcMontantHT');
            var montantTTC = $('#FcMontantTTC');
            var mtSolde = $('#FcMtSolde');
            var mtSoldeXAF = $('#FcMtSoldeXAF');
            var montantHTBlock = $('#montantHTBlock');
            var clIsTVAOn = $('#ClIsTVAOn').val() === '1';
            var clTxTVA = parseFloat($('#ClTxTVA').val());

            // Fonction pour calculer le nombre de jours entre deux dates
            function calculerNombreJours(dateDebutVal, dateFinVal) {
                if (!dateDebutVal || !dateFinVal) return 0;
                const debut = new Date(dateDebutVal);
                const fin = new Date(dateFinVal);
                const diffTime = Math.abs(fin - debut);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                return diffDays;
            }

            // Fonction pour obtenir le nombre de jours dans un mois
            function getJoursDansMois(annee, mois) {
                return new Date(annee, mois, 0).getDate();
            }

            // Fonction pour calculer le prix unitaire par jour
            function calculerPrixUnitaireJour() {
                var prixMensuel = parseFloat(prixUVigile.val()) || 0;
                var annee = anneeSelect.val();
                var mois = moisSelect.val();

                if(annee && mois) {
                    var joursDansMois = getJoursDansMois(annee, mois);
                    return prixMensuel / joursDansMois;
                }
                return prixMensuel / 30;
            }

            // Fonction pour calculer le prix unitaire par jour
            function calculerPrixUnitaireJourMC() {
                var prixMensuelMC = parseFloat(prixUMC.val()) || 0;
                var annee = anneeSelect.val();
                var mois = moisSelect.val();

                if(annee && mois) {
                    var joursDansMois = getJoursDansMois(annee, mois);
                    return prixMensuelMC / joursDansMois;
                }
                return prixMensuelMC / 30;
            }

            // Fonction pour calculer le prix unitaire par jour
            function calculerPrixUnitaireJourSuperv() {
                var prixMensuelSuperv = parseFloat(prixSuperv.val()) || 0;
                var annee = anneeSelect.val();
                var mois = moisSelect.val();

                if(annee && mois) {
                    var joursDansMois = getJoursDansMois(annee, mois);
                    return prixMensuelSuperv / joursDansMois;
                }
                return prixMensuelSuperv / 30;
            }

            // Fonction pour calculer le prix unitaire par jour pour les cmdos
            function calculerPrixUnitaireJourComdo() {
                var prixMensuelComdo = parseFloat(prixComdo.val()) || 0;
                var annee = anneeSelect.val();
                var mois = moisSelect.val();

                if(annee && mois) {
                    var joursDansMois = getJoursDansMois(annee, mois);
                    return prixMensuelComdo / joursDansMois;
                }
                return prixMensuelComdo / 30;
            }

            // Fonction pour calculer tous les montants (avec Math.round direct)
            function calculerMontants() {
                var nbVigilesJour = parseFloat(nbVigileJour.val()) || 0;
                var nbVigilesNuit = parseFloat(nbVigileNuit.val()) || 0;

                var nbMCsJour = parseFloat(nbMCJour.val()) || 0;
                var nbMCsNuit = parseFloat(nbMCNuit.val()) || 0;

                var nbSupervsJour = parseFloat(nbSupervJour.val()) || 0;
                var nbSupervsNuit = parseFloat(nbSupervNuit.val()) || 0;

                var nbComdosJour = parseFloat(nbComdoJour.val()) || 0;
                var nbComdosNuit = parseFloat(nbComdoNuit.val()) || 0;

                var nbJours = parseFloat(nbJourFact.val()) || 0;
                var nbNuits = parseFloat(nbNuitFact.val()) || 0;

                var montantCalculHT = 0;

                if (mode === 'M') {
                    // Mode Mois : forfait mensuel complet
                    var prixMensuel = parseFloat(prixUVigile.val()) || 0;
                    var prixMensuelMC = parseFloat(prixUMC.val()) || 0;
                    var prixMensuelSuperv = parseFloat(prixSuperv.val()) || 0;
                    var prixMensuelComdo = parseFloat(prixComdo.val()) || 0;
                    montantCalculHT = ((prixMensuel * (nbVigilesJour + nbVigilesNuit)) + (prixMensuelMC * (nbMCsJour + nbMCsNuit)) + (prixMensuelSuperv * (nbSupervsJour + nbSupervsNuit)) + (prixMensuelComdo * (nbComdosJour + nbComdosNuit)));
                } else {
                    // Mode Période : calcul au prorata
                    var prixParJour = calculerPrixUnitaireJour();
                    var prixParJourMC = calculerPrixUnitaireJourMC();
                    var prixParJourSuperv = calculerPrixUnitaireJourSuperv();
                    var prixParJourComdo = calculerPrixUnitaireJourComdo();

                    montantCalculHT = ((prixParJour * nbJours * nbVigilesJour) + (prixParJour * nbNuits * nbVigilesNuit) + (prixParJourMC * nbJours * nbMCsJour) + (prixParJourMC * nbNuits * nbMCsNuit) + (prixParJourSuperv * nbJours * nbSupervsJour) + (prixParJourSuperv * nbNuits * nbSupervsNuit) + (prixParJourComdo * nbJours * nbComdosJour) + (prixParJourComdo * nbNuits * nbComdosNuit));

                }

                // Arrondir le montant HT
                montantCalculHT = Math.round(montantCalculHT);

                // Mettre à jour le champ HT si le client est soumis à TVA
                if (clIsTVAOn) {
                    montantHT.val(formatMontant(montantCalculHT));
                    montantHTBlock.show();
                } else {
                    montantHT.val(formatMontant(montantCalculHT));
                    montantHTBlock.show();
                }

                // Calcul du TTC
                var ttc = montantCalculHT;
                if (clIsTVAOn) {
                    ttc = montantCalculHT * (1 + clTxTVA / 100);
                }
                ttc = Math.round(ttc);

                // Mettre à jour le champ TTC (toujours affiché)
                montantTTC.val(formatMontant(ttc));

                // Calcul du solde (toujours égal au TTC car TotalPaye = 0)
                mtSolde.val(ttc);
                mtSoldeXAF.val(formatMontant(ttc) + ' FCFA');
            }

            // Fonction pour mettre à jour les dates selon le mois et année sélectionnés
            function updateDatesFromMonth() {
                var mois = moisSelect.val();
                var annee = anneeSelect.val();

                if(mois && annee) {
                    // Premier jour du mois
                    var premierJour = annee + '-' + (mois.padStart(2, '0')) + '-01';

                    // Dernier jour du mois
                    var dernierJour = new Date(annee, mois, 0);
                    var dernierJourFormatted = annee + '-' + (mois.padStart(2, '0')) + '-' +
                        (dernierJour.getDate().toString().padStart(2, '0'));

                    dateDebut.val(premierJour);
                    dateFin.val(dernierJourFormatted);

                    // Mettre à jour le nombre de jours
                    var nbJours = calculerNombreJours(premierJour, dernierJourFormatted);
                    nbJourFact.val(nbJours);
                    nbNuitFact.val(nbJours);

                    // Vérifier si le mois est ancien (plus de 3 mois)
                    var selectedOption = moisSelect.find('option:selected');
                    if(selectedOption.data('ancien') === 'true') {
                        alert('Attention : Ce mois date de plus de 3 mois. Voulez-vous continuer ?');
                    }

                    // Recalculer les montants
                    calculerMontants();
                }
            }

            // Fonction pour mettre à jour les calculs en mode Période
            function updateFromPeriode() {
                var dateDebutVal = dateDebut.val();
                var dateFinVal = dateFin.val();

                if (dateDebutVal && dateFinVal) {
                    var nbJours = calculerNombreJours(dateDebutVal, dateFinVal);
                    nbJourFact.val(nbJours);
                    nbNuitFact.val(nbJours);

                    // Vérifier que les dates sont dans le mois sélectionné
                    validateDates();

                    // Recalculer les montants
                    calculerMontants();
                }
            }

            // Gestion du changement de mode
            $('input[name="FcIsMoisPeriode"]').change(function() {
                mode = $(this).val();

                if(mode === 'M') {
                    // Mode Mois
                    moisSelect.closest('.col-xl-3').show();
                    anneeSelect.closest('.col-xl-3').show();
                    dateDebut.prop('readonly', true);
                    dateFin.prop('readonly', true);
                    nbJourFact.prop('readonly', true);
                    nbNuitFact.prop('readonly', true);
                    updateDatesFromMonth();
                } else {
                    // Mode Période
                    moisSelect.closest('.col-xl-3').show();
                    anneeSelect.closest('.col-xl-3').show();
                    dateDebut.prop('readonly', false);
                    dateFin.prop('readonly', false);
                    nbJourFact.prop('readonly', false);
                    nbNuitFact.prop('readonly', false);
                    updateFromPeriode();
                }
            });

            // Validation des dates en mode Période
            function validateDates() {
                if(mode === 'P') {
                    var dateDebutVal = dateDebut.val();
                    var dateFinVal = dateFin.val();
                    var moisSelectionne = moisSelect.val();
                    var anneeSelectionnee = anneeSelect.val();

                    if(moisSelectionne && anneeSelectionnee && dateDebutVal && dateFinVal) {
                        var premierJourMois = new Date(anneeSelectionnee, moisSelectionne - 1, 1);
                        var dernierJourMois = new Date(anneeSelectionnee, moisSelectionne, 0);

                        var debut = new Date(dateDebutVal);
                        var fin = new Date(dateFinVal);

                        if(debut < premierJourMois || fin > dernierJourMois) {
                            alert('Les dates doivent être comprises entre le ' +
                                premierJourMois.toLocaleDateString('fr-FR') + ' et le ' +
                                dernierJourMois.toLocaleDateString('fr-FR'));

                            // Remettre les dates dans les limites
                            if (debut < premierJourMois) dateDebut.val(premierJourMois.toISOString().split('T')[0]);
                            if (fin > dernierJourMois) dateFin.val(dernierJourMois.toISOString().split('T')[0]);

                            updateFromPeriode();
                            return false;
                        }
                    }
                }
                return true;
            }

            // Événements
            moisSelect.change(updateDatesFromMonth);
            anneeSelect.change(updateDatesFromMonth);

            dateDebut.change(function() {
                if (mode === 'P') {
                    validateDates();
                    updateFromPeriode();
                }
            });

            dateFin.change(function() {
                if (mode === 'P') {
                    validateDates();
                    updateFromPeriode();
                }
            });

            // Recalculer quand les nombres de vigiles ou le prix unitaire changent
            nbVigileJour.on('input', calculerMontants);
            nbVigileNuit.on('input', calculerMontants);
            prixUVigile.on('input', calculerMontants);

            nbMCJour.on('input', calculerMontants);
            nbMCNuit.on('input', calculerMontants);
            prixUMC.on('input', calculerMontants);

            nbSupervJour.on('input', calculerMontants);
            nbSupervNuit.on('input', calculerMontants);
            prixUSuperv.on('input', calculerMontants);

            nbJourFact.on('input', calculerMontants);
            nbNuitFact.on('input', calculerMontants);

            // Initialisation des dates
            updateDatesFromMonth();
        });
    </script>

    <script>
        function imprimerAvecFiltres() {
            // Récupérer tous les valeurs des filtres
            const startDate = document.getElementById('start_date')?.value || '{{ date("Y-m-01") }}';
            const endDate = document.getElementById('end_date')?.value || '{{ date("Y-m-d") }}';
            const numero_client = document.getElementById('numero_client')?.value;

            // Construire l'URL avec tous les paramètres
            let url = "{{ route('poster-vigile.imprimer') }}?";
            url += `start_date=${encodeURIComponent(startDate)}`;
            url += `&end_date=${encodeURIComponent(endDate)}`;
            url += `&numero_client=${encodeURIComponent(numero_client)}`;
            // Ouvrir dans un nouvel onglet
            window.open(url, '_blank');
        }
    </script>

    <script>
        $(document).ready(function() {
            // Récupération des prix minimum
            var prixMinAgent = parseFloat($("#EnPrixUMinAgentFixe").val()) || 0;
            var prixMinMC = parseFloat($("#EnPrixUMinMC").val()) || 0;
            var prixMinSuperv = parseFloat($("#EnPrixUMinSuperv").val()) || 0;
            var prixMinComdo = parseFloat($("#EnPrixUMinComdo").val()) || 0;

            // Fonction de validation des prix
            function validatePrix(input, minPrix, fieldName) {
                var value = parseFloat(input.val().replace(/\s/g, '')) || 0;
                var errorMsg = `Le prix ${fieldName} (${value.toLocaleString()}) doit être supérieur ou égal au prix minimum (${minPrix.toLocaleString()})`;

                if(value > 0 && value < minPrix) {
                    input[0].setCustomValidity(errorMsg);
                    input.addClass('is-invalid');
                    // Créer ou mettre à jour le message d'erreur
                    var feedback = input.siblings('.invalid-feedback');
                    if (feedback.length === 0) {
                        feedback = $('<div class="invalid-feedback"></div>').insertAfter(input);
                    }
                    feedback.text(errorMsg);
                    return false;
                } else {
                    input[0].setCustomValidity('');
                    input.removeClass('is-invalid');
                    var feedback = input.siblings('.invalid-feedback');
                    if (feedback.length > 0 && feedback.text().includes('prix minimum')) {
                        feedback.remove();
                    }
                    return true;
                }
            }

            // Attacher les événements de validation
            $("#FcPrixUVigile").on('input blur', function() {
                validatePrix($(this), prixMinAgent, "par agent");
            });

            $("#FcPrixUMC").on('input blur', function() {
                validatePrix($(this), prixMinMC, "maître chien");
            });

            $("#FcPrixUSuperv").on('input blur', function() {
                validatePrix($(this), prixMinSuperv, "superviseur");
            });

            $("#FcPrixUComm").on('input blur', function() {
                validatePrix($(this), prixMinComdo, "commando");
            });

            // Validation au soumission du formulaire
            $('form').on('submit', function(e) {
                var isValid = true;
                isValid = validatePrix($("#FcPrixUVigile"), prixMinAgent, "par agent") && isValid;
                isValid = validatePrix($("#FcPrixUMC"), prixMinMC, "maître chien") && isValid;
                isValid = validatePrix($("#FcPrixUSuperv"), prixMinSuperv, "superviseur") && isValid;
                isValid = validatePrix($("#FcPrixUComm"), prixMinComdo, "commando") && isValid;

                if(!isValid) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>

@endsection

