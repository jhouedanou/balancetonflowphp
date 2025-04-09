@extends('layouts.app')

@section('title', 'Balance Ton Flow - Administration')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h1 class="h2 mb-0">Tableau de bord administrateur</h1>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3 mb-4 mb-md-0">
        <div class="dashboard-stat">
            <i class="fas fa-users fa-2x text-primary mb-3"></i>
            <h3>{{ $stats['users'] }}</h3>
            <p>Utilisateurs</p>
        </div>
    </div>
    <div class="col-md-3 mb-4 mb-md-0">
        <div class="dashboard-stat">
            <i class="fas fa-user-alt fa-2x text-primary mb-3"></i>
            <h3>{{ $stats['candidates'] }}</h3>
            <p>Candidats</p>
            <span class="badge bg-warning text-dark">{{ $stats['finalists'] }} finalistes</span>
        </div>
    </div>
    <div class="col-md-3 mb-4 mb-md-0">
        <div class="dashboard-stat">
            <i class="fas fa-vote-yea fa-2x text-primary mb-3"></i>
            <h3>{{ $stats['votes']['total'] }}</h3>
            <p>Votes totaux</p>
            <div class="d-flex justify-content-center">
                <span class="badge bg-info me-2">{{ $stats['votes']['live'] }} live</span>
                <span class="badge bg-secondary">{{ $stats['votes']['post'] }} post</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-stat">
            <i class="fas fa-video fa-2x text-primary mb-3"></i>
            <h3>{{ $stats['videos'] }}</h3>
            <p>Vidéos</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 class="h5 mb-0">Livestream actif</h3>
                <a href="{{ route('admin.livestream') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-cog me-1"></i>Gérer
                </a>
            </div>
            <div class="card-body">
                @if($stats['active_livestream'])
                    <div class="d-flex align-items-center mb-3">
                        @if($stats['active_livestream']->thumbnail)
                            <img src="{{ asset('storage/' . $stats['active_livestream']->thumbnail) }}" 
                                 alt="{{ $stats['active_livestream']->title }}" 
                                 class="me-3 rounded" 
                                 width="100">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center me-3 rounded" style="width: 100px; height: 60px;">
                                <i class="fas fa-video fa-2x text-muted"></i>
                            </div>
                        @endif
                        <div>
                            <h4 class="h6 mb-1">{{ $stats['active_livestream']->title }}</h4>
                            <p class="small text-muted mb-1">
                                <i class="fas fa-calendar-alt me-1"></i>{{ $stats['active_livestream']->start_time->format('d/m/Y H:i') }}
                            </p>
                            <span class="badge bg-success">En direct</span>
                            <span class="badge bg-primary">{{ $stats['active_livestream']->phase == 'semi-final' ? 'Demi-finale' : 'Finale' }}</span>
                        </div>
                    </div>
                    
                    <div class="d-flex mt-3">
                        <a href="{{ route('livestream.index') }}" class="btn btn-sm btn-outline-primary me-2">
                            <i class="fas fa-eye me-1"></i>Voir
                        </a>
                        <a href="{{ route('livestream.results') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-chart-bar me-1"></i>Résultats
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="mb-3">
                            <i class="fas fa-video-slash fa-3x text-muted"></i>
                        </div>
                        <p class="mb-3">Aucun livestream actif pour le moment.</p>
                        <a href="{{ route('admin.livestream') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Créer un livestream
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white">
                <h3 class="h5 mb-0">Gestion de la plateforme</h3>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="{{ route('admin.candidates') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-user-alt me-2 text-primary"></i>
                            Gestion des candidats
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $stats['candidates'] }}</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-users me-2 text-primary"></i>
                            Gestion des utilisateurs
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $stats['users'] }}</span>
                    </a>
                    <a href="{{ route('admin.videos') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-video me-2 text-primary"></i>
                            Gestion des vidéos
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $stats['videos'] }}</span>
                    </a>
                    <a href="{{ route('admin.votes') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-vote-yea me-2 text-primary"></i>
                            Statistiques des votes
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $stats['votes']['total'] }}</span>
                    </a>
                    <a href="{{ route('admin.livestream') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-broadcast-tower me-2 text-primary"></i>
                            Gestion des livestreams
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $stats['livestreams'] }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h3 class="h5 mb-0">Activité récente</h3>
            </div>
            <div class="card-body">
                <canvas id="activityChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sample data for activity chart
        const ctx = document.getElementById('activityChart').getContext('2d');
        const activityChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['7 jours', '6 jours', '5 jours', '4 jours', '3 jours', '2 jours', 'Aujourd\'hui'],
                datasets: [
                    {
                        label: 'Votes',
                        data: [65, 78, 90, 81, 56, 85, 40],
                        borderColor: '#ff5722',
                        backgroundColor: 'rgba(255, 87, 34, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Utilisateurs',
                        data: [28, 48, 40, 19, 36, 27, 20],
                        borderColor: '#3d5afe',
                        backgroundColor: 'rgba(61, 90, 254, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Activité des 7 derniers jours'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection
