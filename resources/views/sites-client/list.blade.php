@extends('layout.app')

@section('title', 'Liste des sites client')

@section('content')

@include('layout.alert')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Liste des sites client</b></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard table-responsive">
                            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create"><i class="ft-plus"></i>&nbsp;Ajouter un site client</button>
                            <button type="button" class="btn btn-danger mb-2" data-toggle="modal" data-target="#import"><i class="ft-download"></i>&nbsp;Importer les sites client</button>

                            <form action="{{ route('site-client.filtre') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-2 col-md-2">
                                        <div class="form-group">
                                            <label for="numero_client">Clients</label>
                                            <select id="numero_client" name="numero_client" class="form-control e1">
                                                <option selected="selected" value="all">Tous</option>
                                                @foreach($clients_f as $client_f)
                                                    <option value="{{ $client_f->ClNumero }}">{{ $client_f->ClDesign }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-2">
                                        <div class="form-group">
                                            <label for="ville_site">Ville</label>
                                            <select id="ville_site" name="ville_site" class="form-control e2">
                                                <option selected="selected" value="all">Tous</option>
                                                @foreach($villes_f as $ville_f)
                                                    <option value="{{ $ville_f->ViNumero }}">{{ $ville_f->ViNom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-2">
                                        <div class="form-group">
                                            <label for="zone_site">Zone</label>
                                            <select id="zone_site" name="zone_site" class="form-control e3">
                                                <option selected="selected" value="all">Tous</option>
                                                @foreach($zones_f as $zone_f)
                                                    <option value="{{ $zone_f->ZoNumero }}">{{ $zone_f->ZoNom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-2">
                                        <div class="form-group">
                                            <label for="quartier_site">Quartier</label>
                                            <select id="quartier_site" name="quartier_site" class="form-control e4">
                                                <option selected="selected" value="all">Tous</option>
                                                @foreach($quartiers_f as $quartier_f)
                                                    <option value="{{ $quartier_f->QuNumero }}">{{ $quartier_f->QuNom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-2">
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
                                        <th>Nom</th>
                                        <th>Client</th>
                                        <th>Ville</th>
                                        <th>Zone</th>
                                        <th>Quartier</th>
                                        <th>Superviseurs ?</th>
                                        <th>Nbre superviseurs jour</th>
                                        <th>Nbre superviseurs nuit</th>
                                        <th>Maitres chiens ?</th>
                                        <th>Nbre maitres chiens jour</th>
                                        <th>Nbre maitres chiens nuit</th>
                                        <th>Vigiles ?</th>
                                        <th>Nbre vigiles jour</th>
                                        <th>Nbre vigiles nuit</th>
                                        <th>Commandos ?</th>
                                        <th>Nbre commandos jour</th>
                                        <th>Nbre commandos nuit</th>
                                        <th>Précision</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sites_client as $site)
                                        <tr>
                                            <td>{{ $site->ScNomSite }}</td>
                                            <td>{{ $site->ClDesign }}</td>
                                            <td>{{ $site->ViNom }}</td>
                                            <td>{{ $site->ZoNom }}</td>
                                            <td>{{ $site->QuNom }}</td>

                                            @if($site->ScIsSuperv == 'Y')
                                                <td>Oui</td>
                                            @else
                                                <td>Non</td>
                                            @endif
                                            <td>{{ $site->ScNbSupervJr }}</td>
                                            <td>{{ $site->ScNbSupervNu }}</td>

                                            @if($site->ScIsMeChien == 'Y')
                                                <td>Oui</td>
                                            @else
                                                <td>Non</td>
                                            @endif
                                            <td>{{ $site->ScNbMeChienJr }}</td>
                                            <td>{{ $site->ScNbMeChienNu }}</td>

                                            @if($site->ScIsVigile == 'Y')
                                                <td>Oui</td>
                                            @else
                                                <td>Non</td>
                                            @endif
                                            <td>{{ $site->ScNbVigileJr }}</td>
                                            <td>{{ $site->ScNbVigileNu }}</td>

                                            @if($site->ScIsCmdo == 'Y')
                                                <td>Oui</td>
                                            @else
                                                <td>Non</td>
                                            @endif
                                            <td>{{ $site->ScNbCmdoJr }}</td>
                                            <td>{{ $site->ScNbCmdoNu }}</td>

                                            <td>{{ $site->ScPrecision }}</td>
                                            <td>{{ str_replace('.', ',',$site->ScLatitudeX) }}</td>
                                            <td>{{ str_replace('.', ',',$site->ScLongitudeY) }}</td>

                                            @if($site->ScIsOn == 'Y')
                                                <td><span class="badge light badge-success">Actif</span></td>
                                            @else
                                                <td><span class="badge light badge-danger">Inactif</span></td>
                                            @endif

                                            <td style="text-align: center;">
                                                <a onclick="showItem('{{ $site->ScNumero  }}')" class="info p-0" id="view">
                                                    <i class="ft-eye font-medium-3 mr-2"></i>
                                                </a>

                                                <a href="#" onclick="editItem('{{$site->ScNumero }}')" class="warning p-0" id="editor">
                                                    <i class="ft-edit font-medium-3 mr-2"></i>
                                                </a>

                                                <!-- NOUVEAU BOUTON MISE À JOUR GPS -->
                                                @if(Auth::user()->type == 'M')
                                                    <a href="#" onclick="updateGpsItem('{{ $site->ScNumero }}')" class="primary p-0" id="updateGps" title="Mettre à jour les coordonnées GPS">
                                                        <i class="ft-map-pin font-medium-3 mr-2"></i>
                                                    </a>
                                                @endif


                                                @if($site->ScIsOn == 'Y')
                                                    <a onclick="desactiveItem('{{ $site->ScNumero  }}')" class="danger p-0" id="desactiver">
                                                        <i class="ft-log-out font-medium-3 mr-2"></i>
                                                    </a>
                                                @else
                                                    <a onclick="activeItem('{{ $site->ScNumero  }}')" class="success p-0" id="activer">
                                                        <i class="ft-log-in font-medium-3 mr-2"></i>
                                                    </a>
                                                @endif

                                                <a onclick="deleteItem('{{ $site->ScNumero }}')" class="danger p-0" id="delete">
                                                    <i class="ft-trash-2 font-medium-3 mr-2"></i>
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

                <form method="POST" action="{{ route('sites-client.store') }}" class="needs-validation" novalidate>
                    @csrf

                    <div class="modal-header bg-info white">
                        <h4 class="modal-title" id="myModalLabel16">CRÉATION D'UN SITE CLIENT</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="ScNomSite">Nom <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" id="ScNomSite" class="form-control" name="ScNomSite" value="{{ old('ScNomSite') }}" placeholder="Nom" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="ScClient">Client <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e5 form-control" id="ScClient" name="ScClient" required>
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
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="ScClient">Ville <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e6 form-control" id="ScVille" name="ScVille" required>
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

                            <div class="col-xl-6 col-md-6" id="afficher_zones">
                                <div class="form-group">
                                    <label for="ScZone">Zone</label>
                                    <select class="e7 form-control">
                                        <option selected value="">Selectionner...</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6" id="afficher_quartiers">
                                <div class="form-group">
                                    <label for="ScQuartier">Quartier</label>
                                <select class="e8 form-control" id="ScQuartier" name="ScQuartier">
                                        <option selected value="">Selectionner...</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="ScPrecision">Précision en mètres <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" id="ScPrecision" class="form-control" name="ScPrecision" value="{{ old('ScPrecision') }}" placeholder="Précision en mètres" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>Y'a t-il des vigiles ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline1" name="ScIsVigile" class="custom-control-input" value="N" checked/>
                                            <label class="custom-control-label" for="customRadioInline1">Non</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline2" name="ScIsVigile" class="custom-control-input" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline2">Oui</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3" id="afficher_nbre_vigiles_1">
                                <div class="form-group">
                                    <label for="ScNbVigileJr">Nbre de vigiles jour</label>
                                    <input type="text" id="ScNbVigileJr" class="form-control" name="ScNbVigileJr" value="{{ old('ScNbVigileJr') }}" placeholder="Nbre de vigiles jour"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3" id="afficher_nbre_vigiles_2">
                                <div class="form-group">
                                    <label for="ScNbVigileNu">Nbre de vigiles nuit</label>
                                    <input type="text" id="ScNbVigileNu" class="form-control" name="ScNbVigileNu" value="{{ old('ScNbVigileNu') }}" placeholder="Nbre de vigiles nuit"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>Y'a t-il des maîtres chiens ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline3" name="ScIsMeChien" class="custom-control-input" value="N" checked/>
                                            <label class="custom-control-label" for="customRadioInline3">Non</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline4" name="ScIsMeChien" class="custom-control-input" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline4">Oui</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3" id="afficher_nbre_maitres_chien_1">
                                <div class="form-group">
                                    <label for="ScNbMeChienJr">Nbre de maîtres chien jour</label>
                                    <input type="text" id="ScNbMeChienJr" class="form-control" name="ScNbMeChienJr" value="{{ old('ScNbMeChienJr') }}" placeholder="Nbre de maîtres chien jour"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3" id="afficher_nbre_maitres_chien_2">
                                <div class="form-group">
                                    <label for="ScNbMeChienNu">Nbre de maîtres chien nuit</label>
                                    <input type="text" id="ScNbMeChienNu" class="form-control" name="ScNbMeChienNu" value="{{ old('ScNbMeChienNu') }}" placeholder="Nbre de maîtres chien nuit"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>Y'a t-il des commandos ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline5" name="ScIsCmdo" class="custom-control-input" value="N" checked/>
                                            <label class="custom-control-label" for="customRadioInline5">Non</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline6" name="ScIsCmdo" class="custom-control-input" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline6">Oui</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3" id="afficher_nbre_commandos_1">
                                <div class="form-group">
                                    <label for="ScNbCmdoJr">Nbre de commandos jour</label>
                                    <input type="text" id="ScNbCmdoJr" class="form-control" name="ScNbCmdoJr" value="{{ old('ScNbCmdoJr') }}" placeholder="Nbre de commandos jour"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3" id="afficher_nbre_commandos_2">
                                <div class="form-group">
                                    <label for="ScNbCmdoNu">Nbre de commandos nuit</label>
                                    <input type="text" id="ScNbCmdoNu" class="form-control" name="ScNbCmdoNu" value="{{ old('ScNbCmdoNu') }}" placeholder="Nbre de commandos nuit"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>Y'a t-il des superviseurs ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline7" name="ScIsSuperv" class="custom-control-input" value="N" checked/>
                                            <label class="custom-control-label" for="customRadioInline7">Non</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline8" name="ScIsSuperv" class="custom-control-input" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline8">Oui</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3" id="afficher_nbre_superviseurs_1">
                                <div class="form-group">
                                    <label for="ScNbCmdoJr">Nbre de superviseurs jour</label>
                                    <input type="text" id="ScNbSupervJr" class="form-control" name="ScNbSupervJr" value="{{ old('ScNbSupervJr') }}" placeholder="Nbre de superviseurs jour"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3" id="afficher_nbre_superviseurs_2">
                                <div class="form-group">
                                    <label for="ScNbSupervNu">Nbre de superviseurs nuit</label>
                                    <input type="text" id="ScNbSupervNu" class="form-control" name="ScNbSupervNu" value="{{ old('ScNbSupervNu') }}" placeholder="Nbre de superviseurs nuit"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>Y'a t-il des opérateurs Vidéo ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline9" name="ScIsOperVideo" class="custom-control-input" value="N" checked/>
                                            <label class="custom-control-label" for="customRadioInline9">Non</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline10" name="ScIsOperVideo" class="custom-control-input" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline10">Oui</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label>Y'a t-il des locations Vidéo ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline11" name="ScIsLocatVideo" class="custom-control-input" value="N" checked/>
                                            <label class="custom-control-label" for="customRadioInline11">Non</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline12" name="ScIsLocatVideo" class="custom-control-input" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline12">Oui</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label>Y'a t-il des installations de Vidéo surveillance ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline13" name="ScIsInstVidSurv" class="custom-control-input" value="N" checked/>
                                            <label class="custom-control-label" for="customRadioInline13">Non</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline14" name="ScIsInstVidSurv" class="custom-control-input" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline14">Oui</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="ScLatitudeX">Latitude</label>
                                    <input type="text" class="form-control" id="latitude" name="ScLatitudeX" readonly/>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="ScLongitudeY">Longitude</label>
                                    <input type="text" class="form-control" id="longitude" name="ScLongitudeY" readonly/>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>Ce site client peut avoir plusieurs superviseurs ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline15" name="ScIsMultiSuperv" class="custom-control-input" value="N" checked/>
                                            <label class="custom-control-label" for="customRadioInline15">Non</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline16" name="ScIsMultiSuperv" class="custom-control-input" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline16">Oui</label>
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
                                    <label>Statut <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline17" name="ScIsOn" class="custom-control-input" value="Y" checked/>
                                            <label class="custom-control-label" for="customRadioInline17">Actif</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline18" name="ScIsOn" class="custom-control-input" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline18">Inactif</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
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
                        <h5 class="modal-title" id="myModalLabel16">MODIFICATION D'UN SITE CLIENT</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="ScNomSite">Nom <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" id="ScNomSite" class="form-control ScNomSite" name="ScNomSite" value="{{ old('ScNomSite') }}" placeholder="Nom" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="ScClient">Client <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="form-control ScClient" id="ScClient" name="ScClient" required>
                                        <option selected value="">Selectionner...</option>
                                        @foreach($clients_1 as $client_1)
                                            <option value="{{ $client_1->ClNumero }}">{{ $client_1->ClDesign }}</option>
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
                                    <label for="ScClient">Ville <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="form-control ScVille" id="ScVille" name="ScVille" required>
                                        <option selected value="">Selectionner...</option>
                                        @foreach($villes_1 as $ville_1)
                                            <option value="{{ $ville_1->ViNumero }}">{{ $ville_1->ViNom }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6" id="afficher_zones_edit">
                                <div class="form-group">
                                    <label for="">Zone</label>
                                    <select class="form-control">
                                        <option selected value="">Selectionner...</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6" id="afficher_quartiers_edit">
                                <div class="form-group">
                                    <label for="">Quartier</label>
                                    <select class="form-control">
                                        <option selected value="">Selectionner...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="ScPrecision">Précision en mètres <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" id="ScPrecision" class="form-control ScPrecision" name="ScPrecision" value="{{ old('ScPrecision') }}" placeholder="Précision en mètres" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>Y'a t-il des vigiles ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline19" name="ScIsVigile" class="custom-control-input ScIsVigile_N" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline19">Non</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline20" name="ScIsVigile" class="custom-control-input ScIsVigile_Y" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline20">Oui</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3" id="afficher_nbre_vigiles_1">
                                <div class="form-group">
                                    <label for="ScNbVigileJr">Nbre de vigiles jour</label>
                                    <input type="text" id="ScNbVigileJr" class="form-control ScNbVigileJr" name="ScNbVigileJr" value="{{ old('ScNbVigileJr') }}" placeholder="Nbre de vigiles jour"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3" id="afficher_nbre_vigiles_2">
                                <div class="form-group">
                                    <label for="ScNbVigileNu">Nbre de vigiles nuit</label>
                                    <input type="text" id="ScNbVigileNu" class="form-control ScNbVigileNu" name="ScNbVigileNu" value="{{ old('ScNbVigileNu') }}" placeholder="Nbre de vigiles nuit"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>Y'a t-il des maîtres chiens ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline21" name="ScIsMeChien" class="custom-control-input ScIsMeChien_N" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline21">Non</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline22" name="ScIsMeChien" class="custom-control-input ScIsMeChien_Y" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline22">Oui</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3" id="afficher_nbre_maitres_chien_1">
                                <div class="form-group">
                                    <label for="ScNbMeChienJr">Nbre de maîtres chien jour</label>
                                    <input type="text" id="ScNbMeChienJr" class="form-control ScNbMeChienJr" name="ScNbMeChienJr" value="{{ old('ScNbMeChienJr') }}" placeholder="Nbre de maîtres chien jour"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3" id="afficher_nbre_maitres_chien_2">
                                <div class="form-group">
                                    <label for="ScNbMeChienNu">Nbre de maîtres chien nuit</label>
                                    <input type="text" id="ScNbMeChienNu" class="form-control ScNbMeChienNu" name="ScNbMeChienNu" value="{{ old('ScNbMeChienNu') }}" placeholder="Nbre de maîtres chien nuit"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>Y'a t-il des commandos ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline23" name="ScIsCmdo" class="custom-control-input ScIsCmdo_N" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline23">Non</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline24" name="ScIsCmdo" class="custom-control-input ScIsCmdo_Y" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline24">Oui</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3" id="afficher_nbre_commandos_1">
                                <div class="form-group">
                                    <label for="ScNbCmdoJr">Nbre de commandos jour</label>
                                    <input type="text" id="ScNbCmdoJr" class="form-control ScNbCmdoJr" name="ScNbCmdoJr" value="{{ old('ScNbCmdoJr') }}" placeholder="Nbre de commandos jour"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3" id="afficher_nbre_commandos_2">
                                <div class="form-group">
                                    <label for="ScNbCmdoNu">Nbre de commandos nuit</label>
                                    <input type="text" id="ScNbCmdoNu" class="form-control ScNbCmdoNu" name="ScNbCmdoNu" value="{{ old('ScNbCmdoNu') }}" placeholder="Nbre de commandos nuit"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>Y'a t-il des superviseurs ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline25" name="ScIsSuperv" class="custom-control-input ScIsSuperv_N" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline25">Non</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline26" name="ScIsSuperv" class="custom-control-input ScIsSuperv_Y" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline26">Oui</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3" id="afficher_nbre_superviseurs_1">
                                <div class="form-group">
                                    <label for="ScNbCmdoJr">Nbre de superviseurs jour</label>
                                    <input type="text" id="ScNbSupervJr" class="form-control ScNbSupervJr" name="ScNbSupervJr" value="{{ old('ScNbSupervJr') }}" placeholder="Nbre de superviseurs jour"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3" id="afficher_nbre_superviseurs_2">
                                <div class="form-group">
                                    <label for="ScNbSupervNu">Nbre de superviseurs nuit</label>
                                    <input type="text" id="ScNbSupervNu" class="form-control ScNbSupervNu" name="ScNbSupervNu" value="{{ old('ScNbSupervNu') }}" placeholder="Nbre de superviseurs nuit"/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>Y'a t-il des opérateurs Vidéo ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline27" name="ScIsOperVideo" class="custom-control-input ScIsOperVideo_N" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline27">Non</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline28" name="ScIsOperVideo" class="custom-control-input ScIsOperVideo_Y" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline28">Oui</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label>Y'a t-il des locations Vidéo ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline29" name="ScIsLocatVideo" class="custom-control-input ScIsLocatVideo_N" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline29">Non</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline30" name="ScIsLocatVideo" class="custom-control-input ScIsLocatVideo_Y" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline30">Oui</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label>Y'a t-il des installations de Vidéo surveillance ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline31" name="ScIsInstVidSurv" class="custom-control-input ScIsInstVidSurv_N" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline31">Non</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline32" name="ScIsInstVidSurv" class="custom-control-input ScIsInstVidSurv_Y" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline32">Oui</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="ScLatitudeX">Latitude</label>
                                    <input type="text" class="form-control latitude" id="latitude" name="ScLatitudeX" readonly/>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="ScLongitudeY">Longitude</label>
                                    <input type="text" class="form-control longitude" id="longitude" name="ScLongitudeY" readonly/>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>Ce site client peut avoir plusieurs superviseurs ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline33" name="ScIsMultiSuperv" class="custom-control-input ScIsMultiSuperv_N" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline33">Non</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline34" name="ScIsMultiSuperv" class="custom-control-input ScIsMultiSuperv_Y" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline34">Oui</label>
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
                                    <label>Statut <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <div class="input-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline35" name="ScIsOn" class="custom-control-input ScIsOn_Y" value="Y"/>
                                            <label class="custom-control-label" for="customRadioInline35">Actif</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline36" name="ScIsOn" class="custom-control-input ScIsOn_N" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline36">Inactif</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
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
                    <h5 class="modal-title" id="myModalLabel16">INFORMATIONS SITE CLIENT</h5>
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
                                            <th scope="row">Nom</th>
                                            <td id="nom"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Client</th>
                                            <td id="client"></td>
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
                                            <th scope="row">Quartier</th>
                                            <td id="quartier"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Précision</th>
                                            <td id="precision"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Latitude</th>
                                            <td id="latitude"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Longitude</th>
                                            <td id="longitude"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Multi Superviseur</th>
                                            <td id="multi_superv"></td>
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


    <!-- IMPORT CLIENT -->
    <div class="modal fade text-left" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <form method="POST" action="{{ route('site-client.import') }}" class="needs-validation" novalidate>
                    @csrf

                    <div class="modal-header bg-info white">
                        <h4 class="modal-title" id="myModalLabel16">IMPORTER LES SITES CLIENT</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-12 col-md-12">
                                <div class="form-group">
                                    <label for="QuNom">Import du fichier excel <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="file" id="file" class="form-control" name="file" accept=".xlsx,.xls,.csv" placeholder="Import du fichier excel" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler <i class="fa fa-refresh position-right"></i></button>
                        <button type="submit" class="btn btn-success">Importer <i class="fa fa-thumbs-o-up position-right"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- IMPORT CLIENT -->

    <!-- MISE À JOUR GPS -->
    <div class="modal fade text-left" id="updateGpsModal" tabindex="-1" role="dialog" aria-labelledby="updateGpsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary white">
                    <h5 class="modal-title" id="updateGpsModalLabel">
                        <i class="ft-map-pin"></i> MISE À JOUR DES COORDONNÉES GPS
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateGpsForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="site_id" id="gps_site_id">

                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="ft-info"></i> Cliquez sur le bouton ci-dessous pour obtenir les coordonnées GPS actuelles.
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-md-12">
                                <div class="form-group text-center">
                                    <button type="button" class="btn btn-primary btn-lg" id="getCurrentLocationBtn" onclick="getCurrentLocationForUpdate()">
                                        <i class="ft-map-pin"></i> Obtenir ma position actuelle
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="gps_latitude">Latitude</label>
                                    <input type="text" class="form-control" id="gps_latitude" name="ScLatitudeX" readonly/>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="gps_longitude">Longitude</label>
                                    <input type="text" class="form-control" id="gps_longitude" name="ScLongitudeY" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="gps_accuracy">Précision (mètres)</label>
                                    <input type="text" class="form-control" id="gps_accuracy" readonly/>
                                    <small class="text-muted">Précision de la mesure GPS</small>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="gps_precision">Précision à enregistrer (mètres)</label>
                                    <input type="number" class="form-control" id="gps_precision" name="ScPrecision" placeholder="Précision en mètres"/>
                                    <small class="text-muted">Laisser vide pour utiliser la précision GPS</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-md-12">
                                <div class="form-group">
                                    <label>Informations du site client</label>
                                    <div class="card bg-light">
                                        <div class="card-body p-2">
                                            <p class="mb-1"><strong>Nom :</strong> <span id="site_nom"></span></p>
                                            <p class="mb-1"><strong>Client :</strong> <span id="site_client"></span></p>
                                            <p class="mb-0"><strong>Zone :</strong> <span id="site_zone"></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="ft-x"></i> Annuler
                        </button>
                        <button type="submit" class="btn btn-success" id="submitGpsUpdate">
                            <i class="ft-save"></i> Enregistrer les coordonnées
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- MISE À JOUR GPS -->

@endsection

@section('add_js')

    <script src="{{ url('assets/geolocation/geolocation.js') }}"></script>

    <script>
        tippy('#view', {
            content: "Consulter",
            placement: 'top',
        });

        tippy('#editor', {
            content: "Modifier",
            placement: 'bottom',
        });

        // Nouveau tippy pour le bouton GPS
        tippy('#updateGps', {
            content: "Mettre à jour GPS",
            placement: 'top',
        });

        tippy('#delete', {
            content: "Supprimer",
            placement: 'bottom',
        });

        tippy('#activer', {
            content: "Activer",
            placement: 'top',
        });

        tippy('#desactiver', {
            content: "Désactiver",
            placement: 'top',
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
            $(".e1,.e2,.e3,.e4").select2();

            $(".e5,.e6,.e7,.e8").select2({
                width: '100%',
                dropdownParent: $("#create")
            });
        });
    </script>

    <script type="text/javascript">
        function showItem(ScNumero) {
            $.ajax({
                url: '{{ route("site-client.show", "") }}/' + ScNumero,
                type: 'GET',
                success: function(data){

                    $('#nom').html(data.ScNomSite)
                    $('#client').html(data.ClDesign)
                    $('#ville').html(data.ViNom)
                    $('#zone').html(data.ZoNom)
                    $('#quartier').html(data.QuNom)
                    $('#precision').html(data.ScPrecision)
                    $('#latitude').html(data.ScLatitudeX)
                    $('#longitude').html(data.ScLongitudeY)


                    if((data.ScIsMultiSuperv) == 'Y')
                    {
                        $('#multi_superv').text('Oui');
                    }
                    else
                    {
                        $('#multi_superv').text('Non');
                    }

                    if((data.ScIsOn) == 'Y')
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
        function editItem(ScNumero) {
            $.ajax({
                url: '{{ route("site-client.edit", "") }}/' + ScNumero,
                type: 'GET',
                success: function(data){

                    var ScNumero=data.ScNumero;

                    $(".form-edit").attr('action', '{{ route("site-client.update", "") }}/' + ScNumero);
                    var form=$(".form-edit").attr('action');

                    $(".ScNomSite").val(data.ScNomSite);
                    $(".ScClient").val(data.ScClient);
                    $(".ScVille").val(data.ScVille);

                    $('.ScPrecision').val(data.ScPrecision)
                    $('.latitude').val($('#latitude').val())
                    $('.longitude').val($('#longitude').val())

                    $(".ScNbVigileJr").val(data.ScNbVigileJr);
                    $(".ScNbVigileNu").val(data.ScNbVigileNu);

                    $(".ScNbMeChienJr").val(data.ScNbMeChienJr);
                    $(".ScNbMeChienNu").val(data.ScNbMeChienNu);

                    $(".ScNbCmdoJr").val(data.ScNbCmdoJr);
                    $(".ScNbCmdoNu").val(data.ScNbCmdoNu);

                    $(".ScNbSupervJr").val(data.ScNbSupervJr);
                    $(".ScNbSupervNu").val(data.ScNbSupervNu);

                    if((data.ScIsMultiSuperv) =='Y')
                    {
                        $('.ScIsMultiSuperv_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.ScIsMultiSuperv_N').attr('checked',true);
                    }

                    if((data.ScIsSuperv) =='Y')
                    {
                        $('.ScIsSuperv_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.ScIsSuperv_N').attr('checked',true);
                    }

                    if((data.ScIsCmdo) =='Y')
                    {
                        $('.ScIsCmdo_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.ScIsCmdo_N').attr('checked',true);
                    }

                    if((data.ScIsMeChien) =='Y')
                    {
                        $('.ScIsMeChien_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.ScIsMeChien_N').attr('checked',true);
                    }

                    if((data.ScIsVigile) =='Y')
                    {
                        $('.ScIsVigile_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.ScIsVigile_N').attr('checked',true);
                    }

                    if((data.ScIsOn) =='Y')
                    {
                        $('.ScIsOn_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.ScIsOn_N').attr('checked',true);
                    }


                    if((data.ScIsOperVideo) =='Y')
                    {
                        $('.ScIsOperVideo_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.ScIsOperVideo_N').attr('checked',true);
                    }

                    if((data.ScIsLocatVideo) =='Y')
                    {
                        $('.ScIsLocatVideo_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.ScIsLocatVideo_N').attr('checked',true);
                    }

                    if((data.ScIsInstVidSurv) =='Y')
                    {
                        $('.ScIsInstVidSurv_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.ScIsInstVidSurv_N').attr('checked',true);
                    }

                    $(".ScVille").change(function()
                    {
                        var ScVille =  $(".ScVille").val();

                        $.get('{{ route("site-client.show_zones_edit","") }}/'+ScVille, function(rep) {
                            $("#afficher_zones_edit").html(rep);

                            $(".ScZone").val(data.ScZone);

                            $(".ScZone").change(function()
                            {
                                var ScZone =  $(".ScZone").val();

                                $.get('{{ route("site-client.show_quartiers_edit","") }}/'+ScZone, function(rep) {
                                    $("#afficher_quartiers_edit").html(rep);
                                    $(".ScQuartier").val(data.ScQuartier);
                                });
                            });

                            if($(".ScZone").val() != null) {
                                var ScZone =  $(".ScZone").val();

                                $.get('{{ route("site-client.show_quartiers_edit","") }}/'+ScZone, function(rep) {
                                    $("#afficher_quartiers_edit").html(rep);
                                    $(".ScQuartier").val(data.ScQuartier);
                                });
                            }
                        });
                    });

                    if($(".ScVille").val() != null) {
                        var ScVille =  $(".ScVille").val();


                        $.get('{{ route("site-client.show_zones_edit","") }}/'+ScVille, function(rep) {
                            $("#afficher_zones_edit").html(rep);
                            $(".ScZone").val(data.ScZone);

                            $(".ScZone").change(function()
                            {
                                var ScZone =  $(".ScZone").val();

                                $.get('{{ route("site-client.show_quartiers_edit","") }}/'+ScZone, function(rep) {
                                    $("#afficher_quartiers_edit").html(rep);
                                    $(".ScQuartier").val(data.ScQuartier);
                                });
                            });

                            if($(".ScZone").val() != null) {
                                var ScZone =  $(".ScZone").val();

                                $.get('{{ route("site-client.show_quartiers_edit","") }}/'+ScZone, function(rep) {
                                    $("#afficher_quartiers_edit").html(rep);
                                    $(".ScQuartier").val(data.ScQuartier);
                                });
                            }
                        });
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
        function desactiveItem(ScNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Désactiver cet élément",
                text: "Confirmez-vous la désactivation de cet élément ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Oui, Désactiver !",
                cancelButtonText: "Annuler !",
                confirmButtonColor: '#2ecc71',
                cancelButtonColor: '#e84118',
                reverseButtons: true

            }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '{{ route("site-client.desactiver", "") }}/' + ScNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire('Site client désactivé avec succès !','','success')
                        window.location.href = "{{ route('sites-client.index') }}";
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
        function activeItem(ScNumero) {
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
                    url: '{{ route("site-client.activer", "") }}/' + ScNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire('Site client activée avec succès !','','success')
                        window.location.href = "{{ route('sites-client.index') }}";
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
        function deleteItem(ScNumero) {
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
                    url: '{{ route("site-client.delete", "") }}/' + ScNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire('Site client supprimé avec succès !','','success')
                        window.location.href = "{{ route('sites-client.index') }}";
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
        // Variable pour stocker les coordonnées actuelles
        var currentGpsCoords = {
            latitude: null,
            longitude: null,
            accuracy: null
        };

        // Fonction pour ouvrir le modal de mise à jour GPS
        function updateGpsItem(ScNumero) {
            // Récupérer les informations du site client
            $.ajax({
                url: '{{ route("site-client.show", "") }}/' + ScNumero,
                type: 'GET',
                success: function(data) {
                    // Stocker l'ID du site
                    $('#gps_site_id').val(ScNumero);

                    // Afficher les informations du site
                    $('#site_nom').html(data.ScNomSite);
                    $('#site_client').html(data.ClDesign);
                    $('#site_zone').html(data.ZoNom);

                    // Pré-remplir la précision avec la valeur existante
                    if(data.ScPrecision) {
                        $('#gps_precision').val(data.ScPrecision);
                    } else {
                        $('#gps_precision').val('');
                    }

                    // Réinitialiser les champs GPS
                    $('#gps_latitude').val('');
                    $('#gps_longitude').val('');
                    $('#gps_accuracy').val('');

                    // Réinitialiser les coordonnées stockées
                    currentGpsCoords = {
                        latitude: null,
                        longitude: null,
                        accuracy: null
                    };

                    // Désactiver le bouton d'enregistrement jusqu'à obtenir une position
                    $('#submitGpsUpdate').prop('disabled', true);
                    $('#submitGpsUpdate').attr('title', 'Veuillez d\'abord obtenir votre position');

                    // Afficher le modal
                    $('#updateGpsModal').modal('show').fadeIn('slow');
                },
                error: function() {
                    Swal.fire('Erreur', 'Impossible de récupérer les informations du site client.', 'error');
                }
            });
        }

        // Fonction pour obtenir la position actuelle
        function getCurrentLocationForUpdate() {
            if (!navigator.geolocation) {
                Swal.fire('Erreur', 'Votre navigateur ne supporte pas la géolocalisation.', 'error');
                return;
            }

            // Afficher un message de chargement
            Swal.fire({
                title: 'Géolocalisation en cours',
                text: 'Veuillez patienter pendant la récupération de votre position...',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    // Stocker les coordonnées
                    currentGpsCoords = {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        accuracy: Math.round(position.coords.accuracy)
                    };

                    // Afficher les coordonnées
                    $('#gps_latitude').val(currentGpsCoords.latitude.toFixed(6).replace('.', ','));
                    $('#gps_longitude').val(currentGpsCoords.longitude.toFixed(6).replace('.', ','));
                    $('#gps_accuracy').val(currentGpsCoords.accuracy + ' m');

                    // Pré-remplir automatiquement la précision si le champ est vide
                    if (!$('#gps_precision').val()) {
                        $('#gps_precision').val(currentGpsCoords.accuracy);
                    }

                    // Activer le bouton d'enregistrement
                    $('#submitGpsUpdate').prop('disabled', false);
                    $('#submitGpsUpdate').attr('title', '');

                    Swal.fire({
                        title: 'Position obtenue !',
                        text: `Latitude: ${currentGpsCoords.latitude.toFixed(6)}\nLongitude: ${currentGpsCoords.longitude.toFixed(6)}\nPrécision: ${currentGpsCoords.accuracy} m`,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: true
                    });
                },
                function(error) {
                    handleGpsErrorForModal(error);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        }

        // Gestion des erreurs GPS pour le modal
        function handleGpsErrorForModal(error) {
            let message = '';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    message = 'Vous avez refusé l\'accès à votre position. Pour utiliser cette fonctionnalité, veuillez autoriser l\'accès à la géolocalisation.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    message = 'Les informations de localisation ne sont pas disponibles. Vérifiez que votre GPS est activé.';
                    break;
                case error.TIMEOUT:
                    message = 'La demande de localisation a expiré. Veuillez réessayer.';
                    break;
                default:
                    message = 'Une erreur inconnue est survenue lors de la géolocalisation.';
                    break;
            }

            Swal.fire({
                title: 'Erreur de géolocalisation',
                text: message,
                icon: 'error',
                confirmButtonText: 'OK'
            });

            // Désactiver le bouton d'enregistrement
            $('#submitGpsUpdate').prop('disabled', true);
        }

        // Soumission du formulaire de mise à jour GPS
        $(document).ready(function() {
            $('#updateGpsForm').on('submit', function(e) {
                e.preventDefault();

                // Vérifier si des coordonnées ont été obtenues
                if (!currentGpsCoords.latitude || !currentGpsCoords.longitude) {
                    Swal.fire('Attention', 'Veuillez d\'abord obtenir votre position GPS.', 'warning');
                    return;
                }

                var siteId = $('#gps_site_id').val();
                var latitude = currentGpsCoords.latitude.toFixed(6);
                var longitude = currentGpsCoords.longitude.toFixed(6);
                var precision = $('#gps_precision').val() || currentGpsCoords.accuracy;

                // Afficher une confirmation
                Swal.fire({
                    title: 'Confirmation',
                    text: `Voulez-vous mettre à jour les coordonnées GPS du site client ?\n\nLatitude: ${latitude}\nLongitude: ${longitude}\nPrécision: ${precision} m`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, mettre à jour',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Envoyer la requête de mise à jour
                        $.ajax({
                            url: '{{ route("site-client.update-gps", "") }}/' + siteId,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'PUT',
                                ScLatitudeX: latitude,
                                ScLongitudeY: longitude,
                                ScPrecision: precision
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Succès !',
                                    text: 'Les coordonnées GPS ont été mises à jour avec succès.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    $('#updateGpsModal').modal('hide');
                                    // Optionnel : recharger la page ou rafraîchir le tableau
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                console.log(xhr);
                                let errorMsg = 'Impossible de mettre à jour les coordonnées.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                }
                                Swal.fire('Erreur', errorMsg, 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#ScVille").change(function()
            {
                var ScVille =  $("#ScVille").val();

                $.get('{{ route("site-client.show_zones","") }}/'+ScVille, function(rep) {
                    $("#afficher_zones").html(rep);

                    $("#ScZone").change(function()
                    {
                        var ScZone =  $("#ScZone").val();

                        $.get('{{ route("site-client.show_quartiers","") }}/'+ScZone, function(rep) {
                            $("#afficher_quartiers").html(rep);
                        });
                    });

                    if($("#ScZone").val() != null) {
                        var ScZone =  $("#ScZone").val();

                        $.get('{{ route("site-client.show_quartiers","") }}/'+ScZone, function(rep) {
                            $("#afficher_quartiers").html(rep);
                        });
                    }
                });
            });

            if($("#ScVille").val() != null) {
                var ScVille =  $("#ScVille").val();

                $.get('{{ route("site-client.show_zones","") }}/'+ScVille, function(rep) {
                    $("#afficher_zones").html(rep);

                    $("#ScZone").change(function()
                    {
                        var ScZone =  $("#ScZone").val();

                        $.get('{{ route("site-client.show_quartiers","") }}/'+ScZone, function(rep) {
                            $("#afficher_quartiers").html(rep);
                        });
                    });

                    if($("#ScZone").val() != null) {
                        var ScZone =  $("#ScZone").val();

                        $.get('{{ route("site-client.show_quartiers","") }}/'+ScZone, function(rep) {
                            $("#afficher_quartiers").html(rep);
                        });
                    }
                });
            }
        });
    </script>


    <script type="text/javascript">
        $(document).ready(function() {
            $("input[type=radio][name=ScIsVigile]").click(function()
            {
                var ScIsVigile = getSelectedValue('ScIsVigile');

                if(ScIsVigile == "Y") {
                    $("#afficher_nbre_vigiles_1").show();
                    $("#afficher_nbre_vigiles_2").show();
                    $("#ScNbVigileJr").attr("required",true);
                    $("#ScNbVigileNu").attr("required",true);
                } else {
                    $("#afficher_nbre_vigiles_1").hide();
                    $("#afficher_nbre_vigiles_2").hide();
                    $("#ScNbVigileJr").attr("required",false);
                    $("#ScNbVigileNu").attr("required",false);
                }
            });

            if($("input[name='ScIsVigile']:checked").val()=='Y')
            {
                $("#afficher_nbre_vigiles_1").show();
                $("#afficher_nbre_vigiles_2").show();
                $("#ScNbVigileJr").attr("required",true);
                $("#ScNbVigileNu").attr("required",true);
            }
            else
            {
                $("#afficher_nbre_vigiles_1").hide();
                $("#afficher_nbre_vigiles_2").hide();
                $("#ScNbVigileJr").attr("required",false);
                $("#ScNbVigileNu").attr("required",false);
            }
        });
    </script>


    <script type="text/javascript">
        $(document).ready(function() {
            $("input[type=radio][name=ScIsMeChien]").click(function()
            {
                var ScIsMeChien = getSelectedValue('ScIsMeChien');

                if(ScIsMeChien == "Y") {
                    $("#afficher_nbre_maitres_chien_1").show();
                    $("#afficher_nbre_maitres_chien_2").show();
                    $("#ScNbMeChienJr").attr("required",true);
                    $("#ScNbMeChienNu").attr("required",true);
                } else {
                    $("#afficher_nbre_maitres_chien_1").hide();
                    $("#afficher_nbre_maitres_chien_2").hide();
                    $("#ScNbMeChienJr").attr("required",false);
                    $("#ScNbMeChienNu").attr("required",false);
                }
            });

            if($("input[name='ScIsMeChien']:checked").val()=='Y')
            {
                $("#afficher_nbre_maitres_chien_1").show();
                $("#afficher_nbre_maitres_chien_2").show();
                $("#ScNbMeChienJr").attr("required",true);
                $("#ScNbMeChienNu").attr("required",true);
            }
            else
            {
                $("#afficher_nbre_maitres_chien_1").hide();
                $("#afficher_nbre_maitres_chien_2").hide();
                $("#ScNbMeChienJr").attr("required",false);
                $("#ScNbMeChienNu").attr("required",false);
            }
        });
    </script>


    <script type="text/javascript">
        $(document).ready(function() {
            $("input[type=radio][name=ScIsCmdo]").click(function()
            {
                var ScIsCmdo = getSelectedValue('ScIsCmdo');

                if(ScIsCmdo == "Y") {
                    $("#afficher_nbre_commandos_1").show();
                    $("#afficher_nbre_commandos_2").show();
                    $("#ScNbCmdoJr").attr("required",true);
                    $("#ScNbCmdoNu").attr("required",true);
                } else {
                    $("#afficher_nbre_commandos_1").hide();
                    $("#afficher_nbre_commandos_2").hide();
                    $("#ScNbCmdoJr").attr("required",false);
                    $("#ScNbCmdoNu").attr("required",false);
                }
            });

            if($("input[name='ScIsCmdo']:checked").val()=='Y')
            {
                $("#afficher_nbre_commandos_1").show();
                $("#afficher_nbre_commandos_2").show();
                $("#ScNbCmdoJr").attr("required",true);
                $("#ScNbCmdoNu").attr("required",true);
            }
            else
            {
                $("#afficher_nbre_commandos_1").hide();
                $("#afficher_nbre_commandos_2").hide();
                $("#ScNbCmdoJr").attr("required",false);
                $("#ScNbCmdoNu").attr("required",false);
            }
        });
    </script>


    <script type="text/javascript">
        $(document).ready(function() {
            $("input[type=radio][name=ScIsSuperv]").click(function()
            {
                var ScIsSuperv = getSelectedValue('ScIsSuperv');

                if(ScIsSuperv == "Y") {
                    $("#afficher_nbre_superviseurs_1").show();
                    $("#afficher_nbre_superviseurs_2").show();
                    $("#ScNbSupervJr").attr("required",true);
                    $("#ScNbSupervNu").attr("required",true);
                } else {
                    $("#afficher_nbre_superviseurs_1").hide();
                    $("#afficher_nbre_superviseurs_2").hide();
                    $("#ScNbSupervJr").attr("required",false);
                    $("#ScNbSupervNu").attr("required",false);
                }
            });

            if($("input[name='ScIsSuperv']:checked").val()=='Y')
            {
                $("#afficher_nbre_superviseurs_1").show();
                $("#afficher_nbre_superviseurs_2").show();
                $("#ScNbSupervJr").attr("required",true);
                $("#ScNbSupervNu").attr("required",true);
            }
            else
            {
                $("#afficher_nbre_superviseurs_1").hide();
                $("#afficher_nbre_superviseurs_2").hide();
                $("#ScNbSupervJr").attr("required",false);
                $("#ScNbSupervNu").attr("required",false);
            }
        });
    </script>


@endsection

