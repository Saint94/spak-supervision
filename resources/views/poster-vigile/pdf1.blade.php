<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Liste des Postings des Vigiles</title>
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
                page-break-inside: avoid;
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
                border-collapse: collapse;
                margin: 0px 0;
            }

            .table1 th, .table1 td {
                border: 1px solid #000;
                padding: 5px;
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
                            <td style="width:25%"><img src="{{ storage_path('app/public/img/entites/'.$entite->EnChemLogo) }}" alt="{{ $entite->EnNom }}" width="100px" height="60px"></td>
                            <td style="width:50%"><h4>LISTE DES POSTINGS DES VIGILES</h4></td>
                            <td style="width:25%;font-size:14px;text-align:center;padding:0px;">
                                <p>
                                    <b>{{ $entite->EnNom }}</b> <br/>
                                    <u>Tel</u>: {{ $entite->EnTel1 }}
                                    @if($entite->EnTel2)
                                        {{ '/'.$entite->EnTel2 }} <br/>
                                    @endif  <br/>
                                    <u>Email</u>: {{ $entite->EnEmail }}
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </header>

            <table class="table1">
                <tbody>
                    <tr>
                        <th>Client</th>
                        <td>PERENCO</td>
                        <th>Laboratoire</th>
                        <td>AMTECH EXPERTISES</td>
                    </tr>
                    <tr>
                        <th>Contact</th>
                        <td>Mr MAKOSSO Romeo</td>
                        <th>D. Gérant</th>
                        <td>Mr. Bruno MUBAMBA</td>
                    </tr>
                    <tr>
                        <th>Téléphone</th>
                        <td>066017176</td>
                        <th>Téléphone</th>
                        <td>+242 05 540 62 80</td>
                    </tr>
                    <tr>
                        <th>E-mail</th>
                        <td><u>romeo.makossozahou@perenco.com</u></td>
                        <th>E-mail</th>
                        <td><u>direction@amtechexpertises.com</u></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <br/><br/>


        <div class="invoice">

            <table class="table2">

                <thead>
                    <tr>
                        <th style="width:20%">Site client</th>
                        <th style="width:20%">Vigile</th>
                        <th style="width:15%">Date Début</th>
                        <th style="width:15%">Date Fin</th>
                        <th style="width:15%">Profil</th>
                        <th style="width:15%">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($poster_vigiles as $poster_vigile)
                        <tr>
                            <td>{{ $poster_vigile->ScNomSite }}</td>
                            <td>{{ $poster_vigile->PeNom.' '.$poster_vigile->PePrenom }}</td>

                            @if($poster_vigile->PvDateDebut != null)
                                <td>{{ date('d/m/Y',strtotime($poster_vigile->PvDateDebut 	)) }}</td>
                            @else
                                <td></td>
                            @endif

                            @if($poster_vigile->PvDateFin != null)
                                <td>{{ date('d/m/Y',strtotime($poster_vigile->PvDateFin)) }}</td>
                            @else
                                <td></td>
                            @endif

                            @if($poster_vigile->PvStatut == 'F')
                                <td>Agent Fixe</td>
                            @elseif($poster_vigile->PvStatut == 'R')
                                <td>Rondier</td>
                            @elseif($poster_vigile->PvStatut == 'B')
                                <td>Stand Bye</td>
                            @elseif($poster_vigile->PvStatut == 'E')
                                <td>Chef d'Equipe</td>
                            @elseif($poster_vigile->PvStatut == 'C')
                                <td>Maître Chien</td>
                            @elseif($poster_vigile->PeStatut == 'SG')
                                <td>Superv. Général</td>
                            @elseif($poster_vigile->PeStatut == 'SA')
                                <td>Superv. Adjoint</td>
                            @else
                                <td></td>
                            @endif

                            @if($poster_vigile->PvIsOn == 'Y')
                                <td>Actif</td>
                            @else
                                <td>Inactif</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <br><br>

        </div>
    </body>
</html>
