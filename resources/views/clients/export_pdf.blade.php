<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Clients Actifs</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px 10px;
            text-align: left;
        }
        th {
            background-color: #4361ee;
            color: white;
        }
        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Liste des Clients Actifs</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Contact</th>
                <th>Site</th>
                <th>Catégorie</th>
                <th>Date Réabonnement</th>
                <th>Montant (F)</th>
                <th>Statut Paiement</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
                <tr>
                    <td>{{ $client->id }}</td>
                    <td>{{ $client->nom_client }}</td>
                    <td>{{ $client->contact }}</td>
                    <td>{{ $client->sites_relais ?? '-' }}</td>
                    <td>{{ $client->categorie ?? '-' }}</td>
                    <td>{{ $client->date_reabonnement ? \Carbon\Carbon::parse($client->date_reabonnement)->format('d/m/Y') : '-' }}</td>
                    <td>{{ number_format($client->montant, 0, ',', ' ') }}</td>
                    <td>{{ $client->a_paye ? 'Payé' : 'Non payé' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Généré le {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>
