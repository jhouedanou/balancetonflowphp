@extends('layouts.app')

@section('title', 'Balance Ton Flow - ' . $candidate->name)

@section('content')
<div class="row mb-5">
    <div class="col-md-4 mb-4 mb-md-0">
        <div class="card border-0 shadow-sm h-100">
            <img src="{{ $candidate->photo ? asset('storage/' . $candidate->photo) : asset('images/default-avatar.png') }}" 
                 class="card-img-top candidate-img" 
                 alt="{{ $candidate->name }}">
            <div class="card-body">
                <h1 class="h3 card-title">{{ $candidate->name }}</h1>
                
                @if($candidate->is_finalist)
                    <div class="mb-3">
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-star me-1"></i>Finaliste
                        </span>
                    </div>
                @endif
                
                <p class="card-text">{{ $candidate->description }}</p>
                
                <div class="d-flex align-items-center mt-4">
                    <div class="me-4">
                        <span class="d-block text-center h3 mb-0">{{ $voteCount }}</span>
                        <span class="small text-muted">Votes</span>
                    </div>
                    <div>
                        <span class="d-block text-center h3 mb-0">{{ count($videos) }}</span>
                        <span class="small text-muted">Vidéos</span>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-0 pb-3">
                <a href="{{ route('livestream.index') }}" class="btn btn-primary">
                    <i class="fas fa-vote-yea me-2"></i>Voter pour ce candidat
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h2 class="h4 mb-0">Vidéos</h2>
            </div>
            <div class="card-body">
                @if(count($videos) > 0)
                    <div class="row">
                        @foreach($videos as $video)
                            <div class="col-md-6 mb-4">
                                <div class="video-card">
                                    <div class="video-thumbnail mb-2">
                                        <img src="{{ $video->thumbnail ? asset('storage/' . $video->thumbnail) : 'https://img.youtube.com/vi/' . \App\Helpers\YoutubeHelper::getYoutubeId($video->url) . '/maxresdefault.jpg' }}" 
                                             alt="{{ $video->title }}" 
                                             class="img-fluid rounded">
                                        <div class="play-icon">
                                            <i class="fas fa-play-circle"></i>
                                        </div>
                                        <a href="{{ $video->url }}" class="stretched-link" target="_blank" rel="noopener noreferrer"></a>
                                    </div>
                                    <h3 class="h6 mb-1">{{ $video->title }}</h3>
                                    <p class="small text-muted">
                                        <i class="far fa-calendar-alt me-1"></i>{{ $video->published_at ? $video->published_at->format('d/m/Y') : 'Non publié' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="mb-3">
                            <i class="fas fa-video-slash fa-3x text-muted"></i>
                        </div>
                        <p class="mb-0">Aucune vidéo disponible pour le moment.</p>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h2 class="h4 mb-0">À propos du concours</h2>
            </div>
            <div class="card-body">
                <p>Balance Ton Flow est un concours de rap qui met en valeur les talents émergents. Les participants s'affrontent lors de différentes phases pour remporter le titre.</p>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Comment soutenir ce candidat:</strong> Connectez-vous et votez pendant le live ou consultez ses vidéos pour découvrir son univers.
                </div>
                
                <div class="text-center mt-4">
                    <a href="{{ route('candidates.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Tous les candidats
                    </a>
                    <a href="{{ route('livestream.index') }}" class="btn btn-primary ms-2">
                        <i class="fas fa-play-circle me-2"></i>Accéder au live
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
