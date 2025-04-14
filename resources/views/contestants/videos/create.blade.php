@extends('layouts.app')

@section('title', 'Balance Ton Flow - Ajouter une vidéo')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h2 class="h4 mb-0">Ajouter une nouvelle vidéo</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('contestants.videos.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre de la vidéo *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" 
                                   value="{{ old('title') }}" required>
                                   
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="url" class="form-label">URL YouTube *</label>
                            <input type="url" class="form-control @error('url') is-invalid @enderror" 
                                   id="url" name="url" 
                                   value="{{ old('url') }}" required
                                   placeholder="https://www.youtube.com/watch?v=...">
                                   
                            @error('url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="form-text">Copiez l'URL complète de votre vidéo YouTube</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                     id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                     
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="thumbnail" class="form-label">Image miniature</label>
                            <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" 
                                   id="thumbnail" name="thumbnail"
                                   accept="image/*">
                                   
                            @error('thumbnail')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="form-text">Si non fournie, la miniature YouTube sera utilisée</div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">
                                    Publier cette vidéo immédiatement
                                </label>
                            </div>
                            <div class="form-text">Si non cochée, la vidéo sera enregistrée comme brouillon</div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('contestants.dashboard') }}" class="btn btn-outline-secondary me-md-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">Ajouter la vidéo</button>
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
    // Preview video URL
    document.addEventListener('DOMContentLoaded', function() {
        const urlInput = document.getElementById('url');
        
        if (urlInput) {
            urlInput.addEventListener('blur', function() {
                // This function could be expanded to show a preview of the YouTube video
                // For now, it's a placeholder
                console.log('Video URL changed:', this.value);
            });
        }
    });
</script>
@endsection
