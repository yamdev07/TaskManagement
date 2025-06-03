<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') - AnyxTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
        }
        .navbar {
            background-color: #1e4d9f;
        }
        .navbar a, .navbar .navbar-brand {
            color: #fff !important;
        }
        .navbar a:hover {
            color: #d4f0ff !important;
        }
        .navbar-brand img {
            height: 40px;
        }
        .navbar-brand span {
            margin-left: 8px;
            font-weight: bold;
            color: #ffffff;
        }
        .nav-link.active {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('clients.index') }}">
                <img src="{{ asset('images/logo-removebg-preview.png') }}" alt="Logo AnyxTech">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav gap-3">
                    <li class="nav-item"><a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.index') ? 'active' : '' }}">Clients</a></li>
                    <li class="nav-item"><a href="{{ route('clients.payes') }}" class="nav-link {{ request()->routeIs('clients.payes') ? 'active' : '' }}">Payés</a></li>
                    <li class="nav-item"><a href="{{ route('clients.nonpayes') }}" class="nav-link {{ request()->routeIs('clients.nonpayes') ? 'active' : '' }}">Non payés</a></li>
                    <li class="nav-item"><a href="{{ route('clients.reabonnement') }}" class="nav-link {{ request()->routeIs('clients.reabonnement') ? 'active' : '' }}">Réabonnement</a></li>
                    <li class="nav-item"><a href="{{ route('clients.depasses') }}" class="nav-link {{ request()->routeIs('clients.depasses') ? 'active' : '' }}">Dépassés</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container mt-4">
        @yield('content')
    </div>
    <x-footer />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
