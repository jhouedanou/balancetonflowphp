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
                
                <!-- Affichage des erreurs de validation -->
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <!-- Formulaire de connexion par email/mot de passe -->
                <form method="POST" action="{{ route('login.authenticate') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse e-mail</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Se souvenir de moi</label>
                    </div>
                    
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                    </div>
                </form>
                
                <div class="text-center mb-3">
                    <p>Ou</p>
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
                    <p class="mb-2">Pas encore de compte ? <a href="{{ route('register') }}">Inscrivez-vous ici</a></p>
                    <p><a href="{{ route('password.request') }}">Mot de passe oublié ?</a></p>
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
