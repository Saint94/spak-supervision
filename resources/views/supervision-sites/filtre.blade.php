@extends('layout.app')

@section('title', 'Liste des rondes des contrôleurs')

@section('content')

@include('layout.alert')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        @if(Auth::user()->type == 'S')
                            <h4 class="card-title">
                                <b>Ronde du superviseur : {{ $superviseurs->PeNom.' '.$superviseurs->PePrenom }}</b>
                            </h4>
                        @elseif(Auth::user()->type == 'T')
                            <h4 class="card-title">
                                <b>Ronde du contrôleur : {{ $superviseurs->PeNom.' '.$superviseurs->PePrenom }}</b>
                            </h4>
                        @else
                            <h4 class="card-title"><b>Liste des rondes des contrôleurs</b></h4>
                        @endif
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard table-responsive">
                            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create"><i class="ft-plus"></i>&nbsp;Nouvelle Ronde de Supervision</button>
                            <button type="button" class="btn btn-danger" onclick="imprimerAvecFiltres()"><i class="ft-printer"></i> Imprimer Liste Rondes</button>

                            @if(Auth::user()->type == 'S')
                              <!--<form action="{{ route('supervision-site.filtre') }}" method="POST">
                                    @csrf

                                    <div class="row">
                                        <div class="col-xl-4 col-md-4">
                                            <div class="form-group">
                                                <label for="">Période du</label>
                                                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $start_date }}"/>
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
                                                <label for="superviseur_site">Superviseur</label>
                                                <input type="text" class="form-control" value="{{ $superviseurs->PeNom.' '.$superviseurs->PePrenom }}" readonly/>
                                                <input type="hidden" id="superviseur_site" name="superviseur_site" class="form-control" value="{{ $superviseurs->PeNumero }}"/>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-md-4">
                                            <div class="form-group">
                                                <label for="ville_site">Ville</label>
                                                <input type="text" class="form-control" value="{{ $villes->ViNom }}" readonly/>
                                                <input type="hidden" id="ville_site" name="ville_site" class="form-control" value="{{ $villes->ViNumero }}"/>
                                            </div>
                                        </div>

                                        @if($villes->ViIsZoneOn == "N")
                                            <input type="hidden" id="zone_site" name="zone_site" class="form-control" value="all"/>
                                        @else
                                            <div class="col-xl-4 col-md-4">
                                                <div class="form-group">
                                                    <label for="zone_site">Zone</label>
                                                    <input type="text" class="form-control" value="{{ $ZoNom }}" readonly/>
                                                    <input type="hidden" id="zone_site" name="zone_site" class="form-control" value="{{ $ZoNumero }}"/>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-xl-4 col-md-4">
                                            <div class="form-group">
                                                <label for="">Afficher</label><br>
                                                <button type="submit" class="btn btn-danger"><i class="fa ft-filter"></i> Afficher</button>
                                            </div>
                                        </div>
                                    </div>
                                </form> -->
                            @elseif(Auth::user()->type == 'T')
                                <!--<form action="{{ route('supervision-site.filtre') }}" method="POST">
                                    @csrf

                                    <div class="row">
                                        <div class="col-xl-4 col-md-4">
                                            <div class="form-group">
                                                <label for="">Période du</label>
                                                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $start_date }}"/>
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
                                                <label for="superviseur_site">Contrôleur</label>
                                                <input type="text" class="form-control" value="{{ $superviseurs->PeNom.' '.$superviseurs->PePrenom }}" readonly/>
                                                <input type="hidden" id="superviseur_site" name="superviseur_site" class="form-control" value="{{ $superviseurs->PeNumero }}"/>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-md-4">
                                            <div class="form-group">
                                                <label for="ville_site">Ville</label>
                                                <input type="text" class="form-control" value="{{ $villes->ViNom }}" readonly/>
                                                <input type="hidden" id="ville_site" name="ville_site" class="form-control" value="{{ $villes->ViNumero }}"/>
                                            </div>
                                        </div>

                                        @if($villes->ViIsZoneOn == "N")
                                            <input type="hidden" id="zone_site" name="zone_site" class="form-control" value="all"/>
                                        @else
                                            <div class="col-xl-4 col-md-4">
                                                <div class="form-group">
                                                    <label for="zone_site">Zone</label>
                                                    <input type="text" class="form-control" value="{{ $ZoNom }}" readonly/>
                                                    <input type="hidden" id="zone_site" name="zone_site" class="form-control" value="{{ $ZoNumero }}"/>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-xl-4 col-md-4">
                                            <div class="form-group">
                                                <label for="">Afficher</label><br>
                                                <button type="submit" class="btn btn-danger"><i class="fa ft-filter"></i> Afficher</button>
                                            </div>
                                        </div>
                                    </div>
                                </form> -->
                            @else
                                <form action="{{ route('supervision-site.filtre') }}" method="POST">
                                    @csrf

                                    <div class="row">
                                        <div class="col-xl-4 col-md-4">
                                            <div class="form-group">
                                                <label for="">Période du</label>
                                                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $start_date }}" max="{{ date('Y-m-d') }}"/>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-md-4">
                                            <div class="form-group">
                                                <label for="">Au</label>
                                                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $end_date }}" max="{{ date('Y-m-d') }}"/>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-md-4">
                                            <div class="form-group">
                                                @if(Auth::user()->type == 'S')
                                                    <label for="superviseur_site">Superviseur</label>
                                                @elseif(Auth::user()->type == 'T')
                                                    <label for="superviseur_site">Contrôleur</label>
                                                @else
                                                    <label for="superviseur_site">Superviseur</label>
                                                @endif
                                                <select id="superviseur_site" name="superviseur_site" class="form-control e5">
                                                    <option selected="selected" value="all">Tous</option>
                                                    @foreach($superviseurs as $superviseur)
                                                        <option value="{{ $superviseur->PeNumero }}" {{ $superviseur->PeNumero == $superviseur_site ? 'selected' : '' }}>{{ $superviseur->PeNom.' '.$superviseur->PePrenom }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-md-4">
                                            <div class="form-group">
                                                <label for="ville_site">Ville</label>
                                                <select id="ville_site" name="ville_site" class="form-control e6">
                                                    <option selected="selected" value="all">Tous</option>
                                                    @foreach($villes as $ville)
                                                        <option value="{{ $ville->ViNumero }}" {{ $ville->ViNumero == $ville_site ? 'selected' : '' }}>{{ $ville->ViNom }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-md-4">
                                            <div class="form-group">
                                                <label for="zone_site">Zone</label>
                                                <select id="zone_site" name="zone_site" class="form-control e7">
                                                    <option selected="selected" value="all">Tous</option>
                                                    @foreach($zones as $zone)
                                                        <option value="{{ $zone->ZoNumero }}" {{ $zone->ZoNumero == $zone_site ? 'selected' : '' }}>{{ $zone->ZoNom }}</option>
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
                            @endif

                            <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                                <thead>
                                    <tr>
                                        @if(Auth::user()->type == 'S')
                                            <th>Superviseur</th>
                                        @elseif(Auth::user()->type == 'T')
                                            <th>Contrôleur</th>
                                        @else
                                            <th>Superviseur</th>
                                        @endif
                                        <th>N° Téléphone</th>
                                        <th>Date</th>
                                        <th>Faction</th>
                                        <th>Heure début</th>
                                        <th>Heure Fin</th>
                                        <th>Ville</th>
                                        <th>Zone</th>
                                        <th>Statut</th>
                                        <th>Obs.</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supervision_sites as $supervision_site)
                                        <tr>
                                            <td>{{ $supervision_site->PeNom.' '.$supervision_site->PePrenom }}</td>
                                            <td>{{ $supervision_site->PeTel1 }}</td>
                                            <td>{{ date('d/m/Y',strtotime($supervision_site->OsDate)) }}</td>

                                            @if($supervision_site->OsFaction == 'J')
                                                <td>Jour</td>
                                            @else
                                                <td>Nuit</td>
                                            @endif

                                            <td>{{ date('H:i',strtotime($supervision_site->OsHeureDebut)) }}</td>
                                            <td>{{ $supervision_site->OsHeureFin == NULL ? '' : date('H:i',strtotime($supervision_site->OsHeureFin)) }}</td>
                                            <td>{{ $supervision_site->ViCode }}</td>
                                            <td>{{ $supervision_site->ZoNom }}</td>

                                            @if($supervision_site->OsStatut == 'I')
                                                <td>Initialisé</td>
                                            @elseif($supervision_site->OsStatut == 'S')
                                                <td>Saisie</td>
                                            @elseif($supervision_site->OsStatut == 'T')
                                                <td>Traité</td>
                                            @elseif($supervision_site->OsStatut == 'V')
                                                <td>Validée</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td>{{ $supervision_site->OsDetails }}</td>

                                            <td style="text-align: center;">
                                                <a onclick="showItem('{{$supervision_site->OsNumero}}')" class="info p-0" id="view">
                                                    <i class="ft-eye font-medium-3 mr-2"></i>
                                                </a>

                                                <a href="#" onclick="editItem('{{$supervision_site->OsNumero}}')" class="warning p-0" id="editor">
                                                    <i class="ft-edit font-medium-3 mr-2"></i>
                                                </a>

                                                @if($supervision_site->OsHeureFin == null)
                                                    <a href="{{ route('supervision-site.sites',crypter($supervision_site->OsNumero)) }}" class="primary p-0" id="sites">
                                                        <i class="ft-align-right font-medium-3 mr-2"></i>
                                                    </a>
                                                @endif

                                                @if(Auth::user()->type != 'S' || Auth::user()->type != 'T')
                                                    <a href="{{ route('supervision-site.pointage-agents',crypter($supervision_site->OsNumero)) }}" class="info p-0" id="operations">
                                                        <i class="ft-edit-3 font-medium-3 mr-2"></i>
                                                    </a>
                                                @endif

                                                <a href="#" onclick="fermerItem('{{$supervision_site->OsNumero}}')" class="danger p-0" id="fermer">
                                                    <i class="ft-clock font-medium-3 mr-2"></i>
                                                </a>

                                                <a target="_blank" href="{{ route('supervision-site.print',crypter($supervision_site->OsNumero)) }}" class="danger p-0" id="print">
                                                    <i class="ft-printer font-medium-3 mr-2"></i>
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

                <form method="POST" action="{{ route('supervision-sites.store') }}" class="needs-validation" novalidate>
                    @csrf

                    <div class="modal-header bg-info white">
                        <h4 class="modal-title" id="myModalLabel16">NOUVELLE RONDE DE CONTRÔLEUR</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div id="afficher_avertir_ronde"></div><br>

                        <div class="row">
                            @if(Auth::user()->type == 'S' || Auth::user()->type == 'T')
                                <div class="col-xl-6 col-md-6">
                                    <div class="form-group">
                                        @if(Auth::user()->type == 'S')
                                            <label for="OsSuperviseur">Superviseur <abbr style="color: #bd4147;">(*)</abbr></label>
                                        @elseif(Auth::user()->type == 'T')
                                            <label for="OsSuperviseur">Contrôleur <abbr style="color: #bd4147;">(*)</abbr></label>
                                        @else
                                            <label for="OsSuperviseur">Superviseur <abbr style="color: #bd4147;">(*)</abbr></label>
                                        @endif
                                        <input type="text" class="form-control" value="{{ $superviseurs->PeNom.' '.$superviseurs->PePrenom }}" readonly/>
                                        <input type="hidden" id="OsSuperviseur" name="OsSuperviseur" class="form-control" value="{{ $superviseurs->PeNumero }}"/>
                                    </div>
                                </div>
                            @else
                                <div class="col-xl-6 col-md-6">
                                    <div class="form-group">
                                        @if(Auth::user()->type == 'S')
                                            <label for="OsSuperviseur">Superviseur <abbr style="color: #bd4147;">(*)</abbr></label>
                                        @elseif(Auth::user()->type == 'T')
                                            <label for="OsSuperviseur">Contrôleur <abbr style="color: #bd4147;">(*)</abbr></label>
                                        @else
                                            <label for="OsSuperviseur">Superviseur <abbr style="color: #bd4147;">(*)</abbr></label>
                                        @endif
                                        <select class="e1 form-control" id="OsSuperviseur" name="OsSuperviseur" required>
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
                            @endif

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="OsDate">Date opération <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" class="form-control" id="OsDate" name="OsDate" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="afficher_site_ville_superviseur">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="OsVille">Ville</label>
                                    <input type="text" class="form-control" readonly/>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>Site client</label>
                                    <input type="text" class="form-control" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6" id="afficher_zones_ville">
                                <div class="form-group">
                                    <label for="OsZone">Zones <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e3 form-control" id="OsZone" name="OsZone">
                                        <option selected value="">Selectionner...</option>
                                    </select>
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
                                            <input type="radio" id="customRadioInline1" name="OsFaction" class="custom-control-input" value="J" checked/>
                                            <label class="custom-control-label" for="customRadioInline1">Jour</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline2" name="OsFaction" class="custom-control-input" value="N"/>
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
                                    <label for="OsHeureDebut">Heure de début <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="time" class="form-control" id="OsHeureDebut" name="OsHeureDebut" value="{{ date('H:i', strtotime(date('H:i'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="OsDetails">Détails</label>
                                    <textarea rows="2" class="form-control" id="OsDetails" name="OsDetails" placeholder="Commentaires"></textarea>
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
                        <h5 class="modal-title" id="myModalLabel16">MODIFICATION RONDE DE CONTRÔLEUR</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div id="afficher_avertir_ronde"></div><br>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    @if(Auth::user()->type == 'S')
                                        <label for="OsSuperviseur">Superviseur</label>
                                    @elseif(Auth::user()->type == 'T')
                                        <label for="OsSuperviseur">Contrôleur</label>
                                    @else
                                        <label for="OsSuperviseur">Superviseur</label>
                                    @endif
                                    <input type="text" class="form-control OsSuperviseur1" readonly/>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="OsDate">Date opération</label>
                                    <input type="text" class="form-control OsDate1" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="OsVille">Ville</label>
                                    <input type="text" class="form-control OsVille1" readonly/>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="OsZone">Zone</label>
                                    <input type="text" class="form-control OsZone1" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="OsFaction1">Faction</label>
                                    <input type="text" class="form-control OsFaction1" readonly/>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="OsHeureDebut">Heure de début <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="time" class="form-control OsHeureDebut" id="OsHeureDebut" name="OsHeureDebut" value="{{ date('H:i', strtotime(date('H:i'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                             <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="OsStatut">Statut</label>
                                    <input type="text" class="form-control OsStatut" readonly/>
                                </div>
                            </div>

                             <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="OsDetails">Détails</label>
                                    <textarea rows="2" class="form-control" id="OsDetails" name="OsDetails" placeholder="Commentaires"></textarea>
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
                    <h5 class="modal-title" id="myModalLabel16">INFORMATIONS RONDE DE CONTRÔLEUR</h5>
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
                                            @if(Auth::user()->type == 'S')
                                                <th scope="row">Superviseur</th>
                                            @elseif(Auth::user()->type == 'T')
                                                <th scope="row">Contrôleur</th>
                                            @else
                                                <th scope="row">Superviseur</th>
                                            @endif
                                            <td id="superviseur"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date</th>
                                            <td id="date"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Ville</th>
                                            <td id="ville"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Zone</th>
                                            <td id="zone"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Heure de début</th>
                                            <td id="heure_debut"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Heure de fin</th>
                                            <td id="heure_fin"></td>
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
                                            <th scope="row">Faction</th>
                                            <td id="faction"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Retour Fiche</th>
                                            <td id="retour_fiche"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date retour fiche</th>
                                            <td id="date_retour_fiche"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Signature superviseur</th>
                                            <td id="superviseur_signe"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Statut</th>
                                            <td id="statut"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Détails</th>
                                            <td id="detail"></td>
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

    <!-- FERMER -->
    <div class="modal fade text-left" id="edit_fermer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">

                <form method="POST" action="#" class="form-edit-fermer needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="_method" value="PUT">

                    <div class="modal-header bg-info white">
                        <h5 class="modal-title" id="myModalLabel16">FERMER RONDE DE CONTRÔLEUR</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    @if(Auth::user()->type == 'S')
                                        <label for="OsSuperviseur">Superviseur</label>
                                    @elseif(Auth::user()->type == 'T')
                                        <label for="OsSuperviseur">Contrôleur</label>
                                    @else
                                        <label for="OsSuperviseur">Superviseur</label>
                                    @endif
                                    <input type="text" class="form-control OsSuperviseur" readonly/>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="OsDate">Date opération</label>
                                    <input type="text" class="form-control OsDate" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="site_client">Site client</label>
                                    <input type="text" class="form-control site_client" readonly/>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="OsVille">Ville</label>
                                    <input type="text" class="form-control OsVille" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="OsZone">Zones</label>
                                    <input type="text" class="form-control OsZone" readonly/>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="OsHeureDebut">Heure de début</label>
                                    <input type="text" class="form-control OsHeureDebut" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="OsFaction">Faction</label>
                                    <input type="text" class="form-control OsFaction" readonly/>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="OsHeureFin">Heure de fin</label>
                                    <input type="time" class="form-control OsHeureFin" id="OsHeureFin" name="OsHeureFin" value="{{ date('H:i', strtotime(date('H:i'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>Retour fiche physique ?</label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline3" name="OsIsRetourFiche" class="custom-control-input OsIsRetourFiche_Y" value="Y" checked/>
                                            <label class="custom-control-label" for="customRadioInline3">Oui</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline4" name="OsIsRetourFiche" class="custom-control-input OsIsRetourFiche_N" value="N"/>
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
                                    <label for="OsDateHFiche">Date retour fiche </label>
                                    <input type="date" class="form-control OsDateHFiche" id="OsDateHFiche" name="OsDateHFiche"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="OsStatut">Selectionner le statut</label>
                                    <select class="form-control OsStatut e4" id="OsStatut" name="OsStatut" required>
                                        <option selected value="">Selectionner...</option>
                                        <option value="I">Initialisé</option>
                                        <option value="S">Saisie</option>
                                        <option value="T">Traité</option>
                                        <option value="V">Validée</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="OsDetails">Détails</label>
                                    <textarea rows="2" class="form-control" id="OsDetails" name="OsDetails" placeholder="Commentaires"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler <i class="fa fa-refresh position-right"></i></button>
                        <button type="submit" class="btn btn-info">Fermer <i class="fa fa-thumbs-o-up position-right"></i></button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!-- FERMER -->

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

        tippy('#sites', {
            content: "Sites Supervisés",
            placement: 'bottom',
        });

        tippy('#operations', {
            content: "Pointage agents sur site",
            placement: 'top',
        });

        tippy('#fermer', {
            content: "Fin Supervision",
            placement: 'bottom',
        });

        tippy('#print', {
            content: "Imprimer Fiche de Supervision",
            placement: 'top',
        });
    </script>

    <script>
        $(document).ready(function(){
            $("#create,#edit,#xlarge,#edit_fermer").modal({
                show:false,
                backdrop:'static'
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $(".e1").select2({
                width: '100%',
                dropdownParent: $("#create")
            });
            $(".e2").select2({
                width: '100%',
                dropdownParent: $("#create")
            });
            $(".e3").select2({
                width: '100%',
                dropdownParent: $("#create")
            });


            $(".e5").select2();
            $(".e6").select2();
            $(".e7").select2();
            $(".e8").select2();
        });
    </script>

    <script type="text/javascript">
        function showItem(OsNumero) {
            $.ajax({
                url: '{{ route("supervision-site.show", "") }}/'+OsNumero,
                type: 'GET',
                success: function(data){
                    $('#superviseur').html(data.PeNom+' '+data.PePrenom)
                    $('#date').html(data.OsDate)
                    $('#ville').html(data.ViNom)
                    $('#zone').html(data.ZoNom)
                    $('#heure_debut').html(data.OsHeureDebut)
                    $('#heure_fin').html(data.OsHeureFin)
                    $('#date_retour_fiche').html(data.OsDateHFiche)
                    $('#detail').html(data.OsDetails)

                    if((data.OsFaction) == 'J')
                    {
                        $('#faction').html('Jour');
                    }
                    else
                    {
                        $('#faction').html('Nuit');
                    }

                    if((data.OsIsRetourFiche) == 'J')
                    {
                        $('#retour_fiche').html('Oui');
                    }
                    else if((data.OsIsRetourFiche) == 'N')
                    {
                        $('#retour_fiche').html('Nuit');
                    }
                    else
                    {
                        $('#retour_fiche').html('');
                    }

                    if((data.OsIsSupervSigne) == 'Y')
                    {
                        $('#superviseur_signe').html('Signé');
                    }
                    else if((data.OsIsSupervSigne) == 'N')
                    {
                        $('#superviseur_signe').html('Non signé');
                    }
                    else
                    {
                        $('#superviseur_signe').html('');
                    }

                    if((data.OsStatut) == 'I')
                    {
                        $('#statut').html('Initialisé');
                    }
                    else if((data.OsStatut) == 'S')
                    {
                        $('#statut').html('Saisie');
                    }
                    else if((data.OsStatut) == 'T')
                    {
                        $('#statut').html('Traité');
                    }
                    else if((data.OsStatut) == 'V')
                    {
                        $('#statut').html('Validée');
                    }
                    else
                    {
                        $('#statut').html('');
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
        function editItem(OsNumero) {
            $.ajax({
                url: '{{ route("supervision-site.edit", "") }}/' + OsNumero,
                type: 'GET',
                success: function(data){

                    var OsNumero=data.OsNumero;

                    $(".form-edit").attr('action', '{{ route("supervision-site.update", "") }}/' + OsNumero);
                    var form=$(".form-edit").attr('action');

                    $(".OsDate1").val(data.OsDate);
                    $(".OsVille2").val(data.ViNumero);
                    $(".OsVille1").val(data.ViNom);
                    $(".OsZone1").val(data.ZoNom);
                    $(".OsSuperviseur2").val(data.OsSuperviseur);
                    $(".OsSuperviseur1").val(data.PeNom+' '+data.PePrenom);
                    $(".OsHeureDebut").val(data.OsHeureDebut);
                    $(".OsDetails").val(data.OsDetails);

                    if((data.OsFaction) =='J')
                    {
                        $('.OsFaction1').val('Jour');
                    }
                    else
                    {
                        $('.OsFaction1').val('Nuit');
                    }

                    if((data.OsStatut) == 'I')
                    {
                        $('.OsStatut').val('Initialisé');
                    }
                    else if((data.OsStatut) == 'S')
                    {
                        $('.OsStatut').val('Saisie');
                    }
                    else if((data.OsStatut) == 'T')
                    {
                        $('.OsStatut').val('Traité');
                    }
                    else if((data.OsStatut) == 'V')
                    {
                        $('.OsStatut').val('Validée');
                    }
                    else
                    {
                        $('.OsStatut').val('');
                    }

                    $('#edit').modal('show').fadeIn('slow')
                },
                error: function(){
                    console.log("Something goes wrong")
                }
            })
        }
    </script>

    <script type="text/javascript">
        function fermerItem(OsNumero) {
            $.ajax({
                url: '{{ route("supervision-site.edit-fermer", "") }}/' + OsNumero,
                type: 'GET',
                success: function(data){

                    var OsNumero=data.OsNumero;

                    $(".form-edit-fermer").attr('action', '{{ route("supervision-site.update-fermer", "") }}/' + OsNumero);
                    var form=$(".form-edit-fermer").attr('action');

                    $(".OsDate").val(data.OsDate);
                    $(".OsVille").val(data.ViNom);
                    $(".OsZone").val(data.ZoNom);
                    $(".OsSuperviseur").val(data.PeNom+' '+data.PePrenom);
                    $(".OsHeureDebut").val(data.OsHeureDebut);
                    $(".OsDetails").val(data.OsDetails);
                    $(".OsStatut").val(data.OsStatut);
                    $(".site_client").val(data.ScNomSite);

                    if((data.OsFaction) == 'J')
                    {
                        $('.OsFaction').val('Jour');
                    }
                    else
                    {
                        $('.OsFaction').val('Nuit');
                    }

                    $(".e4").select2({
                        width: '100%',
                    });

                    $('#edit_fermer').modal('show').fadeIn('slow')
                },
                error: function(){
                    console.log("Something goes wrong")
                }
            })
        }
    </script>

    <script>
        $(document).ready(function() {
            $("input[type=radio][name=OsIsRetourFiche]").click(function()
            {
                if($(this).val() == 'Y')
                {
                    $("#OsDateHFiche").attr("required",true);
                }
                else
                {
                    $("#OsDateHFiche").attr("required",false);
                }
            });

            if($("input[name='OsIsRetourFiche']:checked").val()=='Y')
            {
                $("#OsDateHFiche").attr("required",true);
            }
            else
            {
                $("#OsDateHFiche").attr("required",false);
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $("input[type=radio][name=OsFaction]").click(function()
            {
                if($(this).val() == 'J')
                {
                    $("#OsHeureDebut").val('06:00');
                }
                else
                {
                    $("#OsHeureDebut").val('18:00');
                }
            });

            if($("input[name='OsFaction']:checked").val()=='J')
            {
                $("#OsHeureDebut").val('06:00');
            }
            else
            {
                $("#OsHeureDebut").val('18:00');
            }
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#OsSuperviseur").change(function()
            {
                var OsSuperviseur =  $("#OsSuperviseur").val();
                var OsDate =  $("#OsDate").val();
                var OsFaction = getSelectedValue('OsFaction');

                $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                    $("#afficher_avertir_ronde").html(rep);

                    $("#OsDate").change(function()
                    {
                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();
                        var OsFaction = getSelectedValue('OsFaction');

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    });

                    if($("#OsDate").val() != null) {

                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();
                        var OsFaction = getSelectedValue('OsFaction');

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    }


                    $("input[type=radio][name=OsFaction]").click(function()
                    {
                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();
                        var OsFaction = $(this).val();

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    });

                    if($("input[name='OsFaction']:checked").val()=='J')
                    {
                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();
                        var OsFaction = getSelectedValue('OsFaction');

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    }
                    else
                    {
                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();
                        var OsFaction = getSelectedValue('OsFaction');

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    }
                });
            });

            if($("#OsSuperviseur").val() != null) {

                var OsSuperviseur =  $("#OsSuperviseur").val();
                var OsDate =  $("#OsDate").val();
                var OsFaction = getSelectedValue('OsFaction');

                $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                    $("#afficher_avertir_ronde").html(rep);

                    //Changement de la date
                    $("#OsDate").change(function()
                    {
                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();
                        var OsFaction = getSelectedValue('OsFaction');

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    });

                    if($("#OsDate").val() != null) {

                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();
                        var OsFaction = getSelectedValue('OsFaction');

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    }



                });
            }
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#OsDate").change(function()
            {
                var OsSuperviseur =  $("#OsSuperviseur").val();
                var OsDate =  $("#OsDate").val();
                var OsFaction = getSelectedValue('OsFaction');

                $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                    $("#afficher_avertir_ronde").html(rep);

                    $("#OsSuperviseur").change(function()
                    {
                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();
                        var OsFaction = getSelectedValue('OsFaction');

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    });

                    if($("#OsSuperviseur").val() != null) {

                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();
                        var OsFaction = getSelectedValue('OsFaction');

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    }


                    $("input[type=radio][name=OsFaction]").click(function()
                    {
                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();
                        var OsFaction = $(this).val();

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    });

                    if($("input[name='OsFaction']:checked").val()=='J')
                    {
                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();
                        var OsFaction = getSelectedValue('OsFaction');

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    }
                    else
                    {
                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();
                        var OsFaction = getSelectedValue('OsFaction');

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    }
                });
            });

            if($("#OsDate").val() != null) {

                var OsSuperviseur =  $("#OsSuperviseur").val();
                var OsDate =  $("#OsDate").val();
                var OsFaction = getSelectedValue('OsFaction');

                $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                    $("#afficher_avertir_ronde").html(rep);

                    $("#OsSuperviseur").change(function()
                    {
                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();
                        var OsFaction = getSelectedValue('OsFaction');

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    });

                    if($("#OsSuperviseur").val() != null) {

                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();
                        var OsFaction = getSelectedValue('OsFaction');

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    }


                    if($("input[name='OsFaction']:checked").val()=='J')
                    {
                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();
                        var OsFaction = getSelectedValue('OsFaction');

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    }
                    else
                    {
                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();
                        var OsFaction = getSelectedValue('OsFaction');

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    }

                });
            }
        });
    </script>


    <script>
        $(document).ready(function() {
            $("input[type=radio][name=OsFaction]").click(function()
            {
                var OsSuperviseur =  $("#OsSuperviseur").val();
                var OsDate =  $("#OsDate").val();
                var OsFaction = $(this).val();

                $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                    $("#afficher_avertir_ronde").html(rep);

                    $("#OsDate").change(function()
                    {
                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();
                        var OsFaction = $(this).val();

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    });

                    if($("#OsDate").val() != null) {

                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();
                        var OsFaction = $(this).val();

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    }


                    $("#OsSuperviseur").change(function()
                    {
                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();
                        var OsFaction = $(this).val();

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    });

                    if($("#OsSuperviseur").val() != null) {

                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();
                        var OsFaction = $(this).val();

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+OsFaction, function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    }

                });
            });

            if($("input[name='OsFaction']:checked").val()=='J')
            {
                var OsSuperviseur =  $("#OsSuperviseur").val();
                var OsDate =  $("#OsDate").val();

                $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+'J', function(rep) {
                    $("#afficher_avertir_ronde").html(rep);

                    $("#OsDate").change(function()
                    {
                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();
                        var OsFaction = getSelectedValue('OsFaction');

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+'J', function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    });

                    if($("#OsDate").val() != null) {

                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();
                        var OsFaction = getSelectedValue('OsFaction');

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+'J', function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    }

                });
            }
            else
            {
                var OsSuperviseur =  $("#OsSuperviseur").val();
                var OsDate =  $("#OsDate").val();

                $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+'N', function(rep) {
                    $("#afficher_avertir_ronde").html(rep);

                    $("#OsDate").change(function()
                    {
                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+'N', function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
                        });
                    });

                    if($("#OsDate").val() != null) {

                        var OsSuperviseur =  $("#OsSuperviseur").val();
                        var OsDate =  $("#OsDate").val();

                        $.get('{{ route("supervision-site.avertir-ronde",["","",""]) }}/'+OsSuperviseur+'/'+OsDate+'/'+'N', function(rep) {
                            $("#afficher_avertir_ronde").html(rep);
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
            const superviseur_site = document.getElementById('superviseur_site')?.value || 'all';
            const ville_site = document.getElementById('ville_site')?.value || 'all';
            const zone_site = document.getElementById('zone_site')?.value || 'all';

            // Construire l'URL avec tous les paramètres
            let url = "{{ route('supervision-site.imprimer') }}?";
            url += `start_date=${encodeURIComponent(startDate)}`;
            url += `&end_date=${encodeURIComponent(endDate)}`;
            url += `&superviseur_site=${encodeURIComponent(superviseur_site)}`;
            url += `&ville_site=${encodeURIComponent(ville_site)}`;
            url += `&zone_site=${encodeURIComponent(zone_site)}`;

            // Ouvrir dans un nouvel onglet
            window.open(url, '_blank');
        }
    </script>


    <script type="text/javascript">
        $(document).ready(function() {
            $("#OsSuperviseur").change(function()
            {
                var OsSuperviseur =  $("#OsSuperviseur").val();

                $.get('{{ route("supervision-site.show_ville_superviseur","") }}/'+OsSuperviseur, function(rep) {
                    $("#afficher_site_ville_superviseur").html(rep);

                    $("#OsVille").change(function()
                    {
                        var OsVille =  $("#OsVille").val();

                        $.get('{{ route("supervision-site.zones_ville","") }}/'+OsVille, function(rep) {
                            $("#afficher_zones_ville").html(rep);
                        });
                    });

                    if($("#OsVille").val() != null) {
                        var OsVille =  $("#OsVille").val();

                        $.get('{{ route("supervision-site.zones_ville","") }}/'+OsVille, function(rep) {
                            $("#afficher_zones_ville").html(rep);
                        });
                    }
                });
            });

            if($("#OsSuperviseur").val() != null) {
                var OsSuperviseur =  $("#OsSuperviseur").val();

                $.get('{{ route("supervision-site.show_ville_superviseur","") }}/'+OsSuperviseur, function(rep) {
                    $("#afficher_site_ville_superviseur").html(rep);

                    $("#OsVille").change(function()
                    {
                        var OsVille =  $("#OsVille").val();

                        $.get('{{ route("supervision-site.zones_ville","") }}/'+OsVille, function(rep) {
                            $("#afficher_zones_ville").html(rep);
                        });
                    });

                    if($("#OsVille").val() != null) {
                        var OsVille =  $("#OsVille").val();

                        $.get('{{ route("supervision-site.zones_ville","") }}/'+OsVille, function(rep) {
                            $("#afficher_zones_ville").html(rep);
                        });
                    }
                });
            }
        });
    </script>
@endsection

