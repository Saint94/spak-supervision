@extends('layout.app')

@section('title', 'Liste des fournisseurs')

@section('content')

@include('layout.alert')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Liste des fournisseurs</b></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard table-responsive">
                            <a href="{{ route('fournisseurs.create') }}" class="btn btn-primary mb-2"><i class="ft-plus"></i>&nbsp;Nouveau fournisseur</a>

                            <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Nom</th>
                                        <th>Adresse</th>
                                        <th>Téléphone 1</th>
                                        <th>Téléphone 2</th>
                                        <th>Email</th>
                                        <th>Ville</th>
                                        <th>Pays</th>
                                        <th>TVA</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fournisseurs as $fournisseur)
                                        <tr>
                                            <td>
                                                @if($fournisseur->FoNumero)
                                                    {{ $fournisseur->FoNumero }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $fournisseur->FoCode }}</td>
                                            <td>{{ $fournisseur->FoNom }}</td>
                                            <td>{{ $fournisseur->FoAdresse }}</td>
                                            <td>{{ $fournisseur->FoTel1 }}</td>
                                            <td>{{ $fournisseur->FoTel2 }}</td>
                                            <td>{{ $fournisseur->FoEmail }}</td>
                                            <td>{{ $fournisseur->FoVille }}</td>
                                            <td>{{ $fournisseur->FoPays }}</td>

                                            @if($fournisseur->FoIsTVAOn == 'Y')
                                                <td><span class="badge light badge-success">Appliquée</span></td>
                                            @else
                                                <td><span class="badge light badge-danger">Non appliquée</span></td>
                                            @endif


                                            @if($fournisseur->FoIsOn == 'Y')
                                                <td><span class="badge light badge-success">Actif</span></td>
                                            @else
                                                <td><span class="badge light badge-danger">Inactif</span></td>
                                            @endif

                                            <td style="text-align:center;">
                                                <a onclick="showItem('{{ $fournisseur->FoNumero }}')" class="info p-0" id="view">
                                                    <i class="ft-eye font-medium-3 mr-2"></i>
                                                </a>

                                                <a href="{{ route('fournisseurs.edit', crypter($fournisseur->FoNumero)) }}" class="warning p-0" id="edit">
                                                    <i class="ft-edit font-medium-3 mr-2"></i>
                                                </a>

                                                @if($fournisseur->FoIsOn == 'Y')
                                                    <a onclick="desactiveItem('{{ $fournisseur->FoNumero }}')" class="danger p-0" id="inactif">
                                                        <i class="ft-log-out font-medium-3 mr-2"></i>
                                                    </a>
                                                @else
                                                    <a onclick="activeItem('{{ $fournisseur->FoNumero }}')" class="success p-0" id="actif">
                                                        <i class="ft-log-in font-medium-3 mr-2"></i>
                                                    </a>
                                                @endif

                                                <a onclick="deleteItem('{{ $fournisseur->FoNumero }}')" class="danger p-0" id="delete">
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

    <!-- Consulter un fournisseur -->
    <div class="modal fade text-left" id="show_fournisseur" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">INFORMATIONS FOURNISSEUR</h4>
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
                                            <td id="code"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Nom</th>
                                            <td id="nom"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Adresse</th>
                                            <td id="adresse"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Téléphone 1</th>
                                            <td id="tel1"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Téléphone 2</th>
                                            <td id="tel2"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Email</th>
                                            <td id="email"></td>
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
                                            <th scope="row">Ville</th>
                                            <td id="ville"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Pays</th>
                                            <td id="pays"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">TVA appliquée</th>
                                            <td id="tva"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Créé par</th>
                                            <td id="cree_par"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date création</th>
                                            <td id="date_cree"></td>
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
            content: "Activer ce fournisseur",
            placement: 'top',
        });

        tippy('#inactif', {
            content: "Désactiver ce fournisseur",
            placement: 'top',
        });

        tippy('#delete', {
            content: "Supprimer",
            placement: 'top',
        });
    </script>

    <script type="text/javascript">
        function showItem(FoNumero) {
            $.ajax({
                url: '{{ route("fournisseur.show", "") }}/' + FoNumero,
                type: 'GET',
                success: function(data){
                    $('#code').html(data.FoCode ?? '-');
                    $('#nom').html(data.FoNom ?? '-');
                    $('#adresse').html(data.FoAdresse ?? '-');
                    $('#tel1').html(data.FoTel1 ?? '-');
                    $('#tel2').html(data.FoTel2 ?? '-');
                    $('#email').html(data.FoEmail ?? '-');
                    $('#ville').html(data.FoVille ?? '-');
                    $('#pays').html(data.FoPays ?? '-');
                    $('#entite').html(data.entite_nom ?? '-');
                    $('#cree_par').html(data.nom_createur ?? '-');
                    $('#date_cree').html(data.FoDateHCree ? new Date(data.FoDateHCree).toLocaleString() : '-');

                    if(data.FoIsTVAOn == 'Y') {
                        $('#tva').html('<span class="badge light badge-success">Appliquée</span>');
                    } else {
                        $('#tva').html('<span class="badge light badge-danger">Non appliquée</span>');
                    }

                    if(data.FoIsOn == 'Y') {
                        $('#statut').html('<span class="badge light badge-success">Actif</span>');
                    } else {
                        $('#statut').html('<span class="badge light badge-danger">Inactif</span>');
                    }

                    $('#show_fournisseur').modal('show').fade('slow');
                },
                error: function(){
                    console.log("Something goes wrong");
                    Swal.fire('Erreur', 'Impossible de charger les informations', 'error');
                }
            });
        }
    </script>

    <script type="text/javascript">
        function desactiveItem(FoNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: true
            });

            swalWithBootstrapButtons.fire({
                title: "Désactiver ce fournisseur",
                text: "Confirmez-vous la désactivation de ce fournisseur ?",
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
                        url: '{{ route("fournisseur.desactiver", "") }}/' + FoNumero,
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Fournisseur désactivé avec succès !',
                                '',
                                'success'
                            );
                            window.location.href = "{{ route('fournisseurs.index') }}";
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                        'Annulé',
                        '',
                        'error'
                    );
                }
            });
        }
    </script>

    <script type="text/javascript">
        function activeItem(FoNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: true
            });

            swalWithBootstrapButtons.fire({
                title: "Activer ce fournisseur",
                text: "Confirmez-vous l'activation de ce fournisseur ?",
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
                        url: '{{ route("fournisseur.activer", "") }}/' + FoNumero,
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Fournisseur activé avec succès !',
                                '',
                                'success'
                            );
                            window.location.href = "{{ route('fournisseurs.index') }}";
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                        'Annulé',
                        '',
                        'error'
                    );
                }
            });
        }
    </script>

    <script type="text/javascript">
        function deleteItem(FoNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: true
            });

            swalWithBootstrapButtons.fire({
                title: "Suppression de ce fournisseur",
                text: "Confirmez-vous la suppression de ce fournisseur ?",
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
                        url: '{{ route("fournisseur.supprimer", "") }}/' + FoNumero,
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Fournisseur supprimé avec succès',
                                '',
                                'success'
                            );
                            window.location.href = "{{ route('fournisseurs.index') }}";
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                        'Annulé',
                        '',
                        'error'
                    );
                }
            });
        }
    </script>
@endsection
