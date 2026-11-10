@extends('layout.app')

@section('title', 'Liste des agents')

@section('content')

@include('layout.alert')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Liste des agents</b></h4>
                        <h4 class="card-title">
                        <b>Code Client : </b>{{ $client->ClCode }},
                        <b>Noms Client : </b>{{ $client->ClDesign }},
                        <b>Tél Client : </b>{{ $client->ClTel1 }},
                        <b>Email Client : </b>{{ $client->ClEmail }},
                    </h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard table-responsive">
                            <table class="cell-border hover stripe display nowrap" id="myDataTable" data-page-length="50">
                                <thead>
                                    <tr>
                                        <th>Matri</th>
                                        <th>Nom(s)</th>
                                        <th>Prénom(s)</th>
                                        <th>Statut</th>
                                        <th>Date début</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($personnels as $personnel)
                                        <tr>
                                            <td>{{ $personnel->PeMatric }}</td>
                                            <td>{{ $personnel->PeNom }}</td>
                                            <td>{{ $personnel->PePrenom }}</td>

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
                                            @else
                                                <td></td>
                                            @endif

                                            @if($personnel->PeDateDebut != null)
                                                <td>{{ date('d/m/Y',strtotime($personnel->PeDateDebut)) }}</td>
                                            @else
                                                <td></td>
                                            @endif

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

@endsection
