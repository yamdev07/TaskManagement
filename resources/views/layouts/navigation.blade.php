<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20"> <!-- Augmentation de la hauteur -->
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <img src="{{ asset('images/logo-removebg-preview.png') }}" alt="Logo AnyxTech" class="h-12"> <!-- Logo légèrement plus grand -->                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ml-10 sm:flex"> <!-- Espacement augmenté -->
                    <x-nav-link :href="route('clients.index')" :active="request()->routeIs('clients.index')" class="text-lg"> <!-- Taille de texte augmentée -->
                        <i class="fas fa-users mr-2 text-lg"></i> Tous
                    </x-nav-link>
                    <x-nav-link :href="route('clients.payes')" :active="request()->routeIs('clients.payes')" class="text-lg text-success-600">
                        <i class="fas fa-check-circle mr-2 text-lg"></i> Payés
                    </x-nav-link>
                    <x-nav-link :href="route('clients.nonpayes')" :active="request()->routeIs('clients.nonpayes')" class="text-lg text-danger-600">
                        <i class="fas fa-exclamation-circle mr-2 text-lg"></i> Non Payés
                    </x-nav-link>
                    <x-nav-link :href="route('clients.actifs')" :active="request()->routeIs('clients.actifs')" class="text-lg text-anyxtech">
                        <i class="fas fa-wifi mr-2 text-lg"></i> Actifs
                    </x-nav-link>
                    <x-nav-link :href="route('clients.suspendus')" :active="request()->routeIs('clients.suspendus')" class="text-lg text-warning-600">
                        <i class="fas fa-pause-circle mr-2 text-lg"></i> Suspendus
                    </x-nav-link>
                    <x-nav-link :href="route('clients.reabonnement')" :active="request()->routeIs('clients.reabonnement')" class="text-lg text-info-600">
                        <i class="fas fa-calendar-alt mr-2 text-lg"></i> À venir
                    </x-nav-link>
                    <x-nav-link :href="route('clients.depasses')" :active="request()->routeIs('clients.depasses')" class="text-lg text-danger-600">
                        <i class="fas fa-exclamation-triangle mr-2 text-lg"></i> Dépassés
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-3 border border-transparent text-lg leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-anyxtech focus:outline-none transition ease-in-out duration-150"> <!-- Taille augmentée -->
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-anyxtech-light flex items-center justify-center mr-3"> <!-- Avatar plus grand -->
                                    <i class="fas fa-user text-anyxtech text-xl"></i> <!-- Icône plus grande -->
                                </div>
                                <span class="text-lg">{{ Auth::user()->name }}</span> <!-- Texte plus grand -->
                            </div>
                            <div class="ms-2"> <!-- Marge augmentée -->
                                <svg class="fill-current h-5 w-5 text-anyxtech" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"> <!-- Icône plus grande -->
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content" class="text-lg"> <!-- Contenu plus grand -->
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center text-lg"> <!-- Taille augmentée -->
                            <i class="fas fa-user-cog mr-3 text-anyxtech text-xl"></i> {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 text-lg text-gray-700 hover:bg-gray-100 flex items-center"> <!-- Taille augmentée -->
                                <i class="fas fa-sign-out-alt mr-3 text-danger-600 text-xl"></i> {{ __('Log Out') }}
                            </button>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-3 rounded-md text-gray-400 hover:text-anyxtech hover:bg-anyxtech-light focus:outline-none focus:bg-anyxtech-light focus:text-anyxtech transition duration-150 ease-in-out"> <!-- Taille augmentée -->
                    <svg class="h-8 w-8" stroke="currentColor" fill="none" viewBox="0 0 24 24"> <!-- Icône plus grande -->
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white">
        <div class="pt-2 pb-3 space-y-2">
            <x-responsive-nav-link :href="route('clients.index')" :active="request()->routeIs('clients.index')" class="text-xl px-6 py-4"> <!-- Taille et padding augmentés -->
                <i class="fas fa-users mr-3 text-xl"></i> Tous les clients
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('clients.payes')" :active="request()->routeIs('clients.payes')" class="text-xl px-6 py-4">
                <i class="fas fa-check-circle mr-3 text-xl text-success-600"></i> Clients Payés
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('clients.nonpayes')" :active="request()->routeIs('clients.nonpayes')" class="text-xl px-6 py-4">
                <i class="fas fa-exclamation-circle mr-3 text-xl text-danger-600"></i> Non Payés
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('clients.actifs')" :active="request()->routeIs('clients.actifs')" class="text-xl px-6 py-4">
                <i class="fas fa-wifi mr-3 text-xl text-anyxtech"></i> Actifs
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('clients.suspendus')" :active="request()->routeIs('clients.suspendus')" class="text-xl px-6 py-4">
                <i class="fas fa-pause-circle mr-3 text-xl text-warning-600"></i> Suspendus
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('clients.reabonnement')" :active="request()->routeIs('clients.reabonnement')" class="text-xl px-6 py-4">
                <i class="fas fa-calendar-alt mr-3 text-xl text-info-600"></i> Réabonnement à venir
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('clients.depasses')" :active="request()->routeIs('clients.depasses')" class="text-xl px-6 py-4">
                <i class="fas fa-exclamation-triangle mr-3 text-xl text-danger-600"></i> Réabonnement dépassé
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-6 py-4"> <!-- Padding augmenté -->
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-anyxtech-light flex items-center justify-center mr-4"> <!-- Avatar plus grand -->
                        <i class="fas fa-user text-anyxtech text-2xl"></i> <!-- Icône plus grande -->
                    </div>
                    <div>
                        <div class="font-medium text-xl text-gray-800">{{ Auth::user()->name }}</div> <!-- Texte plus grand -->
                        <div class="font-medium text-lg text-gray-500">{{ Auth::user()->email }}</div> <!-- Texte plus grand -->
                    </div>
                </div>
            </div>

            <div class="mt-3 space-y-2">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-xl px-6 py-4"> <!-- Taille et padding augmentés -->
                    <i class="fas fa-user-cog mr-3 text-2xl text-anyxtech"></i> {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="text-xl px-6 py-4"> <!-- Taille et padding augmentés -->
                        <i class="fas fa-sign-out-alt mr-3 text-2xl text-danger-600"></i> {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<style>
    :root {
        --anyxtech-primary: #1e3a8a;
        --anyxtech-secondary: #3b82f6;
        --anyxtech-light: #e0f2fe;
    }

    .text-anyxtech {
        color: var(--anyxtech-primary);
    }

    .bg-anyxtech-light {
        background-color: var(--anyxtech-light);
    }

    .text-success-600 {
        color: #16a34a;
    }

    .text-danger-600 {
        color: #dc2626;
    }

    .text-warning-600 {
        color: #d97706;
    }

    .text-info-600 {
        color: #0891b2;
    }

    .nav-link {
        transition: all 0.3s ease;
        border-radius: 0.5rem; /* Bord plus arrondi */
        padding: 0.75rem 1.25rem; /* Padding augmenté */
        font-size: 1.125rem; /* Taille de texte de base augmentée */
    }

    .nav-link:hover {
        background-color: var(--anyxtech-light);
        color: var(--anyxtech-primary);
        transform: translateY(-2px); /* Effet de levage */
    }

    .nav-link.active {
        background-color: var(--anyxtech-primary);
        color: white;
        font-weight: 600;
    }

    .nav-link.active i {
        color: white;
    }

    .dropdown-link {
        padding: 0.75rem 1.25rem; /* Padding augmenté */
        font-size: 1.125rem; /* Taille de texte augmentée */
    }

    @media (max-width: 640px) {
        .responsive-nav-link {
            padding: 1rem 1.5rem; /* Padding mobile augmenté */
            border-left: 5px solid transparent; /* Bordure plus épaisse */
            font-size: 1.25rem; /* Taille de texte mobile augmentée */
        }

        .responsive-nav-link.active {
            border-left-color: var(--anyxtech-primary);
            background-color: var(--anyxtech-light);
            font-weight: 600;
        }

        .responsive-nav-link i {
            font-size: 1.5rem; /* Icônes mobiles plus grandes */
            width: 2rem; /* Largeur fixe pour l'alignement */
            text-align: center;
        }
    }
</style>