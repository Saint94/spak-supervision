@extends('layout.app')

@section('title', 'Liste des sites supervisés')

@section('content')

@include('layout.alert')

<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><b>Liste des sites supervisés</b></h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard table-responsive">

                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create"><i class="ft-plus"></i>&nbsp;Affectation des sites à superviser </button>
                        <button type="button" class="btn btn-danger" onclick="imprimerAvecFiltres()"><i class="ft-printer"></i> Imprimer Liste Sites Supervisés</button>

                        <form action="{{ route('site-superviseur.filtre') }}" method="POST">
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
                                        @if(Auth::user()->type == 'S')
                                            <label for="PeNumero">Superviseur</label>
                                        @elseif(Auth::user()->type == 'T')
                                            <label for="PeNumero">Contrôleur</label>
                                        @elseif(Auth::user()->type == 'R')
                                            <label for="PeNumero">Contrôleur</label>
                                        @else
                                            <label for="PeNumero">Superviseur</label>
                                        @endif
                                        <select id="PeNumero" name="PeNumero" class="form-control e5">
                                            <option selected="selected" value="all">Tous</option>
                                            @foreach($superviseurs as $superviseur)
                                                <option value="{{ $superviseur->PeNumero }}">{{ $superviseur->PeNom.' '.$superviseur->PePrenom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-3">
                                    <div class="form-group">
                                        <label for="ViNumero">Ville</label>
                                        <select id="ViNumero" name="ViNumero" class="e6 form-control">
                                            <option selected="selected" value="all">Tous</option>
                                            @foreach($villes as $ville)
                                                <option value="{{ $ville->ViNumero }}">{{ $ville->ViNom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-3">
                                    <div class="form-group">
                                        <label for="ZoNumero">Zone</label>
                                        <select id="ZoNumero" name="ZoNumero" class="e7 form-control">
                                            <option selected="selected" value="all">Tous</option>
                                            @foreach($zones as $zone)
                                                <option value="{{ $zone->ZoNumero }}">{{ $zone->ZoNom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-3">
                                    <div class="form-group">
                                        <label for="QuNumero">Quartier</label>
                                        <select id="QuNumero" name="QuNumero" class="e8 form-control">
                                            <option selected="selected" value="all">Tous</option>
                                            @foreach($quartiers as $quartier)
                                                <option value="{{ $quartier->QuNumero }}">{{ $quartier->QuNom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-3">
                                    <div class="form-group">
                                        <label for="ClNumero">Clients</label>
                                        <select id="ClNumero" name="ClNumero" class="form-control e9">
                                            <option selected="selected" value="all">Tous</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->ClNumero }}">{{ $client->ClDesign }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-3">
                                    <div class="form-group">
                                        <label for="ScNumero">Site</label>
                                        <select id="ScNumero" name="ScNumero" class="e10 form-control">
                                            <option selected="selected" value="all">Tous</option>
                                            @foreach($sites_client_1 as $site_client_1)
                                                <option value="{{ $site_client_1->ScNumero }}">{{ $site_client_1->ScNomSite }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-3">
                                    <div class="form-group">
                                        <label for="faction_site">Faction</label>
                                        <select id="faction_site" name="faction_site" class="form-control e11">
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
                                    @if(Auth::user()->type == 'S')
                                        <th>Superviseur</th>
                                    @elseif(Auth::user()->type == 'T')
                                        <th>Contrôleur</th>
                                    @elseif(Auth::user()->type == 'R')
                                        <th>Contrôleur</th>
                                    @else
                                        <th>Superviseur</th>
                                    @endif
                                    <th>Site client</th>
                                    <th>Zone</th>
                                    <th>Ville</th>
                                    <th>Date Début</th>
                                    <th>Date Fin</th>
                                    <th>Faction</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sites_superviseur as $site_superviseur)
                                    <tr>
                                        <td>{{ $site_superviseur->PeNom.' '.$site_superviseur->PePrenom }}</td>

                                        @if($site_superviseur->SsMode == 'S')
                                            <td>{{ $site_superviseur->ScNomSite }}</td>
                                            <td></td>
                                        @else
                                            <td></td>
                                            <td>{{ $site_superviseur->ZoNom }}</td>
                                        @endif


                                        <td>{{ $site_superviseur->ViCode }}</td>

                                        @if($site_superviseur->SsDateDebut != null)
                                            <td>{{ date('d/m/Y',strtotime($site_superviseur->SsDateDebut 	)) }}</td>
                                        @else
                                            <td></td>
                                        @endif

                                        @if($site_superviseur->SsDateFin != null)
                                            <td>{{ date('d/m/Y',strtotime($site_superviseur->SsDateFin)) }}</td>
                                        @else
                                            <td></td>
                                        @endif

                                        @if($site_superviseur->SsFaction == 'J')
                                            <td>Jour</td>
                                        @else
                                            <td>Nuit</td>
                                        @endif

                                        @if($site_superviseur->SsIsOn == 'Y')
                                            <td><span class="badge light badge-success">Actif</span></td>
                                        @else
                                            <td><span class="badge light badge-danger">Inactif</span></td>
                                        @endif

                                        <td style="text-align: center;">
                                            <a href="#" onclick="showItem('{{ $site_superviseur->SsNumero }}')" class="info p-0" id="view">
                                                <i class="ft-eye font-medium-3 mr-2"></i>
                                            </a>

                                            @if(Auth::user()->type == 'M')
                                                <a href="#" onclick="editItem('{{$site_superviseur->SsNumero}}')" class="warning p-0" id="editor">
                                                    <i class="ft-edit font-medium-3 mr-2"></i>
                                                </a>

                                                <a onclick="finSupervisionItem('{{ $site_superviseur->SsNumero }}')" class="danger p-0" id="fin_supervision">
                                                    <i class="ft-log-out font-medium-3 mr-2"></i>
                                                </a>

                                                <a onclick="deleteItem('{{ $site_superviseur->SsNumero }}')" class="danger p-0" id="delete">
                                                    <i class="ft-trash-2 font-medium-3 mr-2"></i>
                                                </a>
                                            @endif

                                            @if($site_superviseur->SsIsAllSites == 'N')
                                                <a href="{{ route('site-superviseur.sites_supervises',crypter($site_superviseur->SsNumero)) }}" class="info p-0" id="sites">
                                                    <i class="ft-map-pin font-medium-3 mr-2"></i>
                                                </a>
                                            @endif

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

                <form method="POST" action="{{ route('sites-superviseur.store') }}" class="needs-validation" novalidate>
                    @csrf

                    <div class="modal-header bg-info white">
                        <h4 class="modal-title" id="myModalLabel16">AFFECTATION DES SITE AU SUPERVISEUR</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                @if(Auth::user()->type == 'S')
                                    <label for="SsSuperv" id="titre_statut">Superviseur <abbr style="color: #bd4147;">(*)</abbr></label>
                                @elseif(Auth::user()->type == 'T')
                                    <label for="SsSuperv" id="titre_statut">Contrôleur <abbr style="color: #bd4147;">(*)</abbr></label>
                                 @elseif(Auth::user()->type == 'R')
                                    <label for="SsSuperv" id="titre_statut">Contrôleur <abbr style="color: #bd4147;">(*)</abbr></label>
                                @else
                                    <label for="SsSuperv" id="titre_statut">Superviseur <abbr style="color: #bd4147;">(*)</abbr></label>
                                @endif
                                <select class="e1 form-control" id="SsSuperv" name="SsSuperv" required>
                                        <option selected value="">Selectionner...</option>
                                        @foreach($superviseurs as $superviseur)
                                            <option value="{{ $superviseur->PeNumero }}">{{ $superviseur->PeNom.' '.$superviseur->PePrenom }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6" id="afficher_sites_zones"></div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="SsDateDebut">Date de début <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" class="form-control" id="SsDateDebut" name="SsDateDebut" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label>Faction <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline1" name="SsFaction" class="custom-control-input" value="J" checked/>
                                            <label class="custom-control-label" for="customRadioInline1">Jour</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline2" name="SsFaction" class="custom-control-input" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline2">Nuit</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label>Statut <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline3" name="SsIsOn" class="custom-control-input" value="Y" checked/>
                                            <label class="custom-control-label" for="customRadioInline3">Actif</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline4" name="SsIsOn" class="custom-control-input" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline4">Inactif</label>
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
                                    <label for="SsDetails">Détails</label>
                                    <textarea rows="2" class="form-control" id="SsDetails" name="SsDetails" placeholder="Commentaires"></textarea>
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
                        <h5 class="modal-title" id="myModalLabel16">MODIFICATION D'UNE AFFECTATION DE SITE AU SUPERVISEUR</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    @if(Auth::user()->type == 'S')
                                        <label for="SsSuperv" id="titre_statut">Superviseur <abbr style="color: #bd4147;">(*)</abbr></label>
                                    @elseif(Auth::user()->type == 'T')
                                        <label for="SsSuperv" id="titre_statut">Contrôleur <abbr style="color: #bd4147;">(*)</abbr></label>
                                    @elseif(Auth::user()->type == 'R')
                                        <label for="SsSuperv" id="titre_statut">Contrôleur <abbr style="color: #bd4147;">(*)</abbr></label>
                                    @else
                                        <label for="SsSuperv" id="titre_statut">Superviseur <abbr style="color: #bd4147;">(*)</abbr></label>
                                    @endif
                                    <select class="form-control SsSuperv e3" id="SsSuperv" name="SsSuperv" required>
                                        <option selected value="">Selectionner...</option>
                                        @foreach($superviseurs_1 as $superviseur_1)
                                            <option value="{{ $superviseur_1->PeNumero }}">{{ $superviseur_1->PeNom.' '.$superviseur_1->PePrenom }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6" id="afficher_sites_zones_edit"></div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="SsDateDebut">Date de début <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" class="form-control SsDateDebut" id="SsDateDebut" name="SsDateDebut" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label>Faction <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline5" name="SsFaction" class="custom-control-input SsFaction_Y" value="J"/>
                                            <label class="custom-control-label" for="customRadioInline5">Jour</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline6" name="SsFaction" class="custom-control-input SsFaction_N" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline6">Nuit</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label>Statut <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline7" name="SsIsOn" class="custom-control-input SsIsOn_Y" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline7">Actif</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline8" name="SsIsOn" class="custom-control-input SsIsOn_N" value="N"/>
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
                                    <label for="SsDetails">Détails</label>
                                    <textarea rows="2" class="form-control" id="SsDetails" name="SsDetails" placeholder="Commentaires"></textarea>
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
                    <h5 class="modal-title" id="myModalLabel16">INFORMATIONS SITE SUPERVISEUR</h5>
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
                                            @if(Auth::user()->type == 'S')
                                                <th scope="row">Superviseur</th>
                                            @elseif(Auth::user()->type == 'T')
                                                <th scope="row">Contrôleur</th>
                                            @elseif(Auth::user()->type == 'R')
                                                <th scope="row">Contrôleur</th>
                                            @else
                                                <th scope="row">Superviseur</th>
                                            @endif
                                            <td id="superviseur"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Site</th>
                                            <td id="site"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Zone</th>
                                            <td id="zone"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Ville</th>
                                            <td id="ville"></td>
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
                                            <th scope="row">Faction</th>
                                            <td id="faction"></td>
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

    <!-- FIN SUPERVISION -->
    <div class="modal fade text-left" id="edit_fin_supervision" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">

                <form method="POST" action="#" class="form-fin-supervision needs-validation" novalidate>
                    @csrf

                    <input type="hidden" name="_method" value="PUT">

                    <div class="modal-header bg-info white">
                        <h5 class="modal-title" id="myModalLabel16">FIN DE SUPERVISION</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    @if(Auth::user()->type == 'S')
                                        <label for="SsSuperv" id="titre_statut">Superviseur</label>
                                    @elseif(Auth::user()->type == 'T')
                                        <label for="SsSuperv" id="titre_statut">Contrôleur</label>
                                    @elseif(Auth::user()->type == 'R')
                                        <label for="SsSuperv" id="titre_statut">Contrôleur</label>
                                    @else
                                        <label for="SsSuperv" id="titre_statut">Superviseur</label>
                                    @endif
                                    <input type="text" class="form-control SsSuperv1" readonly/>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="SsSiteClient" class="site_client_zone"></label>
                                    <input type="text" class="form-control SsSiteClient1" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="SsDateDebut">Date de début</label>
                                    <input type="date" class="form-control SsDateDebut1" readonly/>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="SsFaction">Faction</label>
                                    <input type="text" class="form-control SsFaction1" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="SsDateFin">Date de fin <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" class="form-control" id="SsDateFin" name="SsDateFin" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-md-12">
                                <div class="form-group">
                                    <label for="SsDetails">Détails</label>
                                    <textarea rows="2" class="form-control SsDetails1" id="SsDetails" name="SsDetails" placeholder="Commentaires"></textarea>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler <i class="fa fa-refresh position-right"></i></button>
                        <button type="submit" class="btn btn-info">Fin supervision <i class="fa fa-thumbs-o-up position-right"></i></button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!-- FIN SUPERVISION -->

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

        tippy('#delete', {
            content: "Supprimer",
            placement: 'bottom',
        });

        tippy('#sites', {
            content: "Sites du contrôleur",
            placement: 'top',
        });
    </script>

    <script>
        $(document).ready(function(){
            $("#create,#edit,#xlarge,#edit_fin_supervision").modal({
                show:false,
                backdrop:'static'
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $(".e1,.e2").select2({
                width: '100%',
                dropdownParent: $("#create")
            });

            $(".e5").select2();
            $(".e6").select2();
            $(".e7").select2();
            $(".e8").select2();
            $(".e8").select2();
            $(".e9").select2();
            $(".e10").select2();
            $(".e11").select2();
            $(".e11").select2();
        });
    </script>

    <script type="text/javascript">
        function showItem(SsNumero) {
            $.ajax({
                url: '{{ route("site-superviseur.show", "") }}/' + SsNumero,
                type: 'GET',
                success: function(data){

                    $('#superviseur').html(data.PeNom+' '+data.PePrenom)

                    if((data.SsMode) == 'S')
                    {
                        $('#site').html(data.ScNomSite)
                        $('#zone').html("")
                    }
                    else
                    {
                        $('#site').html("")
                        $('#zone').html(data.ZoNom)
                    }

                    $('#ville').html(data.ViCode)
                    $('#date_debut').html(data.SsDateDebut)
                    $('#date_fin').html(data.SsDateFin)

                    if((data.SsFaction) == 'J')
                    {
                        $('#faction').text('Jour');
                    }
                    else
                    {
                        $('#faction').text('Nuit');
                    }


                    if((data.SsIsOn) == 'Y')
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
        function editItem(SsNumero) {
            $.ajax({
                url: '{{ route("site-superviseur.edit", "") }}/' + SsNumero,
                type: 'GET',
                success: function(data){

                    var SsNumero=data.SsNumero;

                    $(".form-edit").attr('action', '{{ route("site-superviseur.update", "") }}/' + SsNumero);
                    var form=$(".form-edit").attr('action');

                    $(".SsSuperv").val(data.SsSuperv);
                    //$(".SsSiteClient").val(data.SsSiteClient);
                    $(".SsDateDebut").val(data.SsDateDebut);
                    $(".SsDetails").val(data.SsDetails);

                    if((data.SsFaction) =='J')
                    {
                        $('.SsFaction_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.SsFaction_N').attr('checked',true);
                    }

                    if((data.SsIsOn) =='Y')
                    {
                        $('.SsIsOn_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.SsIsOn_N').attr('checked',true);
                    }

                    $(".SsSuperv").change(function()
                    {
                        var SsSuperv =  $(".SsSuperv").val();

                        $.get('{{ route("site-superviseur.show_sites_zones_edit","") }}/'+SsSuperv, function(rep) {
                            $("#afficher_sites_zones_edit").html(rep);
                            $(".SsSiteClient").val(data.SsSiteClient);
                        });
                    });

                    if($(".SsSuperv").val() != null) {
                        var SsSuperv =  $(".SsSuperv").val();

                        $.get('{{ route("site-superviseur.show_sites_zones_edit","") }}/'+SsSuperv, function(rep) {
                            $("#afficher_sites_zones_edit").html(rep);
                            $(".SsSiteClient").val(data.SsSiteClient);
                        });
                    }

                    $(".e3,.e4").select2({
                        width: '100%',
                        dropdownParent: $("#edit")
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
        function finSupervisionItem(SsNumero) {
            $.ajax({
                url: '{{ route("site-superviseur.fin_supervision_edit", "") }}/' + SsNumero,
                type: 'GET',
                success: function(data){

                    var SsNumero=data.SsNumero;

                    $(".form-fin-supervision").attr('action', '{{ route("site-superviseur.fin_supervision_update", "") }}/' + SsNumero);
                    var form=$(".form-fin-supervision").attr('action');

                    $(".SsSuperv1").val(data.PeNom+' '+data.PePrenom);
                    $(".SsSiteClient1").val(data.ScNomSite);
                    $(".SsDateDebut1").val(data.SsDateDebut);

                    if((data.SsMode) == 'S')
                    {
                        $('.site_client_zone').html("Site client")
                        $('.SsSiteClient1').val(data.ScNomSite)
                    }
                    else
                    {
                        $('.site_client_zone').html("Zone")
                        $('.SsSiteClient1').val(data.ZoNom)
                    }


                    if((data.SsFaction) =='J')
                    {
                        $('.SsFaction1').val('Jour');
                    }
                    else
                    {
                        $('.SsFaction1').val('Nuit');
                    }

                    $(".SsDetails1").val(data.SsDetails);

                    $('#edit_fin_supervision').modal('show').fadeIn('slow')
                },
                error: function(){
                    console.log("Something goes wrong")
                }
            })
        }
    </script>

    <script type="text/javascript">
        function activeItem(SsNumero) {
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
                    url: '{{ route("site-superviseur.activer", "") }}/' + SsNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Site superviseur activé avec succès !',
                            '',
                            'success'
                        )
                    window.location.href = "{{ route('sites-superviseur.index') }}";
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
        function deleteItem(SsNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Supprimer cet élément",
                text: "Confirmez-vous la suppression de cet élément?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Oui, Supprimer !",
                cancelButtonText: "Annuler !",
                confirmButtonColor: '#2ecc71',
                cancelButtonColor: '#e84118',
                reverseButtons: true

            }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '{{ route("site-superviseur.delete", "") }}/' + SsNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Site superviseur supprimé avec succès !',
                            '',
                            'success'
                        )
                    window.location.href = "{{ route('sites-superviseur.index') }}";
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

    <script>
        function imprimerAvecFiltres() {
            // Récupérer tous les valeurs des filtres
            const startDate = document.getElementById('start_date')?.value || '{{ date("Y-m-01") }}';
            const endDate = document.getElementById('end_date')?.value || '{{ date("Y-m-d") }}';
            const PeNumero = document.getElementById('PeNumero')?.value || 'all';
            const ViNumero = document.getElementById('ViNumero')?.value || 'all';
            const ZoNumero = document.getElementById('ZoNumero')?.value || 'all';
            const QuNumero = document.getElementById('QuNumero')?.value || 'all';
            const ScNumero = document.getElementById('ScNumero')?.value || 'all';
            const ClNumero = document.getElementById('ClNumero')?.value || 'all';
            const faction_site = document.getElementById('faction_site')?.value || 'all';

            // Construire l'URL avec tous les paramètres
            let url = "{{ route('site-superviseur.imprimer') }}?";
            url += `start_date=${encodeURIComponent(startDate)}`;
            url += `&end_date=${encodeURIComponent(endDate)}`;
            url += `&PeNumero=${encodeURIComponent(PeNumero)}`;
            url += `&ViNumero=${encodeURIComponent(ViNumero)}`;
            url += `&ZoNumero=${encodeURIComponent(ZoNumero)}`;
            url += `&QuNumero=${encodeURIComponent(QuNumero)}`;
            url += `&ScNumero=${encodeURIComponent(ScNumero)}`;
            url += `&ClNumero=${encodeURIComponent(ClNumero)}`;
            url += `&faction_site=${encodeURIComponent(faction_site)}`;

            // Ouvrir dans un nouvel onglet
            window.open(url, '_blank');
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#SsSuperv").change(function()
            {
                var SsSuperv =  $("#SsSuperv").val();

                $.get('{{ route("site-superviseur.show_sites_zones","") }}/'+SsSuperv, function(rep) {
                    $("#afficher_sites_zones").html(rep);

                    var statut =  $("#statut").val();

                    if(statut == 'CT') {
                        $("#titre_statut").html('Contrôleur <span style="color:red;">(*)</span>');
                    } else if(statut == 'SG') {
                        $("#titre_statut").html('Superviseur <span style="color:red;">(*)</span>');
                    } else if(statut == 'SA') {
                        $("#titre_statut").html('Superviseur <span style="color:red;">(*)</span>');
                    } else {
                        $("#titre_statut").html("");
                    }

                });
            });

            if($("#SsSuperv").val() != null) {
                var SsSuperv =  $("#SsSuperv").val();

                $.get('{{ route("site-superviseur.show_sites_zones","") }}/'+SsSuperv, function(rep) {
                    $("#afficher_sites_zones").html(rep);

                    var statut =  $("#statut").val();

                    if(statut == 'CT') {
                        $("#titre_statut").html('Contrôleur <span style="color:red;">(*)</span>');
                    } else if(statut == 'SG') {
                        $("#titre_statut").html('Superviseur <span style="color:red;">(*)</span>');
                    } else if(statut == 'SA') {
                        $("#titre_statut").html('Superviseur <span style="color:red;">(*)</span>');
                    } else {
                        $("#titre_statut").html("");
                    }

                });
            }
        });
    </script>
@endsection

