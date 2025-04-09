@extends('layouts.app')

@section('title', 'Balance Ton Flow - Aucun live en cours')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 text-center">
        <div class="card border-0 shadow-sm p-5">
            <div class="mb-4">
                <i class="fas fa-video-slash fa-5x text-muted"></i>
            </div>
            <h1 class="h3 mb-3">Aucun live en cours</h1>
            <p class="lead mb-4">Il n'y a pas de diffusion en direct pour le moment. Revenez plus tard pour suivre les prochains événements de Balance Ton Flow.</p>
            
            <div class="row justify-content-center mt-4">
                <div class="col-md-8">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h3 class="h5 mb-3">Prochains événements</h3>
                            <ul class="list-unstyled">
                                <li class="mb-3 d-flex align-items-center">
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-microphone-alt text-white"></i>
                                    </div>
                                    <div class="text-start">
                                        <h4 class="h6 mb-0">Demi-finales</h4>
                                        <p class="small text-muted mb-0">15 Avril 2025 - 20:00</p>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-trophy text-white"></i>
                                    </div>
                                    <div class="text-start">
                                        <h4 class="h6 mb-0">Grande Finale</h4>
                                        <p class="small text-muted mb-0">30 Avril 2025 - 20:00</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-5">
                <a href="{{ route('candidates.index') }}" class="btn btn-primary">
                    <i class="fas fa-users me-2"></i>Découvrir les candidats
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary ms-2">
                    <i class="fas fa-home me-2"></i>Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
