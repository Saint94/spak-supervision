@extends('layout.app')

@section('title', 'Liste des maîtres chien')

@section('content')

@include('layout.alert')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Liste des maîtres chien</b></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard table-responsive">

                            <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                                <thead>
                                    <tr>
                                        <th>Matri</th>
                                        <th>Nom(s) et Prénom(s)</th>
                                        <th>Tel 1</th>
                                        <th>Tel 2</th>
                                        <th>Ville</th>
                                        <th>Zone</th>
                                        <th>Quartier</th>
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
                                            <td>{{ $personnel->ZoNom }}</td>
                                            <td>{{ $personnel->QuNom }}</td>

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
                                            <th scope="row">Noms & Prénoms</th>
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

@endsection

@section('add_js')
    <script>
        tippy('#view', {
            content: "Consulter",
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

@endsection
