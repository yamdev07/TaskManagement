<!-- resources/views/components/footer.blade.php -->
<footer class="bg-[var(--anyxtech-three-blue)] text-gray-300 mt-20 py-10 px-10">
    <div class="max-w-7xl mx-auto grid md:grid-cols-3 gap-10">

        <!-- Présentation -->
        <div>
            <img src="{{ asset('images/logo-removebg-preview.png') }}" alt="AnyxTech Logo" class="mb-4 w-36" />
            <p class="text-sm leading-relaxed">
                Nous vous accompagnons dans votre stratégie numérique, marketing digital et solutions informatiques innovantes.
            </p>
            <div class="flex space-x-4 mt-4 text-xl text-[#1db9ff]">
                <a href="https://www.facebook.com/AnyxTechBenin" target="_blank" aria-label="Facebook">
                    <i class="fab fa-facebook-f hover:text-white"></i>
                </a>
                <a href="tel:+22952415241" aria-label="Téléphone">
                    <i class="fas fa-phone hover:text-white"></i>
                </a>
            </div>
        </div>

        <!-- Navigation -->
        <div>
            <h3 class="text-white font-semibold text-lg mb-4">Navigation</h3>
            <ul class="space-y-2 text-sm">
                <li><a href="/about" class="hover:text-white">À propos</a></li>
                <li><a href="/services" class="hover:text-white">Nos Services</a></li>
                <li><a href="/projects" class="hover:text-white">Réalisations</a></li>
                <li><a href="/contact" class="hover:text-white">Nous contacter</a></li>
            </ul>
        </div>

        <!-- Contact -->
        <div>
            <h3 class="text-white font-semibold text-lg mb-4">Contact</h3>
            <ul class="space-y-3 text-sm">
                <li class="flex items-start">
                    <i class="fas fa-map-marker-alt mr-3 mt-1 text-[#1db9ff]"></i>
                    Cotonou - Bénin, St Michel, en face de l’immeuble Nasuba.
                </li>
                <li class="flex items-center">
                    <i class="fas fa-envelope mr-3 text-[#1db9ff]"></i>
                    contact@anyxtech.com
                </li>
                <li class="flex items-center">
                    <i class="fas fa-phone mr-3 text-[#1db9ff]"></i>
                    <a href="tel:+22952415241" class="hover:text-white">(+229) 52 41 52 41</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="border-t border-gray-600 mt-10 pt-4 text-center text-sm text-gray-400">
        © 2025 AnyxTech Bénin. Tous droits réservés.
    </div>
</footer>
