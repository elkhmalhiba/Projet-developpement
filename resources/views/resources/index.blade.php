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
        margin-bottom: 150px;
        padding-top: 80px;
        position: relative;
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

    /* Category Grid */
    .category-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-top: -100px;
        position: relative;
        z-index: 10;
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
        background: #111;
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
    }

    /* Inventory Section */
    .inventory-section {
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

    /* Resource Row Styling */
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

    /* Animations */
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

    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-subtitle {
            font-size: 1rem;
        }
        
        .category-grid {
            margin-top: -50px;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .cat-img-box {
            height: 160px;
        }
        
        .hero {
            height: 50vh;
            margin-bottom: 60px;
        }
    }

    @media (max-width: 480px) {
        .hero-title {
            font-size: 2rem;
        }
        
        .category-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
    <div class="container" style="margin-top:200px">
        <!-- Category Grid -->
        <div class="category-grid">
            @foreach($categories as $category)
                <div class="cat-card" onclick="filterResources('{{ $category->name }}', this)">
                    <div class="cat-img-box">
                       <img src="{{ asset('storage/images/categories/'.$category->img) }}" alt="{{ $category->name }}">
                    </div>
                    <div class="cat-body">
                        <i class='{{ $category->icon ?? "bx bx-category" }}'></i>
                        <h3>{{ strtoupper($category->name) }}</h3>
                        <p>{{ $category->description ?? 'Description...' }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Resources Table -->
        <section class="inventory-section" id="inventory-section">
            <h2 id="table-title">Liste des équipements</h2>
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
                    <tbody>
                        @foreach($categories as $category)
                            @foreach($category->resources as $resource)
                                @php 
                                    $cat = strtolower(str_replace(['é','è','ê','sécurité'], ['e','e','e','securite'], $category->name));
                                @endphp
                                <tr class="resource-row" data-category="{{ $cat }}">
                                    <td><strong>{{ $resource->name }}</strong></td>
                                    <td>
                                        <small>
                                            @if($resource->cpu)<span>CPU: {{ $resource->cpu }}</span><br>@endif
                                            @if($resource->ram)<span>RAM: {{ $resource->ram }}</span><br>@endif
                                            @if($resource->capacity)<span>Stockage: {{ $resource->capacity }}</span><br>@endif
                                            @if($resource->bandwidth)<span>Bande passante: {{ $resource->bandwidth }}</span>@endif
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $category->name }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($resource->status) {
                                                'available' => 'status-available',
                                                'occupied' => 'status-occupied',
                                                'maintenance' => 'status-maintenance',
                                                default => 'status-available'
                                            };
                                            
                                            $statusText = match($resource->status) {
                                                'available' => 'Disponible',
                                                'occupied' => 'Occupé',
                                                'maintenance' => 'Maintenance',
                                                default => $resource->status
                                            };
                                        @endphp
                                        <span class="status-badge {{ $statusClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td style="text-align: right;">
                                        @auth
                                        @if($resource->status == 'available' && Auth::user()->role_id == 3)
                                            <a href="{{ route('reservations.create', ['resource_id' => $resource->id]) }}" 
                                               class="btn btn-primary btn-sm">
                                                <i class='bx bx-calendar'></i> Réserver
                                            </a>
                                        @else
                                            <span style="color: var(--text-muted); font-size: 0.75rem; font-weight: 700;">
                                                {{ $statusText }}
                                            </span>
                                        @endif
                                        @endauth
                                    </td>


                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script>
    // Lignes 232-242 - Amélioration du script de filtrage :
    function filterResources(category, el) {
        const section = document.getElementById('inventory-section');
        section.style.display = 'block';
        document.getElementById('table-title').innerText = "Ressources : " + category;
        const target = category.toLowerCase()
            .replace(/[éèê]/g, 'e')
            .replace('sécurité', 'securite')
            .replace(/[^a-z0-9]/g, ''); // Supprimer tous les caractères spéciaux
        
        document.querySelectorAll('.resource-row').forEach(row => {
            const rowCategory = row.getAttribute('data-category')
                .replace(/[^a-z0-9]/g, ''); // Nettoyer aussi la catégorie stockée
            row.classList.toggle('visible', rowCategory === target);
        });
        
        // Désélectionner toutes les cartes et sélectionner celle cliquée
        document.querySelectorAll('.cat-card').forEach(card => {
            card.style.borderColor = 'var(--border)';
        });
        el.style.borderColor = 'var(--primary)';
        
        window.scrollTo({ 
            top: document.getElementById('inventory-section').offsetTop - 100, 
            behavior: 'smooth' 
        });
    }
</script>
@endpush
