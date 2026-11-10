@extends('layout.app')

@section('title', 'Liste des personnels affectés sur le contrat/avenant')

@section('content')

@include('layout.alert')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Liste des personnels affectés sur le contrat/avenant</b></h4>
                        <h4 class="card-title">
                            <b>Client : </b>{{$avenant_contrat->ClDesign }},
                            <b>Réf : </b>{{ $avenant_contrat->AcRefAven }},
                            <b>Type : </b>{{ $avenant_contrat->AcType == 'C' ? 'Contrat':'Avenant' }},
                            <b>Date effet : </b>{{ date('d/m/Y',strtotime($avenant_contrat->AcDateEffet)) }},
                            @if($avenant_contrat->AcDateFin != null)
                                <b>Date fin : </b>{{ date('d/m/Y',strtotime($avenant_contrat->AcDateFin)) }},
                            @else
                                <b>Date fin : </b>,
                            @endif

                        </h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard table-responsive">

                            <form action="{{ route('avenant-contrat.filtre_personnel_contrat') }}" method="POST">
                                @csrf

                                <div class="row">

                                    <input type="hidden" name="AcNumero" value="{{ crypter($AcNumero) }}">

                                    <div class="col-xl-4 col-md-4">
                                        <div class="form-group">
                                            <label for="site_personnel">Sites client</label>
                                            <select name="site_personnel" id="site_personnel" class="form-control e1">
                                                <option selected="selected" value="all">Tous</option>
                                                @foreach($sites_client as $site_client)
                                                    <option value="{{ $site_client->ScNumero }}">{{ $site_client->ScNomSite }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                   <div class="col-xl-4 col-md-4">
                                        <div class="form-group">
                                            <label for="profil_personnel">Profil</label>
                                            <select class="form-control e2" name="profil_personnel" id="profil_personnel">
                                                <option selected="selected" value="all">Tous</option>
                                                <option value="F">Agent Fixe</option>
                                                <option value="R">Rondier</option>
                                                <option value="B">Stand Bye</option>
                                                <option value="E">Chef d'Equipe</option>
                                                <option value="C">Maître Chien</option>
                                                <option value="SG">Superv. Général</option>
                                                <option value="SA">Superv. Adjoint</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-md-4">
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
                                        <th>Nom(s) et Prénom(s)</th>
                                        <th>Ville</th>
                                        <th>Date de début</th>
                                        <th>Site</th>
                                        <th>Profil</th>
                                        <th>Statut</th>
                                        <th>Date de Fin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($personnels as $personnel)
                                        <tr>
                                            <td>{{ $personnel->PeMatric }}</td>
                                            <td>{{ $personnel->PeNom.' '.$personnel->PePrenom }}</td>
                                            <td>{{ $personnel->ViNom }}</td>

                                            @if($personnel->PvDateDebut != null)
                                                <td>{{ date('d/m/Y',strtotime($personnel->PvDateDebut)) }}</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td>{{ $personnel->ScNomSite ?? '' }}</td>

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
                                            @elseif($personnel->PeStatut == 'PS')
                                                <td>Postulant</td>
                                            @else
                                                <td></td>
                                            @endif

                                            @if($personnel->PvIsOn == 'Y')
                                                <td><span class="badge light badge-success">Actif</span></td>
                                            @else
                                                <td><span class="badge light badge-danger">Inactif</span></td>
                                            @endif

                                            @if($personnel->PvDateFin != null)
                                                <td>{{ date('d/m/Y',strtotime($personnel->PvDateFin)) }}</td>
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

@section('add_js')

    <script>
        $(document).ready(function() {
            $(".e1").select2();
            $(".e2").select2();
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
    </script>

@endsection
