@extends('layouts.app')

@section('title', 'Balance Ton Flow - Modifier une vidéo')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h2 class="h4 mb-0">Modifier la vidéo</h2>
                    @if($video->is_published)
                        <span class="badge bg-success">Publiée</span>
                    @else
                        <span class="badge bg-secondary">Brouillon</span>
                    @endif
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('contestants.videos.update', $video) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre de la vidéo *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" 
                                   value="{{ old('title', $video->title) }}" required>
                                   
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
                                   value="{{ old('url', $video->url) }}" required
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
                                     id="description" name="description" rows="4">{{ old('description', $video->description) }}</textarea>
                                     
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="thumbnail" class="form-label">Image miniature</label>
                            @if($video->thumbnail)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $video->thumbnail) }}" 
                                         alt="{{ $video->title }}" 
                                         class="img-thumbnail" 
                                         style="max-height: 150px">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" 
                                   id="thumbnail" name="thumbnail"
                                   accept="image/*">
                                   
                            @error('thumbnail')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="form-text">Laissez vide pour conserver l'image actuelle</div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" 
                                       {{ old('is_published', $video->is_published) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">
                                    Publier cette vidéo
                                </label>
                            </div>
                            @if($video->is_published)
                                <div class="form-text text-success mb-2">
                                    Publiée le: {{ $video->published_at ? $video->published_at->format('d/m/Y à H:i') : 'Date inconnue' }}
                                </div>
                            @else
                                <div class="form-text">Si cochée, la vidéo sera publiée immédiatement</div>
                            @endif
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('contestants.dashboard') }}" class="btn btn-outline-secondary me-md-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-4 text-center">
                <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#deleteVideoModal">
                    <i class="fas fa-trash me-1"></i>Supprimer cette vidéo
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Delete Video Modal -->
<div class="modal fade" id="deleteVideoModal" tabindex="-1" aria-labelledby="deleteVideoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteVideoModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer la vidéo "{{ $video->title }}" ?</p>
                <p class="text-danger">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="POST" action="{{ route('contestants.videos.destroy', $video) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
