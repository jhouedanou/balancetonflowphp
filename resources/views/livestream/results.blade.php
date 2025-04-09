@extends('layouts.app')

@section('title', 'Balance Ton Flow - Résultats du vote')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h2 mb-3">Résultats du vote en direct</h1>
        <p class="lead">{{ $livestream->title }}</p>
        <div class="d-flex justify-content-between align-items-center">
            <p>{{ $livestream->description }}</p>
            <a href="{{ route('livestream.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>Retour au live
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h3 class="h4 mb-4">Résultats actuels</h3>
                
                <div id="results-container">
                    @php
                        $totalVotes = array_sum($voteCounts);
                        $maxVotes = max($voteCounts) > 0 ? max($voteCounts) : 1;
                    @endphp
                    
                    @foreach($candidates as $candidate)
                        @php
                            $voteCount = $voteCounts[$candidate->id] ?? 0;
                            $percentage = $totalVotes > 0 ? round(($voteCount / $totalVotes) * 100) : 0;
                            $barWidth = $maxVotes > 0 ? round(($voteCount / $maxVotes) * 100) : 0;
                        @endphp
                        
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ $candidate->photo ? asset('storage/' . $candidate->photo) : asset('images/default-avatar.png') }}" 
                                     alt="{{ $candidate->name }}" 
                                     class="rounded-circle me-3" 
                                     width="40" height="40">
                                <div>
                                    <h4 class="h6 mb-0">{{ $candidate->name }}</h4>
                                    <p class="small text-muted mb-0">{{ $voteCount }} vote(s)</p>
                                </div>
                                <div class="ms-auto">
                                    <span class="badge bg-primary">{{ $percentage }}%</span>
                                </div>
                            </div>
                            
                            <div class="results-bar">
                                <div class="results-bar-fill" style="width: {{ $barWidth }}%">
                                    {{ $percentage > 10 ? $percentage . '%' : '' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="text-center mt-5">
                        <p class="text-muted">Total des votes: <strong>{{ $totalVotes }}</strong></p>
                        <p class="small text-muted">Les résultats sont mis à jour automatiquement toutes les 10 secondes</p>
                    </div>
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
                    <strong>Comment voter:</strong> Retournez au live, connectez-vous et cliquez sur le bouton correspondant à votre candidat préféré.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Function to update results
    function updateResults() {
        axios.get('{{ route("admin.votes.stats") }}', {
            params: {
                vote_type: 'live'
            }
        })
        .then(response => {
            const data = response.data;
            const voteCounts = data.vote_counts;
            const totalVotes = data.total_votes;
            
            // Find the maximum vote count
            let maxVotes = 1;
            for (const candidateId in voteCounts) {
                if (voteCounts[candidateId].count > maxVotes) {
                    maxVotes = voteCounts[candidateId].count;
                }
            }
            
            // Update each candidate's results
            const resultsContainer = document.getElementById('results-container');
            const candidateElements = resultsContainer.querySelectorAll('.mb-4');
            
            candidateElements.forEach((element, index) => {
                const candidateId = {{ Js::from($candidates->pluck('id')) }}[index];
                const voteCount = voteCounts[candidateId] ? voteCounts[candidateId].count : 0;
                const percentage = totalVotes > 0 ? Math.round((voteCount / totalVotes) * 100) : 0;
                const barWidth = maxVotes > 0 ? Math.round((voteCount / maxVotes) * 100) : 0;
                
                // Update vote count
                const voteCountElement = element.querySelector('.small.text-muted');
                voteCountElement.textContent = `${voteCount} vote(s)`;
                
                // Update percentage badge
                const percentageBadge = element.querySelector('.badge');
                percentageBadge.textContent = `${percentage}%`;
                
                // Update progress bar
                const progressBar = element.querySelector('.results-bar-fill');
                progressBar.style.width = `${barWidth}%`;
                progressBar.textContent = percentage > 10 ? `${percentage}%` : '';
            });
            
            // Update total votes
            const totalVotesElement = resultsContainer.querySelector('p.text-muted strong');
            totalVotesElement.textContent = totalVotes;
        })
        .catch(error => {
            console.error('Error updating results:', error);
        });
    }
    
    // Update results every 10 seconds
    document.addEventListener('DOMContentLoaded', function() {
        // Initial update
        setTimeout(updateResults, 2000);
        
        // Set interval for updates
        setInterval(updateResults, 10000);
    });
</script>
@endsection
