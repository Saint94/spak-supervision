@extends('layout.app')

@section('title', 'Liste des articles')

@section('content')

@include('layout.alert')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Liste des articles</b></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard table-responsive">
                            <a href="{{ route('articles.create') }}" class="btn btn-primary mb-2"><i class="ft-plus"></i>&nbsp;Nouvel article</a>

                            <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Nom</th>
                                        <th>Type</th>
                                        <th>Prix achat conseillé</th>
                                        <th>Prix maximum achat</th>
                                        <th>Prix payé par l'agent</th>
                                        <th>Qte stock</th>
                                        <th>Seuil</th>
                                        <th>Fournisseur</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($articles as $article)
                                        <tr>
                                            <td>
                                                <img class="rounded-circle" width="35" src="{{ url('assets/img/articles/'.$article->ArPhoto) }}" alt="{{ $article->ArNom }}">
                                            </td>
                                            <td>{{ $article->ArCode }}</td>
                                            <td>{{ $article->ArNom }}</td>

                                            @if($article->ArType == 'EPI')
                                                <td>EPI</td>
                                            @elseif($article->ArType == 'MB')
                                                <td>Matériels de bureau</td>
                                            @elseif($article->ArType == 'PM')
                                                <td>Pièces de moto</td>
                                            @elseif($article->ArType == 'PV')
                                                <td>Pièces de véhicules</td>
                                            @elseif($article->ArType == 'MC')
                                                <td>Matériels de chantier</td>
                                            @else
                                                <td>Consommables informatiques</td>
                                            @endif

                                            <td>{{ currency_xaf($article->ArPrixAchatCons) }}</td>
                                            <td>{{ currency_xaf($article->ArPrixAchatMax) }}</td>
                                            <td>{{ currency_xaf($article->ArPrixAgent) }}</td>

                                            <td>{{ $article->ArQteStock }}</td>
                                            <td>{{ $article->ArStockSeuil }}</td>
                                            <td>{{ $article->FoNom }}</td>

                                            @if($article->ArIsOn == 'Y')
                                                <td><span class="badge light badge-success">Actif</span></td>
                                            @else
                                                <td><span class="badge light badge-danger">Inactif</span></td>
                                            @endif

                                            <td style="text-align:center;">
                                                <a onclick="showItem('{{$article->ArNumero }}')" class="info p-0" id="view">
                                                    <i class="ft-eye font-medium-3 mr-2"></i>
                                                </a>

                                                <a href="{{ route('articles.edit',crypter($article->ArNumero )) }}" class="warning p-0" id="edit">
                                                    <i class="ft-edit font-medium-3 mr-2"></i>
                                                </a>

                                                @if($article->ArIsOn == 'Y')
                                                    <a onclick="desactiveItem('{{$article->ArNumero }}')" class="danger p-0" id="inactif">
                                                        <i class="ft-log-out font-medium-3 mr-2"></i>
                                                    </a>
                                                @else
                                                    <a onclick="activeItem('{{$article->ArNumero }}')" class="success p-0" id="actif">
                                                        <i class="ft-log-in font-medium-3 mr-2"></i>
                                                    </a>
                                                @endif


                                                <a onclick="deleteItem('{{$article->ArNumero }}')" class="danger p-0" id="delete">
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

    <!-- Consulter un article -->
    <div class="modal fade text-left" id="show_article" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">INFORMATIONS ARTICLE</h4>
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
                                            <th scope="row">Type</th>
                                            <td id="type"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Couleurs Noire/Jaune ?</th>
                                            <td id="couleur"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Prix achat conseillé</th>
                                            <td id="prix_conseille"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Prix maximum achat</th>
                                            <td id="prix_max"></td>
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
                                            <th scope="row">Article payé par l'agent ?</th>
                                            <td id="is_paye_agent"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Prix payé par l'agent</th>
                                            <td id="prix_paye_agent"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Qte en stock</th>
                                            <td id="qte_stock"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Seuil stock</th>
                                            <td id="stock_seuil"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Fournisseur</th>
                                            <td id="fournisseur"></td>
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
            content: "Activer cet article",
            placement: 'top',
        });

        tippy('#inactif', {
            content: "Désactiver cet article",
            placement: 'top',
        });

        tippy('#delete', {
            content: "Supprimer",
            placement: 'top',
        });

    </script>

    <script type="text/javascript">
        function showItem(ArNumero) {
            $.ajax({
                url: '{{ route("article.show", "") }}/' + ArNumero , // Use the named route here
                type: 'GET',
                success: function(data){
                    $('#code').html(data.ArCode)
                    $('#nom').html(data.ArNom)
                    $('#prix_conseille').html(data.ArPrixAchatCons)
                    $('#prix_max').html(data.ArPrixAchatMax)
                    $('#prix_paye_agent').html(data.ArPrixAgent)
                    $('#qte_stock').html(data.ArQteStock)
                    $('#stock_seuil').html(data.ArStockSeuil)
                    $('#fournisseur').html(data.FoNom)

                    if((data.ArType) == 'EPI')
                    {
                        $('#type').text('EPI');
                    }
                    else if((data.ArType) == 'MB')
                    {
                        $('#type').text('Matériels de bureau');
                    }
                    else if((data.ArType) == 'PM')
                    {
                        $('#type').text('Pièces de moto');
                    }
                    else if((data.ArType) == 'PV')
                    {
                        $('#type').text('Pièces de véhicules');
                    }
                    else if((data.ArType) == 'MC')
                    {
                        $('#type').text('Matériels de chantier');
                    }
                    else
                    {
                        $('#type').text('Consommables informatiques');
                    }

                    if((data.ArIsCoulNoirJaunOn) == 'Y')
                    {
                        $('#couleur').text('Oui');
                    }
                    else
                    {
                        $('#couleur').text('Non');
                    }

                    if((data.ArIsPayeParAgent) == 'Y')
                    {
                        $('#is_paye_agent').text('Oui');
                    }
                    else
                    {
                        $('#is_paye_agent').text('Non');
                    }

                    if((data.ArIsOn) == 'Y')
                    {
                        $('#statut').text('Actif');
                    }
                    else
                    {
                        $('#statut').text('Inactif');
                    }

                    $('#show_article').modal('show').fade('slow')
                },
                error: function(){
                    console.log("Something goes wrong")
                }
            })
        }
    </script>


    <script type="text/javascript">
        function desactiveItem(ArNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Désactiver cet article",
                text: "Confirmez-vous la désactivation de cet article ?",
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
                        url: '{{ route("article.desactiver", "") }}/' + ArNumero , // Use the named route here
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Article désactivé avec succès !',
                                '',
                                'success'
                            )
                        window.location.href = "{{ route('articles.index') }}";
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
        function activeItem(ArNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Activer cet article",
                text: "Confirmez-vous l'activation de cet article ?",
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
                        url: '{{ route("article.activer", "") }}/' + ArNumero , // Use the named route here
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Article activé avec succès !',
                                '',
                                'success'
                            )
                        window.location.href = "{{ route('articles.index') }}";
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
        function deleteItem(ArNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Suppression de cet article",
                text: "Confirmez-vous la suppression de cet article ?",
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
                        url: '{{ route("article.supprimer", "") }}/' + ArNumero , // Use the named route here
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Article supprimé avec succès',
                                '',
                                'success'
                            )
                        window.location.href = "{{ route('articles.index') }}";
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
