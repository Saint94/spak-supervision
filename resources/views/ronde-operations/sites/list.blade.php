@extends('layout.app')

@section('title', 'Liste des sites concernés par la ronde')

@section('content')

@include('layout.alert')

<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><b>Liste des sites concernés par la ronde</b></h4>
                    <h4 class="card-title">
                        <b>Responsable opération : </b>{{ $ronde_operation->responsable1_name }},
                        <b>Date début : </b>{{ date('d/m/Y',strtotime($ronde_operation->RoDateDebut)) }},
                        <b>Heure de début : </b>{{ date('H:i',strtotime($ronde_operation->RoHeureDebut)) }},
                    </h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard table-responsive">

                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create"><i class="ft-plus"></i>&nbsp;Ajouter site</button>

                        {{--<form action="{{ route('site-ronde-oper.filtre') }}" method="POST">
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
                                        <label for="site_client">Site client</label>
                                        <select id="site_client" name="site_client" class="form-control e5">
                                            <option selected="selected" value="all">Tous</option>
                                            @foreach($sites_client as $site_client)
                                                <option value="{{ $site_client->ScNumero }}">{{ $site_client->ScNomSite }}</option>
                                            @endforeach
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
                        </form>--}}

                        <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Site client</th>
                                    <th>Date début</th>
                                    <th>Heure début</th>
                                    <th>Date fin</th>
                                    <th>Heure fin</th>
                                    <th>Titre</th>
                                    <th>Type non conformité</th>
                                    <th>Plan d'action ?</th>
                                    <th>Date limite plan d'action</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sites_ronde_oper as $site_ronde_oper)
                                    <tr>
                                        <td>{{ $site_ronde_oper->ClDesign }}</td>
                                        <td>{{ $site_ronde_oper->ScNomSite }}</td>
                                        <td>{{ date('d/m/Y',strtotime($site_ronde_oper->SrDateDebut)) }}</td>
                                        <td>{{ date('H:i',strtotime($site_ronde_oper->SrHeureDebut)) }}</td>
                                        <td>{{ $site_ronde_oper->SrDateFin == NULL ? '' : date('d/m/Y',strtotime($site_ronde_oper->SrDateFin)) }}</td>
                                        <td>{{ $site_ronde_oper->SrHeureFin == NULL ? '' : date('d/m/Y',strtotime($site_ronde_oper->SrHeureFin)) }}</td>
                                        <td>{{ $site_ronde_oper->SrTitre }}</td>

                                        @if($site_ronde_oper->SrTypeNonConform == 'M')
                                            <td>Majeur</td>
                                        @elseif($site_ronde_oper->SrTypeNonConform == 'N')
                                            <td>Mineure</td>
                                        @else
                                            <td>Aucune</td>
                                        @endif

                                        @if($site_ronde_oper->SrIsPlanAction == 'Y')
                                            <td>Oui</td>
                                        @else
                                            <td>Non</td>
                                        @endif

                                        <td>{{ $site_ronde_oper->SrDateLimPlanAct == NULL ? '' : date('d/m/Y',strtotime($site_ronde_oper->SrDateLimPlanAct)) }}</td>

                                        @if($site_ronde_oper->SrStatut == 'V')
                                            <td><span class="badge light badge-success">Vert</span></td>
                                        @else
                                            <td><span class="badge light badge-danger">Rouge</span></td>
                                        @endif

                                        <td style="text-align: center;">
                                            <a href="#" onclick="showItem('{{ $site_ronde_oper->SrNumero }}')" class="info p-0" id="view">
                                                <i class="ft-eye font-medium-3 mr-2"></i>
                                            </a>

                                            <a href="#" onclick="editItem('{{$site_ronde_oper->SrNumero}}')" class="warning p-0" id="editor">
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

            <form method="POST" action="{{ route('sites-ronde-oper.store') }}" class="needs-validation" novalidate>
                @csrf

                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">ENREGISTREMENT DU SITE CONCERNÉ</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <input type="hidden" value="{{ $ronde_operation->RoNumero }}" name="SrRondeSuperv">

                <div class="modal-body">
                    <div class="row">
                       <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="SrClient">Client <abbr style="color: #bd4147;">(*)</abbr></label>
                               <select class="e1 form-control" id="SrClient" name="SrClient" required>
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

                        <div class="col-xl-3 col-md-3" id="afficher_liste_sites_client">
                            <div class="form-group">
                                <label for="SrSiteClient">Site client <abbr style="color: #bd4147;">(*)</abbr></label>
                               <select class="e2 form-control" id="SrSiteClient" name="SrSiteClient" required>
                                    <option selected value="">Selectionner...</option>
                                </select>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="SrDateDebut">Date début <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="date" class="form-control" id="SrDateDebut" name="SrDateDebut" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="SrHeureDebut">Heure début <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="time" class="form-control" id="SrHeureDebut" name="SrHeureDebut" value="{{ date('H:i', strtotime(date('H:i'))) }}" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="SrDateFin">Date fin</label>
                                <input type="date" class="form-control" id="SrDateFin" name="SrDateFin"/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="SrHeureFin">Heure fin</label>
                                <input type="time" class="form-control" id="SrHeureFin" name="SrHeureFin"/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="SrTitre">Titre de l'opération <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="SrTitre" class="form-control" name="SrTitre" value="{{ old('SrTitre') }}" placeholder="Titre de l'opération" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label>Type de non conformité <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline1" name="SrTypeNonConform" class="custom-control-input" value="M" checked/>
                                        <label class="custom-control-label" for="customRadioInline1">Majeure</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline2" name="SrTypeNonConform" class="custom-control-input" value="N"/>
                                        <label class="custom-control-label" for="customRadioInline2">Mineure</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline3" name="SrTypeNonConform" class="custom-control-input" value="A"/>
                                        <label class="custom-control-label" for="customRadioInline3">Aucune</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label>Y'a t-il un plan d'action ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline4" name="SrIsPlanAction" class="custom-control-input" value="N" checked/>
                                        <label class="custom-control-label" for="customRadioInline4">Non</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline5" name="SrIsPlanAction" class="custom-control-input" value="Y"/>
                                        <label class="custom-control-label" for="customRadioInline5">Oui</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3" id="afficher_date_limite">
                            <div class="form-group">
                                <label for="SrDateLimPlanAct">Date limite plan d'action</label>
                                <input type="date" class="form-control" id="SrDateLimPlanAct" name="SrDateLimPlanAct" value=""/>
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
                                        <input type="radio" id="customRadioInline6" name="SrStatut" class="custom-control-input" value="V" checked/>
                                        <label class="custom-control-label" for="customRadioInline6">Vert</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline7" name="SrStatut" class="custom-control-input" value="R"/>
                                        <label class="custom-control-label" for="customRadioInline7">Rouge</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label>Mise en place du plan d'action avec succès ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline8" name="SrIsPlanActionOk" class="custom-control-input" value="Y" checked/>
                                        <label class="custom-control-label" for="customRadioInline8">Oui</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline9" name="SrIsPlanActionOk" class="custom-control-input" value="N"/>
                                        <label class="custom-control-label" for="customRadioInline9">Non</label>
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
                                <label for="SrResumePlanAct">Résumé du Plan d'action</label>
                                <textarea rows="2" class="form-control" id="SrResumePlanAct" name="SrResumePlanAct" placeholder="Commentaires"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <input type="file" class="filepond" id="SrDocumPlanAct" name="SrDocumPlanAct" accept="*"/>
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
                    <h5 class="modal-title" id="myModalLabel16">MODIFICATION D'UN SITE CONCERNÉ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <input type="hidden" value="{{ $ronde_operation->RoNumero }}" name="SrRondeSuperv">

                <div class="modal-body">
                    <div class="row">
                       <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="SrClient">Client <abbr style="color: #bd4147;">(*)</abbr></label>
                               <select class="e3 form-control SrClient" id="SrClient" name="SrClient" required>
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

                        <div class="col-xl-3 col-md-3" id="afficher_liste_sites_client_edit">
                            <div class="form-group">
                                <label for="SrSiteClient">Site client <abbr style="color: #bd4147;">(*)</abbr></label>
                               <select class="e4 form-control SrSiteClient" id="SrSiteClient" name="SrSiteClient" required>
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
                                <label for="SrDateDebut">Date début <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="date" class="form-control SrDateDebut" id="SrDateDebut" name="SrDateDebut" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="SrHeureDebut">Heure début <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="time" class="form-control SrHeureDebut" id="SrHeureDebut" name="SrHeureDebut" value="{{ date('H:i', strtotime(date('H:i'))) }}" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="SrDateFin">Date fin</label>
                                <input type="date" class="form-control SrDateFin" id="SrDateFin" name="SrDateFin"/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label for="SrHeureFin">Heure fin</label>
                                <input type="time" class="form-control SrHeureFin" id="SrHeureFin" name="SrHeureFin"/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="SrTitre">Titre de l'opération <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="text" id="SrTitre" class="form-control SrTitre" name="SrTitre" value="{{ old('SrTitre') }}" placeholder="Titre de l'opération" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label>Type de non conformité <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline10" name="SrTypeNonConform" class="custom-control-input SrTypeNonConform_M" value="M"/>
                                        <label class="custom-control-label" for="customRadioInline10">Majeure</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline11" name="SrTypeNonConform" class="custom-control-input SrTypeNonConform_N" value="N"/>
                                        <label class="custom-control-label" for="customRadioInline11">Mineure</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline12" name="SrTypeNonConform" class="custom-control-input SrTypeNonConform_A" value="A"/>
                                        <label class="custom-control-label" for="customRadioInline12">Aucune</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label>Y'a t-il un plan d'action ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline13" name="SrIsPlanAction" class="custom-control-input SrIsPlanAction_N" value="N"/>
                                        <label class="custom-control-label" for="customRadioInline13">Non</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline14" name="SrIsPlanAction" class="custom-control-input SrIsPlanAction_Y" value="Y"/>
                                        <label class="custom-control-label" for="customRadioInline14">Oui</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3" id="afficher_date_limite">
                            <div class="form-group">
                                <label for="SrDateLimPlanAct">Date limite plan d'action</label>
                                <input type="date" class="form-control SrDateLimPlanAct" id="SrDateLimPlanAct" name="SrDateLimPlanAct" value=""/>
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
                                        <input type="radio" id="customRadioInline15" name="SrStatut" class="custom-control-input SrStatut_V" value="V"/>
                                        <label class="custom-control-label" for="customRadioInline15">Vert</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline16" name="SrStatut" class="custom-control-input SrStatut_R" value="R"/>
                                        <label class="custom-control-label" for="customRadioInline16">Rouge</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label>Mise en place du plan d'action avec succès ? <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline17" name="SrIsPlanActionOk" class="custom-control-input SrIsPlanActionOk_Y" value="Y"/>
                                        <label class="custom-control-label" for="customRadioInline17">Oui</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline18" name="SrIsPlanActionOk" class="custom-control-input SrIsPlanActionOk_N" value="N"/>
                                        <label class="custom-control-label" for="customRadioInline18">Non</label>
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
                                <label for="SrResumePlanAct">Résumé du Plan d'action</label>
                                <textarea rows="2" class="form-control SrResumePlanAct" id="SrResumePlanAct" name="SrResumePlanAct" placeholder="Commentaires"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <input type="file" class="filepond" id="SrDocumPlanActEdit" name="SrDocumPlanAct" accept="*"/>
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
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h5 class="modal-title" id="myModalLabel16">INFORMATIONS SITE CONCERNÉ</h5>
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
                                        <td id="nom_client"></td>
                                    </tr>
                                   
                                    <tr>
                                        <th scope="row">Site client</th>
                                        <td id="nom_site_client"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Date début</th>
                                        <td id="date_debut"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Heure début</th>
                                        <td id="heure_debut"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Date fin</th>
                                        <td id="date_fin"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Heure fin</th>
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
                                        <th scope="row">Titre</th>
                                        <td id="titre"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Type non conformité</th>
                                        <td id="type_non_conformite"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Y'a t-il un plan d'action ?</th>
                                        <td id="is_plan_action"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Date limite plan action</th>
                                        <td id="date_limite_plan_action"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Statut</th>
                                        <td id="statut"></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Mise en place du plan d'action avec succès ?</th>
                                        <td id="is_mise_plan_action"></td>
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

        tippy('#desactiver', {
            content: "Suspendre",
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
            $(".e1").select2({
                width: '100%',
                dropdownParent: $("#create")
            });
            
            $(".e5").select2();
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#SrClient").change(function()
            {
                var SrClient =  $("#SrClient").val();

                $.get('{{ route("site-ronde-oper.show_sites_client","") }}/'+SrClient, function(rep) {
                    $("#afficher_liste_sites_client").html(rep);
                });
            });

            if($("#SrClient").val() != null) {
                var SrClient =  $("#SrClient").val();

                $.get('{{ route("site-ronde-oper.show_sites_client","") }}/'+SrClient, function(rep) {
                    $("#afficher_liste_sites_client").html(rep);
                });
            }
        });
    </script>

    <script type="text/javascript">
        function showItem(SrNumero) {
            $.ajax({
                url: '{{ route("site-ronde-oper.show", "") }}/' + SrNumero,
                type: 'GET',
                success: function(data){

                    $('#nom_client').html(data.ClDesign)
                    $('#nom_site_client').html(data.ScNomSite)
                    $('#date_debut').html(data.SrDateDebut)
                    $('#heure_debut').html(data.SrHeureDebut)
                    $('#date_fin').html(data.SrDateFin)
                    $('#heure_fin').html(data.SrHeureFin)
                    $('#titre').html(data.SrTitre)
                    $('#date_limite_plan_action').html(data.SrDateLimPlanAct)

                    if((data.SrTypeNonConform) == 'M')
                    {
                        $('#type_non_conformite').text('Majeure');
                    }
                    else if((data.SrTypeNonConform) == 'N')
                    {
                        $('#type_non_conformite').text('Mineure');
                    }
                    else
                    {
                        $('#type_non_conformite').text('Aucune');
                    }

                    if((data.SrIsPlanAction) == 'M')
                    {
                        $('#is_plan_action').text('Oui');
                    }
                    else
                    {
                        $('#is_plan_action').text('Non');
                    }


                    if((data.SrIsPlanActionOk) == 'Y')
                    {
                        $('#is_mise_plan_action').text('Oui');
                    }
                    else
                    {
                        $('#is_mise_plan_action').text('Non');
                    }

                    if((data.SrStatut) == 'V')
                    {
                        $('#statut').text('Vert');
                    }
                    else
                    {
                        $('#statut').text('Rouge');
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
        function editItem(SrNumero) {
            $.ajax({
                url: '{{ route("site-ronde-oper.edit", "") }}/' + SrNumero,
                type: 'GET',
                success: function(data){

                    var SrNumero=data.SrNumero;

                    $(".form-edit").attr('action', '{{ route("site-ronde-oper.update", "") }}/' + SrNumero);
                    var form=$(".form-edit").attr('action');

                    $(".SrClient").val(data.SrClient);
                    $(".SrSiteClient").val(data.SrSiteClient);
                    $(".SrDateDebut").val(data.SrDateDebut);
                    $(".SrHeureDebut").val(data.SrHeureDebut);
                    $(".SrDateFin").val(data.SrDateFin);
                    $(".SrHeureFin").val(data.SrHeureFin);
                    $(".SrTitre").val(data.SrTitre);
                    $(".SrDateLimPlanAct").val(data.SrDateLimPlanAct);
                    $(".SrResumePlanAct").val(data.SrResumePlanAct);

                    if((data.SrStatut) =='V')
                    {
                        $('.SrStatut_V').attr('checked',true);
                    }
                    else
                    {
                        $('.SrStatut_R').attr('checked',true);
                    }

                    if((data.SrTypeNonConform) =='M')
                    {
                        $('.SrTypeNonConform_M').attr('checked',true);
                    }
                    else if((data.SrTypeNonConform) =='N')
                    {
                        $('.SrTypeNonConform_N').attr('checked',true);
                    }
                    else
                    {
                        $('.SrTypeNonConform_A').attr('checked',true);
                    }

                    if((data.SrIsPlanAction) =='Y')
                    {
                        $('.SrIsPlanAction_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.SrIsPlanAction_N').attr('checked',true);
                    }

                    if((data.SrIsPlanActionOk) =='Y')
                    {
                        $('.SrIsPlanActionOk_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.SrIsPlanActionOk_N').attr('checked',true);
                    }

                    $(".SrClient").change(function()
                    {
                        var SrClient =  $(".SrClient").val();

                        $.get('{{ route("site-ronde-oper.show_sites_client_edit","") }}/'+SrClient, function(rep) {
                            $("#afficher_liste_sites_client_edit").html(rep);
                        });
                    });

                    if($(".SrClient").val() != null) {
                        var SrClient =  $(".SrClient").val();

                        $.get('{{ route("site-ronde-oper.show_sites_client_edit","") }}/'+SrClient, function(rep) {
                            $("#afficher_liste_sites_client_edit").html(rep);
                        });
                    }

                    $(".e3").select2({
                        width: '100%',
                    });

                    $(".e4").select2({
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

    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);
            // Get a reference to the file input element
            const inputDocument = document.querySelector('input[id="SrDocumPlanAct"]');

            // Create a FilePond instance
            FilePond.create(inputDocument,{
                labelIdle: `Faites glisser et déposez le document ou <span class="filepond--label-action">Parcourir</span>`,
                imageResizeTargetWidth: 200,
                imageResizeTargetHeight: 200,
                styleLoadIndicatorPosition: 'center bottom',
                styleProgressIndicatorPosition: 'right bottom',
                styleButtonRemoveItemPosition: 'left bottom',
                styleButtonProcessItemPosition: 'right bottom',

                server: {
                    process: 'https://diffapps.diff-itc.net/spak-supervision/upload-doc-plan-action',
                    //process: '/upload-doc-plan-action',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                },
        });
    </script>

    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);
            // Get a reference to the file input element
            const inputDocumentEdit = document.querySelector('input[id="SrDocumPlanActEdit"]');

            // Create a FilePond instance
            FilePond.create(inputDocumentEdit,{
                labelIdle: `Faites glisser et déposez le document ou <span class="filepond--label-action">Parcourir</span>`,
                imageResizeTargetWidth: 200,
                imageResizeTargetHeight: 200,
                styleLoadIndicatorPosition: 'center bottom',
                styleProgressIndicatorPosition: 'right bottom',
                styleButtonRemoveItemPosition: 'left bottom',
                styleButtonProcessItemPosition: 'right bottom',

                server: {
                    process: 'https://diffapps.diff-itc.net/spak-supervision/upload-doc-plan-action-edit',
                    //process: '/upload-doc-plan-action-edit',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                },
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("input[type=radio][name=SrIsPlanAction]").click(function()
            {
                var SrIsPlanAction = getSelectedValue('SrIsPlanAction');

                if (SrIsPlanAction == "Y") {
                    $("#afficher_date_limite").show();
                    $("#SrDateLimPlanAct").attr("required",true);
                } else {
                    $("#afficher_date_limite").hide();
                    $("#SrDateLimPlanAct").attr("required",false);
                }
            });

            if($("input[name='SrIsPlanAction']:checked").val()=='Y')
            {
                $("#afficher_date_limite").show();
                $("#SrDateLimPlanAct").attr("required",true);
            }
            else
            {
                $("#afficher_date_limite").hide();
                $("#SrDateLimPlanAct").attr("required",false);
            }
        });
    </script>
@endsection

