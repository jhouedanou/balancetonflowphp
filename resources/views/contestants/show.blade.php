@extends('layouts.app')

@section('title', 'Balance Ton Flow - ' . $contestant->name)

@section('content')
<div class="row mb-5">
    <div class="col-md-4 mb-4 mb-md-0">
        <div class="card border-0 shadow-sm h-100">
            <img src="{{ $contestant->photo ? asset('storage/' . $contestant->photo) : asset('images/default-avatar.png') }}" 
                 class="card-img-top contestant-img" 
                 alt="{{ $contestant->name }}">
            <div class="card-body">
                <h1 class="h3 card-title">{{ $contestant->name }}</h1>
                
                @if($contestant->is_finalist)
                    <div class="mb-3">
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-star me-1"></i>Finaliste
                        </span>
                    </div>
                @endif
                
                <p class="card-text">{{ $contestant->bio }}</p>
                
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
                    <i class="fas fa-vote-yea me-2"></i>Voter pour ce contestant
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
                                        
                                        @if(\App\Helpers\YoutubeHelper::isTikTokUrl($video->url))
                                            <a href="#" class="stretched-link" data-bs-toggle="modal" data-bs-target="#videoModal" 
                                               data-video-title="{{ $video->title }}" 
                                               data-video-type="tiktok"
                                               data-video-url="{{ \App\Helpers\YoutubeHelper::getTikTokEmbedUrl($video->url) }}"></a>
                                        @elseif(\App\Helpers\YoutubeHelper::isYoutubeUrl($video->url))
                                            <a href="#" class="stretched-link" data-bs-toggle="modal" data-bs-target="#videoModal" 
                                               data-video-title="{{ $video->title }}" 
                                               data-video-type="youtube"
                                               data-video-url="{{ \App\Helpers\YoutubeHelper::getYoutubeEmbedUrl($video->url) }}"></a>
                                        @else
                                            <a href="{{ $video->url }}" class="stretched-link" target="_blank" rel="noopener noreferrer"></a>
                                        @endif
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
                    <strong>Comment soutenir ce contestant:</strong> Connectez-vous et votez pendant le live ou consultez ses vidéos pour découvrir son univers.
                </div>
                
                <div class="text-center mt-4">
                    <a href="{{ route('contestants.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Tous les contestants
                    </a>
                    <a href="{{ route('livestream.index') }}" class="btn btn-primary ms-2">
                        <i class="fas fa-play-circle me-2"></i>Accéder au live
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les vidéos TikTok et YouTube -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ratio ratio-9x16 tiktok-embed-container">
                    <iframe id="tiktokFrame" src="" allowfullscreen></iframe>
                </div>
                <div class="ratio ratio-16x9 youtube-embed-container" style="display: none;">
                    <div id="youtubePlayer"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- API YouTube IFrame -->
<script src="https://www.youtube.com/iframe_api"></script>
<script>
    // Variable globale pour le player YouTube
    let youtubePlayer;
    
    // Fonction appelée par l'API YouTube IFrame une fois chargée
    function onYouTubeIframeAPIReady() {
        // Le player sera initialisé dans l'event listener du modal
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion du modal vidéo
        const videoModal = document.getElementById('videoModal');
        if (videoModal) {
            videoModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const videoTitle = button.getAttribute('data-video-title');
                const videoType = button.getAttribute('data-video-type');
                const videoUrl = button.getAttribute('data-video-url');
                
                document.getElementById('videoModalLabel').textContent = videoTitle;
                
                if (videoType === 'tiktok') {
                    document.getElementById('tiktokFrame').src = videoUrl;
                    document.querySelector('.tiktok-embed-container').style.display = 'block';
                    document.querySelector('.youtube-embed-container').style.display = 'none';
                    
                    // Détruire le player YouTube s'il existe
                    if (youtubePlayer) {
                        youtubePlayer.destroy();
                        youtubePlayer = null;
                    }
                } else if (videoType === 'youtube') {
                    document.querySelector('.tiktok-embed-container').style.display = 'none';
                    document.querySelector('.youtube-embed-container').style.display = 'block';
                    
                    // Extraire l'ID de la vidéo YouTube de l'URL
                    const videoId = videoUrl.split('embed/')[1].split('?')[0];
                    
                    // Si le player existe déjà, le charger avec la nouvelle vidéo
                    if (youtubePlayer) {
                        youtubePlayer.loadVideoById(videoId);
                        youtubePlayer.playVideo();
                    } else {
                        // Initialiser un nouveau player YouTube
                        youtubePlayer = new YT.Player('youtubePlayer', {
                            videoId: videoId,
                            playerVars: {
                                'autoplay': 1,
                                'rel': 0,
                                'modestbranding': 1
                            },
                            events: {
                                'onReady': function(event) {
                                    event.target.playVideo();
                                }
                            }
                        });
                    }
                }
            });
            
            // Réinitialiser les iframes lors de la fermeture du modal pour arrêter les vidéos
            videoModal.addEventListener('hidden.bs.modal', function() {
                document.getElementById('tiktokFrame').src = '';
                
                // Mettre en pause le player YouTube s'il existe
                if (youtubePlayer) {
                    youtubePlayer.pauseVideo();
                }
            });
        }
    });
</script>
@endsection
