@extends('layouts.app')

@section('title', 'Balance Ton Flow - Modifier mon profil')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h2 class="h4 mb-0">Modifier mon profil</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('contestants.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4 text-center">
                            <div class="profile-photo-wrapper mb-3">
                                <img src="{{ $contestant->photo ? asset('storage/' . $contestant->photo) : asset('images/default-avatar.png') }}" 
                                     alt="{{ $contestant->name }}" 
                                     class="rounded-circle" 
                                     id="profile-photo-preview"
                                     width="150" height="150">
                            </div>
                            
                            <div class="mb-3">
                                <label for="photo" class="form-label">Photo de profil</label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                       id="photo" name="photo"
                                       accept="image/*">
                                       
                                @error('photo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                
                                <div class="form-text">Format recommandé: carré, JPG ou PNG, max 2 Mo</div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom d'artiste</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" 
                                   value="{{ old('name', $contestant->name) }}" required>
                                   
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="bio" class="form-label">Biographie</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                     id="bio" name="bio" rows="6">{{ old('bio', $contestant->bio) }}</textarea>
                                     
                            @error('bio')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            
                            <div class="form-text">Parlez de vous, de votre style musical et de vos influences</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="social_links" class="form-label">Liens sociaux</label>
                            <textarea class="form-control @error('social_links') is-invalid @enderror" 
                                     id="social_links" name="social_links" rows="3">{{ old('social_links', $contestant->social_links) }}</textarea>
                                     
                            @error('social_links')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            
                            <div class="form-text">Ajoutez vos liens YouTube, Instagram, TikTok, etc. (un par ligne)</div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('contestants.dashboard') }}" class="btn btn-outline-secondary me-md-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Preview profile photo before upload
    document.addEventListener('DOMContentLoaded', function() {
        const photoInput = document.getElementById('photo');
        const photoPreview = document.getElementById('profile-photo-preview');
        
        if (photoInput && photoPreview) {
            photoInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        photoPreview.src = e.target.result;
                    };
                    
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    });
</script>
@endsection
