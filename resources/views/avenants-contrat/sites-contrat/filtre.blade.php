@extends('layout.app')

@section('title', 'Liste des personnels affectés sur le contrat/avenant')

@section('content')

@include('layout.alert')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Liste des personnels affectés sur le contrat/avenant</b></h4>
                        <h4 class="card-title">
                            <b>Client : </b>{{$avenant_contrat->ClDesign }},
                            <b>Réf : </b>{{ $avenant_contrat->AcRefAven }},
                            <b>Type : </b>{{ $avenant_contrat->AcType == 'C' ? 'Contrat':'Avenant' }},
                            <b>Nbre agents jour : </b>{{$avenant_contrat->AcNbAgentJr }},
                            <b>Nbre agents nuit : </b>{{ $avenant_contrat->AcNbAgentNu }},
                            <b>Nbre maîtres chien jour : </b>{{$avenant_contrat->AcNbreMCJour }},
                            <b>Nbre maîtres chien nuit : </b>{{ $avenant_contrat->AcNbreMCNuit }},
                            <b>Nbre superviseurs jour : </b>{{$avenant_contrat->AcNbSupervJr }},
                            <b>Nbre superviseurs nuit : </b>{{ $avenant_contrat->AcNbSupervNu }},
                            <b>Nbre commando jour : </b>{{$avenant_contrat->AcNbComdoJr }},
                            <b>Nbre commando nuit : </b>{{ $avenant_contrat->AcNbComdoNu }},
                            <b>Date effet : </b>{{ date('d/m/Y',strtotime($avenant_contrat->AcDateEffet)) }},
                            @if($avenant_contrat->AcDateFin != null)
                                <b>Date fin : </b>{{ date('d/m/Y',strtotime($avenant_contrat->AcDateFin)) }},
                            @else
                                <b>Date fin : </b>,
                            @endif

                        </h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard table-responsive">
                            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create"><i class="ft-plus"></i>&nbsp;Ajouter site client</button>

                            <form action="{{ route('avenant-contrat.filtre_sites_contrat') }}" method="POST">
                                @csrf
                                <div class="row">

                                    <input type="hidden" name="AcNumero" value="{{ crypter($AcNumero) }}">

                                    <div class="col-xl-2 col-md-2">
                                        <div class="form-group">
                                            <label for="site_personnel">Sites client</label>
                                            <select name="site_personnel" id="site_personnel" class="form-control e1">
                                                <option selected="selected" value="all">Tous</option>
                                                @foreach($sites_client_f as $site_client_f)
                                                    <option value="{{ $site_client_f->ScNumero }}" {{ $site_client_f->ScVille == $site_personnel ? 'selected' : '' }}>{{ $site_client_f->ScNomSite }}</option>
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
                                                    <option value="{{ $ville_f->ViNumero }}" {{ $ville_f->ViNumero == $ville_site ? 'selected' : '' }}>{{ $ville_f->ViNom }}</option>
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
                                                    <option value="{{ $zone_f->ZoNumero }}" {{ $zone_f->ZoNumero == $zone_site ? 'selected' : '' }}>{{ $zone_f->ZoNom }}</option>
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
                                                    <option value="{{ $quartier_f->QuNumero }}" {{ $quartier_f->QuNumero == $quartier_site ? 'selected' : '' }}>{{ $quartier_f->QuNom }}</option>
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
                                        <th>Site</th>
                                        <th>Nbre agents jour</th>
                                        <th>Nbre agents nuit</th>
                                        <th>Nbre maîtres chien jour</th>
                                        <th>Nbre maîtres chien nuit</th>
                                        <th>Nbre superviseurs jour</th>
                                        <th>Nbre superviseurs nuit</th>
                                        <th>Nbre commando jour</th>
                                        <th>Nbre commando nuit</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sites_contrats as $site_contrat)
                                        <tr>
                                            <td>{{ $site_contrat->ScNomSite }}</td>
                                            <td>{{ $site_contrat->CsNbreAgentJr }}</td>
                                            <td>{{ $site_contrat->CsNbreAgentNu }}</td>
                                            <td>{{ $site_contrat->CsNbreMCJr }}</td>
                                            <td>{{ $site_contrat->CsNbreMCNu }}</td>
                                            <td>{{ $site_contrat->CsNbSupervJr }}</td>
                                            <td>{{ $site_contrat->CsNbSupervNu }}</td>
                                            <td>{{ $site_contrat->CsNbComdoJr }}</td>
                                            <td>{{ $site_contrat->CsNbComdoNu }}</td>

                                            @if($site_contrat->CsIsOn == 'Y')
                                                <td><span class="badge light badge-success">Actif</span></td>
                                            @else
                                                <td><span class="badge light badge-danger">Inactif</span></td>
                                            @endif

                                            <td style="text-align:center;">
                                                <a onclick="showItem('{{$site_contrat->CsNumero}}')" class="info p-0" id="view">
                                                    <i class="ft-eye font-medium-3 mr-2"></i>
                                                </a>

                                                <a href="#" onclick="editItem('{{$site_contrat->CsNumero}}')" class="warning p-0" id="editor">
                                                    <i class="ft-edit font-medium-3 mr-2"></i>
                                                </a>


                                                @if($site_contrat->CsIsOn == 'Y')
                                                    <a onclick="desactiveItem('{{$site_contrat->CsNumero }}')" class="danger p-0" id="inactif">
                                                        <i class="ft-log-out font-medium-3 mr-2"></i>
                                                    </a>
                                                @else
                                                    <a onclick="activeItem('{{$site_contrat->CsNumero }}')" class="success p-0" id="actif">
                                                        <i class="ft-log-in font-medium-3 mr-2"></i>
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
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">

                <form method="POST" action="{{ route('contrats-site.store') }}" class="needs-validation" novalidate>
                    @csrf

                    <input type="hidden" class="form-control" name="CsContratClient" value="{{ $avenant_contrat->AcNumero }}"/>


                    <div class="modal-header bg-info white">
                        <h4 class="modal-title" id="myModalLabel16">AJOUTER CONTRAT SITE CLIENT</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CsSiteClient">Site client <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e5 form-control" id="CsSiteClient" name="CsSiteClient" required>
                                        <option selected value="">Selectionner...</option>
                                        @foreach($sites_client as $site_client)
                                            <option value="{{ $site_client->ScNumero }}">{{ $site_client->ScNomSite }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="CsNbreAgentJr">Nombre d'agents jour <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" class="form-control number" id="CsNbreAgentJr" name="CsNbreAgentJr" value="{{ old('CsNbreAgentJr') }}" placeholder="Nombre d'agents jour" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="CsNbreAgentNu">Nombre d'agents nuit <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" class="form-control number" id="CsNbreAgentNu" name="CsNbreAgentNu" value="{{ old('CsNbreAgentNu') }}" placeholder="Nombre d'agents nuit" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CsNbreMCJr">Nombre de maîtres chien par jour <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" class="form-control number" id="CsNbreMCJr" name="CsNbreMCJr" value="{{ old('CsNbreMCJr') }}" placeholder="Nombre de maîtres chien par jour" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CsNbreMCNu">Nombre de maîtres chien par nuit <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" class="form-control number" id="CsNbreMCNu" name="CsNbreMCNu" value="{{ old('CsNbreMCNu') }}" placeholder="Nombre de maîtres chien par nuit" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                             <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CsNbSupervJr">Nombre de superviseurs par jour <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" class="form-control number" id="CsNbSupervJr" name="CsNbSupervJr" value="{{ old('CsNbSupervJr') }}" placeholder="Nombre de superviseurs par jour" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CsNbSupervNu">Nombre de superviseurs par nuit <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" class="form-control number" id="CsNbSupervNu" name="CsNbSupervNu" value="{{ old('CsNbSupervNu') }}" placeholder="Nombre de superviseurs par nuit" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                             <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CsNbComdoJr">Nombre de commando par jour <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" class="form-control number" id="CsNbComdoJr" name="CsNbComdoJr" value="{{ old('CsNbComdoJr') }}" placeholder="Nombre de commando par jour" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CsNbComdoNu">Nombre de commando par nuit <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" class="form-control number" id="CsNbComdoNu" name="CsNbComdoNu" value="{{ old('CsNbComdoNu') }}" placeholder="Nombre de commando par nuit" required/>
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
                                            <input type="radio" id="customRadioInline1" name="CsIsOn" class="custom-control-input" value="Y" checked/>
                                            <label class="custom-control-label" for="customRadioInline1">Actif</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline2" name="CsIsOn" class="custom-control-input" value="N"/>
                                            <label class="custom-control-label" for="customRadioInline2">Inactif</label>
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
                                    <label for="CsDetails">Détails</label>
                                    <textarea rows="2" class="form-control" id="CsDetails" name="PvDetails" placeholder="Commentaires"></textarea>
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
                        <h5 class="modal-title" id="myModalLabel16">MODIFICATION CONTRAT SITE CLIENT</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CsSiteClient">Site client</label>
                                    <input type="text" class="form-control number CsSiteClient" readonly/>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="CsNbreAgentJr">Nombre d'agents jour <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" class="form-control number CsNbreAgentJr" id="CsNbreAgentJr" name="CsNbreAgentJr" value="{{ old('CsNbreAgentJr') }}" placeholder="Nombre d'agents jour" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="CsNbreAgentNu">Nombre d'agents nuit <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" class="form-control number CsNbreAgentNu" id="CsNbreAgentNu" name="CsNbreAgentNu" value="{{ old('CsNbreAgentNu') }}" placeholder="Nombre d'agents nuit" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CsNbreMCJr">Nombre de maîtres chien par jour <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" class="form-control number CsNbreMCJr" id="CsNbreMCJr" name="CsNbreMCJr" value="{{ old('CsNbreMCJr') }}" placeholder="Nombre de maîtres chien par jour" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CsNbreMCNu">Nombre de maîtres chien par nuit <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" class="form-control number CsNbreMCNu" id="CsNbreMCNu" name="CsNbreMCNu" value="{{ old('CsNbreMCNu') }}" placeholder="Nombre de maîtres chien par nuit" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CsNbSupervJr">Nombre de superviseurs par jour <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" class="form-control number CsNbSupervJr" id="CsNbSupervJr" name="CsNbSupervJr" value="{{ old('CsNbSupervJr') }}" placeholder="Nombre de superviseurs par jour" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CsNbSupervNu">Nombre de superviseurs par nuit <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" class="form-control number CsNbSupervNu" id="CsNbSupervNu" name="CsNbSupervNu" value="{{ old('CsNbSupervNu') }}" placeholder="Nombre de superviseurs par nuit" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CsNbComdoJr">Nombre de commando par jour <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" class="form-control number CsNbComdoJr" id="CsNbComdoJr" name="CsNbComdoJr" value="{{ old('CsNbComdoJr') }}" placeholder="Nombre de commando par jour" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="CsNbComdoNu">Nombre de commando par nuit <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="text" class="form-control number CsNbComdoNu" id="CsNbComdoNu" name="CsNbComdoNu" value="{{ old('CsNbComdoNu') }}" placeholder="Nombre de commando par nuit" required/>
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
                                            <input type="radio" id="customRadioInline3" name="CsIsOn" class="custom-control-input CsIsOn_Y" value="Y" checked/>
                                            <label class="custom-control-label" for="customRadioInline3">Actif</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="customRadioInline4" name="CsIsOn" class="custom-control-input CsIsOn_N" value="N"/>
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
                                    <label for="CsDetails">Détails</label>
                                    <textarea rows="2" class="form-control CsDetails" id="CsDetails" name="PvDetails" placeholder="Commentaires"></textarea>
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

    <!-- Consulter un contrat site -->
    <div class="modal fade text-left" id="show_contrat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">INFORMATIONS CONTRAT SITE</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <tbody>
                                        <tr>
                                            <th scope="row">Contrat</th>
                                            <td>{{ $avenant_contrat->AcRefAven }}</td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Client</th>
                                            <td>{{$avenant_contrat->ClDesign }}</td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Site</th>
                                            <td id="site"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Nbre agents de jour</th>
                                            <td id="nb_agent_jour"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Nbre agents de nuit</th>
                                            <td id="nb_agent_nuit"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Nbre maîtres chien de jour</th>
                                            <td id="nb_mc_jour"></td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <tbody>

                                        <tr>
                                            <th scope="row">Nbre maîtres chien de nuit</th>
                                            <td id="nb_mc_nuit"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Nbre superviseurs de jour</th>
                                            <td id="nb_superv_jour"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Nbre superviseurs de nuit</th>
                                            <td id="nb_superv_nuit"></td>
                                        </tr>

                                         <tr>
                                            <th scope="row">Nbre cmdo de jour</th>
                                            <td id="nb_comdo_jour"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Nbre cmdo de nuit</th>
                                            <td id="nb_comdo_nuit"></td>
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
                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Consulter un contrat site -->

@endsection

@section('add_js')

    <script>
        $(document).ready(function() {
            $(".e1").select2();
            $(".e2").select2();
            $(".e3").select2();
            $(".e4").select2();

            $(".e5").select2({
                width: '100%',
                dropdownParent: $("#create")
            });
        });
    </script>

    <script>
        tippy('#view', {
            content: "Consulter",
            placement: 'top',
        });

        tippy('#edit', {
            content: "Modifier",
            placement: 'bottom',
        });

        tippy('#actif', {
            content: "Activer ce contrat",
            placement: 'top',
        });

        tippy('#inactif', {
            content: "Désactiver ce contrat",
            placement: 'top',
        });

        tippy('#delete', {
            content: "Supprimer",
            placement: 'top',
        });
    </script>

    <script type="text/javascript">
        function showItem(CsNumero) {
            $.ajax({
                url: '{{ route("contrat-site.show", "") }}/' + CsNumero , // Use the named route here
                type: 'GET',
                success: function(data){
                    $('#site').html(data.ScNomSite)
                    $('#nb_agent_jour').html(data.CsNbreAgentJr)
                    $('#nb_agent_nuit').html(data.CsNbreAgentNu)
                    $('#nb_mc_jour').html(data.CsNbreMCJr)
                    $('#nb_mc_nuit').html(data.CsNbreMCNu)
                    $('#nb_superv_jour').html(data.CsNbSupervJr)
                    $('#nb_superv_nuit').html(data.CsNbSupervNu)
                    $('#nb_comdo_jour').html(data.CsNbComdoJr)
                    $('#nb_comdo_nuit').html(data.CsNbComdoNu)

                    if((data.CsIsOn) == 'Y')
                    {
                        $('#statut').text('Actif');
                    }
                    else
                    {
                        $('#statut').text('Inactif');
                    }

                    $('#show_contrat').modal('show').fade('slow')
                },
                error: function(){
                    console.log("Something goes wrong")
                }
            })
        }
    </script>

    <script type="text/javascript">
        function editItem(CsNumero) {
            $.ajax({
                url: '{{ route("contrat-site.edit", "") }}/' + CsNumero,
                type: 'GET',
                success: function(data){

                    var CsNumero=data.CsNumero;

                    $(".form-edit").attr('action', '{{ route("contrat-site.update", "") }}/' + CsNumero);
                    var form=$(".form-edit").attr('action');

                    $(".CsSiteClient").val(data.ScNomSite);
                    $(".CsNbreAgentJr").val(data.CsNbreAgentJr);
                    $(".CsNbreAgentNu").val(data.CsNbreAgentNu);
                    $(".CsNbreMCJr").val(data.CsNbreMCJr);
                    $(".CsNbreMCNu").val(data.CsNbreMCNu);
                    $(".CsNbSupervJr").val(data.CsNbSupervJr);
                    $(".CsNbSupervNu").val(data.CsNbSupervNu);
                    $(".CsNbComdoJr").val(data.CsNbComdoJr);
                    $(".CsNbComdoNu").val(data.CsNbComdoNu);

                    if((data.CsIsOn) =='Y')
                    {
                        $('.CsIsOn_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.CsIsOn_N').attr('checked',true);
                    }

                    $(".CsDetails").val(data.CsDetails);



                    $(".e2").select2({
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
        function desactiveItem(CsNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Désactiver ce contrat",
                text: "Confirmez-vous la désactivation de ce contrat ?",
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
                        url: '{{ route("contrat-site.desactiver", "") }}/' + CsNumero , // Use the named route here
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Contrat site désactivé avec succès !',
                                '',
                                'success'
                            )
                        window.location.href = "{{ route('avenants-contrat.index') }}";
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
        function activeItem(CsNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Activer ce contrat",
                text: "Confirmez-vous l'activation de ce contrat ?",
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
                        url: '{{ route("contrat-site.activer", "") }}/' + CsNumero , // Use the named route here
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Contrat site avec succès !',
                                '',
                                'success'
                            )
                        window.location.href = "{{ route('avenants-contrat.index') }}";
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

@endsection
