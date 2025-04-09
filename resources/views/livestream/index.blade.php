@extends('layouts.app')

@section('title', 'Balance Ton Flow - Live')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h2 mb-3">Livestream {{ $livestream->phase == 'semi-final' ? 'Demi-Finale' : 'Finale' }}</h1>
        <p class="lead">{{ $livestream->title }}</p>
        <p>{{ $livestream->description }}</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="livestream-container mb-4">
            <iframe src="{{ $livestream->embed_url }}" allowfullscreen></iframe>
        </div>
        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h3 class="h4 mb-3">Votez pour votre candidat préféré</h3>
                
                @guest
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Vous devez être connecté pour voter.
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm ms-3">Se connecter</a>
                    </div>
                @else
                    @if($userVoted)
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>Merci pour votre vote ! Vous pouvez modifier votre vote à tout moment.
                        </div>
                    @endif
                    
                    <div class="row" id="vote-buttons">
                        @foreach($candidates as $candidate)
                            <div class="col-md-4 mb-3">
                                <button class="btn btn-outline-primary vote-btn w-100" 
                                        data-candidate-id="{{ $candidate->id }}"
                                        onclick="castVote({{ $candidate->id }})">
                                    <img src="{{ $candidate->photo ? asset('storage/' . $candidate->photo) : asset('images/default-avatar.png') }}" 
                                         alt="{{ $candidate->name }}" 
                                         class="rounded-circle me-2" 
                                         width="30" height="30">
                                    {{ $candidate->name }}
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endguest
                
                <div class="text-center mt-4">
                    <a href="{{ route('livestream.results') }}" class="btn btn-secondary">
                        <i class="fas fa-chart-bar me-2"></i>Voir les résultats
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="h5 mb-0">Candidats en compétition</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($candidates as $candidate)
                        <li class="list-group-item d-flex align-items-center p-3">
                            <img src="{{ $candidate->photo ? asset('storage/' . $candidate->photo) : asset('images/default-avatar.png') }}" 
                                 alt="{{ $candidate->name }}" 
                                 class="rounded-circle me-3" 
                                 width="50" height="50">
                            <div>
                                <h4 class="h6 mb-1">{{ $candidate->name }}</h4>
                                <p class="small text-muted mb-0">{{ Str::limit($candidate->description, 50) }}</p>
                            </div>
                            <a href="{{ route('candidates.show', $candidate) }}" class="btn btn-sm btn-outline-primary ms-auto">Profil</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h3 class="h5 mb-0">Informations</h3>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <i class="fas fa-calendar-alt me-2 text-primary"></i>
                        <strong>Date:</strong> {{ $livestream->start_time->format('d/m/Y') }}
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-clock me-2 text-primary"></i>
                        <strong>Heure:</strong> {{ $livestream->start_time->format('H:i') }} - {{ $livestream->end_time->format('H:i') }}
                    </li>
                    <li>
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        <strong>Phase:</strong> {{ $livestream->phase == 'semi-final' ? 'Demi-Finale' : 'Finale' }}
                    </li>
                </ul>
                
                <div class="alert alert-info mt-4">
                    <i class="fas fa-lightbulb me-2"></i>
                    <strong>Comment voter:</strong> Connectez-vous et cliquez sur le bouton correspondant à votre candidat préféré.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function castVote(candidateId) {
        // Disable all vote buttons during the request
        const voteButtons = document.querySelectorAll('.vote-btn');
        voteButtons.forEach(button => {
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Traitement...';
        });
        
        // Send vote request
        axios.post('{{ route("vote.store") }}', {
            candidate_id: candidateId,
            vote_type: 'live'
        })
        .then(response => {
            // Re-enable buttons and update UI
            voteButtons.forEach(button => {
                button.disabled = false;
                
                // Get the original content
                const candidateIdAttr = button.getAttribute('data-candidate-id');
                if (candidateIdAttr == candidateId) {
                    button.classList.remove('btn-outline-primary');
                    button.classList.add('btn-primary');
                } else {
                    button.classList.remove('btn-primary');
                    button.classList.add('btn-outline-primary');
                }
                
                // Restore original button content
                const candidateImg = button.querySelector('img');
                const candidateName = button.textContent.trim();
                button.innerHTML = '';
                if (candidateImg) {
                    button.appendChild(candidateImg);
                }
                button.innerHTML += ' ' + candidateName;
            });
            
            // Show success message
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
            alertDiv.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>${response.data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            const voteButtonsDiv = document.getElementById('vote-buttons');
            voteButtonsDiv.parentNode.insertBefore(alertDiv, voteButtonsDiv.nextSibling);
            
            // Auto-dismiss alert after 5 seconds
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alertDiv);
                bsAlert.close();
            }, 5000);
        })
        .catch(error => {
            // Re-enable buttons
            voteButtons.forEach(button => {
                button.disabled = false;
                
                // Restore original button content
                const candidateId = button.getAttribute('data-candidate-id');
                const candidateImg = button.querySelector('img');
                const candidateName = button.textContent.trim();
                button.innerHTML = '';
                if (candidateImg) {
                    button.appendChild(candidateImg);
                }
                button.innerHTML += ' ' + candidateName;
            });
            
            // Show error message
            let errorMessage = 'Une erreur est survenue lors du vote.';
            if (error.response && error.response.data && error.response.data.error) {
                errorMessage = error.response.data.error;
            }
            
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
            alertDiv.innerHTML = `
                <i class="fas fa-exclamation-circle me-2"></i>${errorMessage}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            const voteButtonsDiv = document.getElementById('vote-buttons');
            voteButtonsDiv.parentNode.insertBefore(alertDiv, voteButtonsDiv.nextSibling);
            
            // Auto-dismiss alert after 5 seconds
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alertDiv);
                bsAlert.close();
            }, 5000);
        });
    }
</script>
@endsection
