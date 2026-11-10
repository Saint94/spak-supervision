@extends('layout.app')

@section('title', 'Liste des sites supervisés')

@section('content')

@include('layout.alert')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Liste des pointage des agent supervisés par</b></h4>
                        <h4 class="card-title">
                            @if(Auth::user()->type == 'S')
                                <b>Superviseur : </b>{{ $supervision_site->PeNom.' '.$supervision_site->PePrenom }},
                            @elseif(Auth::user()->type == 'T')
                                <b>Contrôleur : </b>{{ $supervision_site->PeNom.' '.$supervision_site->PePrenom }},
                            @else
                                <b>Superviseur : </b>{{ $supervision_site->PeNom.' '.$supervision_site->PePrenom }},
                            @endif
                            <b>Date : </b>{{ date('d/m/Y',strtotime($supervision_site->OsDate)) }},
                            <b>Faction : </b>{{ $supervision_site->OsFaction == 'J' ? 'Jour':'Nuit' }},
                            <b>Heure de début : </b>{{ date('H:i',strtotime($supervision_site->OsHeureDebut)) }},
                            <b>Ville : </b>{{ $supervision_site->ViNom }},
                            <b>Zone : </b>{{ $supervision_site->ZoNom }}
                        </h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard table-responsive">

                            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create_pointage_agent"><i class="ft-plus"></i>&nbsp;Ajouter pointage agent sur site</button>

                            @if(Auth::user()->type == 'S' || Auth::user()->type == 'T')
                                <form action="{{ route('pointage-agents.filtre') }}" method="POST">
                                    @csrf

                                    <input type="hidden" class="form-control" name="OsNumero" value="{{ $OsNumero }}"/>

                                    <div class="row">
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="">Période du</label>
                                                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $start_date }}"/>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="">Au</label>
                                                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $end_date }}"/>
                                            </div>
                                        </div>

                                        @if(Auth::user()->type == 'S')
                                        <div class="col-xl-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="superviseur_site">Superviseur</label>
                                                    <input type="text" class="form-control" value="{{ $supervision_site->PeNom.' '.$supervision_site->PePrenom }}" readonly/>
                                                    <input type="hidden" id="superviseur_site" name="superviseur_site" class="form-control" value="{{ $supervision_site->PeNumero }}"/>
                                                </div>
                                            </div>
                                        @elseif(Auth::user()->type == 'T')
                                        <div class="col-xl-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="superviseur_site">Contrôleur</label>
                                                    <input type="text" class="form-control" value="{{ $supervision_site->PeNom.' '.$supervision_site->PePrenom }}" readonly/>
                                                    <input type="hidden" id="superviseur_site" name="superviseur_site" class="form-control" value="{{ $supervision_site->PeNumero }}"/>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-xl-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="superviseur_site">Superviseur</label>
                                                    <input type="text" class="form-control" value="{{ $supervision_site->PeNom.' '.$supervision_site->PePrenom }}" readonly/>
                                                    <input type="hidden" id="superviseur_site" name="superviseur_site" class="form-control" value="{{ $supervision_site->PeNumero }}"/>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="numero_client">Clients</label>
                                                <select id="numero_client" name="numero_client" class="form-control e1">
                                                    <option selected="selected" value="all">Tous</option>
                                                    @foreach($clients as $client)
                                                        <option value="{{ $client->ClNumero }}" {{ $client->ClNumero == $numero_client ? 'selected' : '' }}>{{ $client->ClDesign }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="site_client">Site</label>
                                                <select id="site_client" name="site_client" class="form-control e2">
                                                    <option selected="selected" value="all">Tous</option>
                                                    @foreach($sites_client as $site_client)
                                                        <option value="{{ $site_client->ScNumero }}" {{ old('site_client', 'all') == $site_client->ScNumero ? 'selected' : '' }}> {{ $site_client->ScNomSite }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="ville_site">Ville</label>
                                                <select id="ville_site" name="ville_site" class="form-control e3">
                                                    <option selected="selected" value="all">Tous</option>
                                                    @foreach($villes as $ville)
                                                        <option value="{{ $ville->ViNumero }}" {{ $ville->ViNumero == $ville_site ? 'selected' : '' }}>{{ $ville->ViNom }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="zone_site">Zone</label>
                                                <select id="zone_site" name="zone_site" class="form-control e4">
                                                    <option selected="selected" value="all">Tous</option>
                                                    @foreach($zones as $zone)
                                                        <option value="{{ $zone->ZoNumero }}" {{ $zone->ZoNumero == $zone_site ? 'selected' : '' }}>{{ $zone->ZoNom }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="quartier_site">Quartier</label>
                                                <select id="quartier_site" name="quartier_site" class="form-control e5">
                                                    <option selected="selected" value="all">Tous</option>
                                                    @foreach($quartiers as $quartier)
                                                        <option value="{{ $quartier->QuNumero }}" {{ $quartier->QuNumero == $quartier_site ? 'selected' : '' }}>{{ $quartier->QuNom }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="pointage_site">Pointage</label>
                                                <select id="pointage_site" name="pointage_site" class="form-control e6">
                                                    <option selected="selected" value="all">Tous</option>
                                                    <option value="C" {{ $client->ClNumero == $numero_client ? 'selected' : '' }}>Conforme</option>
                                                    <option value="N" {{ $client->ClNumero == $numero_client ? 'selected' : '' }}>Non Conforme</option>
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
                            @else
                            <form action="{{ route('pointage-agents.filtre') }}" method="POST">
                                    @csrf

                                    <input type="hidden" class="form-control" name="OsNumero" value="{{ $OsNumero }}"/>

                                    <div class="row">
                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="">Période du</label>
                                                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $start_date }}"/>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="">Au</label>
                                                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $end_date }}"/>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                @if(Auth::user()->type == 'S')
                                                    <label for="superviseur_site">Superviseur</label>
                                                @elseif(Auth::user()->type == 'T')
                                                    <label for="superviseur_site">Contrôleur</label>
                                                @else
                                                    <label for="superviseur_site">Superviseur</label>
                                                @endif
                                                <select id="superviseur_site" name="superviseur_site" class="form-control e50">
                                                    <option selected="selected" value="all">Tous</option>
                                                    @foreach($superviseurs as $superviseur)
                                                        <option value="{{ $superviseur->PeNumero }}" {{ $superviseur->PeNumero == $superviseur_site ? 'selected' : '' }}>{{ $superviseur->PeNom.' '.$superviseur->PePrenom }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="numero_client">Clients</label>
                                                <select id="numero_client" name="numero_client" class="form-control e1">
                                                    <option selected="selected" value="all">Tous</option>
                                                    @foreach($clients as $client)
                                                        <option value="{{ $client->ClNumero }}" {{ $client->ClNumero == $numero_client ? 'selected' : '' }}>{{ $client->ClDesign }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="site_client">Site</label>
                                                <select id="site_client" name="site_client" class="form-control e2">
                                                    <option selected="selected" value="all">Tous</option>
                                                    @foreach($sites_client as $site_client)
                                                        <option value="{{ $site_client->ScNumero }}" {{ old('site_client', 'all') == $site_client->ScNumero ? 'selected' : '' }}> {{ $site_client->ScNomSite }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="ville_site">Ville</label>
                                                <select id="ville_site" name="ville_site" class="form-control e3">
                                                    <option selected="selected" value="all">Tous</option>
                                                    @foreach($villes as $ville)
                                                        <option value="{{ $ville->ViNumero }}" {{ $ville->ViNumero == $ville_site ? 'selected' : '' }}>{{ $ville->ViNom }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="zone_site">Zone</label>
                                                <select id="zone_site" name="zone_site" class="form-control e4">
                                                    <option selected="selected" value="all">Tous</option>
                                                    @foreach($zones as $zone)
                                                        <option value="{{ $zone->ZoNumero }}" {{ $zone->ZoNumero == $zone_site ? 'selected' : '' }}>{{ $zone->ZoNom }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="quartier_site">Quartier</label>
                                                <select id="quartier_site" name="quartier_site" class="form-control e5">
                                                    <option selected="selected" value="all">Tous</option>
                                                    @foreach($quartiers as $quartier)
                                                        <option value="{{ $quartier->QuNumero }}" {{ $quartier->QuNumero == $quartier_site ? 'selected' : '' }}>{{ $quartier->QuNom }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3">
                                            <div class="form-group">
                                                <label for="pointage_site">Pointage</label>
                                                <select id="pointage_site" name="pointage_site" class="form-control e6">
                                                    <option selected="selected" value="all">Tous</option>
                                                    <option value="C" {{ $client->ClNumero == $numero_client ? 'selected' : '' }}>Conforme</option>
                                                    <option value="N" {{ $client->ClNumero == $numero_client ? 'selected' : '' }}>Non Conforme</option>
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
                            @endif

                            <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                                <thead>
                                    <tr>
                                        <th>Site client</th>
                                        <th>Agent prog</th>
                                        <th>N° Téléphone</th>
                                        <th>Agent opér</th>
                                        <th>Heure arrivée</th>
                                        <th>Heure départ</th>
                                        <th>Présent°</th>
                                        <th>Obs Super</th>
                                        <th>Obs Client</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pointage_agents as $pointage_agent)
                                        <tr>
                                            <td>{{ $pointage_agent->ScNomSite }}</td>
                                            <td>{{ $pointage_agent->agent_pgme_nom.' '.$pointage_agent->agent_pgme_prenom }}</td>
                                            <td>{{ $pointage_agent->agent_pgme_tel1 }}</td>
                                            <td>{{ $pointage_agent->agent_oper_nom.' '.$pointage_agent->agent_oper_prenom }}</td>
                                            <td>{{ $pointage_agent->SoHeureArrivAg }}</td>
                                            <td>{{ $pointage_agent->SoHeureDepart }}</td>

                                            @if($pointage_agent->SoPresentation == 'T')
                                                <td>Très Bonne</td>
                                            @elseif($pointage_agent->SoPresentation == 'B')
                                                <td>Bonne</td>
                                            @elseif($pointage_agent->SoPresentation == 'P')
                                                <td>Passable</td>
                                            @elseif($pointage_agent->SoPresentation == 'M')
                                                <td>Mauvais</td>
                                            @elseif($pointage_agent->SoPresentation == 'V')
                                                <td>Très Mauvais</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td>{{ $pointage_agent->SoObservSuperv }}</td>
                                            <td>{{ $pointage_agent->SoObservClt }}</td>

                                        @if($pointage_agent->SoStatut == 'I')
                                                <td>Initialisé</td>
                                            @elseif($pointage_agent->SoStatut == 'S')
                                                <td>Saisi</td>
                                            @elseif($pointage_agent->SoStatut == 'T')
                                                <td>Traité</td>
                                            @elseif($pointage_agent->SoStatut == 'V')
                                                <td>Vérifié</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td style="text-align:center;">
                                                <a href="{{ route('pointage-agent.google-maps',crypter($pointage_agent->SoNumero)) }}" class="info p-0" id="carte_maps">
                                                    <i class="ft-map-pin font-medium-3 mr-2"></i>
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
    <div class="modal fade text-left" id="create_pointage_agent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">

                <form method="POST" action="{{ route('pointage-agents.store') }}" class="needs-validation" novalidate>
                    @csrf

                    <input type="hidden" class="form-control" id="SoOperSuperv" name="SoOperSuperv" value="{{ $OsNumero }}"/>
                    <input type="hidden" class="form-control" id="OsHeureDebut" value="{{ $supervision_site->OsHeureDebut }}"/>


                    <div class="modal-header bg-info white">
                        <h4 class="modal-title" id="myModalLabel16">POINTAGE D'UN AGENT</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    @if(Auth::user()->type == 'S')
                                        <label for="">Superviseur</label>
                                    @elseif(Auth::user()->type == 'T')
                                        <label for="">Contrôleur</label>
                                    @else
                                        <label for="">Superviseur</label>
                                    @endif
                                    <input type="text" class="form-control" value="{{ $supervision_site->PeNom.' '.$supervision_site->PePrenom }}" readonly/>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-3" id="afficher_sites_zones">
                                <div class="form-group">
                                    <label for="SoSite">Sites supervisés <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e7 form-control" id="SoSite" name="SoSite" required>
                                        <option selected value="">Selectionner...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-3" id="afficher_agents_programmes">
                                <div class="form-group">
                                    <label for="SoAgentPgme">Agents programmés <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e8 form-control" id="SoAgentPgme" name="SoAgentPgme" required>
                                        <option selected value="">Selectionner...</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label>Présent ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline1" name="SoIsPresent" class="custom-control-input" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline1">Oui</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline2" name="SoIsPresent" class="custom-control-input" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline2">Non</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-3 col-md-3" id="afficher_agents_trouves">
                                <div class="form-group">
                                    <label for="SoAgentOper">Agents trouvés</label>
                                    <select class="e9 form-control" id="SoAgentOper" name="SoAgentOper">
                                        <option selected value="">Selectionner...</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="SoNomPrenAgent">Noms et Prénoms agent</label>
                                    <input type="text" class="form-control" id="SoNomPrenAgent" name="SoNomPrenAgent" value="{{ old('SoNomPrenAgent') }}" placeholder="Noms et Prénoms agent"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-2 col-md-2">
                                <div class="form-group">
                                    <label for="SoHeureArrivAg">Heure d'arrivée <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="time" class="form-control" id="SoHeureArrivAg" name="SoHeureArrivAg" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-2 col-md-2">
                                <div class="form-group">
                                    <label for="retard">Retard</label>
                                    <input type="text" class="form-control" id="retard" readonly/>
                                </div>
                            </div>

                            <div class="col-xl-2 col-md-2">
                                <div class="form-group">
                                    <label for="SoHeureDepart">Heure départ</label>
                                    <input type="time" class="form-control" id="SoHeureDepart" name="SoHeureDepart"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label>L'agent a t'il signé ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline3" name="SoIsAgentSigne" class="custom-control-input" value="Y" checked/>
                                            <label class="custom-control-label" for="customRadioInline3">Oui</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline4" name="SoIsAgentSigne" class="custom-control-input" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline4">Non</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="SoPresentation">Présentation <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="form-control" id="SoPresentation" name="SoPresentation" required>
                                        <option selected value="">Selectionner...</option>
                                        <option value="T">Très bonne</option>
                                        <option value="B">Bonne</option>
                                        <option value="P">Passable</option>
                                        <option value="M">Mauvaise</option>
                                        <option value="V">Très mauvaise</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="SoLatitudeX">Latitude</label>
                                    <input type="text" class="form-control" id="latitude" name="SoLatitudeX" readonly/>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="SoLongitudeY">Longitude</label>
                                    <input type="text" class="form-control" id="longitude" name="SoLongitudeY" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="afficher_distance_pointage_site">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="distance">Distance en mètres entre le site et point de pointage</label>
                                    <input type="text" class="form-control" id="distance" name="distance" readonly/>
                                    <span id="distance_pointage_site_not_exist"></span>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="statut_pointage">Statut du pointage</label>
                                    <input type="text" class="form-control" id="statut_pointage" name="statut_pointage" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="SoObservSuperv">Observation superviseur</label>
                                    <textarea rows="2" class="form-control" id="SoObservSuperv" name="SoObservSuperv" placeholder="Obs superviseur"></textarea>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="SoObservClt">Observation Client</label>
                                    <textarea rows="2" class="form-control" id="SoObservClt" name="SoObservClt" placeholder="Obs client"></textarea>
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
    <div class="modal fade text-left" id="edit_pointage_agent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">

                <form method="POST" action="#" class="form-edit needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="_method" value="PUT">

                    <div class="modal-header bg-info white">
                        <h5 class="modal-title" id="myModalLabel16">MODIFICATION D'UN SITE SUPERVISEUR</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
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
    <div class="modal fade text-left" id="xlarge_pointage_agent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
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
                                            <th scope="row">Date et Heure</th>
                                            <td id="date_heure"></td>
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

@endsection

@section('add_js')
    <script src="{{ url('assets/geolocation/geolocation.js') }}"></script>

    <script>
        tippy('#carte_maps', {
            content: "cartes Google maps",
            placement: 'top',
        });

        tippy('#editor', {
            content: "Modifier",
            placement: 'bottom',
        });

        tippy('#desactiver', {
            content: "Suspendre",
            placement: 'top',
        });
    </script>

    <script>
        $(document).ready(function(){
            $("#create_pointage_agent,#edit_pointage_agent,#xlarge_pointage_agent").modal({
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
            $(".e50").select2();

            $(".e7,.e8,.e9,.e10").select2({
                width: '100%',
                dropdownParent: $("#create_pointage_agent")
            });
        });
    </script>

    <script type="text/javascript">
        function showItem(SsNumero) {
            $.ajax({
                url: '{{ route("site-superviseur.show_site_superviseur", "") }}/' + SsNumero,
                type: 'GET',
                success: function(data){

                    $('#superviseur').html(data.PeNom+' '+data.PePrenom)
                    $('#site').html(data.ScNomSite)
                    $('#date_heure').html(data.SsDateHCree)

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

                    $('#xlarge_site_superviseur').modal('show').fadeIn('slow')
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
                url: '{{ route("site-superviseur.edit_site_superviseur", "") }}/' + SsNumero,
                type: 'GET',
                success: function(data){

                    var SsNumero=data.SsNumero;

                    $(".form-edit").attr('action', '{{ route("site-superviseur.update_site_superviseur", "") }}/' + SsNumero);
                    var form=$(".form-edit").attr('action');

                    $(".SsSuperv").val(data.SsSuperv);
                    $(".SsSiteClient").val(data.SsSiteClient);
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

                    $('#edit_site_superviseur').modal('show').fadeIn('slow')
                },
                error: function(){
                    console.log("Something goes wrong")
                }
            })
        }
    </script>

    <script type="text/javascript">
        function suspendreItem(SsNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Suspendre cet élément",
                text: "Confirmez-vous la suspension de cet élément ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Oui, Suspendre !",
                cancelButtonText: "Annuler !",
                confirmButtonColor: '#2ecc71',
                cancelButtonColor: '#e84118',
                reverseButtons: true

            }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '{{ route("site-superviseur.desactiver_site_superviseur", "") }}/' + SsNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire('Site superviseur suspendu avec succès !','','success')
                        window.location.reload();
                    }
                });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire('Annulé','','error')
                }
            });
        };
    </script>



    <script type="text/javascript">
        $(document).ready(function() {
            if($("#SoOperSuperv").val() != null) {
                var SoOperSuperv = $("#SoOperSuperv").val();

                // Fonctions helper
                function chargerAgentsTrouvesY(SoAgentPgme) {
                    $.get('{{ route("pointage-agent.show_agents_trouves1","") }}/'+SoAgentPgme, function(rep) {
                        $("#afficher_agents_trouves").html(rep);

                        // Sélectionner automatiquement l'agent programmé dans la liste
                        $("#SoAgentOper").val(SoAgentPgme);
                        $("#SoAgentOper").prop('disabled', true);

                        // Charger les infos de l'agent trouvé
                        $.get('{{ route("pointage-agent.show_agent_trouve","") }}/'+SoAgentPgme, function(rep) {
                            $("#afficher_agent_trouve").html(rep);
                        });

                        // Vider et griser le champ Noms et Prénoms
                        $("#SoNomPrenAgent").val('');
                        $("#SoNomPrenAgent").prop('readonly', true);
                        $("#SoNomPrenAgent").prop('disabled', true);
                    });
                }

                function chargerAgentsTrouvesN(SoOperSuperv, SoSite) {
                    $.get('{{ route("pointage-agent.show_agents_trouves2",["",""]) }}/'+SoOperSuperv+'/'+SoSite, function(rep) {
                        $("#afficher_agents_trouves").html(rep);

                        // Activer la liste déroulante pour sélection
                        $("#SoAgentOper").prop('disabled', false);

                        // Gérer le changement de sélection dans Agent trouvé
                        $("#SoAgentOper").off('change').on('change', function() {
                            var SoAgentOper = $("#SoAgentOper").val();
                            if(SoAgentOper != null && SoAgentOper != '') {
                                $.get('{{ route("pointage-agent.show_agent_trouve","") }}/'+SoAgentOper, function(rep) {
                                    $("#afficher_agent_trouve").html(rep);
                                });
                                // Si un agent est sélectionné, vider et griser le champ manuel
                                $("#SoNomPrenAgent").val('');
                                $("#SoNomPrenAgent").prop('readonly', true);
                                $("#SoNomPrenAgent").prop('disabled', true);
                            } else {
                                $("#afficher_agent_trouve").html('');
                                // Si aucune sélection, activer le champ de saisie manuelle
                                $("#SoNomPrenAgent").prop('readonly', false);
                                $("#SoNomPrenAgent").prop('disabled', false);
                            }
                        });

                        var SoAgentOper = $("#SoAgentOper").val();
                        if(SoAgentOper != null && SoAgentOper != '') {
                            $.get('{{ route("pointage-agent.show_agent_trouve","") }}/'+SoAgentOper, function(rep) {
                                $("#afficher_agent_trouve").html(rep);
                            });
                            $("#SoNomPrenAgent").val('');
                            $("#SoNomPrenAgent").prop('readonly', true);
                            $("#SoNomPrenAgent").prop('disabled', true);
                        } else {
                            $("#afficher_agent_trouve").html('');
                            // Activer le champ de saisie manuelle
                            $("#SoNomPrenAgent").prop('readonly', false);
                            $("#SoNomPrenAgent").prop('disabled', false);
                        }
                    });
                }

                function gererChangementAgentPgme(SoOperSuperv, SoSite) {
                    var SoAgentPgme = $("#SoAgentPgme").val();
                    var SoIsPresent = getSelectedValue('SoIsPresent');
                    if(SoIsPresent == 'Y') {
                        if(SoAgentPgme != null && SoAgentPgme != '') {
                            chargerAgentsTrouvesY(SoAgentPgme);
                        }
                    } else if(SoIsPresent == 'N') {
                        chargerAgentsTrouvesN(SoOperSuperv, SoSite);
                    }
                }

                function gererClickRadio(SoOperSuperv, SoSite) {
                    var SoAgentPgme = $("#SoAgentPgme").val();
                    var SoIsPresent = getSelectedValue('SoIsPresent');
                    if(SoIsPresent == 'Y') {
                        if(SoAgentPgme != null && SoAgentPgme != '') {
                            chargerAgentsTrouvesY(SoAgentPgme);
                        }
                    } else if(SoIsPresent == 'N') {
                        chargerAgentsTrouvesN(SoOperSuperv, SoSite);
                    } else {
                        // Aucune sélection, réinitialiser
                        $("#SoAgentOper").prop('disabled', false);
                        $("#SoNomPrenAgent").prop('readonly', false);
                        $("#SoNomPrenAgent").prop('disabled', false);
                        $("#SoNomPrenAgent").val('');
                    }
                }

                function gererEtatInitialRadio(SoOperSuperv, SoSite, SoAgentPgme) {
                    if($("input[name='SoIsPresent']:checked").val() == "Y") {
                        if(SoAgentPgme != null && SoAgentPgme != '') {
                            chargerAgentsTrouvesY(SoAgentPgme);
                        }
                    } else if($("input[name='SoIsPresent']:checked").val() == "N") {
                        chargerAgentsTrouvesN(SoOperSuperv, SoSite);
                    } else {
                        // Aucune sélection, réinitialiser
                        $("#SoAgentOper").prop('disabled', false);
                        $("#SoNomPrenAgent").prop('readonly', false);
                        $("#SoNomPrenAgent").prop('disabled', false);
                        $("#SoNomPrenAgent").val('');
                    }
                }



                function gererChangementSite(SoOperSuperv) {
                    var SoSite = $("#SoSite").val();
                    $.get('{{ route("pointage-agent.show_agents_programmes",["",""]) }}/'+SoOperSuperv+'/'+SoSite, function(rep) {
                        $("#afficher_agents_programmes").html(rep);
                        $("#SoAgentPgme").off('change').on('change', function() {
                            gererChangementAgentPgme(SoOperSuperv, SoSite);
                        });
                        if($("#SoAgentPgme").val() != null) {
                            var SoAgentPgme = $("#SoAgentPgme").val();
                            var SoIsPresent = getSelectedValue('SoIsPresent');
                            if(SoIsPresent == 'Y') {
                                if(SoAgentPgme != null && SoAgentPgme != '') {
                                    chargerAgentsTrouvesY(SoAgentPgme);
                                }
                            } else if(SoIsPresent == 'N') {
                                chargerAgentsTrouvesN(SoOperSuperv, SoSite);
                            }
                        }
                        $("input[type=radio][name=SoIsPresent]").off('click').on('click', function() {
                            gererClickRadio(SoOperSuperv, SoSite);
                        });
                        var SoAgentPgme = $("#SoAgentPgme").val();
                        gererEtatInitialRadio(SoOperSuperv, SoSite, SoAgentPgme);
                    });

                    var SoSite =  $("#SoSite").val();
                    var latitude2 =  $("#latitude").val();
                    var longitude2 = $("#longitude").val();

                    if(latitude2 != null && longitude2 != null) {
                        $.get('{{ route("pointage-agent.show_distance_pointage_site",["","",""]) }}/'+SoSite+'/'+latitude2+'/'+longitude2, function(rep) {
                            $("#afficher_distance_pointage_site").html(rep);
                        });
                    } else {
                        $("#distance_pointage_site_not_exist").html("Les points GPS de ce pointage doivent exister afin de calculer la distance");
                        $("#distance_pointage_site_not_exist").css("color", "red");
                    }
                }

                $.get('{{ route("pointage-agent.show_sites_zones","") }}/'+SoOperSuperv, function(rep) {
                    $("#afficher_sites_zones").html(rep);
                    $("#SoSite").off('change').on('change', function() {
                        gererChangementSite(SoOperSuperv);
                    });
                    if($("#SoSite").val() != null) {
                        gererChangementSite(SoOperSuperv);
                    }
                });

            }
        });
    </script>

    <script>
        // Fonction pour calculer le retard
        function calculerRetard() {
            // Récupérer les valeurs
            var heureArrivee = $("#SoHeureArrivAg").val();
            var heureDebut = $("#OsHeureDebut").val();

            // Vérifier si les deux champs sont remplis
            if (!heureArrivee || !heureDebut) {
                $("#retard").val('');
                return;
            }

            // Convertir les heures en minutes depuis minuit
            function timeToMinutes(time) {
                var parts = time.split(':');
                return parseInt(parts[0]) * 60 + parseInt(parts[1]);
            }

            var arriveeMinutes = timeToMinutes(heureArrivee);
            var debutMinutes = timeToMinutes(heureDebut);

            // Calculer le retard en minutes
            var retardMinutes = arriveeMinutes - debutMinutes;

            // Si l'arrivée est après l'heure de début (retard positif)
            if (retardMinutes > 0) {
                var heures = Math.floor(retardMinutes / 60);
                var minutes = retardMinutes % 60;
                var texteRetard = '';

                if (heures > 0) {
                    texteRetard = heures + ' heure' + (heures > 1 ? 's' : '');
                    if (minutes > 0) {
                        texteRetard += ' ' + minutes + ' minute' + (minutes > 1 ? 's' : '');
                    }
                } else {
                    texteRetard = retardMinutes + ' minute' + (retardMinutes > 1 ? 's' : '');
                }

                $("#retard").val(texteRetard);
            } else {
                // Pas de retard (arrivée à l'heure ou en avance)
                $("#retard").val('');
            }
        }

        // Écouteurs d'événements avec jQuery
        $(document).ready(function() {
            $('#SoHeureArrivAg').on('change input', calculerRetard);

            // Appel initial pour vérifier si une valeur existe déjà
            calculerRetard();
        });
    </script>


@endsection

