@extends('layouts.app')

@section('title', 'Balance Ton Flow - Connexion')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h2 class="h3 mb-3">Connexion</h2>
                    <p class="text-muted">Connectez-vous pour voter et soutenir vos candidats préférés</p>
                </div>
                
                <div class="d-grid gap-3 mb-4">
                    <a href="{{ route('socialite.redirect', 'google') }}" class="btn social-login-btn google-btn">
                        <i class="fab fa-google"></i> Continuer avec Google
                    </a>
                    <a href="{{ route('socialite.redirect', 'facebook') }}" class="btn social-login-btn facebook-btn">
                        <i class="fab fa-facebook-f"></i> Continuer avec Facebook
                    </a>
                </div>
                
                <div class="text-center mt-4">
                    <p class="small text-muted">
                        En vous connectant, vous acceptez nos <a href="#">Conditions d'utilisation</a> et notre <a href="#">Politique de confidentialité</a>.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="text-decoration-none">
                <i class="fas fa-arrow-left me-2"></i>Retour à l'accueil
            </a>
        </div>
    </div>
</div>
@endsection
