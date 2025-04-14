@extends('layouts.app')

@section('title', 'Balance Ton Flow - Accueil')

@section('content')
<div class="row align-items-center py-5">
    <div class="col-lg-6 fade-in">
        <h1 class="display-4 fw-bold mb-4">Balance Ton Flow</h1>
        <h2 class="h3 mb-4">La plateforme officielle du concours de rap</h2>
        <p class="lead mb-4">Suivez les phases finales en direct, votez pour vos candidats préférés et découvrez les contenus exclusifs des finalistes.</p>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('livestream.index') }}" class="btn btn-primary btn-lg pulse">
                <i class="fas fa-play-circle me-2"></i>Voir le live
            </a>
            <a href="{{ route('candidates.index') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-users me-2"></i>Découvrir les candidats
            </a>
        </div>
    </div>
    <div class="col-lg-6 mt-5 mt-lg-0 text-center fade-in">
        <img src="{{ asset('images/hero-image.png') }}" alt="Balance Ton Flow" class="img-fluid rounded-3 shadow-lg" style="max-height: 400px;">
    </div>
</div>

<div class="row mt-5 pt-5">
    <div class="col-12 text-center mb-5">
        <h2 class="h1 mb-4">Comment ça marche ?</h2>
        <p class="lead">Participez au concours Balance Ton Flow en quelques étapes simples</p>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-4 mb-4 mb-md-0 fade-in">
        <div class="card h-100 border-0">
            <div class="card-body text-center p-4">
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                    <i class="fas fa-user-plus text-white fa-2x"></i>
                </div>
                <h3 class="h4 mb-3">1. Connectez-vous</h3>
                <p>Utilisez votre compte Google ou Facebook pour vous connecter rapidement et sécurisé.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4 mb-md-0 fade-in" style="animation-delay: 0.2s;">
        <div class="card h-100 border-0">
            <div class="card-body text-center p-4">
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                    <i class="fas fa-tv text-white fa-2x"></i>
                </div>
                <h3 class="h4 mb-3">2. Regardez le live</h3>
                <p>Suivez les performances des candidats en direct pendant les phases semi-finales et finales.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 fade-in" style="animation-delay: 0.4s;">
        <div class="card h-100 border-0">
            <div class="card-body text-center p-4">
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                    <i class="fas fa-vote-yea text-white fa-2x"></i>
                </div>
                <h3 class="h4 mb-3">3. Votez</h3>
                <p>Soutenez votre candidat préféré en votant pendant le live et découvrez les résultats en temps réel.</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5 pt-4">
    <div class="col-12 text-center mb-5">
        <h2 class="h1 mb-4">Prochains événements</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4 fade-in">
        <div class="card h-100 border-0">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3" style="min-width: 60px; height: 60px;">
                        <i class="fas fa-microphone-alt text-white fa-lg"></i>
                    </div>
                    <div>
                        <h3 class="h4 mb-1">Demi-finales</h3>
                        <p class="text-muted mb-0">15 Avril 2025 - 20:00</p>
                    </div>
                </div>
                <p>Les 6 meilleurs candidats s'affronteront lors des demi-finales pour tenter de décrocher leur place en finale. Votez en direct pour vos 3 finalistes préférés !</p>
                <a href="{{ route('livestream.index') }}" class="btn btn-outline-primary">En savoir plus</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4 fade-in" style="animation-delay: 0.2s;">
        <div class="card h-100 border-0">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3" style="min-width: 60px; height: 60px;">
                        <i class="fas fa-trophy text-white fa-lg"></i>
                    </div>
                    <div>
                        <h3 class="h4 mb-1">Grande Finale</h3>
                        <p class="text-muted mb-0">30 Avril 2025 - 20:00</p>
                    </div>
                </div>
                <p>Les 3 finalistes s'affronteront lors de la grande finale pour remporter le titre de Balance Ton Flow 2025. Qui sera le grand gagnant ? À vous de décider !</p>
                <a href="{{ route('livestream.index') }}" class="btn btn-outline-primary">En savoir plus</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5 py-5 bg-light rounded-3">
    <div class="col-md-6 offset-md-3 text-center">
        <h2 class="mb-4">Prêt à participer ?</h2>
        <p class="lead mb-4">Rejoignez-nous dès maintenant pour vivre l'expérience Balance Ton Flow et soutenir vos artistes préférés.</p>
        <a href="{{ route('livestream.index') }}" class="btn btn-primary btn-lg px-5">
            <i class="fas fa-play-circle me-2"></i>Accéder au live
        </a>
    </div>
</div>
@endsection
