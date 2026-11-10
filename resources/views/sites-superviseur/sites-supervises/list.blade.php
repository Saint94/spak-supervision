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
                    <h4 class="card-title">
                        <b>Supervisuer/Contrôleur : </b>{{$site_superviseur->PeNom.' '.$site_superviseur->PePrenom }},
                        <b>Site par défaut : </b>{{ $site_superviseur->ScNomSite }},
                        <b>Faction : </b>{{ $site_superviseur->SsFaction == 'J' ? 'Jour':'Nuit' }},
                        <b>Ville : </b>{{ $site_superviseur->ViNom }},
                    </h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard table-responsive">

                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create"><i class="ft-plus"></i>&nbsp;Ajouter Site/Agent</button>


                        <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                            <thead>
                                <tr>
                                    <th>Site client</th>
                                    <th>Agent du site</th>
                                    <th>Date début</th>
                                    <th>Date Fin</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sites_supervises as $site_supervise)
                                    <tr>
                                        <td>{{ $site_supervise->ScNomSite }}</td>
                                        <td>{{ $site_supervise->PeNom.' '.$site_supervise->PePrenom }}</td>

                                        @if($site_supervise->SaDateDebut != null)
                                            <td>{{ date('d/m/Y',strtotime($site_supervise->SaDateDebut)) }}</td>
                                        @else
                                            <td></td>
                                        @endif

                                        @if($site_supervise->SaDateFin != null)
                                            <td>{{ date('d/m/Y',strtotime($site_supervise->SaDateFin)) }}</td>
                                        @else
                                            <td></td>
                                        @endif
                                      
                                        @if($site_supervise->SaIsOn == 'Y')
                                            <td><span class="badge light badge-success">Actif</span></td>
                                        @elseif($site_supervise->SaIsOn == 'N')
                                            <td><span class="badge light badge-danger">Inactif</span></td>
                                        @else
                                            <td></td>
                                        @endif

                                        <td style="text-align:center;">
                                            <a href="#" onclick="editItem('{{$site_supervise->SaNumero}}')" class="warning p-0" id="editor">
                                                <i class="ft-edit font-medium-3 mr-2"></i>
                                            </a>

                                            @if($site_supervise->SaIsOn == 'Y')
                                                <a onclick="desactiveItem('{{ $site_supervise->SaNumero  }}')" class="danger p-0" id="desactiver">
                                                    <i class="ft-log-out font-medium-3 mr-2"></i>
                                                </a>
                                            @else
                                                <a onclick="activeItem('{{ $site_supervise->SaNumero  }}')" class="success p-0" id="activer">
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
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <form method="POST" action="{{ route('sites-supervises.store') }}" class="needs-validation" novalidate>
                @csrf

                <input type="hidden" name="SaSupervCtrl" value="{{ $site_superviseur->PeNumero }}">

                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">AJOUTER SITE/AGENT</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="SaSiteClt">Site client <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e1 form-control" id="SaSiteClt" name="SaSiteClt" required>
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

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="SaAgent">Agent du site <abbr style="color: #bd4147;">(*)</abbr></label>                             
                                <select class="e2 form-control" id="SaAgent" name="SaAgent" required>
                                    <option selected value="">Selectionner...</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->PeNumero }}">{{ $agent->PeNom.' '.$agent->PePrenom }}</option>
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
                                <label for="SaDateDebut">Date de début <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="date" class="form-control" id="SaDateDebut" name="SaDateDebut" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="SaDateFin">Date de fin</label>
                                <input type="date" class="form-control" id="SaDateFin" name="SaDateFin" value="" />
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>
                    

                    <div class="row">
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label>Statut <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline1" name="SaIsOn" class="custom-control-input" value="Y" checked/>
                                        <label class="custom-control-label" for="customRadioInline1">Actif</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline2" name="SaIsOn" class="custom-control-input" value="N"/>
                                        <label class="custom-control-label" for="customRadioInline2">Inactif</label>
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
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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
                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="SaSiteClt">Site client <abbr style="color: #bd4147;">(*)</abbr></label>
                                <select class="e3 form-control SaSiteClt" id="SaSiteClt" name="SaSiteClt" required>
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

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="SaAgent">Agent du site <abbr style="color: #bd4147;">(*)</abbr></label>                             
                                <select class="e4 form-control SaAgent" id="SaAgent" name="SaAgent" required>
                                    <option selected value="">Selectionner...</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->PeNumero }}">{{ $agent->PeNom.' '.$agent->PePrenom }}</option>
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
                                <label for="SaDateDebut">Date de début <abbr style="color: #bd4147;">(*)</abbr></label>
                                <input type="date" class="form-control SaDateDebut" id="SaDateDebut" name="SaDateDebut" value="{{ date('Y-m-d', strtotime(date('Y-m-d'))) }}" required/>
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label for="SaDateFin">Date de fin</label>
                                <input type="date" class="form-control SaDateFin" id="SaDateFin" name="SaDateFin" value="" />
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire.
                                </div>
                            </div>
                        </div>
                    </div>
                    

                    <div class="row">
                        <div class="col-xl-3 col-md-3">
                            <div class="form-group">
                                <label>Statut <abbr style="color: #bd4147;">(*)</abbr></label>
                                <div class="input-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline3" name="SaIsOn" class="custom-control-input SaIsOn_Y" value="Y"/>
                                        <label class="custom-control-label" for="customRadioInline3">Actif</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline4" name="SaIsOn" class="custom-control-input SaIsOn_N" value="N"/>
                                        <label class="custom-control-label" for="customRadioInline4">Inactif</label>
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


@endsection

@section('add_js')

    <script>
        tippy('#editor', {
            content: "Modifier",
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
            $(".e1,.e2").select2({
                width: '100%',
                dropdownParent: $("#create")
            });
        });
    </script>


    <script type="text/javascript">
        function editItem(SaNumero) {
            $.ajax({
                url: '{{ route("site-supervise.edit", "") }}/' + SaNumero,
                type: 'GET',
                success: function(data) {
                    // Récupérer les données de la facture
                    var facture = data.facture || data;

                    // Mettre à jour l'action du formulaire
                    $(".form-edit").attr('action', '{{ route("site-supervise.update", "") }}/' + facture.FcNumero);

                    // Remplir les champs du formulaire
                    $(".SaSiteClt").val(facture.SaSiteClt);
                    $(".SaAgent").val(facture.SaAgent);
                    $(".SaDateDebut").val(facture.SaDateDebut);
                    $(".SaDateFin").val(facture.SaDateFin);

                    if((data.SaIsOn) =='Y')
                    {
                        $('.SaIsOn_Y').attr('checked',true);
                    }
                    else
                    {
                        $('.SaIsOn_N').attr('checked',true);
                    }

                    $(".e3,.e4").select2({
                        width: '100%',
                    });

                    // Afficher le modal
                    $('#edit').modal('show').fadeIn('slow');
                }
            });
        }
    </script>


    <script type="text/javascript">
        function desactiveItem(SaNumero ) {
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
                    url: '{{ route("site-supervise.desactiver", "") }}/' + SaNumero , // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Site/Agent désactivé avec succès !',
                            '',
                            'success'
                        )
                    window.location.reload();
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
        function activeItem(SaNumero ) {
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
                    url: '{{ route("site-supervise.activer", "") }}/' + SaNumero , // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Site/Agent activé avec succès !',
                            '',
                            'success'
                        )
                    window.location.reload();
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

