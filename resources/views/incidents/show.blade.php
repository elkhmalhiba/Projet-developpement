@extends('layouts.app')

@section('title', 'Détails de l\'incident')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Détails de l'incident #{{ $incident->id }}</h1>

    <div class="card">
        <div class="card-header bg-warning text-white">
            <strong>{{ $incident->title }}</strong>
        </div>
        <div class="card-body">
            <p><strong>Description :</strong></p>
            <p>{{ $incident->description }}</p>

            <p><strong>Statut :</strong> 
                @if($incident->status == 'ouvert')
                    <span class="badge bg-success">Ouvert</span>
                @else
                    <span class="badge bg-secondary">{{ ucfirst($incident->status) }}</span>
                @endif
            </p>

            <p><strong>Signalé par :</strong> {{ $incident->user->name ?? 'Inconnu' }}</p>
            <p><strong>Date :</strong> {{ $incident->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('tech.incidents.index') }}" class="btn btn-secondary">Retour</a>

            <form action="{{ route('tech.incidents.destroy', $incident->id) }}" method="POST" 
                  onsubmit="return confirm('Voulez-vous vraiment supprimer cet incident ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-success">Résolu & supprimer</button>
            </form>
        </div>
    </div>
</div>
@endsection
