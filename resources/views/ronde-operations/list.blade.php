@extends('layout.app')

@section('title', 'Liste des rondes des responsables des opérations')

@section('content')

@include('layout.alert')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Liste des rondes des responsables des opérations</b></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard table-responsive">
                            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create"><i class="ft-plus"></i>&nbsp;Nouvelle Ronde Opération</button>
                            <button type="button" class="btn btn-danger" onclick="imprimerAvecFiltres()"><i class="ft-printer"></i> Imprimer Liste Rondes</button>

                            <form action="{{ route('ronde-operations.filtre') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-xl-4 col-md-4">
                                        <div class="form-group">
                                            <label for="">Période du</label>
                                            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ date('Y-m-01') }}" max="{{ date('Y-m-d') }}"/>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-md-4">
                                        <div class="form-group">
                                            <label for="">Au</label>
                                            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}"/>
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
                                        <th>Responsable 1</th>
                                        <th>Responsable 2</th>
                                        <th>Responsable 3</th>
                                        <th>Date début</th>
                                        <th>Heure début</th>
                                        <th>Date fin</th>
                                        <th>Heure fin</th>
                                        <th>Statut</th>
                                        <th>Objectif</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rondes_operations as $ronde_operation)
                                        <tr>
                                            <td>{{ $ronde_operation->responsable1_name ?? '' }}</td>
                                            <td>{{ $ronde_operation->responsable2_name ?? '' }}</td>
                                            <td>{{ $ronde_operation->responsable3_name ?? '' }}</td>
                                            <td>{{ date('d/m/Y',strtotime($ronde_operation->RoDateDebut)) }}</td>
                                            <td>{{ date('H:i',strtotime($ronde_operation->RoHeureDebut)) }}</td>
                                            <td>{{ $ronde_operation->RoDateFin == NULL ? '' : date('d/m/Y',strtotime($ronde_operation->RoDateFin)) }}</td>
                                            <td>{{ $ronde_operation->RoHeureFin == NULL ? '' : date('H:i',strtotime($supervision_site->RoHeureFin)) }}</td>

                                            @if($ronde_operation->RoStatut == 'I')
                                                <td>Initialisé</td>
                                            @elseif($ronde_operation->RoStatut == 'E')
                                                <td>En cours</td>
                                            @elseif($ronde_operation->RoStatut == 'S')
                                                <td>Clôturé avec satisfaction</td>
                                            @elseif($ronde_operation->RoStatut == 'N')
                                                <td>Clôturé avec Insatisfaction</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td>{{ $ronde_operation->RoObjectif }}</td>

                                            <td style="text-align: center;">
                                                <a onclick="showItem('{{$ronde_operation->RoNumero}}')" class="info p-0" id="view">
                                                    <i class="ft-eye font-medium-3 mr-2"></i>
                                                </a>

                                                <a href="#" onclick="editItem('{{$ronde_operation->RoNumero}}')" class="warning p-0" id="editor">
                                                    <i class="ft-edit font-medium-3 mr-2"></i>
                                                </a>

                                                @if($ronde_operation->RoDateFin == null)
                                                    <a href="{{ route('ronde-operation.sites',crypter($ronde_operation->RoNumero)) }}" class="primary p-0" id="sites">
                                                        <i class="ft-align-right font-medium-3 mr-2"></i>
                                                    </a>
                                                @endif

                                                <a href="#" onclick="fermerItem('{{$ronde_operation->RoNumero}}')" class="danger p-0" id="fermer">
                                                    <i class="ft-clock font-medium-3 mr-2"></i>
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

                <form method="POST" action="{{ route('ronde-operations.store') }}" class="needs-validation" novalidate>
                    @csrf

                    <div class="modal-header bg-info white">
                        <h4 class="modal-title" id="myModalLabel16">NOUVELLE RONDE OPÉRATION</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="RoResponsable1">Responsable d'opération 1<abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e1 form-control" id="RoResponsable1" name="RoResponsable1" required>
                                        <option selected value="">Selectionner...</option>
                                        @foreach($responsables_operations as $responsable_operation)
                                            <option value="{{ $responsable_operation->id }}">{{ $responsable_operation->noms.' '.$responsable_operation->prenoms }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="RoResponsable2">Responsables d'opération 2</label>
                                    <select class="e2 form-control" id="RoResponsable2" name="RoResponsable2">
                                        <option selected value="">Selectionner...</option>
                                        @foreach($responsables_operations as $responsable_operation)
                                            <option value="{{ $responsable_operation->id }}">{{ $responsable_operation->noms.' '.$responsable_operation->prenoms }}</option>
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
                                    <label for="RoResponsable3">Responsables d'opération 3</label>
                                    <select class="e3 form-control" id="RoResponsable3" name="RoResponsable3">
                                        <option selected value="">Selectionner...</option>
                                        @foreach($responsables_operations as $responsable_operation)
                                            <option value="{{ $responsable_operation->id }}">{{ $responsable_operation->noms.' '.$responsable_operation->prenoms }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="RoDateDebut">Date début <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" class="form-control" id="RoDateDebut" name="RoDateDebut" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="RoHeureDebut">Heure de début <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="time" class="form-control" id="RoHeureDebut" name="RoHeureDebut" value="{{ date('H:i', strtotime(date('H:i'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="RoObjectif">Objectifs</label>
                                    <textarea rows="2" class="form-control" id="RoObjectif" name="RoObjectif" placeholder="Commentaires"></textarea>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="RoPlanAction">Plan d'actions</label>
                                    <textarea rows="2" class="form-control" id="RoPlanAction" name="RoPlanAction" placeholder="Commentaires"></textarea>
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
                        <h5 class="modal-title" id="myModalLabel16">MODIFICATION RONDE OPÉRATION</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="RoResponsable1">Responsable d'opération 1<abbr style="color: #bd4147;">(*)</abbr></label>
                                    <select class="e4 form-control RoResponsable1" id="RoResponsable1" name="RoResponsable1" required>
                                        <option selected value="">Selectionner...</option>
                                        @foreach($responsables_operations as $responsable_operation)
                                            <option value="{{ $responsable_operation->id }}">{{ $responsable_operation->noms.' '.$responsable_operation->prenoms }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="RoResponsable2">Responsables d'opération 2</label>
                                    <select class="e5 form-control RoResponsable2" id="RoResponsable2" name="RoResponsable2">
                                        <option selected value="">Selectionner...</option>
                                        @foreach($responsables_operations as $responsable_operation)
                                            <option value="{{ $responsable_operation->id }}">{{ $responsable_operation->noms.' '.$responsable_operation->prenoms }}</option>
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
                                    <label for="RoResponsable3">Responsables d'opération 3</label>
                                    <select class="e6 form-control RoResponsable3" id="RoResponsable3" name="RoResponsable3">
                                        <option selected value="">Selectionner...</option>
                                        @foreach($responsables_operations as $responsable_operation)
                                            <option value="{{ $responsable_operation->id }}">{{ $responsable_operation->noms.' '.$responsable_operation->prenoms }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="RoDateDebut">Date début <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" class="form-control RoDateDebut" id="RoDateDebut" name="RoDateDebut" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="RoHeureDebut">Heure de début <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="time" class="form-control RoHeureDebut" id="RoHeureDebut" name="RoHeureDebut" value="{{ date('H:i', strtotime(date('H:i'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="RoObjectif">Objectifs</label>
                                    <textarea rows="2" class="form-control RoObjectif" id="RoObjectif" name="RoObjectif" placeholder="Commentaires"></textarea>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="RoPlanAction">Plan d'actions</label>
                                    <textarea rows="2" class="form-control RoPlanAction" id="RoPlanAction" name="RoPlanAction" placeholder="Commentaires"></textarea>
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
                    <h5 class="modal-title" id="myModalLabel16">INFORMATIONS RONDE OPÉRATION</h5>
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
                                            <th scope="row">Responsable Opération 1</th>
                                            <td id="resp1"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Responsable Opération 2</th>
                                            <td id="resp2"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Responsable Opération 3</th>
                                            <td id="resp3"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date début</th>
                                            <td id="date_debut"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Heure début</th>
                                            <td id="heure_debut"></td>
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
                                            <th scope="row">Date fin</th>
                                            <td id="date_fin"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Heure fin</th>
                                            <td id="heure_fin"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Statut</th>
                                            <td id="statut"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Objectif</th>
                                            <td id="objectif"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Plan d'action</th>
                                            <td id="plan_action"></td>
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
                        <h5 class="modal-title" id="myModalLabel16">FERMER RONDE OPÉRATION</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="RoResponsable1">Responsable Opération 1</label>
                                    <input type="text" class="form-control RoResponsable1" readonly/>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="RoResponsable2">Responsable Opération 2</label>
                                    <input type="text" class="form-control RoResponsable2" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="RoResponsable3">Responsable Opération 3</label>
                                    <input type="text" class="form-control RoResponsable3" readonly/>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="RoDateDebut">Date de début</label>
                                    <input type="text" class="form-control RoDateDebut" readonly/>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="RoHeureDebut">Heure de début</label>
                                    <input type="text" class="form-control RoHeureDebut" readonly/>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="RoDateFin">Date de fin <abbr style="color: #bd4147;">(*)</abbr></label>
                                    <input type="date" class="form-control" id="RoDateFin" name="RoDateFin" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-3">
                                <div class="form-group">
                                    <label for="RoHeureFin">Heure de fin</label>
                                    <input type="time" class="form-control RoHeureFin" id="RoHeureFin" name="RoHeureFin" value="{{ date('H:i', strtotime(date('H:i'))) }}" required/>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="RoStatut">Selectionner le statut</label>
                                    <select class="form-control RoStatut e7" id="RoStatut" name="RoStatut" required>
                                        <option selected value="">Selectionner...</option>
                                        <option value="I">Initialisé</option>
                                        <option value="E">En cours</option>
                                        <option value="S">Clôturé avec satisfaction</option>
                                        <option value="N">Clôturé avec Insatisfaction</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire.
                                    </div>
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
            content: "Sites",
            placement: 'bottom',
        });

        tippy('#fermer', {
            content: "Fin ronde",
            placement: 'bottom',
        });

        tippy('#print', {
            content: "Imprimer Fiche de ronde",
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

            $(".e8").select2();
        });
    </script>

    <script type="text/javascript">
        function showItem(RoNumero) {
            $.ajax({
                url: '{{ route("ronde-operation.show", "") }}/'+RoNumero,
                type: 'GET',
                success: function(data){
                    $('#resp1').html(data.responsable1_name)
                    $('#resp2').html(data.responsable2_name)
                    $('#resp3').html(data.responsable3_name)
                    $('#date_debut').html(data.RoDateDebut)
                    $('#heure_debut').html(data.RoHeureDebut)
                    $('#date_fin').html(data.RoDateFin)
                    $('#heure_fin').html(data.RoHeureFin)
                    $('#objectif').html(data.RoObjectif)
                    $('#plan_action').html(data.RoPlanAction)


                    if((data.RoStatut) == 'I')
                    {
                        $('#statut').val('Initialisé');
                    }
                    else if((data.RoStatut) == 'E')
                    {
                        $('#statut').val('En cours');
                    }
                    else if((data.RoStatut) == 'S')
                    {
                        $('#statut').val('Clôturé avec satisfaction');
                    }
                    else if((data.RoStatut) == 'N')
                    {
                        $('#statut').val('Clôturé avec Insatisfaction');
                    }
                    else
                    {
                        $('#statut').val('');
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
        function editItem(RoNumero) {
            $.ajax({
                url: '{{ route("ronde-operation.edit", "") }}/' + RoNumero,
                type: 'GET',
                success: function(data){

                    var RoNumero=data.RoNumero;

                    $(".form-edit").attr('action', '{{ route("ronde-operation.update", "") }}/' + RoNumero);
                    var form=$(".form-edit").attr('action');

                    $(".RoResponsable1").val(data.RoResponsable1);
                    $(".RoResponsable2").val(data.RoResponsable2);
                    $(".RoResponsable3").val(data.RoResponsable3);
                    $(".RoDateDebut").val(data.RoDateDebut);
                    $(".RoHeureDebut").val(data.RoHeureDebut);
                    $(".RoObjectif").val(data.RoObjectif);
                    $(".RoPlanAction").val(data.RoPlanAction);

                    if((data.RoStatut) == 'I')
                    {
                        $('.RoStatut').val('Initialisé');
                    }
                    else if((data.RoStatut) == 'E')
                    {
                        $('.RoStatut').val('En cours');
                    }
                    else if((data.RoStatut) == 'S')
                    {
                        $('.RoStatut').val('Clôturé avec satisfaction');
                    }
                    else if((data.RoStatut) == 'N')
                    {
                        $('.RoStatut').val('Clôturé avec Insatisfaction');
                    }
                    else
                    {
                        $('.RoStatut').val('');
                    }

                    $(".e4").select2({
                        width: '100%',
                    });

                    $(".e5").select2({
                        width: '100%',
                    });

                    $(".e6").select2({
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
        function fermerItem(RoNumero) {
            $.ajax({
                url: '{{ route("ronde-operation.edit-fermer", "") }}/' + RoNumero,
                type: 'GET',
                success: function(data){

                    var RoNumero=data.RoNumero;

                    $(".form-edit-fermer").attr('action', '{{ route("ronde-operation.update-fermer", "") }}/' + RoNumero);
                    var form=$(".form-edit-fermer").attr('action');

                    $(".RoResponsable1").val(data.responsable1_name);
                    $(".RoResponsable2").val(data.responsable2_name);
                    $(".RoResponsable3").val(data.responsable3_name);
                    $(".RoDateDebut").val(data.RoDateDebut);
                    $(".RoHeureDebut").val(data.RoHeureDebut);
                    $(".RoStatut").val(data.RoStatut);

                    $(".e7").select2({
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
        function imprimerAvecFiltres() {
            // Récupérer tous les valeurs des filtres
            const startDate = document.getElementById('start_date')?.value || '{{ date("Y-m-01") }}';
            const endDate = document.getElementById('end_date')?.value || '{{ date("Y-m-d") }}';

            // Construire l'URL avec tous les paramètres
            let url = "{{ route('ronde-operation.imprimer') }}?";
            url += `start_date=${encodeURIComponent(startDate)}`;
            url += `&end_date=${encodeURIComponent(endDate)}`;

            // Ouvrir dans un nouvel onglet
            window.open(url, '_blank');
        }
    </script>

@endsection

