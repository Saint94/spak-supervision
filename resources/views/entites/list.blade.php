@extends('layout.app')

@section('title', 'Liste des entités')

@section('content')

@include('layout.alert')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Liste des entités</b></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard table-responsive">
                            <a href="{{ route('entites.create') }}" class="btn btn-primary mb-2"><i class="ft-plus"></i>&nbsp;Nouvelle entité</a>
                            <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Code</th>
                                        <th>Nom</th>
                                        <th>Boîte postale</th>
                                        <th>Email</th>
                                        <th>Tel 1</th>
                                        <th>Tel 2</th>
                                        <th>Site Web</th>
                                        <th>Prix minimum agent fixe</th>
                                        <th>Prix minimum maître chien</th>
                                        <th>Prix minimum superviseur</th>
                                        <th>Prix minimum commando</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($entites as $entite)
                                        <tr>
                                            <td>
                                                <img class="rounded-circle" width="35" src="{{ url('assets/img/entites/'.$entite->EnChemLogo) }}" alt="{{ $entite->EnNom }}">
                                            </td>
                                            <td>{{ $entite->EnAbrge }}</td>
                                            <td>{{ $entite->EnNom }}</td>
                                            <td>{{ $entite->EnBP }}</td>
                                            <td>{{ $entite->EnEmail }}</td>
                                            <td>{{ $entite->EnTel1 }}</td>
                                            <td>{{ $entite->EnTel2 }}</td>
                                            <td>{{ $entite->EnSiteWeb }}</td>
                                            <td>{{ currency_xaf($entite->EnPrixUMinAgentFixe) }}</td>
                                            <td>{{ currency_xaf($entite->EnPrixUMinMC) }}</td>
                                            <td>{{ currency_xaf($entite->EnPrixUMinSuperv) }}</td>
                                            <td>{{ currency_xaf($entite->EnPrixUMinComdo) }}</td>

                                            <td style="text-align:center;">
                                                <a onclick="showItem('{{$entite->EnNumero}}')" class="info p-0" id="view">
                                                    <i class="ft-eye font-medium-3 mr-2"></i>
                                                </a>

                                                <a href="{{ route('entites.edit',crypter($entite->EnNumero)) }}" class="warning p-0" id="edit">
                                                    <i class="ft-edit font-medium-3 mr-2"></i>
                                                </a>

                                                <a onclick="deleteItem('{{$entite->EnNumero}}')" class="danger p-0" id="delete">
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

    <!-- Consulter un entité -->
    <div class="modal fade text-left" id="show_entite" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">INFORMATIONS ENTITE</h4>
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
                                            <td id="abrege"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Nom</th>
                                            <td id="nom"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Boîte postale</th>
                                            <td id="bp"></td>
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
                                            <th scope="row">Nbre max mois tolérés</th>
                                            <td id="nbre_max_mois_toleres"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Site Web</th>
                                            <td id="site_web"></td>
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
                                            <th scope="row">DG</th>
                                            <td id="dg"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">RT</th>
                                            <td id="dt"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">RAF</th>
                                            <td id="raf"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Prix minimum agent fixe</th>
                                            <td id="prix_min_agent_fixe"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Prix minimum maître chien</th>
                                            <td id="prix_min_mc"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Prix minimum superviseur</th>
                                            <td id="prix_min_superv"></td>
                                        </tr>

                                         <tr>
                                            <th scope="row">Prix minimum commando</th>
                                            <td id="prix_min_comdo"></td>
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
    <!-- Consulter un entité -->

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
        tippy('#delete', {
            content: "Supprimer",
            placement: 'top',
        });
    </script>

    <script type="text/javascript">
        function showItem(EnNumero) {
            $.ajax({
                url: '{{ route("entite.show", "") }}/' + EnNumero, // Use the named route here
                type: 'GET',
                success: function(data){
                    $('#abrege').html(data.EnAbrge)
                    $('#nom').html(data.EnNom)
                    $('#bp').html(data.EnBP);
                    $('#tel1').html(data.EnTel1)
                    $('#tel2').html(data.EnTel2)
                    $('#email').html(data.EnEmail)
                    $('#site_web').html(data.EnSiteWeb)
                    $('#dg').html(data.nom_dg.' '.data.prenom_dg)
                    $('#dt').html(data.nom_dt.' '.data.prenom_dt)
                    $('#raf').html(data.nom_raf.' '.data.prenom_raf)
                    $('#nbre_max_mois_toleres').html(data.EnNbMoisMaxImpClt)
                    $('#prix_min_agent_fixe').html(data.EnPrixUMinAgentFixe)
                    $('#prix_min_mc').html(data.EnPrixUMinMC)
                    $('#prix_min_superv').html(data.EnPrixUMinSuperv)
                    $('#prix_min_comdo').html(data.EnPrixUMinComdo)

                    $('#show_entite').modal('show').fade('slow')
                },
                error: function(){
                    console.log("Something goes wrong")
                }
            })
        }
    </script>

    <script type="text/javascript">
        function deleteItem(EnNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Suppression de cette entité ?",
                text: "Confirmez-vous la suppression de cette entité ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Oui, Supprimé",
                cancelButtonText: "Annuler !",
                confirmButtonColor: '#2ecc71',
                cancelButtonColor: '#e84118',
                reverseButtons: true

            }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '{{ route("entite.supprimer", "") }}/' + EnNumero, // Use the named route here
                    type: 'GET',
                    success: function(data) {
                        swalWithBootstrapButtons.fire('Entité supprimée avec succès','','success')
                        window.location.href = "{{ route('entites.index') }}";
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
