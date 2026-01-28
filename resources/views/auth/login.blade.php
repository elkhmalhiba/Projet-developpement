@extends('layouts.app')

@section('title', 'Accès Sécurisé | DataCenter Pro')

@section('body-class', 'auth-page')

@section('content')
<div class="auth-container">
    <div class="auth-header">
        <div class="auth-icon">
            <i class='bx bxs-lock-open-alt'></i>
        </div>
        <h2>Accès Client</h2>
        <p>Identifiez-vous pour continuer</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        <div class="form-group">
            <label class="form-label">Email Professionnel</label>
            <div class="input-wrapper">
                <input type="email" name="email" class="form-control" placeholder="nom@entreprise.com" required autofocus>
                <i class='bx bx-envelope'></i>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Mot de passe</label>
            <div class="input-wrapper">
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                <i class='bx bx-key'></i>
                <span class="toggle-password" id="togglePassword">
                    <i class='bx bx-show' id="toggleIcon"></i>
                </span>
            </div>
        </div>

        <div class="d-flex justify-between align-center mb-3">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="checkbox" name="remember" style="accent-color: var(--primary);"> 
                <span style="font-size: 0.85rem; color: var(--text-muted);">Se souvenir</span>
            </label>
            <a href="#" style="font-size: 0.85rem; color: var(--primary); text-decoration: none; font-weight: 600;">
                Mot de passe oublié ?
            </a>
        </div>
        <button style="height: 50px;" type="submit" class="btn btn-primary w-100">
            Connexion Sécurisée
        </button>
    </form>

    @if($errors->any())
        <div class="alert alert-danger mt-3">
            <i class='bx bx-error-circle'></i> {{ $errors->first() }}
        </div>
    @endif

    <div class="mt-4 text-center" style="color: var(--text-muted);">
        <a href="{{ route('register') }}" style="color: var(--text-muted); text-decoration: none;">
            Créer un compte
        </a>
        <span style="margin: 0 10px; color: var(--border);">|</span>
        <a href="#" style="color: var(--text-muted); text-decoration: none;">
            Support 24/7
        </a>
    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        const isPassword = passwordField.type === 'password';
        
        passwordField.type = isPassword ? 'text' : 'password';
        icon.classList.toggle('bx-show');
        icon.classList.toggle('bx-hide');
    });
</script>
@endsection