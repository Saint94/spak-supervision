<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Liste Sites Supervisés</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap" rel="stylesheet">
        <style>

            body {
                font-family: Arial, sans-serif;
                justify-content: center;
                align-items: center;
            }

            .invoice {
                width: 19cm;
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

                            <td style="width:60%"><h4>LISTE DES SITES SUPERVISÉS</h4></td>
                        </tr>
                    </tbody>
                </table>
            </header>

            <br>


            <table class="table2">
                <thead>
                    <tr>
                        <th style="width:15%" >Superviseur</th>
                        <th style="width:15%">Site client</th>
                        <th style="width:15%">Zone</th>
                        <th style="width:15%">Ville</th>
                        <th style="width:15%">Date Début</th>
                        <th style="width:15%">Date Fin</th>
                        <th style="width:15%">Faction</th>
                        <th style="width:15%">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sites_superviseur as $site_superviseur)
                        <tr>
                            <td>{{ $site_superviseur->PeNom.' '.$site_superviseur->PePrenom }}</td>

                            @if($site_superviseur->SsMode == 'S')
                                <td>{{ $site_superviseur->ScNomSite }}</td>
                                <td></td>
                            @else
                                <td></td>
                                <td>{{ $site_superviseur->ZoNom }}</td>
                            @endif


                            <td>{{ $site_superviseur->ViCode }}</td>

                            @if($site_superviseur->SsDateDebut != null)
                                <td>{{ date('d/m/Y',strtotime($site_superviseur->SsDateDebut 	)) }}</td>
                            @else
                                <td></td>
                            @endif

                            @if($site_superviseur->SsDateFin != null)
                                <td>{{ date('d/m/Y',strtotime($site_superviseur->SsDateFin)) }}</td>
                            @else
                                <td></td>
                            @endif

                            @if($site_superviseur->SsFaction == 'J')
                                <td>Jour</td>
                            @else
                                <td>Nuit</td>
                            @endif

                            @if($site_superviseur->SsIsOn == 'Y')
                                <td><span class="badge light badge-success">Actif</span></td>
                            @else
                                <td><span class="badge light badge-danger">Inactif</span></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="footer">
                Document généré le <?php echo date('d/m/Y'); ?>
            </div>

            <!-- Affichage de la pagination avec numéro de page -->
            <div class="pagination">
                Page <span>{{ $sites_superviseur->currentPage() }}</span> / <span>{{ $sites_superviseur->lastPage() }}</span>
            </div>

        </div>
    </body>
</html>
