@extends('layouts.app')

@section('title', 'Balance Ton Flow - Inscription')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h2 class="h3 mb-3">Inscription</h2>
                    <p class="text-muted">Créez un compte pour voter et soutenir vos candidats préférés</p>
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
                
                <!-- Formulaire d'inscription -->
                <form method="POST" action="{{ route('user.register.submit') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom complet</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse e-mail</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirmation du mot de passe</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Créer un compte</button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <p class="mb-0">Vous avez déjà un compte ? <a href="{{ route('login') }}">Connexion</a></p>
                </div>
                
                <div class="text-center mt-4">
                    <p class="small text-muted">
                        En vous inscrivant, vous acceptez nos <a href="#">Conditions d'utilisation</a> et notre <a href="#">Politique de confidentialité</a>.
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
