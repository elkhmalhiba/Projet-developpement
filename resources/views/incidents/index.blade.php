@extends('layouts.app')

@section('title', 'Tous les incidents | DataCenter Pro')

@section('body-class', 'incidents-page')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Liste des incidents</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($incidents->isEmpty())
        <p class="text-muted">Aucun incident n’a été signalé pour le moment.</p>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#ID</th>
                        <th>Utilisateur</th>
                        <th>Ressource</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($incidents as $incident)
                        @php
                            // Classe CSS pour la couleur selon le status
                            $statusClass = match($incident->status) {
                                'ouvert' => 'badge bg-danger',
                                'en cours' => 'badge bg-warning text-dark',
                                'résolu' => 'badge bg-success',
                                default => 'badge bg-secondary',
                            };
                        @endphp
                        <tr>
                            <td>{{ $incident->id }}</td>
                            <td>{{ $incident->user->name ?? 'Utilisateur inconnu' }}</td>
                            <td>{{ $incident->resource->name ?? 'Ressource inconnue' }}</td>
                            <td>{{ Str::limit($incident->description, 50) }}</td>
                            <td><span class="{{ $statusClass }}">{{ ucfirst($incident->status) }}</span></td>
                            <td>{{ $incident->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <form action="{{ route('tech.incidents.destroy', $incident) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer cet incident ?')">
                                        <i class='bx bx-check'></i>
                                    </button>
                                </form>
                                <a href="{{ route('tech.incidents.show', $incident) }}" class="btn btn-primary">
                                    <i style="font-size: 23px;" class='bx bx-info-circle'></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection