@extends('layout.app')

@section('title', 'Liste des sites client')

@section('content')

@include('layout.alert')

    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><b>Liste des sites client</b></h4>
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
                                        <th>Nom</th>
                                        <th>Ville</th>
                                        <th>Zone</th>
                                        <th>Quartier</th>
                                        <th>Date Début</th>
                                        <th>Date Fin</th>
                                        <th>Nb Agents jour</th>
                                        <th>Nb Agents nuit</th>
                                        <th>Nb M.Chien jour</th>
                                        <th>Nb M.Chien nuit</th>
                                        <th>Nb. Superv jour</th>
                                        <th>Nb. Superv nuit</th>
                                        <th>Nb. Cmdo jour</th>
                                        <th>Nb. Cmdo nuit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sites_client as $site)
                                        <tr>
                                            <td>{{ $site->ScNomSite }}</td>
                                            <td>{{ $site->ViNom }}</td>
                                            <td>{{ $site->ZoNom }}</td>
                                            <td>{{ $site->QuNom }}</td>

                                            @if(!empty($contrat))
                                                @php
                                                    $contrat_site = DB::table('contrat_site')
                                                        ->where('contrat_site.CsContratClient',$contrat->AcNumero)
                                                        ->where('contrat_site.CsSiteClient',$site->ScNumero)
                                                        ->where('contrat_site.CsEntite',Auth::user()->entite)
                                                        ->first();
                                                @endphp

                                                @if($contrat->AcDateEffet != null)
                                                    <td>{{ date('d/m/Y',strtotime($contrat->AcDateEffet)) }}</td>
                                                @else
                                                    <td></td>
                                                @endif

                                                @if($contrat->AcDateFin != null)
                                                    <td>{{ date('d/m/Y',strtotime($contrat->AcDateFin)) }}</td>
                                                @else
                                                    <td></td>
                                                @endif

                                                <td>{{ $contrat_site->CsNbreAgentJr }}</td>
                                                <td>{{ $contrat_site->CsNbreAgentNu }}</td>
                                                <td>{{ $contrat_site->CsNbreMCJr }}</td>
                                                <td>{{ $contrat_site->CsNbreMCNu }}</td>
                                                <td>{{ $contrat_site->CsNbSupervJr }}</td>
                                                <td>{{ $contrat_site->CsNbSupervNu }}</td>
                                                <td>{{ $contrat_site->CsNbComdoJr }}</td>
                                                <td>{{ $contrat_site->CsNbComdoNu }}</td>
                                            @else
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
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
