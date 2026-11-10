@extends('layout.app')

@section('title', 'Liste des utilisateurs')

@section('content')

@include('layout.alert')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Liste des utilisateurs</b></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard table-responsive">
                            <a href="{{ route('users.create') }}" class="btn btn-primary mb-2"><i class="ft-plus"></i>&nbsp;Nouvel utilisateur</a>
                            <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                                <thead>
                                    <tr>
                                        <th>Nom(s) et Prénom(s)</th>
                                        <th>Fonction</th>
                                        <th>Tel 1</th>
                                        <th>Tel 2</th>
                                        <th>Nom d'utiliateur</th>
                                        <th>Statut</th>
                                        <th>Type</th>
                                        <th>Personnel</th>
                                        <th>Entité</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->noms.' '.$user->prenoms }}</td>
                                            <td>{{ $user->fonction }}</td>
                                            <td>{{ $user->tel1 }}</td>
                                            <td>{{ $user->tel2 }}</td>
                                            <td>{{ $user->username }}</td>

                                            @if($user->statut == 'Y')
                                                <td><span class="badge light badge-success">Actif</span></td>
                                            @else
                                                <td><span class="badge light badge-danger">Inactif</span></td>
                                            @endif

                                            @if($user->type == 'M')
                                                <td>Super Manager</td>
                                            @elseif($user->type == 'R')
                                                <td>Responsable Opérations</td>
                                            @elseif($user->type == 'S')
                                                <td>Agent Superviseur</td>
                                            @elseif($user->type == 'E')
                                                <td>Chef d'Equipe</td>
                                            @elseif($user->type == 'P')
                                                <td>Agent de Paie</td>
                                            @elseif($user->type == 'C')
                                                <td>Responsable Commercial</td>
                                            @elseif($user->type == 'F')
                                                <td>Agent de Facturation</td>
                                            @elseif($user->type == 'T')
                                                <td>Agent Contrôleur</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td>{{ $user->PeNom.' '.$user->PePrenom }}</td>
                                            <td>{{ $user->EnNom }}</td>

                                            <td style="text-align:center;">
                                                <a onclick="showItem('{{$user->id}}')" class="info p-0" id="view">
                                                    <i class="ft-eye font-medium-3 mr-2"></i>
                                                </a>

                                                <a href="{{ route('users.edit',crypter($user->id)) }}" class="warning p-0" id="edit">
                                                    <i class="ft-edit font-medium-3 mr-2"></i>
                                                </a>

                                                @if($user->statut == 'Y')
                                                    <a onclick="desactiveItem('{{$user->id}}')" class="danger p-0" id="inactif">
                                                        <i class="ft-log-out font-medium-3 mr-2"></i>
                                                    </a>
                                                @else
                                                    <a onclick="activeItem('{{$user->id}}')" class="success p-0" id="actif">
                                                        <i class="ft-log-in font-medium-3 mr-2"></i>
                                                    </a>
                                                @endif

                                                <a onclick="deleteItem('{{$user->id}}')" class="danger p-0" id="delete">
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

    <!-- Consulter un utilisateur -->
    <div class="modal fade text-left" id="show_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">INFORMATIONS UTILISATEUR</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <tbody>
                                        <tr>
                                            <th scope="row">Nom(s) & Prénom(s)</th>
                                            <td id="name"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">N° de téléphone 1</th>
                                            <td id="tel1"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">N° de téléphone 2</th>
                                            <td id="tel2"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Email</th>
                                            <td id="email"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Fonction</th>
                                            <td id="fonction"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Nom d'utilisateur</th>
                                            <td id="username"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Entité</th>
                                            <td id="entite"></td>
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
    <!-- Consulter un utilisateur -->

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
            content: "Activer cet utilisateur",
            placement: 'top',
        });

        tippy('#inactif', {
            content: "Désactiver cet utilisateur",
            placement: 'top',
        });

        tippy('#delete', {
            content: "Supprimer",
            placement: 'top',
        });
    </script>

    <script type="text/javascript">
        function showItem(id) {
            $.ajax({
                url: '{{ route("user.show", "") }}/' + id, // Use the named route here
                type: 'GET',
                success: function(data){
                    $('#name').html(data[0].noms+' '+data[0].prenoms)
                    $('#tel1').html(data[0].tel1)
                    $('#tel2').html(data[0].tel2)
                    $('#email').html(data[0].email)
                    $('#fonction').html(data[0].fonction)
                    $('#username').html(data[0].username)
                    $('#entite').html(data[0].EnNom)

                    $('#show_user').modal('show').fade('slow')
                },
                error: function(){
                    console.log("Something goes wrong")
                }
            })
        }
    </script>


    <script type="text/javascript">
        function desactiveItem(id) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Désactiver cet utilisateur",
                text: "Confirmez-vous la désactivation de cet utilisateur ?",
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
                        url: '{{ route("user.desactiver", "") }}/' + id, // Use the named route here
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Utilisateur désactivé avec succès !',
                                '',
                                'success'
                            )
                        window.location.href = "{{ route('users.index') }}";
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
        function activeItem(id) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Activer cet utilisateur",
                text: "Confirmez-vous l'activation de cet utilisateur ?",
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
                        url: '{{ route("user.activer", "") }}/' + id, // Use the named route here
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Utilisateur activé avec succès !',
                                '',
                                'success'
                            )
                        window.location.href = "{{ route('users.index') }}";
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
        function deleteItem(id) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Suppression de cet utilisateur",
                text: "Confirmez-vous la suppression de cet utilisateur ?",
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
                        url: '{{ route("user.supprimer", "") }}/' + id, // Use the named route here
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Utilisateur supprimé avec succès',
                                '',
                                'success'
                            )
                        window.location.href = "{{ route('users.index') }}";
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
