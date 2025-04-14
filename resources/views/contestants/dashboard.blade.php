@extends('layouts.app')

@section('title', 'Balance Ton Flow - Espace Contestant')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h1 class="h2 mb-0">Mon espace contestant</h1>
        <a href="{{ route('contestants.edit') }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>Modifier mon profil
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <img src="{{ $contestant->photo ? asset('storage/' . $contestant->photo) : asset('images/default-avatar.png') }}" 
                     alt="{{ $contestant->name }}" 
                     class="rounded-circle mb-3" 
                     width="120" height="120">
                <h2 class="h4 mb-2">{{ $contestant->name }}</h2>
                
                @if($contestant->is_finalist)
                    <div class="mb-3">
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-star me-1"></i>Finaliste
                        </span>
                    </div>
                @endif
                
                <p class="text-muted">{{ Str::limit($contestant->bio, 150) }}</p>
                
                <div class="d-flex justify-content-around mt-4">
                    <div class="text-center">
                        <h3 class="h2 mb-0">{{ $voteStats['total'] }}</h3>
                        <p class="small text-muted">Votes totaux</p>
                    </div>
                    <div class="text-center">
                        <h3 class="h2 mb-0">{{ count($videos) }}</h3>
                        <p class="small text-muted">Vidéos</p>
                    </div>
                </div>
                
                <a href="{{ route('contestants.show', $contestant) }}" class="btn btn-outline-primary mt-3">
                    <i class="fas fa-eye me-2"></i>Voir mon profil public
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 class="h5 mb-0">Statistiques</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="dashboard-stat">
                            <i class="fas fa-vote-yea fa-2x text-primary mb-3"></i>
                            <h3>{{ $voteStats['live'] }}</h3>
                            <p>Votes en direct</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="dashboard-stat">
                            <i class="fas fa-thumbs-up fa-2x text-primary mb-3"></i>
                            <h3>{{ $voteStats['post'] }}</h3>
                            <p>Votes post-live</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dashboard-stat">
                            <i class="fas fa-video fa-2x text-primary mb-3"></i>
                            <h3>{{ $publishedVideos }}</h3>
                            <p>Vidéos publiées</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 class="h5 mb-0">Mes vidéos</h3>
                <a href="{{ route('contestants.videos.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-2"></i>Ajouter une vidéo
                </a>
            </div>
            <div class="card-body">
                @if(count($videos) > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($videos as $video)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="video-thumbnail me-2">
                                                    <img src="{{ $video->thumbnail ? asset('storage/' . $video->thumbnail) : asset('images/default-thumbnail.png') }}" 
                                                         alt="{{ $video->title }}" 
                                                         width="50" height="30"
                                                         class="rounded">
                                                </div>
                                                <span>{{ $video->title }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($video->is_published)
                                                <span class="badge bg-success">Publiée</span>
                                            @else
                                                <span class="badge bg-secondary">Brouillon</span>
                                            @endif
                                        </td>
                                        <td>{{ $video->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ $video->url }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('contestants.videos.edit', $video) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteVideoModal" 
                                                        data-video-id="{{ $video->id }}" 
                                                        data-video-title="{{ $video->title }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="mb-3">
                            <i class="fas fa-video-slash fa-3x text-muted"></i>
                        </div>
                        <p class="mb-3">Vous n'avez pas encore ajouté de vidéos.</p>
                        <a href="{{ route('contestants.videos.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Ajouter ma première vidéo
                        </a>
                    </div>
                @endif
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
                <p>Êtes-vous sûr de vouloir supprimer la vidéo <span id="videoTitle"></span> ?</p>
                <p class="text-danger">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteVideoForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete video modal
        const deleteVideoModal = document.getElementById('deleteVideoModal');
        if (deleteVideoModal) {
            deleteVideoModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const videoId = button.getAttribute('data-video-id');
                const videoTitle = button.getAttribute('data-video-title');
                
                document.getElementById('videoTitle').textContent = `"${videoTitle}"`;
                document.getElementById('deleteVideoForm').action = `{{ route('contestants.videos.destroy', '') }}/${videoId}`;
            });
        }
    });
</script>
@endsection
