@extends('layout.app')

@section('title', 'Liste des clients')

@section('content')

@include('layout.alert')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Liste des clients</b></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard table-responsive">
                            <a href="{{ route('clients.create') }}" class="btn btn-primary mb-2"><i class="ft-plus"></i>&nbsp;Nouveau client</a>
                            <button type="button" class="btn btn-danger mb-2" data-toggle="modal" data-target="#import"><i class="ft-download"></i>&nbsp;Importer les clients</button>

                            <form action="{{ route('clients.filtre') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-xl-3 col-md-3">
                                        <div class="form-group">
                                            <label for="statut_client">Statuts</label>
                                            <select name="statut_client" id="statut_client" class="form-control e1">
                                                <option selected="selected" value="all">Tous</option>
                                                <option value="Y">Clients actifs</option>
                                                <option value="N">Clients suspendus</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-3">
                                        <div class="form-group">
                                            <label for="ville_client">Ville (Siège)</label>
                                            <select name="ville_client" id="ville_client" class="form-control e2">
                                                <option selected="selected" value="all">Tous</option>
                                                @foreach($villes as $ville)
                                                    <option value="{{ $ville->ViNumero }}">{{ $ville->ViNom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-3">
                                        <div class="form-group">
                                            <label for="ville_client">Secteurs d'activité</label>
                                            <select name="secteur_activite_client" id="secteur_activite_client" class="form-control e3">
                                                <option selected="selected" value="all">Tous</option>
                                                @foreach($secteurs_activites as $secteur_activite)
                                                    <option value="{{ $secteur_activite->SaNumero }}">{{ $secteur_activite->SaNom }}</option>
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
                                        <th>Code</th>
                                        <th>Désignation</th>
                                        <th>Email</th>
                                        <th>Date début</th>
                                        <th>Ville (Siège)</th>
                                        <th>Secteur d'activité</th>
                                        <th>Tel 1</th>
                                        <th>Tel 2</th>
                                        <th>Nbre sites</th>
                                        <th>Nbre jour</th>
                                        <th>Nbre nuit</th>
                                        <th>Nbre vigiles jour</th>
                                        <th>Nbre vigiles nuit</th>
                                        <th>Nbre maîtres chien jour</th>
                                        <th>Nbre maîtres chien nuit</th>
                                        <th>Nbre commandos jour</th>
                                        <th>Nbre commandos nuit</th>
                                        <th>Nbre superviseurs jour</th>
                                        <th>Nbre superviseurs nuit</th>
                                        <th>Statut</th>
                                        <th>TVA</th>
                                        <th>Solde impayé</th>
                                        <th>Nb Mois Imp.</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clients as $client)
                                        <tr>
                                            <td>{{ $client->ClCode }}</td>
                                            <td>{{ $client->ClDesign }}</td>
                                            <td>{{ $client->ClEmail }}</td>

                                            @if($client->ClDateDebut != null)
                                                <td>{{ date('d/m/Y',strtotime($client->ClDateDebut)) }}</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td>{{ $client->ViNom }}</td>
                                            <td>{{ $client->SaNom }}</td>
                                            <td>{{ $client->ClTel1 }}</td>
                                            <td>{{ $client->ClTel2 }}</td>
                                            <td>{{ $client->ClNbreSites }}</td>
                                            <td>{{ $client->ClNbAgentJour }}</td>
                                            <td>{{ $client->ClNbAgentNuit }}</td>
                                            <td>{{ $client->ClNbVigileJr }}</td>
                                            <td>{{ $client->ClNbVigileNu }}</td>
                                            <td>{{ $client->ClNbMeChienJr }}</td>
                                            <td>{{ $client->ClNbMeChienNu }}</td>
                                            <td>{{ $client->ClNbCmdoJr }}</td>
                                            <td>{{ $client->ClNbCmdoNu }}</td>
                                            <td>{{ $client->ClNbSupervJr }}</td>
                                            <td>{{ $client->ClNbSupervNu }}</td>

                                            @if($client->ClIsOn == 'Y')
                                                <td><span class="badge light badge-success">Actif</span></td>
                                            @else
                                                <td><span class="badge light badge-danger">Suspendu</span></td>
                                            @endif

                                            @if($client->ClIsTVAOn == 'Y')
                                                <td><span class="badge light badge-success">Activé</span></td>
                                            @else
                                                <td><span class="badge light badge-danger">Non activé</span></td>
                                            @endif

                                            <td>{{ currency_xaf($client->ClMtImpayes) }}</td>
                                            <td>{{ $client->ClNbMoisImp }}</td>

                                            <td style="text-align:center;">
                                                <a onclick="showItem('{{$client->ClNumero }}')" class="info p-0" id="view">
                                                    <i class="ft-eye font-medium-3 mr-2"></i>
                                                </a>

                                                <a href="{{ route('clients.edit',crypter($client->ClNumero )) }}" class="warning p-0" id="edit">
                                                    <i class="ft-edit font-medium-3 mr-2"></i>
                                                </a>

                                                @if($client->ClIsOn == 'Y')
                                                    @if($client->ClNbMoisImp > $client->EnNbMoisMaxImpClt)
                                                        <a onclick="desactiveItem('{{$client->ClNumero }}')" class="danger p-0" id="suspendre">
                                                            <i class="ft-log-out font-medium-3 mr-2"></i>
                                                        </a>
                                                    @endif
                                                @else
                                                    <a onclick="activeItem('{{$client->ClNumero }}')" class="success p-0" id="actif">
                                                        <i class="ft-log-in font-medium-3 mr-2"></i>
                                                    </a>
                                                @endif

                                                <a onclick="deleteItem('{{$client->ClNumero }}')" class="danger p-0" id="delete">
                                                    <i class="ft-trash-2 font-medium-3 mr-2"></i>
                                                </a>

                                                <a href="{{ route('client.agents',crypter($client->ClNumero )) }}" class="primary p-0" id="agents">
                                                    <i class="ft-users font-medium-3 mr-2"></i>
                                                </a>

                                                <a href="{{ route('client.sites',crypter($client->ClNumero )) }}" class="warning p-0" id="sites">
                                                    <i class="ft-map-pin font-medium-3 mr-2"></i>
                                                </a>

                                                <a href="{{ route('client.contacts',crypter($client->ClNumero )) }}" class="primary p-0" id="contacts">
                                                    <i class="ft-user-minus font-medium-3 mr-2"></i>
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

    <!-- Consulter un client -->
    <div class="modal fade text-left" id="show_client" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">INFORMATIONS CLIENT</h4>
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
                                            <th scope="row">Code</th>
                                            <td id="abrege_client"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Désignation</th>
                                            <td id="nom_client"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Email</th>
                                            <td id="email_client"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Ville (Siège)</th>
                                            <td id="ville_client_1"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Secteur d'activité</th>
                                            <td id="secteur_activite_client_1"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">N° de téléphone 1</th>
                                            <td id="tel1_client"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">N° de téléphone 2</th>
                                            <td id="tel2_client"></td>
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
                                            <th scope="row">Date début</th>
                                            <td id="date_debut_client"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Nb. sites</th>
                                            <td id="nbre_sites_client"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Nbre jour</th>
                                            <td id="nbre_agents_jour_client"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Nbre nuit</th>
                                            <td id="nbre_agents_nuit_client"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Statut</th>
                                            <td id="statut_client"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">TVA</th>
                                            <td id="statut_tva"></td>
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

                <form method="POST" action="{{ route('client.import') }}" class="needs-validation" novalidate>
                    @csrf

                    <div class="modal-header bg-info white">
                        <h4 class="modal-title" id="myModalLabel16">IMPORTER LES CLIENTS</h4>
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
        $(document).ready(function() {
            $(".e1").select2();
            $(".e2").select2();
            $(".e3").select2();
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
            content: "Activer ce client",
            placement: 'top',
        });

        tippy('#suspendre', {
            content: "Suspendre ce client",
            placement: 'top',
        });

        tippy('#delete', {
            content: "Supprimer",
            placement: 'top',
        });

        tippy('#agents', {
            content: "Liste des agents",
            placement: 'bottom',
        });

        tippy('#sites', {
            content: "Liste des sites",
            placement: 'top',
        });

        tippy('#contacts', {
            content: "Liste des contacts",
            placement: 'bottom',
        });
    </script>

    <script type="text/javascript">
        function showItem(ClNumero ) {
            $.ajax({
                url: '{{ route("client.show", "") }}/' + ClNumero , // Use the named route here
                type: 'GET',
                success: function(data){
                    $('#abrege_client').html(data.ClCode)
                    $('#nom_client').html(data.ClDesign)
                    $('#tel1_client').html(data.ClTel1)
                    $('#tel2_client').html(data.ClTel2)
                    $('#email_client').html(data.ClEmail)
                    $('#ville_client_1').html(data.ViNom)
                    $('#secteur_activite_client_1').html(data.SaNom)
                    $('#date_debut_client').html(data.ClDateDebut)
                    $('#nbre_sites_client').html(data.ClNbreSites)
                    $('#nbre_agents_jour_client').html(data.ClNbAgentJour)
                    $('#nbre_agents_nuit_client').html(data.ClNbAgentNuit)

                    if((data.ClIsOn) == 'Y')
                    {
                        $('#statut_client').html('Actif');
                    }
                    else
                    {
                        $('#statut_client').html('Suspendu');
                    }

                    if((data.ClIsTVAOn) == 'Y')
                    {
                        $('#statut_tva').html('Activé');
                    }
                    else
                    {
                        $('#statut_tva').html('Non activé');
                    }

                    $('#show_client').modal('show').fade('slow')
                },
                error: function(){
                    console.log("Something goes wrong")
                }
            })
        }
    </script>


    <script type="text/javascript">
        function desactiveItem(ClNumero ) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Suspendre ce client",
                text: "Confirmez-vous la suspension de ce client ?",
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
                        url: '{{ route("client.desactiver", "") }}/' + ClNumero , // Use the named route here
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Client suspendu avec succès !',
                                '',
                                'success'
                            )
                        window.location.href = "{{ route('clients.index') }}";
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
        function activeItem(ClNumero ) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Activer ce client",
                text: "Confirmez-vous l'activation de ce client ?",
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
                        url: '{{ route("client.activer", "") }}/' + ClNumero , // Use the named route here
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Client activé avec succès !',
                                '',
                                'success'
                            )
                        window.location.href = "{{ route('clients.index') }}";
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
        function deleteItem(ClNumero ) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Suppression de ce client",
                text: "Confirmez-vous la suppression de ce client ?",
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
                        url: '{{ route("client.supprimer", "") }}/' + ClNumero , // Use the named route here
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Client supprimé avec succès',
                                '',
                                'success'
                            )
                        window.location.href = "{{ route('clients.index') }}";
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
