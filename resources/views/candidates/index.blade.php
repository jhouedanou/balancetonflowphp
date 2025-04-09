@extends('layouts.app')

@section('title', 'Balance Ton Flow - Candidats')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h2 mb-3">Nos candidats</h1>
        <p class="lead">Découvrez les talents qui participent au concours Balance Ton Flow</p>
    </div>
</div>

<div class="row">
    @foreach($candidates as $candidate)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card candidate-card h-100 border-0 shadow-sm">
                <img src="{{ $candidate->photo ? asset('storage/' . $candidate->photo) : asset('images/default-avatar.png') }}" 
                     class="card-img-top candidate-img" 
                     alt="{{ $candidate->name }}">
                <div class="card-body">
                    <h2 class="h5 card-title">{{ $candidate->name }}</h2>
                    <p class="card-text text-muted">{{ Str::limit($candidate->description, 100) }}</p>
                </div>
                <div class="card-footer bg-white border-0 pb-3">
                    <a href="{{ route('candidates.show', $candidate) }}" class="btn btn-primary">
                        <i class="fas fa-user me-2"></i>Voir le profil
                    </a>
                    @if($candidate->is_finalist)
                        <span class="badge bg-warning text-dark ms-2">
                            <i class="fas fa-star me-1"></i>Finaliste
                        </span>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

@if(count($candidates) === 0)
    <div class="row">
        <div class="col-12 text-center py-5">
            <div class="mb-4">
                <i class="fas fa-users fa-4x text-muted"></i>
            </div>
            <h2 class="h4 mb-3">Aucun candidat pour le moment</h2>
            <p class="text-muted">Les candidats seront ajoutés prochainement. Revenez bientôt !</p>
        </div>
    </div>
@endif

<div class="row mt-5">
    <div class="col-md-8 offset-md-2">
        <div class="card bg-light border-0">
            <div class="card-body p-4 text-center">
                <h3 class="h4 mb-3">Prêt à soutenir votre candidat préféré ?</h3>
                <p class="mb-4">Suivez le live et votez pour votre candidat favori lors des phases finales du concours.</p>
                <a href="{{ route('livestream.index') }}" class="btn btn-primary">
                    <i class="fas fa-play-circle me-2"></i>Accéder au live
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
