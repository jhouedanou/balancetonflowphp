@extends('layouts.app')

@section('title', 'Balance Ton Flow - Réinitialisation du mot de passe')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h2 class="h3 mb-3">Réinitialisation du mot de passe</h2>
                    <p class="text-muted">Entrez votre adresse e-mail et nous vous enverrons un lien pour réinitialiser votre mot de passe</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success mb-4" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                
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
                
                <!-- Formulaire de demande de réinitialisation du mot de passe -->
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="email" class="form-label">Adresse e-mail</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Envoyer le lien de réinitialisation</button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <p><a href="{{ route('login') }}">Retour à la connexion</a></p>
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
