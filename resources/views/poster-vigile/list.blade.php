@extends('layout.app')

@section('title', 'Liste des Postings des Vigiles')

@section('content')

@include('layout.alert')

<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><b>Liste des Postings des Vigiles</b></h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard table-responsive">
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create"><i class="ft-plus"></i>&nbsp;Poster un vigile</button>
                        <button type="button" class="btn btn-danger" onclick="imprimerAvecFiltres()"><i class="ft-printer"></i> Imprimer Liste Postings</button>

                        <form action="{{ route('poster-vigile.filtre') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-xl-3 col-md-3">
                                    <div class="form-group">
                                        <label for="">Période du</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control" value="{{ date('Y-m-01') }}" max="{{ date('Y-m-d') }}"/>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-3">
                                    <div class="form-group">
                                        <label for="">Au</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}"/>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-3">
                                    <div class="form-group">
                                        <label for="PvStatut">Profil</label>
                                        <select class="form-control e1" name="PvStatut">
                                            <option selected="selected" value="all">Tous</option>
                                            <option value="F">Agent Fixe</option>
                                            <option value="R">Rondier</option>
                                            <option value="B">Stand Bye</option>
                                            <option value="E">Chef d'Equipe</option>
                                            <option value="C">Maître Chien</option>
                                            <option value="SG">Superv. Général</option>
                                            <option value="SA">Superv. Adjoint</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-3">
                                    <div class="form-group">
                                        <label for="PvVigile">Agent</label>
                                        <select name="PvVigile" class="e2 form-control">
                                            <option selected="selected" value="all">Tous</option>
                                            @foreach($personnels as $personnel)
                                                <option value="{{ $personnel->PeNumero }}">{{ $personnel->PeMatric.' '.$personnel->PeNom.' '.$personnel->PePrenom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-3">
                                    <div class="form-group">
                                        <label for="ville_posting">Ville</label>
                                        <select name="ville_posting" class="form-control e3">
                                            <option selected="selected" value="all">Tous</option>
                                            @foreach($villes as $ville)
                                                <option value="{{ $ville->ViNumero }}">{{ $ville->ViNom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-3">
                                    <div class="form-group">
                                        <label for="zone_posting">Zone</label>
                                        <select id="zone_posting" name="zone_posting" class="form-control e4">
                                            <option selected="selected" value="all">Tous</option>
                                            @foreach($zones as $zone)
                                                <option value="{{ $zone->ZoNumero }}">{{ $zone->ZoNom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-3">
                                    <div class="form-group">
                                        <label for="client_posting">Clients</label>
                                        <select name="client_posting" class="form-control e5">
                                            <option selected="selected" value="all">Tous</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->ClNumero }}">{{ $client->ClDesign }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-3">
                                    <div class="form-group">
                                        <label for="site_posting">Site</label>
                                        <select name="site_posting" class="form-control e6">
                                            <option selected="selected" value="all">Tous</option>
                                            @foreach($sites_client as $site_client)
                                                <option value="{{ $site_client->ScNumero }}">{{ $site_client->ScNomSite }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-3">
                                    <div class="form-group">
                                        <label for="faction_site">Faction</label>
                                        <select id="faction_site" name="faction_site" class="form-control e7">
                                            <option selected="selected" value="all">Tous</option>
                                            <option value="J">jour</option>
                                            <option value="N">Nuit</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-3">
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
                                    <th>Site client</th>
                                    <th>Vigile</th>
                                    <th>Date Début</th>
                                    <th>Date Fin</th>
                                    <th>Faction</th>
                                    <th>Profil</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($poster_vigiles as $poster_vigile)
                                    <tr>
                                        <td>{{ $poster_vigile->ScNomSite }}</td>
                                        <td>{{ $poster_vigile->PeNom.' '.$poster_vigile->PePrenom }}</td>

                                        @if($poster_vigile->PvDateDebut != null)
                                            <td>{{ date('d/m/Y',strtotime($poster_vigile->PvDateDebut 	)) }}</td>
                                        @else
                                            <td></td>
                                        @endif

                                        @if($poster_vigile->PvDateFin != null)
                                            <td>{{ date('d/m/Y',strtotime($poster_vigile->PvDateFin)) }}</td>
                                        @else
                                            <td></td>
                                        @endif

                                        @if($poster_vigile->PvFaction == 'J')
                                            <td>Jour</td>
                                        @else
                                            <td>Nuit</td>
                                        @endif

                                        @if($poster_vigile->PvStatut == 'F')
                                            <td>Agent Fixe</td>
                                        @elseif($poster_vigile->PvStatut == 'R')
                                            <td>Rondier</td>
                                        @elseif($poster_vigile->PvStatut == 'B')
                                            <td>Stand Bye</td>
                                        @elseif($poster_vigile->PvStatut == 'E')
                                            <td>Chef d'Equipe</td>
                                        @elseif($poster_vigile->PvStatut == 'C')
                                            <td>Maître Chien</td>
                                        @elseif($poster_vigile->PeStatut == 'SG')
                                            <td>Superv. Général</td>
                                        @elseif($poster_vigile->PeStatut == 'SA')
                                            <td>Superv. Adjoint</td>
                                        @else
                                            <td></td>
                                        @endif

                                        @if($poster_vigile->PvIsOn == 'Y')
                                            <td><span class="badge light badge-success">Actif</span></td>
                                        @else
                                            <td><span class="badge light badge-danger">Inactif</span></td>
                                        @endif

                                        <td style="text-align:center;">
                                            <a onclick="showItem('{{ $poster_vigile->PvNumero }}')" class="info p-0" id="view">
                                                <i class="ft-eye font-medium-3 mr-2"></i>
                                            </a>

                                            <a href="#" onclick="editItem('{{$poster_vigile->PvNumero}}')" class="warning p-0" id="editor">
                                                <i class="ft-edit font-medium-3 mr-2"></i>
                                            </a>

                                            @if($poster_vigile->PvIsOn == 'Y')
                                                <a onclick="finDeploiementItem('{{ $poster_vigile->PvNumero }}')" class="danger p-0" id="fin_deploiement">
                                                    <i class="ft-trash-2 font-medium-3 mr-2"></i>
                                                </a>
                                            @else
                                                <a onclick="activeItem('{{ $poster_vigile->PvNumero }}')" class="success p-0" id="activer">
                                                    <i class="ft-log-in font-medium-3 mr-2"></i>
                                                </a>
                                            @endif

                                            <a onclick="redeployerItem('{{ $poster_vigile->PvNumero }}')" class="info p-0" id="redeployer">
                                                <i class="ft-user-plus font-medium-3 mr-2"></i>
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
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">

                <form method="POST" action="{{ route('poster-vigiles.store') }}" class="needs-validation" novalidate>
                    @csrf

                    <div class="modal-header bg-info white">
                        <h4 class="modal-title" id="myModalLabel16">POSTER UN VIGILE</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="PvVigile">Personnel vigile <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e9 form-control" id="PvVigile" name="PvVigile" required>
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
                            <div class="col-xl-6 col-md-6" id="afficher_sites_vigile">
                                <div class="form-group">
                                    <label for="PvSiteClient">Site client <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e8 form-control" id="PvSiteClient" name="PvSiteClient" required>
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
                                    <label for="PvDateDebut">Date de début <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" class="form-control" id="PvDateDebut" name="PvDateDebut" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6" id="afficher_profil_vigile">
                                <div class="form-group">
                                    <label for="PvStatut">Profil <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e10 form-control" id="PvStatut" name="PvStatut" required>
                                        <option selected value="">Selectionner...</option>
                                        <option value="F">Agent Fixe</option>
                                        <option value="R">Rondier</option>
                                        <option value="B">Stand Bye</option>
                                        <option value="E">Chef d'Equipe</option>
                                        <option value="C">Maître Chien</option>
                                        <option value="SG">Superv. Général</option>
                                        <option value="SA">Superv. Adjoint</option>
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
                                    <label>Faction <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline1" name="PvFaction" class="custom-control-input" value="J" checked/>
                                            <label class="custom-control-label" for="customRadioInline1">Jour</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline2" name="PvFaction" class="custom-control-input" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline2">Nuit</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>Statut <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline3" name="PvIsOn" class="custom-control-input" value="Y" checked/>
                                            <label class="custom-control-label" for="customRadioInline3">Actif</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline4" name="PvIsOn" class="custom-control-input" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline4">Inactif</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="afficher_nbre_vigile_mc_contrat"></div>

                        <div class="row">
                            <div class="col-xl-12 col-md-12">
                                <div class="form-group">
                                    <label for="PvDetails">Détails</label>
                                    <textarea rows="2" class="form-control" id="PvDetails" name="PvDetails" placeholder="Commentaires"></textarea>
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
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">

                <form method="POST" action="#" class="form-edit needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="_method" value="PUT">

                    <div class="modal-header bg-info white">
                        <h5 class="modal-title" id="myModalLabel16">MODIFICATION D'UN POSTE VIGILE</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="PvVigile">Personnel vigile</label>
                                    <input type="text" class="form-control PvVigile" readonly/>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="PvSiteClient">Site client</label>
                                    <input type="text" class="form-control PvSiteClient" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="PvDateDebut">Date de début</label>
                                    <input type="date" class="form-control PvDateDebut" readonly/>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="PvStatut">Profil <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="form-control PvStatut e11" id="PvStatut" name="PvStatut" required>
                                        <option selected value="">Selectionner...</option>
                                        <option value="F">Agent Fixe</option>
                                        <option value="R">Rondier</option>
                                        <option value="B">Stand Bye</option>
                                        <option value="E">Chef d'Equipe</option>
                                        <option value="C">Maître Chien</option>
                                        <option value="SG">Superv. Général</option>
                                        <option value="SA">Superv. Adjoint</option>
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
                                    <label>Faction <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline5" name="PvFaction" class="custom-control-input PvFaction_J" value="J"/>
                                            <label class="custom-control-label" for="customRadioInline5">Jour</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline6" name="PvFaction" class="custom-control-input PvFaction_N" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline6">Nuit</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>Statut <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline7" name="PvIsOn" class="custom-control-input PvIsOn_Y" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline7">Actif</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline8" name="PvIsOn" class="custom-control-input PvIsOn_N" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline8">Inactif</label>
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
                                    <label for="PvDetails">Détails</label>
                                    <textarea rows="2" class="form-control PvDetails" id="PvDetails" name="PvDetails" placeholder="Commentaires"></textarea>
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
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info white">
                    <h5 class="modal-title" id="myModalLabel16">INFORMATIONS POSTE VIGILE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-xl-12">
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <tbody>
                                        <tr>
                                            <th scope="row">Vigile</th>
                                            <td id="vigile"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Site</th>
                                            <td id="site"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date de début</th>
                                            <td id="date_debut"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date de fin</th>
                                            <td id="date_fin"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Faction</th>
                                            <td id="faction"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Profil</th>
                                            <td id="profil"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date et Heure</th>
                                            <td id="date_heure"></td>
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


    <!-- REDÉPLOYER VIGILE -->
    <div class="modal fade text-left" id="edit_redeployer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">

                <form method="POST" action="#" class="form-redeployer needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="_method" value="PUT">

                    <div class="modal-header bg-info white">
                        <h5 class="modal-title" id="myModalLabel16">REDÉPLOYER VIGILE</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <h3><b>Déploiement actuel</b></h3>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="">Vigile</label>
                                    <input type="text" class="form-control Vigile1" readonly/>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="">Site Client</label>
                                    <input type="text" class="form-control SiteClient1" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="">Profil</label>
                                    <input type="text" class="form-control Profil1" readonly/>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="">Date de début</label>
                                    <input type="text" class="form-control DateDebut1" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="PvDateFin">Date de fin <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" class="form-control DateFin1" name="PvDateFin" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="">Faction</label>
                                    <input type="text" class="form-control PvFaction1" readonly/>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h3><b>Nouveau Déploiement</b></h3>

                        <input type="hidden" class="form-control PvVigile1" name="PvVigile"/>

                        <div class="row">
                            <div class="col-xl-6 col-md-6" id="afficher_sites_vigile_edit">
                                <div class="form-group">
                                    <label for="PvSiteClient1">Site client <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="form-control PvSiteClient1 e12" id="PvSiteClient" name="PvSiteClient" required>
                                        <option selected value="">Selectionner...</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6" id="afficher_profil_vigile_edit">
                                <div class="form-group">
                                    <label for="PvStatut">Le profil <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="form-control PvStatut1 e13" id="PvStatut" name="PvStatut" required>
                                        <option selected value="">Selectionner...</option>
                                        <option value="F">Agent Fixe</option>
                                        <option value="R">Rondier</option>
                                        <option value="B">Stand Bye</option>
                                        <option value="E">Chef d'Equipe</option>
                                        <option value="C">Maître Chien</option>
                                        <option value="SG">Superv. Général</option>
                                        <option value="SA">Superv. Adjoint</option>
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
                                    <label for="PvDateDebut">Date de début <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" class="form-control PvDateDebut1" id="PvDateDebut1" name="PvDateDebut" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>Faction <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline9" name="PvFaction" class="custom-control-input PvFaction1_J" value="J" checked/>
                                            <label class="custom-control-label" for="customRadioInline9">Jour</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline10" name="PvFaction" class="custom-control-input PvFaction1_N" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline10">Nuit</label>
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
                                    <label for="PvDetails">Détails</label>
                                    <textarea rows="2" class="form-control PvDetails1" id="PvDetails" name="PvDetails" placeholder="Commentaires"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler <i class="fa fa-refresh position-right"></i></button>
                        <button type="submit" class="btn btn-info">Redéployer vigile <i class="fa fa-thumbs-o-up position-right"></i></button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!-- REDÉPLOYER VIGILE -->


    <!-- FIN DEPLOIEMENT -->
    <div class="modal fade text-left" id="edit_fin_deploiement" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">

                <form method="POST" action="#" class="form-fin-deploiement needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="_method" value="PUT">

                    <div class="modal-header bg-info white">
                        <h5 class="modal-title" id="myModalLabel16">FIN DEPLOIEMENT D'UN VIGILE</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="PvSiteClient2">Site client</label>
                                    <input type="text" class="form-control PvSiteClient2" readonly/>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="PvVigile2">Personnel vigile</label>
                                    <input type="text" class="form-control PvVigile2" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="PvStatut2">Profil</label>
                                    <input type="text" class="form-control PvStatut2" readonly/>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="PvDateDebut2">Date de début</label>
                                    <input type="date" class="form-control PvDateDebut2" readonly/>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="PvDateFin">Date de fin <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" class="form-control" id="PvDateFin" name="PvDateFin" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="PvDateDebut2">Faction</label>
                                    <input type="text" class="form-control PvFaction2" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-md-12">
                                <div class="form-group">
                                    <label for="PvDetails2">Détails</label>
                                    <textarea rows="2" class="form-control PvDetails2" id="PvDetails" name="PvDetails" placeholder="Commentaires"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler <i class="fa fa-refresh position-right"></i></button>
                        <button type="submit" class="btn btn-info">Fin de déploiement <i class="fa fa-thumbs-o-up position-right"></i></button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!-- FIN DEPLOIEMENT -->

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

        tippy('#activer', {
            content: "Activer",
            placement: 'top',
        });

        tippy('#fin_deploiement', {
            content: "Fin Déploiement",
            placement: 'top',
        });

        tippy('#redeployer', {
            content: "Redéployer Vigile",
            placement: 'bottom',
        });
    </script>

    <script>
        $(document).ready(function(){
            $("#create,#edit,#xlarge,#edit_redeployer,#edit_fin_deploiement").modal({
                show:false,
                backdrop:'static'
            });
        });
    </script>

    <script>
        $(document).ready(function() {

            $(".e1").select2();
            $(".e2").select2();
            $(".e3").select2();
            $(".e4").select2();
            $(".e5").select2();
            $(".e6").select2();
            $(".e7").select2();

            $(".e8,.e9,.e10").select2({
                width: '100%',
                dropdownParent: $("#create")
            });
        });
    </script>

    <script type="text/javascript">
        function showItem(PvNumero) {
            $.ajax({
                url: '{{ route("poster-vigile.show", "") }}/' + PvNumero,
                type: 'GET',
                success: function(data){

                    $('#site').html(data.ScNomSite)
                    $('#vigile').html(data.PeNom+' '+data.PePrenom)
                    $('#date_heure').html(data.PvDateHOper)
                    $('#date_debut').html(data.PvDateDebut)
                    $('#date_fin').html(data.PvDateFin)

                    if((data.PvFaction) == 'J')
                    {
                        $('#faction').text('Jour');
                    }
                    else if((data.PvFaction) == 'N')
                    {
                        $('#faction').text('Nuit');
                    }
                    else
                    {
                        $('#faction').text('');
                    }

                    if((data.PvStatut) == 'F')
                    {
                        $('#profil').text('Agent Fixe');
                    }
                    else if((data.PvStatut) == 'R')
                    {
                        $('#profil').text('Rondier');
                    }
                    else if((data.PvStatut) == 'B')
                    {
                        $('#profil').text('Stand Bye');
                    }
                    else if((data.PvStatut) == 'E')
                    {
                        $('#profil').text('Chef d\'Equipe');
                    }
                    else if((data.PvStatut) == 'C')
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
                    else
                    {
                        $('#profil').text('Inactif');
                    }


                    if((data.PvIsOn) == 'Y')
                    {
                        $('#statut').text('Actif');
                    }
                    else
                    {
                        $('#statut').text('Inactif');
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
        function editItem(PvNumero) {
            $.ajax({
                url: '{{ route("poster-vigile.edit", "") }}/' + PvNumero,
                type: 'GET',
                success: function(data){

                    var PvNumero=data.PvNumero;

                    $(".form-edit").attr('action', '{{ route("poster-vigile.update", "") }}/' + PvNumero);
                    var form=$(".form-edit").attr('action');

                    $(".PvSiteClient").val(data.ScNomSite);
                    $(".PvVigile").val(data.PeNom+' '+data.PePrenom);
                    $(".PvStatut").val(data.PvStatut);
                    $(".PvDateDebut").val(data.PvDateDebut);

                    if((data.PvFaction) =='J')
                    {
                        $('.PvFaction_J').attr('checked',true);
                    }
                    else
                    {
                        $('.PvFaction_N').attr('checked',true);
                    }

                    $(".PvDetails").val(data.PvDetails);

                    if((data.PvIsOn) =='Y')
                    {
                        $('.PvIsOn_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.PvIsOn_N').attr('checked',true);
                    }

                    $(".e11").select2({
                        width: '100%'
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
        function redeployerItem(PvNumero) {
            $.ajax({
                url: '{{ route("poster-vigile.redeployer_edit", "") }}/' + PvNumero,
                type: 'GET',
                success: function(data){

                    var PvNumero=data.PvNumero;

                    $(".form-redeployer").attr('action', '{{ route("poster-vigile.redeployer_update", "") }}/' + PvNumero);
                    var form=$(".form-redeployer").attr('action');

                    $(".SiteClient1").val(data.ScNomSite);
                    $(".Vigile1").val(data.PeNom+' '+data.PePrenom);
                    $(".DateDebut1").val(formatDate(data.PvDateDebut));

                    const today = new Date();
                    const yyyy = today.getFullYear();
                    const mm = String(today.getMonth() + 1).padStart(2, '0');
                    const dd = String(today.getDate()).padStart(2, '0');
                    $(".DateFin1").val(`${yyyy}-${mm}-${dd}`);

                    if((data.PvFaction) =='J')
                    {
                        $('.PvFaction1').val('Jour');
                    }
                    else if((data.PvFaction) =='N')
                    {
                        $('.PvFaction1').val('Nuit');
                    }else
                    {
                        $('.PvFaction1').val('');
                    }

                    if((data.PvFaction) =='J')
                    {
                        $('.PvFaction1_J').attr('checked',true);
                    }
                    else
                    {
                        $('.PvFaction1_N').attr('checked',true);
                    }


                    if((data.PvStatut) =='F')
                    {
                        $('.Profil1').val('Agent Fixe');
                    }
                    else if((data.PvStatut) =='R')
                    {
                        $('.Profil1').val('Rondier');
                    }
                    else if((data.PvStatut) =='B')
                    {
                        $('.Profil1').val('Stand Bye');
                    }
                    else if((data.PvStatut) =='E')
                    {
                        $('.Profil1').val("Chef d'Equipe");
                    }
                    else if((data.PvStatut) =='C')
                    {
                        $('.Profil1').val("Maître Chien");
                    }
                    else if((data.PvStatut) == 'SG')
                    {
                        $('.Profil1').text('Superv. Général');
                    }
                    else if((data.PvStatut) == 'SA')
                    {
                        $('.Profil1').text('Superv. Adjoint');
                    }
                    else
                    {
                        $('.Profil1').val('');
                    }


                    $(".PvDetails1").val(data.PvDetails);

                    $(".PvDateFin1").val(data.PvDateFin);
                    $(".vigile_redeployer").val(data.PeNom+' '+data.PePrenom);

                    $(".PvVigile1").val(data.PvVigile);

                    $("#PvDateDebut1").val(`${yyyy}-${mm}-${dd}`);

                    $(".DateFin1, #PvDateDebut1").on("change", function() {
                        var dateFin = $(".DateFin1").val();
                        var dateDebut = $("#PvDateDebut1").val();

                        if (dateFin && dateDebut) {
                            var dFin = new Date(dateFin);
                            var dDebut = new Date(dateDebut);

                            if (dFin > dDebut) {
                                alert("La date de fin doit être inférieure ou égale à la date de début !");
                                $(".DateFin1").val(`${yyyy}-${mm}-${dd}`);
                            }
                        }
                    });


                    if($(".PvVigile1").val() != null) {
                        var PvVigile =  $(".PvVigile1").val();

                        $.get('{{ route("poster-vigile.profil_vigile_edit","") }}/'+PvVigile, function(rep) {
                            $("#afficher_profil_vigile_edit").html(rep);
                            $(".PvStatut1").val(data.PvStatut);
                        });

                        $.get('{{ route("poster-vigile.show_sites_vigile_edit","") }}/'+PvVigile, function(rep) {
                            $("#afficher_sites_vigile_edit").html(rep);
                            $(".PvSiteClient1").val(data.PvSiteClient);


                            $("#PvSiteClient1").change(function()
                            {
                                var PvSiteClient =  $(".PvSiteClient1").val(data.PvSiteClient);
                                $.get('{{ route("poster-vigile.show_nbre_vigile_mc_contrat_edit",["",""]) }}/'+PvVigile+'/'+PvSiteClient, function(rep) {
                                    $("#afficher_nbre_vigile_mc_contrat").html(rep);
                                });
                            });

                            if($("#PvSiteClient").val() != null) {
                                var PvSiteClient =  $(".PvSiteClient1").val(data.PvSiteClient);
                                $.get('{{ route("poster-vigile.show_nbre_vigile_mc_contrat_edit",["",""]) }}/'+PvVigile+'/'+PvSiteClient, function(rep) {
                                    $("#afficher_nbre_vigile_mc_contrat").html(rep);
                                });
                            }

                        });
                    }

                    $(".e12,.e13").select2({
                        width: '100%'
                    });

                    $('#edit_redeployer').modal('show').fadeIn('slow')
                },
                error: function(){
                    console.log("Something goes wrong")
                }
            })
        }
    </script>

    <script type="text/javascript">
        function finDeploiementItem(PvNumero) {
            $.ajax({
                url: '{{ route("poster-vigile.fin_deploiement_edit", "") }}/' + PvNumero,
                type: 'GET',
                success: function(data){

                    var PvNumero=data.PvNumero;

                    $(".form-fin-deploiement").attr('action', '{{ route("poster-vigile.fin_deploiement_update", "") }}/' + PvNumero);
                    var form=$(".form-fin-deploiement").attr('action');

                    $(".PvSiteClient2").val(data.ScNomSite);
                    $(".PvVigile2").val(data.PeNom+' '+data.PePrenom);
                    $(".PvDateDebut2").val(data.PvDateDebut);
                    $(".PvDetails2").val(data.PvDetails);

                    if((data.PvFaction) =='J')
                    {
                        $('.PvFaction2').val('Jour');
                    }
                    else if((data.PvStatut) =='N')
                    {
                        $('.PvFaction2').val('Nuit');
                    }else
                    {
                        $('.PvFaction2').val('');
                    }

                    if((data.PvStatut) =='F')
                    {
                        $('.PvStatut2').val('Agent Fixe');
                    }
                    else if((data.PvStatut) =='R')
                    {
                        $('.PvStatut2').val('Rondier');
                    }
                    else if((data.PvStatut) =='B')
                    {
                        $('.PvStatut2').val('Stand Bye');
                    }
                    else if((data.PvStatut) =='E')
                    {
                        $('.PvStatut2').val("Chef d'Equipe");
                    }
                    else if((data.PvStatut) =='C')
                    {
                        $('.PvStatut2').val("Maître Chien");
                    }
                    else if((data.PeStatut) == 'SG')
                    {
                        $('#PvStatut2').text('Superv. Général');
                    }
                    else if((data.PeStatut) == 'SA')
                    {
                        $('#PvStatut2').text('Superv. Adjoint');
                    }
                    else
                    {
                        $('.PvStatut2').val('');
                    }

                    //$("#PvDateFin").val(getdateFormat(addDays(Date.now(),14)));
                    document.getElementById("PvDateFin").setAttribute("min", getdateFormat(addDays(data.PvDateDebut,1)));
                    $("#PvDateFin").val(getdateFormat(addDays(data.PvDateDebut,1)));

                    $('#edit_fin_deploiement').modal('show').fadeIn('slow')
                },
                error: function(){
                    console.log("Something goes wrong")
                }
            })
        }
    </script>

    <script type="text/javascript">
        function activeItem(PvNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Activer cet élément",
                text: "Confirmez-vous l'activation de cet élément ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Oui, Activer !",
                cancelButtonText: "Annuler !",
                confirmButtonColor: '#2ecc71',
                cancelButtonColor: '#e84118',
                reverseButtons: true

            }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '{{ route("poster-vigile.activer", "") }}/' + PvNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Poste vigile activé avec succès !',
                            '',
                            'success'
                        )
                    window.location.href = "{{ route('poster-vigiles.index') }}";
                    }
                });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                    'Annulé',
                    '',
                    'error'
                    )
                }
            });

        };
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#PvVigile").change(function()
            {
                var PvVigile =  $("#PvVigile").val();

                $.get('{{ route("poster-vigile.profil_vigile","") }}/'+PvVigile, function(rep) {
                    $("#afficher_profil_vigile").html(rep);
                });

                $.get('{{ route("poster-vigile.show_sites_vigile","") }}/'+PvVigile, function(rep) {
                    $("#afficher_sites_vigile").html(rep);

                    $("#PvSiteClient").change(function()
                    {
                        var PvSiteClient =  $("#PvSiteClient").val();
                        $.get('{{ route("poster-vigile.show_nbre_vigile_mc_contrat",["",""]) }}/'+PvVigile+'/'+PvSiteClient, function(rep) {
                            $("#afficher_nbre_vigile_mc_contrat").html(rep);
                        });
                    });

                    if($("#PvVigile").val() != null) {
                        var PvSiteClient =  $("#PvSiteClient").val();
                        $.get('{{ route("poster-vigile.show_nbre_vigile_mc_contrat",["",""]) }}/'+PvVigile+'/'+PvSiteClient, function(rep) {
                            $("#afficher_nbre_vigile_mc_contrat").html(rep);
                        });
                    }

                });
            });

            if($("#PvVigile").val() != null) {
                var PvVigile =  $("#PvVigile").val();

                $.get('{{ route("poster-vigile.profil_vigile","") }}/'+PvVigile, function(rep) {
                    $("#afficher_profil_vigile").html(rep);
                });

                $.get('{{ route("poster-vigile.show_sites_vigile","") }}/'+PvVigile, function(rep) {
                    $("#afficher_sites_vigile").html(rep);

                    $("#PvSiteClient").change(function()
                    {
                        var PvSiteClient =  $("#PvSiteClient").val();
                        $.get('{{ route("poster-vigile.show_nbre_vigile_mc_contrat",["",""]) }}/'+PvVigile+'/'+PvSiteClient, function(rep) {
                            $("#afficher_nbre_vigile_mc_contrat").html(rep);
                        });
                    });

                    if($("#PvSiteClient").val() != null) {
                        var PvSiteClient =  $("#PvSiteClient").val();
                        $.get('{{ route("poster-vigile.show_nbre_vigile_mc_contrat",["",""]) }}/'+PvVigile+'/'+PvSiteClient, function(rep) {
                            $("#afficher_nbre_vigile_mc_contrat").html(rep);
                        });
                    }

                });
            }
        });
    </script>

    <script>
        function imprimerAvecFiltres() {
            // Récupérer tous les valeurs des filtres
            const startDate = document.getElementById('start_date')?.value || '{{ date("Y-m-01") }}';
            const endDate = document.getElementById('end_date')?.value || '{{ date("Y-m-d") }}';
            const profil = document.getElementById('PvStatut')?.value || 'all';
            const agent = document.getElementById('PvVigile')?.value || 'all';
            const ville = document.getElementById('ville_posting')?.value || 'all';
            const zone = document.getElementById('zone_posting')?.value || 'all';
            const client = document.getElementById('client_posting')?.value || 'all';
            const site = document.getElementById('site_posting')?.value || 'all';

            // Construire l'URL avec tous les paramètres
            let url = "{{ route('poster-vigile.imprimer') }}?";
            url += `start_date=${encodeURIComponent(startDate)}`;
            url += `&end_date=${encodeURIComponent(endDate)}`;
            url += `&PvStatut=${encodeURIComponent(profil)}`;
            url += `&PvVigile=${encodeURIComponent(agent)}`;
            url += `&ville_posting=${encodeURIComponent(ville)}`;
            url += `&zone_posting=${encodeURIComponent(zone)}`;
            url += `&client_posting=${encodeURIComponent(client)}`;
            url += `&site_posting=${encodeURIComponent(site)}`;

            // Ouvrir dans un nouvel onglet
            window.open(url, '_blank');
        }
    </script>

@endsection
