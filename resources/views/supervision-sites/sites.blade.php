@extends('layout.app')

@section('title', 'Liste des sites supervisés')

@section('content')

@include('layout.alert')

<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><b>Liste des sites supervisés par</b></h4>
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

                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create_site_superviseur"><i class="ft-plus"></i>&nbsp;Ajouter site</button>

                        <form action="#" method="POST">
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
                                        <select id="site_client" name="site_client" class="form-control e3">
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
                        </form>

                        <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                            <thead>
                                <tr>
                                    <th>Site client</th>
                                    <th>Date opér</th>
                                    <th>Faction</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sites_superviseur as $site_superviseur)
                                    <tr>
                                        <td>{{ $site_superviseur->ScNomSite }}</td>
                                        <td>{{ $site_superviseur->SsDateHCree }}</td>

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

                                            <a href="#" onclick="editItem('{{$site_superviseur->SsNumero}}')" class="warning p-0" id="editor">
                                                <i class="ft-edit font-medium-3 mr-2"></i>
                                            </a>

                                            <a onclick="suspendreItem('{{ $site_superviseur->SsNumero }}')" class="danger p-0" id="desactiver">
                                                <i class="ft-log-out font-medium-3 mr-2"></i>
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
<div class="modal fade text-left" id="create_site_superviseur" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <form method="POST" action="{{ route('sites-superviseur.store') }}" class="needs-validation" novalidate>
                @csrf

                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">CRÉATION D'UN SITE SUPERVISEUR</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                @if(Auth::user()->type == 'S')
                                    <label for="">Superviseur</label>
                                @elseif(Auth::user()->type == 'T')
                                    <label for="">Contrôleur</label>
                                @else
                                    <label for="">Superviseur</label>
                                @endif
                                <input type="text" class="form-control" value="{{ $supervision_site->PeNom.' '.$supervision_site->PePrenom }}" readonly/>
                                <input type="hidden" class="form-control" name="SsSuperv" value="{{ $supervision_site->PeNumero }}"/>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="SsSiteClient">Site client <abbr style="color: #bd4147;">(*)</abbr></label>
                               <select class="e1 form-control" id="SsSiteClient" name="SsSiteClient" required>
                                    <option selected value="">Selectionner...</option>
                                    @foreach($sites_client_1 as $site_client_1)
                                        <option value="{{ $site_client_1->ScNumero }}">{{ $site_client_1->ScNomSite }}</option>
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
                        <div class="col-xl-6 col-md-6">
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
<div class="modal fade text-left" id="edit_site_superviseur" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
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

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
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
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="SsSiteClient">Site client <abbr style="color: #bd4147;">(*)</abbr></label>
                               <select class="form-control SsSiteClient" id="SsSiteClient" name="SsSiteClient" required>
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
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label>Faction <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline3" name="SsFaction" class="custom-control-input SsFaction_Y" value="J"/>
                                        <label class="custom-control-label" for="customRadioInline3">Jour</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline4" name="SsFaction" class="custom-control-input SsFaction_N" value="N"/>
                                        <label class="custom-control-label" for="customRadioInline4">Nuit</label>
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
                                        <input type="radio" id="customRadioInline5" name="SsIsOn" class="custom-control-input SsIsOn_Y" value="Y"/>
                                        <label class="custom-control-label" for="customRadioInline5">Actif</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline6" name="SsIsOn" class="custom-control-input SsIsOn_N" value="N"/>
                                        <label class="custom-control-label" for="customRadioInline6">Inactif</label>
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
<div class="modal fade text-left" id="xlarge_site_superviseur" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
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
            $("#create_site_superviseur,#edit_site_superviseur,#xlarge_site_superviseur").modal({
                show:false,
                backdrop:'static'
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $(".e1").select2({
                width: '100%',
                dropdownParent: $("#create_site_superviseur")
            });

            $(".e2").select2();
            $(".e3").select2();
            $(".e4").select2();
            $(".e5").select2();
            $(".e6").select2();
            $(".e7").select2();
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

@endsection

