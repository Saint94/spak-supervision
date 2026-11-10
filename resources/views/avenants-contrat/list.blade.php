@extends('layout.app')

@section('title', 'Liste des Avenants')

@section('content')

@include('layout.alert')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Liste des Avenants</b></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard table-responsive">
                            <a href="{{ route('avenants-contrat.create') }}" class="btn btn-primary mb-2"><i class="ft-plus"></i>&nbsp;Nouvel Avenant</a>
                            <button type="button" class="btn btn-danger mb-2" data-toggle="modal" data-target="#import"><i class="ft-download"></i>&nbsp;Importer les avenants contrats</button>

                            <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                                <thead>
                                    <tr>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Réf</th>
                                        <th>Nature</th>
                                        <th>Date effet</th>
                                        <th>Date fin</th>
                                        <th>Prix agent</th>
                                        <th>Nbre agents jour</th>
                                        <th>Nbre agents nuit</th>
                                        <th>Prix maîtres chien</th>
                                        <th>Nbre maîtres chien jour</th>
                                        <th>Nbre maîtres chien nuit</th>
                                        <th>Prix superviseur</th>
                                        <th>Nbre superviseurs jour</th>
                                        <th>Nbre superviseurs nuit</th>
                                        <th>Prix cmdo</th>
                                        <th>Nbre cmdo jour</th>
                                        <th>Nbre cmdo nuit</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($avenants_contrat as $avenant_contrat)
                                        <tr>
                                            <td>{{ $avenant_contrat->ClDesign }}</td>

                                            @if($avenant_contrat->AcDate != null)
                                                <td>{{ date('d/m/Y',strtotime($avenant_contrat->AcDate)) }}</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td>{{ $avenant_contrat->AcRefAven }}</td>

                                            @if($avenant_contrat->AcType == 'C')
                                                <td>Contrat</td>
                                            @else
                                                <td>Avenant</td>
                                            @endif

                                            @if($avenant_contrat->AcDateEffet != null)
                                                <td>{{ date('d/m/Y',strtotime($avenant_contrat->AcDateEffet)) }}</td>
                                            @else
                                                <td></td>
                                            @endif

                                            @if($avenant_contrat->AcDateFin != null)
                                                <td>{{ date('d/m/Y',strtotime($avenant_contrat->AcDateFin)) }}</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td>{{ currency_xaf($avenant_contrat->AcPrixAgent) }}</td>
                                            <td>{{ $avenant_contrat->AcNbAgentJr }}</td>
                                            <td>{{ $avenant_contrat->AcNbAgentNu }}</td>

                                            <td>{{ currency_xaf($avenant_contrat->AcPrixUMC) }}</td>
                                            <td>{{ $avenant_contrat->AcNbreMCJour }}</td>
                                            <td>{{ $avenant_contrat->AcNbreMCNuit }}</td>

                                            <td>{{ currency_xaf($avenant_contrat->AcPrixUSuperv) }}</td>
                                            <td>{{ $avenant_contrat->AcNbSupervJr }}</td>
                                            <td>{{ $avenant_contrat->AcNbSupervNu }}</td>

                                            <td>{{ currency_xaf($avenant_contrat->AcPrixUComm) }}</td>
                                            <td>{{ $avenant_contrat->AcNbComdoJr }}</td>
                                            <td>{{ $avenant_contrat->AcNbComdoNu }}</td>

                                            @if($avenant_contrat->AcIsOn == 'Y')
                                                <td><span class="badge light badge-success">Actif</span></td>
                                            @else
                                                <td><span class="badge light badge-danger">Inactif</span></td>
                                            @endif

                                            <td style="text-align:center;">
                                                <a onclick="showItem('{{$avenant_contrat->AcNumero }}')" class="info p-0" id="view">
                                                    <i class="ft-eye font-medium-3 mr-2"></i>
                                                </a>

                                                <a href="{{ route('avenants-contrat.edit',crypter($avenant_contrat->AcNumero )) }}" class="warning p-0" id="edit">
                                                    <i class="ft-edit font-medium-3 mr-2"></i>
                                                </a>

                                                @if($avenant_contrat->ClIsOn == 'Y')
                                                    <a onclick="desactiveItem('{{$avenant_contrat->AcNumero }}')" class="danger p-0" id="inactif">
                                                        <i class="ft-log-out font-medium-3 mr-2"></i>
                                                    </a>
                                                @else
                                                    <a onclick="activeItem('{{$avenant_contrat->AcNumero }}')" class="success p-0" id="actif">
                                                        <i class="ft-log-in font-medium-3 mr-2"></i>
                                                    </a>
                                                @endif

                                                <a href="{{ route('avenant-contrat.personnel_contrat',crypter($avenant_contrat->AcNumero )) }}" class="primary p-0" id="personnel">
                                                    <i class="ft-users font-medium-3 mr-2"></i>
                                                </a>

                                                <a href="{{ route('avenant-contrat.sites_contrat',crypter($avenant_contrat->AcNumero )) }}" class="info p-0" id="sites">
                                                    <i class="ft-map-pin font-medium-3 mr-2"></i>
                                                </a>

                                                <a href="{{ route('avenant-contrat.file',crypter($avenant_contrat->AcNumero )) }}" class="info p-0" id="document">
                                                    <i class="ft-file-text font-medium-3 mr-2"></i>
                                                </a>

                                                <a onclick="deleteItem('{{$avenant_contrat->AcNumero }}')" class="danger p-0" id="delete">
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

    <!-- Consulter un avenant contrat -->
    <div class="modal fade text-left" id="show_contrat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">INFORMATIONS AVENANT</h4>
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
                                            <th scope="row">Client</th>
                                            <td id="client"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date de saisie</th>
                                            <td id="date_saisie"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Référence</th>
                                            <td id="ref"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date effet</th>
                                            <td id="date_effet"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date fin</th>
                                            <td id="date_fin"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Prix par agent</th>
                                            <td id="prix_agent"></td>
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
                                            <th scope="row">Prix par maître chien</th>
                                            <td id="prix_mc"></td>
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
                                            <th scope="row">Nbre maîtres chien de jour</th>
                                            <td id="nb_mc_jour"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Nbre maîtres chien de nuit</th>
                                            <td id="nb_mc_nuit"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Prix par superviseur</th>
                                            <td id="prix_sup"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Nbre superviseurs de jour</th>
                                            <td id="nb_sup_jour"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Nbre superviseurs de nuit</th>
                                            <td id="nb_sup_nuit"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Prix par cmdo</th>
                                            <td id="prix_comdo"></td>
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
    <!-- Consulter un client -->


    <!-- IMPORT CLIENT -->
    <div class="modal fade text-left" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <form method="POST" action="{{ route('avenant-contrat.import') }}" class="needs-validation" novalidate>
                    @csrf

                    <div class="modal-header bg-info white">
                        <h4 class="modal-title" id="myModalLabel16">IMPORTER LES AVENANTS CONTRATS</h4>
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

@endsection

@section('add_js')

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
            content: "Activer cet avenant",
            placement: 'top',
        });

        tippy('#inactif', {
            content: "Désactiver cet avenant",
            placement: 'top',
        });

        tippy('#personnel', {
            content: "Personnel affecté sur le contrat/avenant",
            placement: 'bottom',
        });

        tippy('#sites', {
            content: "Sites client du contrat",
            placement: 'top',
        });

        tippy('#document', {
            content: "Afficher avenant/Contrat",
            placement: 'bottom',
        });

        tippy('#delete', {
            content: "Supprimer",
            placement: 'top',
        });

    </script>

    <script type="text/javascript">
        function showItem(AcNumero) {
            $.ajax({
                url: '{{ route("avenant-contrat.show", "") }}/' + AcNumero , // Use the named route here
                type: 'GET',
                success: function(data){
                    $('#client').html(data.ClDesign)
                    $('#date_saisie').html(data.AcDate)
                    $('#ref').html(data.AcRefAven)
                    $('#date_effet').html(data.AcDateEffet)
                    $('#date_fin').html(data.AcDateFin)
                    $('#prix_agent').html(data.AcPrixAgent)
                    $('#nb_agent_jour').html(data.AcNbAgentJr)
                    $('#nb_agent_nuit').html(data.AcNbAgentNu)

                    $('#prix_mc').html(data.AcPrixUMC)
                    $('#nb_mc_jour').html(data.AcNbreMCJour)
                    $('#nb_mc_nuit').html(data.AcNbreMCNuit)

                    $('#prix_sup').html(data.AcPrixUSuperv)
                    $('#nb_sup_jour').html(data.AcNbSupervJr)
                    $('#nb_sup_nuit').html(data.AcNbSupervNu)

                    $('#prix_comdo').html(data.AcPrixUComm)
                    $('#nb_comdo_jour').html(data.AcNbComdoJr)
                    $('#nb_comdo_nuit').html(data.AcNbComdoNu)

                    if((data.AcIsOn) == 'Y')
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
        function desactiveItem(AcNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Désactiver cet avenant",
                text: "Confirmez-vous la désactivation de cet avenant ?",
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
                        url: '{{ route("avenant-contrat.desactiver", "") }}/' + AcNumero , // Use the named route here
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Avenant désactivé avec succès !',
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
        function activeItem(AcNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Activer cet avenant",
                text: "Confirmez-vous l'activation de cet avenant ?",
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
                        url: '{{ route("avenant-contrat.activer", "") }}/' + AcNumero , // Use the named route here
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Avenant activé avec succès !',
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
        function deleteItem(AcNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Suppression de cet avenant",
                text: "Confirmez-vous la suppression de cet avenant ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Oui, Supprimer",
                cancelButtonText: "Annuler !",
                confirmButtonColor: '#2ecc71',
                cancelButtonColor: '#e84118',
                reverseButtons: true
            }).then((result) => {
            if (result.value) {

                    $.ajax({
                        url: '{{ route("avenant-contrat.supprimer", "") }}/' + AcNumero , // Use the named route here
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Avenant supprimé avec succès',
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
