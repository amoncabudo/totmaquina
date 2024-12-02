<?php
// Verificar si es la primera vez que se visita el sitio o si es un nuevo usuario
$showBanner = !isset($_COOKIE['cookies_accepted']) || 
              (isset($_SESSION['user']) && !isset($_COOKIE['user_' . $_SESSION['user']['id'] . '_accepted']));

if ($showBanner): ?>
    <!-- Banner de cookies -->
    <div id="cookie-banner" class="fixed bottom-0 left-0 right-0 bg-gray-900 text-white p-4 shadow-lg z-50">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex-1">
                <p class="text-sm">
                    Utilizamos cookies propias y de terceros para mejorar nuestros servicios y mostrarle publicidad relacionada con sus preferencias mediante el análisis de sus hábitos de navegación.
                    <a href="/politica-cookies" class="underline hover:text-blue-300">Más información</a>
                </p>
            </div>
            <div class="flex gap-3">
                <button id="reject-cookies" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-sm font-medium transition-colors">
                    Rechazar
                </button>
                <button id="accept-cookies" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-sm font-medium transition-colors">
                    Aceptar
                </button>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('accept-cookies').addEventListener('click', function() {
            setCookie('cookies_accepted', 'true', 365);
            <?php if (isset($_SESSION['user'])): ?>
            setCookie('user_<?= $_SESSION['user']['id'] ?>_accepted', 'true', 365);
            <?php endif; ?>
            hideCookieBanner();
        });

        document.getElementById('reject-cookies').addEventListener('click', function() {
            setCookie('cookies_accepted', 'false', 365);
            <?php if (isset($_SESSION['user'])): ?>
            setCookie('user_<?= $_SESSION['user']['id'] ?>_accepted', 'false', 365);
            <?php endif; ?>
            hideCookieBanner();
        });

        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }

        function hideCookieBanner() {
            document.getElementById('cookie-banner').style.display = 'none';
        }
    </script>
<?php endif; ?> 