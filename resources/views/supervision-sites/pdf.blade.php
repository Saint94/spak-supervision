<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Liste des Rondes</title>
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
                margin-bottom: 20px;
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
                padding: 15px;
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
                text-align: center;
                font-size: 0.9em;
                color: #666;
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

                            <td style="width:60%"><h4>LISTE DES RONDES DE SUPERVISION DU {{ date('d/m/Y',strtotime($start_date)) }} AU {{ date('d/m/Y',strtotime($end_date)) }}</h4></td>
                        </tr>
                    </tbody>
                </table>
            </header>
        </div>

        <br/><br/>

        <div class="invoice">
            <table class="table2">
                <thead>
                    <tr>
                        <th style="width:20%">Superviseur</th>
                        <th style="width:10%">Date</th>
                        <th style="width:10%">Faction</th>
                        <th style="width:10%">Heure début</th>
                        <th style="width:10%">Heure Fin</th>
                        <th style="width:10%">Ville</th>
                        <th style="width:10%">Zone</th>
                        <th style="width:10%">Statut</th>
                        <th style="width:15%">Obs.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($supervision_sites as $supervision_site)
                        <tr>
                            <td>{{ $supervision_site->PeNom.' '.$supervision_site->PePrenom }}</td>
                            <td>{{ date('d/m/Y',strtotime($supervision_site->OsDate)) }}</td>

                            @if($supervision_site->OsFaction == 'J')
                                <td>Jour</td>
                            @else
                                <td>Nuit</td>
                            @endif

                            <td>{{ date('H:i',strtotime($supervision_site->OsHeureDebut)) }}</td>
                            <td>{{ $supervision_site->OsHeureFin == NULL ? '' : date('H:i',strtotime($supervision_site->OsHeureFin)) }}</td>
                            <td>{{ $supervision_site->ViNom }}</td>
                            <td>{{ $supervision_site->ZoNom }}</td>

                            @if($supervision_site->OsStatut == 'I')
                                <td>Initialisé</td>
                            @elseif($supervision_site->OsStatut == 'S')
                                <td>Saisie</td>
                            @elseif($supervision_site->OsStatut == 'T')
                                <td>Traité</td>
                            @elseif($supervision_site->OsStatut == 'V')
                                <td>Validée</td>
                            @else
                                <td></td>
                            @endif

                            <td>{{ $supervision_site->OsDetails }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="footer">
                Document généré le <?php echo date('d/m/Y'); ?>
            </div>

            <!-- Affichage de la pagination avec numéro de page -->
            <div class="pagination">
                Page <span>{{ $supervision_sites->currentPage() }}</span> / <span>{{ $supervision_sites->lastPage() }}</span>
            </div>

        </div>
    </body>
</html>
