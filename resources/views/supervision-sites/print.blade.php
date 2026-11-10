<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Fiche de Supervision</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap" rel="stylesheet">
        <style>

            body {
                font-family: Arial, sans-serif;
                justify-content: center;
                align-items: center;
            }

            .invoice {
                width: 27.5cm;
                padding: 0px;
                box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            }

            header {
                text-align: left;
                margin-bottom: 0px;
            }

            h1 {
                color: #0056b3;
                font-size: 1.8em;
            }

            h4 {
                text-align: center;
                color: #0056b3;
                font-size: 1.1em;
            }

            .table1 {
                width: 100%;
                margin: 0px 0;
            }

            .table1 th, .table1 td {
                padding: 2px;
            }

            .table1 th {
                background-color: #f2f2f2;
            }

            .table2 {
                width: 100%;
                border-collapse: collapse;
                margin: 0;
            }

            .table2 th, .table2 td {
                border: 1px solid #000;
                padding: 5px;
                text-align: left;
            }

            .table2 th {
                background-color: #f2f2f2;
            }

            .pagination {
                margin-top: 20px;
                text-align: center;
                font-size: 0.9em;
                color: #333;
            }

            .pagination span {
                font-weight: bold;
                color: #333;
            }

            .footer {
                margin-top: 30px;
                font-size: 12px;
                text-align: right;
            }
        </style>
    </head>
    <body>
        <div class="invoice">
            <header>
                <table class="table1">
                    <tbody>
                        <tr>
                            <td style="width:40%;font-size:14px;text-align:left;padding:0px;">
                                <img src="{{ storage_path('app/public/img/entites/'.$entite->EnChemLogo) }}" alt="{{ $entite->EnNom }}" width="100px" height="60px">
                                <h2>{{ $entite->EnNom }}</h2>
                                <p>
                                    <u>Tel</u>: {{ $entite->EnTel1 }}
                                    @if($entite->EnTel2)
                                        {{ '/'.$entite->EnTel2 }} <br/>
                                    @endif  <br/>
                                    <u>Email</u>: {{ $entite->EnEmail }}
                                </p>
                            </td>

                            <td style="width:60%"><h4>LISTE DES POINTAGES DE PRÉSENCE DE LA RONDE SUR LES SITES</h4></td>
                        </tr>
                    </tbody>
                </table>
            </header>

            <br>

            <table class="table1">
                <tbody>
                    <tr>
                        <th style="width:20%">N° Fiche :</th>
                        <td style="width:20%">{{ $supervision_site->OsNumero ?? '' }}</td>
                        <th style="width:20%">Ville :</th>
                        <td style="width:20%">{{ $supervision_site->ViNom ?? '' }}</td>
                        <th style="width:20%">Zone :</th>
                        <td style="width:20%">{{ $supervision_site->ZoNom ?? '' }}</td>
                        <th style="width:20%">Date :</th>
                        <td style="width:20%">{{ $supervision_site->OsDate ? date('d/m/Y', strtotime($supervision_site->OsDate)) : '' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <th style="width:20%">Superviseur :</th>
                        <td style="width:20%">{{ trim(($supervision_site->PeNom ?? '') . ' ' . ($supervision_site->PePrenom ?? '')) }}</td>
                        <th style="width:20%">Signature :</th>
                        <td style="width:20%"></td>
                        <th style="width:20%">Faction :</th>
                        @if($supervision_site->OsFaction == 'J')
                            <td style="width:20%">Jour</td>
                        @else
                            <td style="width:20%">Nuit</td>
                        @endif
                    </tr>
                </tbody>
            </table>

            <br/><br/>

            <table class="table2">
                <thead>
                    <tr>
                        <th rowspan="2" style="width:5%; vertical-align: middle;">N°</th>
                        <th rowspan="2" style="width:15%; vertical-align: middle;">Poste/Site</th>
                        <th colspan="2" style="width:30%; text-align: center;">Agent</th>
                        <th colspan="4" style="width:40%; text-align: center;">Superviseur</th>
                        <th rowspan="2" style="width:20%; vertical-align: middle;">Obs Client</th>
                    </tr>
                    <tr>
                        <!-- Sous-colonnes pour Agent -->
                        <th style="width:30%;">Nom</th>
                        <th style="width:20%;">Signat</th>

                        <!-- Sous-colonnes pour Superviseur -->
                        <th style="width:10%;">H.A</th>
                        <th style="width:10%;">H.D</th>
                        <th style="width:10%;">Presentat°</th>
                        <th style="width:20%;">Obs Super</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pointage_agents as $index => $pointage_agent)
                        <tr>
                            <td class="fw-bold">{{ $pointage_agent->SoNumero  }}</td>
                            <td>{{ $pointage_agent->ScNomSite }}</td>

                            <!-- Colonnes Agent divisées -->
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm">
                                        <span class="avatar-initial rounded-circle bg-primary">
                                            {{ substr($pointage_agent->agent_oper_nom, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <strong>{{ $pointage_agent->agent_oper_nom }}</strong><br>
                                        <small>{{ $pointage_agent->agent_oper_prenom }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @if(!empty($pointage_agent->SoSignature))
                                    <span class="badge bg-success" title="Signé le {{ $pointage_agent->SoDateSignature ?? '' }}">
                                        <i class="fas fa-signature"></i> Signé
                                    </span>
                                @else
                                    <span class="badge bg-warning" title="En attente de signature">
                                        <i class="fas fa-clock"></i> En attente
                                    </span>
                                @endif
                            </td>

                            <!-- Colonnes Superviseur divisées en 4 -->
                            <td class="text-center">
                                <span class="badge bg-info">
                                    <i class="fas fa-clock"></i> {{ $pointage_agent->SoHeureArrivAg ?? '--:--' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">
                                    <i class="fas fa-clock"></i> {{ $pointage_agent->SoHeureDepart ?? '--:--' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($pointage_agent->SoPresentation == 'T')
                                    <span class="badge bg-success" title="Très Bonne">
                                        <i class="fas fa-star"></i> T
                                    </span>
                                @elseif($pointage_agent->SoPresentation == 'B')
                                    <span class="badge bg-info" title="Bonne">
                                        <i class="fas fa-star-half-alt"></i> Bonne
                                    </span>
                                @elseif($pointage_agent->SoPresentation == 'P')
                                    <span class="badge bg-warning" title="Passable">
                                        <i class="fas fa-exclamation-triangle"></i> Passable
                                    </span>
                                @elseif($pointage_agent->SoPresentation == 'M')
                                    <span class="badge bg-danger" title="Mauvais">
                                        <i class="fas fa-times"></i> Mauvais
                                    </span>
                                @elseif($pointage_agent->SoPresentation == 'V')
                                    <span class="badge bg-dark" title="Très Mauvais">
                                        <i class="fas fa-skull"></i> Très Mauvais
                                    </span>
                                @else
                                    <span class="badge bg-secondary" title="Non évalué">
                                        <i class="fas fa-question"></i> Non évalué
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($pointage_agent->SoObservSuperv)
                                    <span class="text-truncate d-inline-block" style="max-width: 100px;" title="{{ $pointage_agent->SoObservSuperv }}">
                                        {{ Str::limit($pointage_agent->SoObservSuperv, 20) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <!-- Observation Client -->
                            <td>
                                @if($pointage_agent->SoObservClt)
                                    <span class="text-truncate d-inline-block" style="max-width: 100px;" title="{{ $pointage_agent->SoObservClt }}">
                                        {{ Str::limit($pointage_agent->SoObservClt, 20) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <br><br><br><br>
            <br><br><br><br>

            <table style="width: 100%; margin-top: 30px; border-collapse: collapse;">
                <tr>
                    <td style="width: 33.33%; text-align: center; vertical-align: top;">
                        <div style="font-weight: bold; margin-bottom: 10px;">Visa Directeur</div>
                        <br><br>
                        <div style="font-style: italic; color: #666;">Par. __________________</div>
                    </td>
                    <td style="width: 33.33%; text-align: center; vertical-align: top;">
                        <div style="font-weight: bold; margin-bottom: 10px;">Visa Operations</div>
                         <br><br>
                        <div style="font-style: italic; color: #666;">Par. __________________</div>
                    </td>
                    <td style="width: 33.34%; text-align: center; vertical-align: top;">
                        <div style="font-weight: bold; margin-bottom: 10px;">Visa Contrôles</div>
                         <br><br>
                        <div style="font-style: italic; color: #666;">Par. __________________</div>
                    </td>
                </tr>
            </table>

            <div class="footer">
                Document généré le <?php echo date('d/m/Y'); ?>
            </div>

            <!-- Affichage de la pagination avec numéro de page -->
            <div class="pagination">
                Page <span>{{ $pointage_agents->currentPage() }}</span> / <span>{{ $pointage_agents->lastPage() }}</span>
            </div>

        </div>
    </body>
</html>
