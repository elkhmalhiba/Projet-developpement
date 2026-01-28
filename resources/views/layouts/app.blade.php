<!DOCTYPE html>
<html lang="fr" data-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'DataCenter Pro')</title>

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- CSS Commun -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container nav-content">
            <a href="/" class="logo">
                <i class='bx bxs-bolt-circle'></i>
                DATA<span>PRO</span>
            </a>

            <div class="nav-right">
                <!-- Theme Toggle -->
                <button class="btn btn-icon" id="themeBtn">
                    <i class='bx bx-moon'></i>
                </button>

                <!-- Auth Links -->
                <a href="{{ route('welcome') }}" class="nav-link"><i class='bx bx-home'></i>
                    Accueil</a>
                <a href="{{ route('resources.index') }}" class="nav-link">
                    <i class='bx bx-server'></i> Ressources
                </a>
                @guest
                    <a href="{{ route('login') }}" class="nav-link"><i class='bx bx-log-in'></i>
                        Connexion</a>
                    <a href="{{ route('register') }}" class="btn btn-primary"><i class='bx bx-play'></i>
                        Démarrer</a>
                @else
                    <div class="d-flex align-center gap-2">
                        @if(auth()->user()->role_id == 3) <!-- Utilisateur Interne -->
                            <a href="{{ route('user.dashboard') }}" class="nav-link"><i class='bx bx-grid-alt'></i>
                                Dashboard</a>
                            <a href="{{ route('user.historique') }}" class="nav-link"><i class='bx bx-history'></i>
                                Historique</a>

                        @elseif(auth()->user()->role_id == 2) <!-- Responsable Technique -->
                            <a href="{{ route('tech.dashboard') }}" class="nav-link"><i class='bx bx-grid-alt'></i>
                                Dashboard</a>
                        @elseif(auth()->user()->role_id == 1) <!-- Admin -->
                            <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class='bx bx-grid-alt'></i>
                                Dashboard</a>
                        @endif
                        <div class="notif-container" id="notifContainer">
                            <div class="notif-trigger" id="notifBtn">
                                <i class='bx bxs-bell'></i>
                                @if(auth()->user()->notifications()->where('is_read', false)->count() > 0)
                                    <span class="badge-dot" id="notifBadge"></span>
                                @endif
                            </div>
                            <div class="notif-dropdown" id="notifDropdown">
                                <div class="notif-header">NOTIFICATIONS RÉCENTES</div>
                                <div id="notifList">
                                    @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notif)
                                        <div class="notif-item"
                                            >
                                            <strong>{{ $notif->title }}</strong>
                                            <p>{{ $notif->message }}</p>
                                            <small
                                                style="display:block; margin-top:5px; opacity:0.6; font-size:10px;">{{ $notif->created_at->diffForHumans() }}</small>
                                        </div>
                                    @empty
                                        <div class="notif-item" style="text-align: center;">Aucune notification</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <span class="text-muted small">
                            {{ Auth::user()->name }}
                        </span>
                        <form action="{{ route('logout') }}" method="POST" class="mb-0">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-secondary">
                                <i class='bx bx-log-out'></i> Sortir
                            </button>
                        </form>
                    </div>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main style="margin-top: 80px; min-height: calc(100vh - 120px);">
        @if(session('success'))
            <div class="container">
                <div class="alert alert-success fade-in">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container">
                <div class="alert alert-danger fade-in">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer style="padding: 2rem 0; border-top: 1px solid var(--border);">
        <div class="container text-center">
            <p class="text-muted">© 2026 DataCenter Pro • Infrastructure de Pointe</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Theme Toggle
        const themeBtn = document.getElementById('themeBtn');
        const html = document.documentElement;

        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'dark';
        html.setAttribute('data-theme', savedTheme);
        updateThemeIcon(savedTheme);

        themeBtn.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });

        function updateThemeIcon(theme) {
            themeBtn.innerHTML = theme === 'dark'
                ? "<i class='bx bx-moon'></i>"
                : "<i class='bx bx-sun'></i>";
        }

        const notifBtn = document.getElementById('notifBtn');
        const notifDropdown = document.getElementById('notifDropdown');
        const badge = document.getElementById('notifBadge');

        notifBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            notifDropdown.classList.toggle('active');

            if (notifDropdown.classList.contains('active') && badge) {
                fetch("{{ route('notifications.markRead') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) badge.style.display = 'none';
                    })
                    .catch(error => console.error('Error:', error));
            }
        });

        document.addEventListener('click', (e) => {
            if (!notifDropdown.contains(e.target) && e.target !== notifBtn) {
                notifDropdown.classList.remove('active');
            }
        });

    </script>

    @stack('scripts')
</body>

</html>