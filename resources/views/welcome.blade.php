@extends('layouts.app')

@section('title', 'DataCenter Pro | Infrastructure Cloud')

@push('styles')
<style>
    /* ============================================
       WELCOME PAGE SPECIFIC STYLES
    ============================================ */

    /* Hero Section */
    .hero {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        background: url('https://images.unsplash.com/photo-1551434678-e076c223a692?q=80&w=2000') center/cover no-repeat;
        margin-bottom: 100px;
        padding-top: 80px;
        position: relative;
        min-height: 60vh;
    }

    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, rgba(2, 6, 23, 0.7) 0%, var(--bg-body) 100%);
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 800px;
        padding: 2rem;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 900;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: -1px;
        margin-bottom: 1rem;
        line-height: 1.1;
    }

    .hero-subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.2rem;
        max-width: 600px;
        margin: 0 auto 2rem;
    }

    /* ============================================
       CATEGORY GRID FOR GUESTS
    ============================================ */
    .category-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin: 3rem 0;
    }

    .cat-card {
        background: var(--bg-card);
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid var(--border);
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: var(--shadow);
    }

    .cat-card:hover {
        transform: translateY(-10px);
        border-color: var(--primary);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
    }

    .cat-img-box {
        height: 180px;
        width: 100%;
        overflow: hidden;
        position: relative;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
    }

    .cat-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
        display: block;
    }

    .cat-card:hover .cat-img-box img {
        transform: scale(1.05);
    }

    .cat-body {
        padding: 1.5rem;
        text-align: center;
    }

    .cat-body i {
        font-size: 2.2rem;
        color: var(--primary);
        margin-bottom: 12px;
        display: block;
    }

    .cat-body h3 {
        font-size: 1.2rem;
        margin-bottom: 5px;
        color: var(--text-main);
    }

    .cat-body p {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .resource-count {
        background: var(--primary);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* ============================================
       ACTION CARDS
    ============================================ */
    .action-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin: 3rem 0;
    }

    .action-card {
        background: var(--bg-card);
        border-radius: 16px;
        padding: 2.5rem 2rem;
        border: 1px solid var(--border);
        text-align: center;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .action-card:hover {
        transform: translateY(-5px);
        border-color: var(--primary);
        box-shadow: var(--shadow);
        text-decoration: none;
        color: inherit;
    }

    .action-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--success));
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 1.8rem;
        color: white;
    }

    .action-card h3 {
        font-size: 1.4rem;
        margin-bottom: 1rem;
        color: var(--text-main);
    }

    .action-card p {
        color: var(--text-muted);
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    /* ============================================
       QUICK STATS FOR LOGGED USERS
    ============================================ */
    .quick-stats {
        text-align: center;
        margin: 3rem 0;
    }

    .quick-stats h3 {
        margin-bottom: 2rem;
        font-size: 1.8rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .stat-card {
        background: var(--bg-card);
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid var(--border);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        border-color: var(--primary);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .stat-label {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    /* ============================================
       WELCOME MESSAGE
    ============================================ */
    .welcome-box {
        background: var(--bg-card);
        border-radius: 16px;
        padding: 2rem;
        border: 1px solid var(--border);
        margin-bottom: 3rem;
        text-align: center;
    }

    .welcome-icon {
        font-size: 3rem;
        color: var(--primary);
        margin-bottom: 1rem;
    }

    /* ============================================
       RESOURCES TABLE SECTION
    ============================================ */
    .resources-section {
        background: var(--bg-card);
        border-radius: 24px;
        padding: 40px;
        margin: 60px 0;
        border: 1px solid var(--border);
        display: none;
        animation: fadeInUp 0.6s ease;
    }

    #table-title {
        font-size: 1.4rem;
        margin-bottom: 30px;
        border-left: 5px solid var(--primary);
        padding-left: 15px;
    }

    .resource-row {
        display: none;
    }

    .resource-row.visible {
        display: table-row;
    }

    /* Status Badges */
    .status-badge {
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-block;
    }

    .status-available {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .status-occupied {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .status-maintenance {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    /* ============================================
       FEATURES SECTION FOR GUESTS
    ============================================ */
    .features-section {
        text-align: center;
        margin: 4rem 0;
    }

    .features-section h3 {
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 1rem;
        margin-top: 2rem;
    }

    .feature-item {
        text-align: center;
    }

    .feature-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        background: var(--primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
    }

    /* ============================================
       ANIMATIONS
    ============================================ */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-content">
            @auth
                <h1 class="hero-title">Bienvenue, {{ Auth::user()->name }}</h1>
                <p class="hero-subtitle">
                    @if(Auth::user()->role_id == 1)
                        Administrateur de la plateforme DataCenter Pro
                    @elseif(Auth::user()->role_id == 2)
                        Responsable technique - Supervision des ressources
                    @else
                        Gestion de vos ressources cloud en toute simplicité
                    @endif
                </p>
            @else
                <h1 class="hero-title">Infrastructure <span>Cloud Future</span></h1>
                <p class="hero-subtitle">Réservez des ressources haute performance en un clic</p>
            @endauth
            
            @auth
                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    @if(Auth::user()->role_id == 1)
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary" style="padding: 1rem 2rem;">
                            <i class='bx bx-cog'></i> Tableau de bord Admin
                        </a>
                    @elseif(Auth::user()->role_id == 2)
                        <a href="{{ route('tech.dashboard') }}" class="btn btn-primary" style="padding: 1rem 2rem;">
                            <i class='bx bx-wrench'></i> Dashboard Technique
                        </a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="btn btn-primary" style="padding: 1rem 2rem;">
                            <i class='bx bx-grid-alt'></i> Mon Dashboard
                        </a>
                    @endif
                    
                    <a href="{{ route('resources.index') }}" class="btn btn-secondary" style="padding: 1rem 2rem;">
                        <i class='bx bx-server'></i> Catalogue complet
                    </a>
                </div>
            @else
                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 1rem 2rem;">
                        <i class='bx bx-log-in'></i> Se connecter
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-secondary" style="padding: 1rem 2rem;">
                        <i class='bx bx-user-plus'></i> Créer un compte
                    </a>
                </div>
            @endauth
        </div>
    </div>

    <div class="container">
        <!-- ============================================
           SECTION POUR LES UTILISATEURS CONNECTÉS
        ============================================ -->
        @auth
            <!-- Welcome Message -->
            <div class="welcome-box">
                <div class="welcome-icon">
                    <i class='bx bxs-bolt-circle'></i>
                </div>
                <h2 style="margin-bottom: 1rem;">
                    @if(Auth::user()->role_id == 1)
                        Gestion complète de la plateforme
                    @elseif(Auth::user()->role_id == 2)
                        Supervision des ressources techniques
                    @else
                        Gestion de vos ressources cloud
                    @endif
                </h2>
                <p class="text-muted">
                    @if(Auth::user()->role_id == 1)
                        Vous avez accès à tous les outils d'administration pour gérer utilisateurs, ressources et configurations système.
                    @elseif(Auth::user()->role_id == 2)
                        Supervisez les réservations, gérez les ressources techniques et assurez le bon fonctionnement de l'infrastructure.
                    @else
                        Réservez, gérez et surveillez vos ressources cloud en temps réel.
                    @endif
                </p>
            </div>

            <!-- Quick Stats -->
            <div class="quick-stats">
                <h3>Vue rapide</h3>
                <div class="stats-grid">
                    @if(Auth::user()->role_id == 3) <!-- Utilisateur Interne -->
                        <div class="stat-card">
                            <div class="stat-number" style="color: var(--primary);">
                                {{ Auth::user()->reservations()->count() }}
                            </div>
                            <div class="stat-label">Réservations totales</div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-number" style="color: var(--success);">
                                {{ Auth::user()->reservations()->where('status', 'VALIDÉE')->count() }}
                            </div>
                            <div class="stat-label">Réservations actives</div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-number" style="color: var(--warning);">
                                {{ Auth::user()->reservations()->where('status', 'EN ATTENTE')->count() }}
                            </div>
                            <div class="stat-label">En attente</div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-number" style="color: var(--danger);">
                                {{ Auth::user()->reservations()->where('status', 'REFUSÉE')->count() }}
                            </div>
                            <div class="stat-label">Refusées</div>
                        </div>
                        
                  @elseif(Auth::user()->role_id == 2) <!-- Responsable Technique -->
                    <div class="stat-card">
                        <div class="stat-number" style="color: var(--primary);">
                            {{ \App\Models\Resource::count() }}
                        </div>
                        <div class="stat-label">Ressources totales</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-number" style="color: var(--warning);">
                            {{ \App\Models\Reservation::where('status', 'EN ATTENTE')->count() }}
                        </div>
                        <div class="stat-label">Demandes en attente</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-number" style="color: var(--danger);">
                            {{ \App\Models\Incident::where('status', 'ouvert')->count() }}
                        </div>
                        <div class="stat-label">Incidents ouverts</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-number" style="color: var(--success);">
                            {{ \App\Models\Resource::where('status', 'available')->count() }}
                        </div>
                        <div class="stat-label">Disponibles</div>
                    </div>
                                            
                    @elseif(Auth::user()->role_id == 1) <!-- Admin -->
                        <div class="stat-card">
                            <div class="stat-number" style="color: var(--primary);">
                                {{ \App\Models\User::count() }}
                            </div>
                            <div class="stat-label">Utilisateurs</div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-number" style="color: var(--success);">
                                {{ \App\Models\Resource::count() }}
                            </div>
                            <div class="stat-label">Ressources IT</div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-number" style="color: var(--warning);">
                                {{ \App\Models\Reservation::where('status', 'EN ATTENTE')->count() }}
                            </div>
                            <div class="stat-label">Réservations en attente</div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-number" style="color: var(--danger);">
                                {{ \App\Models\Incident::where('status', 'ouvert')->count() }}
                            </div>
                            <div class="stat-label">Incidents ouverts</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Cards par rôle -->
            <div class="action-cards">
                @if(Auth::user()->role_id == 3) <!-- Utilisateur Interne -->
                    <a href="{{ route('resources.index') }}" class="action-card">
                        <div class="action-icon">
                            <i class='bx bx-search-alt'></i>
                        </div>
                        <h3>Rechercher des ressources</h3>
                        <p>Explorez le catalogue complet avec filtres avancés par catégorie et spécifications</p>
                        <span class="btn btn-primary">Explorer</span>
                    </a>

                    <a href="{{ route('reservations.create') }}" class="action-card">
                        <div class="action-icon">
                            <i class='bx bx-calendar-plus'></i>
                        </div>
                        <h3>Nouvelle réservation</h3>
                        <p>Faites une demande de réservation avec période, ressources souhaitées et justification</p>
                        <span class="btn btn-primary">Réserver</span>
                    </a>

                    <a href="{{ route('user.dashboard') }}" class="action-card">
                        <div class="action-icon">
                            <i class='bx bx-history'></i>
                        </div>
                        <h3>Suivi des demandes</h3>
                        <p>Consultez vos réservations par statut : En attente / Approuvée / Refusée / Active / Terminée</p>
                        <span class="btn btn-primary">Voir</span>
                    </a>

                    <a href="{{ route('user.historique') }}" class="action-card">
                        <div class="action-icon">
                            <i class='bx bx-archive'></i>
                        </div>
                        <h3>Historique complet</h3>
                        <p>Archive de toutes vos réservations, filtrée par date, ressource ou état</p>
                        <span class="btn btn-primary">Consulter</span>
                    </a>

                @elseif(Auth::user()->role_id == 2) <!-- Responsable Technique -->
                    <a href="{{ route('tech.dashboard') }}#pending" class="action-card">
                        <div class="action-icon">
                            <i class='bx bx-check-shield'></i>
                        </div>
                        <h3>Réservations en attente</h3>
                        <p>Approuvez, planifiez ou refusez les demandes avec justification</p>
                        <span class="btn btn-primary">Valider</span>
                    </a>

                    <a href="{{ route('tech.incidents.index') }}" class="action-card">
                        <div class="action-icon">
                            <i class='bx bx-error-alt'></i>
                        </div>
                        <h3>Gestion des incidents</h3>
                        <p>Traitez les problèmes signalés et modérez les discussions</p>
                        <span class="btn btn-primary">Gérer</span>
                    </a>

                @elseif(Auth::user()->role_id == 1) <!-- Admin -->
                  

                    <a href="{{ route('admin.dashboard') }}#users" class="action-card">
                        <div class="action-icon">
                            <i class='bx bx-group'></i>
                        </div>
                        <h3>Gestion des utilisateurs</h3>
                        <p>Activez/désactivez les comptes, gérez les rôles et permissions</p>
                        <span class="btn btn-primary">Gérer</span>
                    </a>

                    <a href="{{ route('admin.dashboard') }}#resources" class="action-card">
                        <div class="action-icon">
                            <i class='bx bx-server'></i>
                        </div>
                        <h3>Catalogue des ressources</h3>
                        <p>Gérez toutes les catégories, caractéristiques et états des ressources</p>
                        <span class="btn btn-primary">Configurer</span>
                    </a>

                    <a href="{{ route('admin.dashboard') }}#stats" class="action-card">
                        <div class="action-icon">
                            <i class='bx bx-stats'></i>
                        </div>
                        <h3>Statistiques globales</h3>
                        <p>Consultez le taux d'occupation, les périodes de maintenance et les rapports</p>
                        <span class="btn btn-primary">Analyser</span>
                    </a>
                @endif
            </div>

        @else
            <!-- =================== SECTION POUR LES INVITÉS (NON CONNECTÉS)==================-->
            <!-- Catégories de ressources -->
            <div class="action-cards">
                <a href="{{ route('login') }}" class="action-card">
                    <div class="action-icon">
                        <i class='bx bx-user'></i>
                    </div>
                    <h3>Connexion</h3>
                    <p>Accédez à votre compte pour gérer vos ressources et réservations</p>
                    <span class="btn btn-primary">Se connecter</span>
                </a>
                <a href="{{ route('register') }}" class="action-card">
                    <div class="action-icon">
                        <i class='bx bx-user-plus'></i>
                    </div>
                    <h3>Créer un compte</h3>
                    <p>Inscrivez-vous et commencez à réserver vos ressources cloud immédiatement</p>
                    <span class="btn btn-primary">S’inscrire</span>
                </a>
                <a href="{{ route('resources.index') }}" class="action-card">
                    <div class="action-icon">
                        <i class='bx bx-server'></i>
                    </div>
                    <h3>Catalogue des ressources</h3>
                    <p>Explorez le catalogue complet avec filtres avancés par catégorie et spécifications</p>
                    <span class="btn btn-primary">Explorer</span>
                </a>
            </div>

            <!-- Features Section -->
            <div class="features-section">
                <h3>Pourquoi choisir DataCenter Pro ?</h3>
                <p class="text-muted" style="max-width: 600px; margin: 0 auto 3rem;">
                    Une plateforme complète pour la gestion de votre infrastructure IT
                </p>
                
               <div class="features-grid">
    @foreach([
        ['icon'=>'bx bx-shield-quarter','title'=>'Sécurité','desc'=>'Auth sécurisée'],
        ['icon'=>'bx bx-trending-up','title'=>'Performance','desc'=>'Ressources optimisées'],
        ['icon'=>'bx bx-support','title'=>'Support','desc'=>'Assistance 24/7'],
        ['icon'=>'bx bx-line-chart','title'=>'Monitoring','desc'=>'Suivi en direct'],
        ['icon'=>'bx bx-calendar-check','title'=>'Réservation','desc'=>'Système anti-conflit'],
        ['icon'=>'bx bx-user-check','title'=>'Rôle','desc'=>'Permissions adaptées']
    ] as $feature)
        <div class="feature-item">
            <div class="feature-icon"><i class='{{ $feature['icon'] }}'></i></div>
            <h4>{{ $feature['title'] }}</h4>
            <p class="small text-muted">{{ $feature['desc'] }}</p>
        </div>
    @endforeach
</div>

            </div>

            <!-- Call to Action -->
            <div class="welcome-box" style="background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white;">
                <div class="welcome-icon" style="color: var(--text-main);">
                    <i class='bx bx-rocket'></i>
                </div>
                <h2 style="margin-bottom: 1rem; color: var(--text-main);">Prêt à commencer ?</h2>
                <p style="color:var(--text-main); margin-bottom: 2rem;">
                    Créez votre compte gratuitement et accédez à notre infrastructure cloud de pointe
                </p>
                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 1rem 2rem;">
                        <i class='bx bx-user-plus'></i> Créer un compte
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-secondary" style="padding: 1rem 2rem;">
                        <i class='bx bx-log-in'></i> Se connecter
                    </a>
                    <a href="#categories" class="btn btn-success" style="padding: 1rem 2rem;">
                        <i class='bx bx-info-circle'></i> En savoir plus
                    </a>
                </div>
            </div>

            <!-- Resources Preview Modal -->
            <div class="resources-section" id="resources-preview">
                <h2 id="table-title">Ressources disponibles</h2>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Désignation</th>
                                <th>Spécifications</th>
                                <th>Catégorie</th>
                                <th>Statut</th>
                                <th style="text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="resources-table-body">
                            <!-- Les ressources seront chargées dynamiquement -->
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-4">
                    <p class="text-muted">Connectez-vous pour réserver ces ressources</p>
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class='bx bx-log-in'></i> Se connecter pour réserver
                    </a>
                </div>
            </section>
        @endauth
    </div>
@endsection

@push('scripts')
<script>
    // Hover effect pour les cartes d'action
    document.querySelectorAll('.action-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
        });
    });

    @guest
    // Fonction pour afficher les ressources d'une catégorie (invités)
    function showCategoryResources(categoryId) {
        const section = document.getElementById('resources-preview');
        const tableBody = document.getElementById('resources-table-body');
        
        // Afficher la section
        section.style.display = 'block';
        
        // Charger les ressources via AJAX
        fetch(`/api/categories/${categoryId}/resources`)
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = '';
                
                if (data.resources.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class='bx bx-inbox bx-lg text-muted'></i>
                                <p class="text-muted mt-2">Aucune ressource disponible dans cette catégorie</p>
                            </td>
                        </tr>
                    `;
                    return;
                }
                
                data.resources.forEach(resource => {
                    const row = document.createElement('tr');
                    
                    // Déterminer la classe de statut
                    let statusClass = 'status-available';
                    let statusText = 'Disponible';
                    
                    if (resource.status === 'occupied') {
                        statusClass = 'status-occupied';
                        statusText = 'Occupé';
                    } else if (resource.status === 'maintenance') {
                        statusClass = 'status-maintenance';
                        statusText = 'Maintenance';
                    }
                    
                    row.innerHTML = `
                        <td><strong>${resource.name}</strong></td>
                        <td>
                            <small>
                                ${resource.cpu ? `<span>CPU: ${resource.cpu}</span><br>` : ''}
                                ${resource.ram ? `<span>RAM: ${resource.ram}</span><br>` : ''}
                                ${resource.capacity ? `<span>Stockage: ${resource.capacity}</span><br>` : ''}
                                ${resource.bandwidth ? `<span>Bande passante: ${resource.bandwidth}</span>` : ''}
                            </small>
                        </td>
                        <td>
                            <span class="badge badge-info">
                                ${data.category.name}
                            </span>
                        </td>
                        <td>
                            <span class="status-badge ${statusClass}">
                                ${statusText}
                            </span>
                        </td>
                        <td style="text-align: right;">
                            ${resource.status === 'available' ? 
                                `<a href="/login" class="btn btn-primary btn-sm">
                                    <i class='bx bx-calendar'></i> Connectez-vous
                                </a>` :
                                `<span style="color: var(--text-muted); font-size: 0.75rem; font-weight: 700;">
                                    ${statusText}
                                </span>`
                            }
                        </td>
                    `;
                    
                    tableBody.appendChild(row);
                });
                
                document.getElementById('table-title').innerText = `Ressources : ${data.category.name}`;
                window.scrollTo({ 
                    top: section.offsetTop - 100, 
                    behavior: 'smooth' 
                });
            })
            .catch(error => {
                console.error('Erreur:', error);
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-4 text-danger">
                            <i class='bx bx-error-circle bx-lg'></i>
                            <p class="mt-2">Erreur de chargement des ressources</p>
                        </td>
                    </tr>
                `;
            });
    }
    @endguest
</script>
@endpush