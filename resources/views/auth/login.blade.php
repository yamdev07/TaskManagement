<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Connexion - AnyxTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-card {
            background-color: #1e293b;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            max-width: 400px;
            width: 100%;
        }
        h2 {
            color: #38bdf8;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1.25rem;
            border-radius: 0.5rem;
            border: none;
            background-color: #334155;
            color: white;
        }
        input::placeholder {
            color: #cbd5e1;
        }
        .btn {
            width: 100%;
            padding: 0.75rem;
            font-weight: bold;
            background-color: #38bdf8;
            border: none;
            border-radius: 0.5rem;
            color: #0f172a;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0ea5e9;
        }
        .links {
            margin-top: 1rem;
            text-align: center;
        }
        .links a {
            color: #94a3b8;
            font-size: 0.9rem;
            text-decoration: none;
        }
        .links a:hover {
            text-decoration: underline;
        }
        .error-message {
            background-color: #f87171;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            color: white;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Connexion - AnyxTech</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            @if ($errors->any())
                <div class="error-message">
                    <ul style="margin: 0; padding-left: 1.2rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <label for="email">Adresse e-mail</label>
            <input id="email" type="email" name="email" placeholder="you@example.com" value="{{ old('email') }}" required autofocus />

            <label for="password">Mot de passe</label>
            <input id="password" type="password" name="password" placeholder="********" required />

            <button type="submit" class="btn">Se connecter</button>
        </form>

        <div class="links">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Mot de passe oublié ?</a>
            @endif
        </div>
    </div>
</body>
</html>
