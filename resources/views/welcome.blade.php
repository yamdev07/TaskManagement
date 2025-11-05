<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bienvenue - AnyxTech</title> 
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            padding: 1rem;
        }
        .logo {
            width: 20%;
            max-width: 220px;
            min-width: 150px;
            margin-bottom: 2rem;
        }

        h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #38bdf8;
        }
        p {
            font-size: 1.25rem;
            margin-bottom: 3rem;
            color: #94a3b8;
            max-width: 400px;
        }
        .btn {
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            margin: 0 0.5rem;
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
            color: #0f172a;
            background-color: #38bdf8;
            min-width: 140px;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #0ea5e9;
        }
        .btn-secondary {
            background-color: #64748b;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #475569;
        }
        .buttons {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
        @media (max-width: 400px) {
            .buttons {
                flex-direction: column;
            }
            .btn {
                margin: 0.5rem 0;
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <!-- Logo placé ici -->
    <img src="/images/logo-removebg-preview.png" alt="Logo AnyxTech" class="logo" />
    
    <h1>Bienvenue chez AnyxTech</h1>
    <p>Gérez facilement vos clients, paiements et réabonnements avec notre application intuitive.</p>
    
    <div class="buttons">
        <a href="{{ route('login') }}" class="btn">Se connecter</a>
        
    </div>
</body>
</html>
