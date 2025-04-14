<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Balance Ton Flow')</title>
    <meta name="description" content="Plateforme de vote en direct pour le concours Balance Ton Flow">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #ff5722;
            --secondary-color: #3d5afe;
            --dark-color: #212121;
            --light-color: #f5f5f5;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-color);
            color: var(--dark-color);
        }
        
        .navbar {
            background-color: var(--dark-color);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #e64a19;
            border-color: #e64a19;
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-secondary:hover {
            background-color: #303f9f;
            border-color: #303f9f;
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .footer {
            background-color: var(--dark-color);
            color: white;
            padding: 20px 0;
            margin-top: 50px;
        }
        
        /* Vote buttons */
        .vote-btn {
            padding: 15px 30px;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 50px;
            margin: 10px;
            transition: all 0.3s ease;
        }
        
        .vote-btn:hover {
            transform: scale(1.05);
        }
        
        /* Livestream container */
        .livestream-container {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
            height: 0;
            overflow: hidden;
            max-width: 100%;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }
        
        .livestream-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }
        
        /* Candidate cards */
        .candidate-card {
            transition: all 0.3s ease;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .candidate-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
        }
        
        .candidate-img {
            height: 250px;
            object-fit: cover;
        }
        
        /* Video cards */
        .video-card {
            transition: all 0.3s ease;
        }
        
        .video-card:hover {
            transform: translateY(-5px);
        }
        
        .video-thumbnail {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
        }
        
        .video-thumbnail img {
            transition: all 0.5s ease;
        }
        
        .video-thumbnail:hover img {
            transform: scale(1.05);
        }
        
        .play-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 3rem;
            opacity: 0.8;
            transition: all 0.3s ease;
        }
        
        .video-thumbnail:hover .play-icon {
            opacity: 1;
            font-size: 3.5rem;
        }
        
        /* Dashboard */
        .dashboard-stat {
            padding: 20px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 20px;
        }
        
        .dashboard-stat h3 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .dashboard-stat p {
            font-size: 1rem;
            color: #666;
            margin-bottom: 0;
        }
        
        /* Auth buttons */
        .social-login-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 20px;
            font-weight: 600;
            border-radius: 50px;
            margin-bottom: 15px;
            width: 100%;
            text-align: center;
        }
        
        .google-btn {
            background-color: #DB4437;
            color: white;
        }
        
        .facebook-btn {
            background-color: #4267B2;
            color: white;
        }
        
        .social-login-btn i {
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        /* Results */
        .results-bar {
            height: 30px;
            border-radius: 15px;
            margin-bottom: 10px;
            background-color: #e0e0e0;
            overflow: hidden;
        }
        
        .results-bar-fill {
            height: 100%;
            background-color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 10px;
            color: white;
            font-weight: 600;
            transition: width 1s ease-in-out;
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        /* CSS personnalisé pour l'application */
        .tiktok-embed-container {
            position: relative;
            width: 100%;
            max-width: 325px;  /* Largeur standard d'un TikTok */
            margin: 0 auto;
            height: 575px;     /* Hauteur adaptée au format vertical */
            overflow: hidden;
        }

        .tiktok-embed-container iframe {
            border: 0;
            height: 100%;
            width: 100%;
        }

        .youtube-embed-container {
            position: relative;
            width: 100%;
            margin: 0 auto;
            overflow: hidden;
        }

        .youtube-embed-container iframe {
            border: 0;
            height: 100%;
            width: 100%;
        }

        .video-thumbnail {
            position: relative;
            cursor: pointer;
            padding-top: 56.25%;  /* Ratio 16:9 */
            overflow: hidden;
        }

        .video-thumbnail img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .video-thumbnail:hover img {
            transform: scale(1.05);
        }

        .play-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 3rem;
            opacity: 0.8;
            text-shadow: 0 0 10px rgba(0,0,0,0.5);
            transition: opacity 0.3s ease;
        }

        .video-thumbnail:hover .play-icon {
            opacity: 1;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <i class="fas fa-music me-2"></i>Balance Ton Flow
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('livestream.*') ? 'active' : '' }}" href="{{ route('livestream.index') }}">Live</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('candidates.index') ? 'active' : '' }}" href="{{ route('candidates.index') }}">Candidats</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="rounded-circle me-1" width="25" height="25">
                                    @else
                                        <i class="fas fa-user-circle me-1"></i>
                                    @endif
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    @if(Auth::user()->is_admin)
                                        <li><a class="dropdown-item" href="{{ url('/admin') }}"><i class="fas fa-tachometer-alt me-2"></i>Admin</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                    {{-- Temporairement commenté pour éviter l'erreur de relation candidate
                                    @if(Auth::user()->candidate)
                                        <li><a class="dropdown-item" href="{{ route('candidates.dashboard') }}"><i class="fas fa-user-alt me-2"></i>Mon espace</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                    --}}
                                    <li>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link btn btn-outline-light btn-sm px-3 py-1 mt-1" href="{{ route('login') }}">Connexion</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Balance Ton Flow</h5>
                    <p>Plateforme officielle du concours Balance Ton Flow.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; {{ date('Y') }} Balance Ton Flow. Tous droits réservés.</p>
                    <div class="mt-2">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <script>
        // Common JavaScript for all pages
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(tooltip => {
                new bootstrap.Tooltip(tooltip);
            });
            
            // Auto-dismiss alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>
