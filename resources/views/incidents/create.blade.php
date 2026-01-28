@extends('layouts.app')

@section('title', 'Signaler un problème | DataCenter Pro')

@section('body-class', 'incident-page')

@section('content')
    <div class="incident-container">
        <div class="incident-card">
            <div class="incident-icon">
                <i class='bx bxs-error-alt'></i>
            </div>

            <h2>Signaler un problème</h2>
            <p class="text-muted mb-3">
                Ressource : <span class="text-primary" style="font-weight: 700;">{{ $resource->name }}</span>
            </p>

            <form action="{{ route('incidents.store') }}" method="POST">
                @csrf
                <input type="hidden" name="resource_id" value="{{ $resource->id }}">
                <div class="form-group">
                    <label for="title" class="form-label">Titre du problème</label>
                    <input type="text" id="title" name="title" class="form-control" required
                        placeholder="Ex: Serveur inaccessible">
                </div>

                <div class="form-group mt-3">
                    <label for="description" class="form-label">Description technique</label>
                    <textarea id="description" name="description" class="incident-textarea" rows="6" required
                        placeholder="Détaillez la panne..."></textarea>
                </div>

                <button type="submit" class="btn btn-danger w-100">
                    <i class='bx bxs-paper-plane'></i>
                    ENVOYER LE SIGNALEMENT
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('user.dashboard') }}" class="incident-back-link">
                    <i class='bx bx-arrow-back'></i>
                    Retour au Dashboard
                </a>
            </div>
        </div>
    </div>
@endsection