@extends('layout.app')

@section('title', 'Liste du personnel')

@section('content')

@include('layout.alert')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Liste du personnel</b></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard table-responsive">
                            <a href="{{ route('personnels.create') }}" class="btn btn-primary mb-2"><i class="ft-plus"></i>&nbsp;Nouveau personnel</a>
                            <button type="button" class="btn btn-danger mb-2" data-toggle="modal" data-target="#import"><i class="ft-download"></i>&nbsp;Importer le personnels</button>

                            <form action="{{ route('personnel.filtre') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-xl-2 col-md-2">
                                        <div class="form-group">
                                            <label for="ville_personnel">Ville</label>
                                            <select name="ville_personnel" id="ville_personnel" class="form-control e1">
                                                <option selected="selected" value="all">Tous</option>
                                                @foreach($villes as $ville)
                                                    <option value="{{ $ville->ViNumero }}">{{ $ville->ViNom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-2">
                                        <div class="form-group">
                                            <label for="zone_personnel">Zone</label>
                                            <select id="zone_personnel" name="zone_personnel" class="form-control e2">
                                                <option selected="selected" value="all">Tous</option>
                                                @foreach($zones as $zone)
                                                    <option value="{{ $zone->ZoNumero }}">{{ $zone->ZoNom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-2">
                                        <div class="form-group">
                                            <label for="quartier_personnel">Quartier</label>
                                            <select id="quartier_personnel" name="quartier_personnel" class="form-control e3">
                                                <option selected="selected" value="all">Tous</option>
                                                @foreach($quartiers as $quartier)
                                                    <option value="{{ $quartier->QuNumero }}">{{ $quartier->QuNom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-2">
                                        <div class="form-group">
                                            <label for="site_personnel">Sites client</label>
                                            <select name="site_personnel" id="site_personnel" class="form-control e4">
                                                <option selected="selected" value="all">Tous</option>
                                                @foreach($sites_client as $site_client)
                                                    <option value="{{ $site_client->ScNumero }}">{{ $site_client->ScNomSite }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-2">
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
                                        <th>Matri</th>
                                        <th>Nom et Prénom(s)</th>
                                        <th>Tél 1</th>
                                        <th>Tél 2</th>
                                        <th>Ville</th>
                                        <th>Zone</th>
                                        <th>Quartier</th>
                                        <th>N° pièce</th>
                                        <th>Date embauche</th>
                                        <th>Préférence de Faction</th>
                                        <th>Niv. Lecture</th>
                                        <th>Niv. Écriture</th>
                                        <th>Taille</th>
                                        <th>Poids</th>
                                        <th>Gabarit</th>
                                        <th>Spécialité</th>
                                        <th>Prétention sal</th>
                                        <th>Nature emploi</th>
                                        <th>Statut</th>
                                        <th>Profil</th>
                                        <th>Poste</th>
                                        <th>Depuis le</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($personnels as $personnel)
                                        <tr>
                                            <td>{{ $personnel->PeMatric }}</td>
                                            <td>{{ $personnel->PeNom.' '.$personnel->PePrenom }}</td>
                                            <td>{{ $personnel->PeTel1 }}</td>
                                            <td>{{ $personnel->PeTel2 }}</td>
                                            <td>{{ $personnel->ViNom }}</td>
                                            <td>{{ $personnel->ZoNom ?? '' }}</td>
                                            <td>{{ $personnel->QuNom }}</td>
                                            <td>{{ $personnel->PeNumPieceID }}</td>
                                            @if($personnel->PeDateEmb != null)
                                                <td>{{ date('d/m/Y',strtotime($personnel->PeDateEmb)) }}</td>
                                            @else
                                                <td></td>
                                            @endif


                                            @if($personnel->PePrefTravJN == 'J')
                                                <td>Jour</td>
                                            @elseif($personnel->PePrefTravJN == 'N')
                                                <td>Nuit</td>
                                            @else
                                                <td></td>
                                            @endif

                                            @if($personnel->PeNivLect == 'A')
                                                <td>Aucun</td>
                                            @elseif($personnel->PeNivLect == 'D')
                                                <td>Mediocre</td>
                                            @elseif($personnel->PeNivLect == 'M')
                                                <td> Moyen </td>
                                            @elseif($personnel->PeNivLect == 'B')
                                                <td>Bien</td>
                                            @elseif($personnel->PeNivLect == 'T')
                                                <td>Très bien</td>
                                            @else
                                                <td></td>
                                            @endif

                                            @if($personnel->PeNivEcr == 'A')
                                                <td>Aucun</td>
                                            @elseif($personnel->PeNivEcr == 'D')
                                                <td>Mediocre</td>
                                            @elseif($personnel->PeNivEcr == 'M')
                                                <td> Moyen </td>
                                            @elseif($personnel->PeNivEcr == 'B')
                                                <td>Bien</td>
                                            @elseif($personnel->PeNivEcr == 'T')
                                                <td>Très bien</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td>{{ $personnel->PeTaille }}</td>
                                            <td>{{ $personnel->PePoids }}</td>
                                            <td>{{ $personnel->PeGabarit }}</td>
                                            <td>{{ $personnel->PeSpecialite }}</td>

                                            <td>{{ $personnel->PePretenSal }}</td>

                                            @if($personnel->PeNatureEmpl == 'A')
                                                <td>Personnel Administratif</td>
                                            @elseif($personnel->PeNatureEmpl == 'S')
                                                <td>Agents de Sécurité</td>
                                            @else
                                                <td></td>
                                            @endif

                                            @if($personnel->PeIsOn == 'Y')
                                                <td><span class="badge light badge-success">Actif</span></td>
                                            @else
                                                <td><span class="badge light badge-danger">Inactif</span></td>
                                            @endif

                                            @if($personnel->PeStatut == 'F')
                                                <td>Agent Fixe</td>
                                            @elseif($personnel->PeStatut == 'R')
                                                <td>Rondier</td>
                                            @elseif($personnel->PeStatut == 'B')
                                                <td>Stand Bye</td>
                                            @elseif($personnel->PeStatut == 'E')
                                                <td>Chef d'Equipe</td>
                                            @elseif($personnel->PeStatut == 'C')
                                                <td>Maître Chien</td>
                                            @elseif($personnel->PeStatut == 'SG')
                                                <td>Superv. Général</td>
                                            @elseif($personnel->PeStatut == 'SA')
                                                <td>Superv. Adjoint</td>
                                            @elseif($personnel->PeStatut == 'CT')
                                                <td>Contrôleur</td>
                                            @elseif($personnel->PeStatut == 'CM')
                                                <td>Commando</td>
                                            @elseif($personnel->PeStatut == 'PS')
                                                <td>Postulant</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td>{{ $personnel->ScNomSite ?? '' }}</td>

                                            @if($personnel->PeDateDebut != null)
                                                <td>{{ date('d/m/Y',strtotime($personnel->PeDateDebut)) }}</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td style="text-align:center;">
                                                <a onclick="showItem('{{$personnel->PeNumero}}')" class="info p-0" id="view">
                                                    <i class="ft-eye font-medium-3 mr-2"></i>
                                                </a>

                                                <a href="{{ route('personnels.edit',crypter($personnel->PeNumero)) }}" class="warning p-0" id="edit">
                                                    <i class="ft-edit font-medium-3 mr-2"></i>
                                                </a>

                                                @if($personnel->PeIsOn == 'Y')
                                                    <a onclick="desactiveItem('{{$personnel->PeNumero}}')" class="danger p-0" id="inactif">
                                                        <i class="ft-log-out font-medium-3 mr-2"></i>
                                                    </a>
                                                @else
                                                    <a onclick="activeItem('{{$personnel->PeNumero}}')" class="success p-0" id="actif">
                                                        <i class="ft-log-in font-medium-3 mr-2"></i>
                                                    </a>
                                                @endif

                                                @if($personnel->PeNatureEmpl == 'A')
                                                    <a href="{{ route('personnels.carrieres',crypter($personnel->PeNumero)) }}" class="info p-0" id="carrieres">
                                                        <i class="ft-users font-medium-3 mr-2"></i>
                                                    </a>
                                                @endif

                                                <a onclick="deleteItem('{{$personnel->PeNumero }}')" class="danger p-0" id="delete">
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

    <!-- Consulter un personnel -->
    <div class="modal fade text-left" id="show_personnel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info white">
                    <h4 class="modal-title" id="myModalLabel16">INFORMATIONS PERSONNEL</h4>
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
                                            <th scope="row">Matri</th>
                                            <td id="matri"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Nom & Prénoms</th>
                                            <td id="nom"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date de début</th>
                                            <td id="date_debut"></td>
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
                                            <th scope="row">Poste</th>
                                            <td id="poste"></td>
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
                                            <th scope="row">Quartier</th>
                                            <td id="quartier"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Profil</th>
                                            <td id="profil"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Superviser déjà les sites ?</th>
                                            <td id="site_on"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Domicile</th>
                                            <td id="domicile"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date départ</th>
                                            <td id="date_depart"></td>
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
    <!-- Consulter un personnel -->

    <!-- IMPORT PERSONNEL -->
    <div class="modal fade text-left" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <form method="POST" action="{{ route('personnel.import') }}" class="needs-validation" novalidate>
                    @csrf

                    <div class="modal-header bg-info white">
                        <h4 class="modal-title" id="myModalLabel16">IMPORTER LE PERSONNEL</h4>
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
    <!-- IMPORT PERSONNEL -->

@endsection

@section('add_js')

    <script>
        $(document).ready(function() {
            $(".e1").select2();
            $(".e2").select2();
            $(".e3").select2();
            $(".e4").select2();
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
            content: "Activer ce personnel",
            placement: 'top',
        });

        tippy('#inactif', {
            content: "Désactiver ce personnel",
            placement: 'top',
        });

        tippy('#carrieres', {
            content: "Liste des carrières",
            placement: 'bottom',
        });

        tippy('#delete', {
            content: "Supprimer",
            placement: 'top',
        });
    </script>

    <script type="text/javascript">
        function showItem(PeNumero) {
            $.ajax({
                url: '{{ route("personnel.show", "") }}/' + PeNumero, // Use the named route here
                type: 'GET',
                success: function(data){
                    $('#matri').html(data.PeMatric)
                    $('#nom').html(data.PeNom+' '+data.PePrenom)
                    $('#date_debut').html(data.PeDateDebut)
                    $('#tel1').html(data.PeTel1)
                    $('#tel2').html(data.PeTel2)
                    $('#email').html(data.PeEmail)
                    $('#ville').html(data.ViNom)
                    $('#quartier').html(data.QuNom)
                    $('#domicile').html(data.domicile)
                    $('#date_depart').html(data.EnNom)
                    $('#poste').html(data.ScNomSite)

                    if((data.PeStatut) == 'F')
                    {
                        $('#profil').text('Agent Fixe');
                    }
                    else if((data.PeStatut) == 'R')
                    {
                        $('#profil').text('Rondier');
                    }
                    else if((data.PeStatut) == 'B')
                    {
                        $('#profil').text('Stand Bye');
                    }
                    else if((data.PeStatut) == 'E')
                    {
                        $('#profil').text('Chef d\'Equipe');
                    }
                    else if((data.PeStatut) == 'C')
                    {
                        $('#profil').text('Maître Chien');
                    }
                    else if((data.PeStatut) == 'SG')
                    {
                        $('#profil').text('Superv. Général');
                    }
                    else if((data.PeStatut) == 'SA')
                    {
                        $('#profil').text('Superv. Adjoint');
                    }
                    else if((data.PeStatut) == 'CT')
                    {
                        $('#profil').text('Contrôleur');
                    }
                    else if((data.PeStatut) == 'CM')
                    {
                        $('#profil').text('Commando');
                    }
                    else if((data.PeStatut) == 'PS')
                    {
                        $('#profil').text('Postulant');
                    }
                    else
                    {
                        $('#profil').text('Inactif');
                    }

                    if((data.PeIsSupervOn) == 'Y')
                    {
                        $('#site_on').text('Oui');
                    }
                    else
                    {
                        $('#site_on').text('Non');
                    }

                    if((data.PeIsOn) == 'Y')
                    {
                        $('#statut').text('Actif');
                    }
                    else
                    {
                        $('#statut').text('Inactif');
                    }


                    $('#show_personnel').modal('show').fade('slow')
                },
                error: function(){
                    console.log("Something goes wrong")
                }
            })
        }
    </script>


    <script type="text/javascript">
        function desactiveItem(PeNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Désactiver ce personnel",
                text: "Confirmez-vous la désactivation de ce personnel ?",
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
                        url: '{{ route("personnel.desactiver", "") }}/' + PeNumero, // Use the named route here
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Personnel désactivé avec succès !',
                                '',
                                'success'
                            )
                        window.location.href = "{{ route('personnels.index') }}";
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
        function activeItem(PeNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Activer ce personnel",
                text: "Confirmez-vous l'activation de ce personnel ?",
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
                        url: '{{ route("personnel.activer", "") }}/' + PeNumero, // Use the named route here
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Personnel activé avec succès !',
                                '',
                                'success'
                            )
                        window.location.href = "{{ route('personnels.index') }}";
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
        function deleteItem(PeNumero) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: "Suppression de ce personnel",
                text: "Confirmez-vous la suppression de ce personnel ?",
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
                        url: '{{ route("personnel.supprimer", "") }}/' + PeNumero, // Use the named route here
                        type: 'GET',
                        success: function(data) {
                            swalWithBootstrapButtons.fire(
                                'Personnel supprimé avec succès',
                                '',
                                'success'
                            )
                        window.location.href = "{{ route('personnels.index') }}";
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
