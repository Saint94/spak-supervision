@extends('layout.app')

@section('title', 'Liste des évènements clients')

@section('content')

@include('layout.alert')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Liste des évènements clients</b></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard table-responsive">
                            <a href="{{ route('suivi-clients.create') }}" class="btn btn-primary mb-2"><i class="ft-plus"></i>&nbsp;Nouvel évènement</a>

                            <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                                <thead>
                                    <tr>
                                        <th>Client</th>
                                        <th>Type</th>
                                        <th>Titre</th>
                                        <th>Date/Heure</th>
                                        <th>Date/Heure rappel</th>
                                        <th>Date fin rappel</th>
                                        <th>Fréquence rappel en jour</th>
                                        <th>Nbre rappels par jour</th>
                                        <th>Écart entre rappels</th>
                                        <th>Date/Heure prochain rappel</th>
                                        <th>Titre rappel</th>
                                        <th>Bip sonore ?</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($evenements as $evenement)
                                        <tr>
                                            <td>{{ $evenement->ClDesign }}</td>

                                            @if($evenement->EvType == 'EP')
                                                <td>Échéance de Paiement</td>
                                            @elseif($evenement->EvType == 'QS')
                                                <td>Qualité de Service</td>
                                            @elseif($evenement->EvType == 'RD')
                                                <td>Rendez-vous</td>
                                            @elseif($evenement->EvType == 'PR')
                                                <td>Prospect</td>
                                            @elseif($evenement->EvType == 'EV')
                                                <td>Événement</td>
                                            @else
                                                <td>Autre</td>
                                            @endif

                                            <td>{{ $evenement->EvTitre }}</td>

                                            @if($evenement->EvDate != null)
                                                <td>{{ date('d/m/Y',strtotime($evenement->EvDate)).' '.$evenement->EvHeure }}</td>
                                            @else
                                                <td></td>
                                            @endif

                                            @if($evenement->EvDateHRappel != null)
                                                <td>{{ date('d/m/Y',strtotime($evenement->EvDateHRappel)).' '.date('H:i',strtotime($evenement->EvDateHRappel)) }}</td>
                                            @else
                                                <td></td>
                                            @endif

                                            @if($evenement->EvDateFinRappel != null)
                                                <td>{{ date('d/m/Y',strtotime($evenement->EvDateFinRappel)) }}</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td>{{ $evenement->EvFreqRapAprXJr }}</td>
                                            <td>{{ $evenement->EvNbRapByJr }}</td>
                                            <td>{{ $evenement->EvTmpEcartRappels }}</td>

                                            @if($evenement->EvDateHNextRapp != null)
                                                <td>{{ date('d/m/Y',strtotime($evenement->EvDateHNextRapp)).' '.date('H:i',strtotime($evenement->EvDateHNextRapp)) }}</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td>{{ $evenement->EvTitreRapp }}</td>

                                            @if($evenement->EvIsSonOn == 'Y')
                                                <td><span class="badge light badge-success">Actif</span></td>
                                            @else
                                                <td><span class="badge light badge-danger">Inactif</span></td>
                                            @endif

                                            @if($evenement->EvIsOn == 'Y')
                                                <td><span class="badge light badge-success">Actif</span></td>
                                            @else
                                                <td><span class="badge light badge-danger">Inactif</span></td>
                                            @endif

                                            <td style="text-align:center;">
                                                <a onclick="showItem('{{$evenement->EvNumero }}')" class="info p-0" id="view">
                                                    <i class="ft-eye font-medium-3 mr-2"></i>
                                                </a>

                                                <a href="{{ route('suivi-clients.edit',crypter($evenement->EvNumero )) }}" class="warning p-0" id="edit">
                                                    <i class="ft-edit font-medium-3 mr-2"></i>
                                                </a>

                                                @if($evenement->ClIsOn == 'Y')
                                                    <a onclick="desactiveItem('{{$evenement->EvNumero }}')" class="danger p-0" id="inactif">
                                                        <i class="ft-log-out font-medium-3 mr-2"></i>
                                                    </a>
                                                @else
                                                    <a onclick="activeItem('{{$evenement->EvNumero }}')" class="success p-0" id="actif">
                                                        <i class="ft-log-in font-medium-3 mr-2"></i>
                                                    </a>
                                                @endif

                                                <a onclick="deleteItem('{{$evenement->EvNumero }}')" class="danger p-0" id="delete">
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

    <!-- Consulter un évènement -->
    <div class="modal fade text-left" id="show_evenement" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">INFORMATIONS ÉVÈNEMENT</h4>
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
                                            <th scope="row">Type</th>
                                            <td id="type"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Titre</th>
                                            <td id="titre"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date et Heure</th>
                                            <td id="date_heure"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date et heure de rappel</th>
                                            <td id="date_heure_rappel"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date de fin de rappel</th>
                                            <td id="date_fin_rappel"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Fréquence de rappel par jour</th>
                                            <td id="frequence_rappel_jour"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Nombre de rappels par jour</th>
                                            <td id="nb_rappel_jour"></td>
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
                                            <th scope="row">Écart entre rappels</th>
                                            <td id="ecart_rappels"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Date et heure du prochain rappel</th>
                                            <td id="date_heure_next_rappel"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Titre du rappel</th>
                                            <td id="titre_rappel"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Message de rappel</th>
                                            <td id="message_rappel"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Bip sonore est il actif ?</th>
                                            <td id="bip_sonore"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Ce rappel est il destiné à un seul utilisateur ?</th>
                                            <td id="rappel_one_user"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Utilisateur concerné</th>
                                            <td id="user_concerne"></td>
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
    <!-- Consulter un évènement -->


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
            content: "Activer cet évènement",
            placement: 'top',
        });

        tippy('#inactif', {
            content: "Désactiver cet évènement",
            placement: 'top',
        });

        tippy('#delete', {
            content: "Supprimer",
            placement: 'top',
        });

    </script>

    <script type="text/javascript">
        function showItem(EvNumero) {
            $.ajax({
                url: '{{ route("suivi-client.show", "") }}/' + EvNumero , // Use the named route here
                type: 'GET',
                success: function(data){
                    $('#client').html(data.ClDesign)

                    if((data.EvType) == 'EP') {
                        $('#type').text('Échéance de Paiement');
                    } else if((data.EvType) == 'QS') {
                        $('#type').text('Qualité de Service');
                    } else if((data.EvType) == 'RD') {
                        $('#type').text('Rendez-vous');
                    } else if((data.EvType) == 'PR') {
                        $('#type').text('Prospect');
                    } else if((data.EvType) == 'EV') {
                        $('#type').text('Événement');
                    } else if((data.EvType) == 'AU') {
                        $('#type').text('Autre');
                    } else {
                        $('#type').text('Sélectionner...');
                    }

                    $('#titre').html(data.EvTitre)
                    $('#date_heure').html(data.EvDate+' '+data.EvHeure)
                    $('#date_heure_rappel').html(data.EvDateHRappel)
                    $('#date_fin_rappel').html(data.EvDateFinRappel)
                    $('#frequence_rappel_jour').html(data.EvFreqRapAprXJr)
                    $('#nb_rappel_jour').html(data.EvNbRapByJr)
                    $('#ecart_rappels').html(data.EvTmpEcartRappels)
                    $('#date_heure_next_rappel').html(data.EvDateHNextRapp)
                    $('#titre_rappel').html(data.EvTitreRapp)
                    $('#message_rappel').html(data.EvMessageRapp)

                    if((data.EvIsSonOn) == 'Y')
                    {
                        $('#bip_sonore').text('Oui');
                    }
                    else
                    {
                        $('#bip_sonore').text('Non');
                    }

                    if((data.EvIsOnly1User) == 'Y')
                    {
                        $('#rappel_one_user').text('Oui');
                    }
                    else
                    {
                        $('#rappel_one_user').text('Non');
                    }

                    if((data.EvIsOn) == 'Y')
                    {
                        $('#statut').text('Oui');
                    }
                    else
                    {
                        $('#statut').text('Non');
                    }

                    $('#user_concerne').html(data.user_rappel_noms+' '+data.user_rappel_prenoms)

                    $('#show_evenement').modal('show').fade('slow')
                },
                error: function(){
                    console.log("Something goes wrong")
                }
            })
        }
    </script>


    <script type="text/javascript">
        function desactiveItem(EvNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Désactiver cet évènement",
                text: "Confirmez-vous la désactivation de cet évènement ?",
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
                        url: '{{ route("suivi-client.desactiver", "") }}/' + EvNumero , // Use the named route here
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Évènement désactivé avec succès !',
                                '',
                                'success'
                            )
                        window.location.href = "{{ route('suivi-clients.index') }}";
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
        function activeItem(EvNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Activer cet évènement",
                text: "Confirmez-vous l'activation de cet évènement ?",
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
                        url: '{{ route("suivi-client.activer", "") }}/' + EvNumero , // Use the named route here
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Évènement activé avec succès !',
                                '',
                                'success'
                            )
                        window.location.href = "{{ route('suivi-clients.index') }}";
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
        function deleteItem(EvNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Suppression de cet évènement",
                text: "Confirmez-vous la suppression de cet évènement ?",
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
                        url: '{{ route("suivi-client.supprimer", "") }}/' + EvNumero , // Use the named route here
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Évènement supprimé avec succès',
                                '',
                                'success'
                            )
                        window.location.href = "{{ route('suivi-clients.index') }}";
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
