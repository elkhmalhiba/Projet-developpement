@extends('layouts.app')

@section('title', 'Inscription | DataCenter Pro')

@section('body-class', 'register-page')

@section('content')
<div class="auth-container">
    <div class="auth-header">
        <div class="auth-icon">
            <i class='bx bxs-user-plus'></i>
        </div>
        <h2>Nouveau Compte</h2>
        <p>Rejoignez DataCenter Pro</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        
        <div class="form-group">
            <label class="form-label">Nom complet</label>
            <div class="input-wrapper">
                <input type="text" name="name" class="form-control" placeholder="Ex: Ali Alami" required>
                <i class='bx bx-user'></i>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Email Professionnel</label>
            <div class="input-wrapper">
                <input type="email" name="email" class="form-control" placeholder="nom@site.com" required>
                <i class='bx bx-envelope'></i>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Mot de passe</label>
            <div class="input-wrapper">
                <input type="password" name="password" id="p1" class="form-control" placeholder="••••••••" required oninput="checkP(this.value)">
                <i class='bx bx-lock-alt'></i>
                <span class="toggle-btn" onclick="toggle('p1', this)">
                    <i class='bx bx-hide'></i>
                </span>
            </div>
            <div class="strength-container">
                <div class="strength-bar" id="sb"></div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Confirmation</label>
            <div class="input-wrapper">
                <input type="password" name="password_confirmation" id="p2" class="form-control" placeholder="••••••••" required>
                <i class='bx bx-check-shield'></i>
                <span class="toggle-btn" onclick="toggle('p2', this)">
                    <i class='bx bx-hide'></i>
                </span>
            </div>
        </div>

        <button style="height: 50px;" type="submit" class="btn btn-primary w-100 mt-3">
            S'inscrire
        </button>

        <p class="text-center mt-4" style="color: var(--text-muted); font-size: 0.82rem;">
            Déjà inscrit ? 
            <a href="{{ route('login') }}" style="color: var(--primary); text-decoration: none; font-weight: 700;">
                Connexion
            </a>
        </p>
    </form>
</div>

<script>
    function toggle(id, el) {
        const inp = document.getElementById(id);
        const ic = el.querySelector('i');
        inp.type = inp.type === "password" ? "text" : "password";
        ic.classList.toggle('bx-hide');
        ic.classList.toggle('bx-show');
    }

    function checkP(v) {
        const b = document.getElementById('sb');
        if(v.length === 0) {
            b.style.width = '0%';
        } else if(v.length < 6) {
            b.style.width = '35%';
            b.style.background = 'var(--danger)';
        } else if(v.length < 10) {
            b.style.width = '65%';
            b.style.background = 'var(--warning)';
        } else {
            b.style.width = '100%';
            b.style.background = 'var(--success)';
        }
    }
</script>
@endsection