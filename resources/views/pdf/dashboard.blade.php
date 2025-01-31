<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ public_path('assets/bootstrap/css/bootstrap.min.css')}}">
    <title>Tableau de bord Telecom {{ $annee }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: black;
        }
        /* Style pour centrer le titre */
        .title-container {
            text-align: center;
        }
        .title-container h1 {
            font-size: 20px;
            color: #0056b3;
            margin: 0;
        }
        /* Style pour positionner le logo à droite */
        .logo-container {
            text-align: right;
        }
        /* Reste des styles inchangé */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            font-size: 13px;
        }
        table th {
            background-color: #f4f4f4;
            color: #333;
            font-size: 13px;
        }
        .footer {
            text-align: right;
            font-size: 13px;
            color: darkcyan;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="logo-container">
                    <img src="{{ public_path('assets/img/COLAS.png') }}" class="logo" width="201" height="63" alt="Logo">
                </div>
            </div>
            <div class="col-8">
                <div class="title-container">
                    <h1>Tableau de bord Telecom {{ $annee }}</h1>
                    <p>Résumé des chiffres par type de ligne</p>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Type ligne</th>
                    @foreach($moisFrancais as $mois)
                        <th>{{ $mois }}</th>
                    @endforeach
                    <th>Total Année</th>
                </tr>
            </thead>                
            <tbody>
                @foreach($data as $type => $values)
                    <tr>
                        <td>{{ $type }}</td>
                        @foreach(range(1, 12) as $mois)
                            <td>{{ number_format($values[$mois]['total_prix_forfait_ht'] ?? 0, 2, ',', ' ') }}</td>
                        @endforeach
                        <td>{{ number_format($values['total_annuel'] ?? 0, 2, ',', ' ') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            Généré le {{ now()->format('d/m/Y') }}
        </div>
    </div>        
</body>
</html>