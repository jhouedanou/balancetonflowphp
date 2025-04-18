<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\User;
use App\Models\Video;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    /**
     * Display a listing of all candidates.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $candidates = Candidate::all();
        
        // Débogage: Vérifier si des candidats sont récupérés
        if ($candidates->isEmpty()) {
            // Si aucun candidat n'est trouvé, essayer de récupérer les contestants et les utiliser à la place
            $contestants = \App\Models\Contestant::all();
            if (!$contestants->isEmpty()) {
                return view('contestants.index', compact('contestants'));
            }
        }
        
        return view('candidates.index', compact('candidates'));
    }
    
    /**
     * Display the specified candidate's profile.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\View\View
     */
    public function show(Candidate $candidate)
    {
        $contestant = Contestant::find($candidate->id);
        if ($contestant) {
            return redirect()->route('contestants.show', $contestant);
        }
        
        // Fallback to old logic if contestant not found
        $videos = $candidate->videos()->where('is_published', true)->orderBy('published_at', 'desc')->get();
        $voteCount = Vote::where('candidate_id', $candidate->id)->count();
        
        return view('candidates.show', compact('candidate', 'videos', 'voteCount'));
    }
    
    /**
     * Show the form for editing the candidate's profile.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\View\View
     */
    public function edit(Candidate $candidate)
    {
        // Check if user is authorized to edit this candidate
        $this->authorize('update', $candidate);
        
        return view('candidates.edit', compact('candidate'));
    }
    
    /**
     * Update the specified candidate's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Candidate $candidate)
    {
        // Check if user is authorized to update this candidate
        $this->authorize('update', $candidate);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($candidate->photo && Storage::disk('public')->exists($candidate->photo)) {
                Storage::disk('public')->delete($candidate->photo);
            }
            
            $path = $request->file('photo')->store('candidates', 'public');
            $validated['photo'] = $path;
        }
        
        $candidate->update($validated);
        
        return redirect()->route('candidates.show', $candidate)
            ->with('success', 'Profile updated successfully');
    }
    
    /**
     * Display the candidate's dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $user = Auth::user();
        $candidate = $user->candidate;
        
        if (!$candidate) {
            return redirect()->route('candidates.index')
                ->with('error', 'You do not have a candidate profile');
        }
        
        $videos = $candidate->videos()->orderBy('created_at', 'desc')->get();
        $publishedVideos = $videos->where('is_published', true)->count();
        $draftVideos = $videos->where('is_published', false)->count();
        
        $voteStats = [
            'total' => $candidate->votes()->count(),
            'live' => $candidate->votes()->where('vote_type', 'live')->count(),
            'post' => $candidate->votes()->where('vote_type', 'post')->count(),
        ];
        
        return view('candidates.dashboard', compact('candidate', 'videos', 'publishedVideos', 'draftVideos', 'voteStats'));
    }
    
    /**
     * Show the form for creating a new video.
     *
     * @return \Illuminate\View\View
     */
    public function createVideo()
    {
        $user = Auth::user();
        $candidate = $user->candidate;
        
        if (!$candidate) {
            return redirect()->route('candidates.index')
                ->with('error', 'You do not have a candidate profile');
        }
        
        return view('candidates.videos.create', compact('candidate'));
    }
    
    /**
     * Store a newly created video.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeVideo(Request $request)
    {
        $user = Auth::user();
        
        // Tentative de redirection vers le nouveau système contestant
        $contestant = $user->contestant;
        if ($contestant) {
            return app(ContestantController::class)->storeVideo($request);
        }
        
        $candidate = $user->candidate;
        
        if (!$candidate) {
            return redirect()->route('candidates.index')
                ->with('error', 'You do not have a candidate profile');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|url',
            'thumbnail' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('videos', 'public');
            $validated['thumbnail'] = $path;
        }
        
        // Set default values
        $validated['candidate_id'] = $candidate->id;
        
        // Determine if the video should be published
        if ($request->has('is_published')) {
            $isPublished = (bool) $request->input('is_published');
            
            if ($isPublished) {
                $validated['published_at'] = now();
            }
        }
        
        $video = Video::create($validated);
        
        return redirect()->route('candidates.dashboard')
            ->with('success', 'Video created successfully');
    }
    
    /**
     * Show the form for editing a video.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\View\View
     */
    public function editVideo(Video $video)
    {
        $user = Auth::user();
        
        // Tentative de redirection vers le nouveau système contestant
        $contestant = $user->contestant;
        if ($contestant) {
            return app(ContestantController::class)->editVideo($video);
        }
        
        $candidate = $user->candidate;
        
        if (!$candidate || $video->candidate_id !== $candidate->id) {
            return redirect()->route('candidates.dashboard')
                ->with('error', 'You are not authorized to edit this video');
        }
        
        return view('candidates.videos.edit', compact('video', 'candidate'));
    }
    
    /**
     * Update the specified video.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateVideo(Request $request, Video $video)
    {
        $user = Auth::user();
        
        // Tentative de redirection vers le nouveau système contestant
        $contestant = $user->contestant;
        if ($contestant) {
            return app(ContestantController::class)->updateVideo($request, $video);
        }
        
        $candidate = $user->candidate;
        
        if (!$candidate || $video->candidate_id !== $candidate->id) {
            return redirect()->route('candidates.dashboard')
                ->with('error', 'You are not authorized to update this video');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|url',
            'thumbnail' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($video->thumbnail && Storage::disk('public')->exists($video->thumbnail)) {
                Storage::disk('public')->delete($video->thumbnail);
            }
            
            $path = $request->file('thumbnail')->store('videos', 'public');
            $validated['thumbnail'] = $path;
        }
        
        // Traiter explicitement le toggle is_published
        if ($request->has('is_published')) {
            $isPublished = (bool) $request->input('is_published');
            
            if ($isPublished && !$video->is_published) {
                $validated['published_at'] = now();
            }
        }
        
        $video->update($validated);
        
        return redirect()->route('candidates.dashboard')
            ->with('success', 'Video updated successfully');
    }
    
    /**
     * Remove the specified video.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyVideo(Video $video)
    {
        $user = Auth::user();
        
        // Tentative de redirection vers le nouveau système contestant
        $contestant = $user->contestant;
        if ($contestant) {
            return app(ContestantController::class)->destroyVideo($video);
        }
        
        $candidate = $user->candidate;
        
        if (!$candidate || $video->candidate_id !== $candidate->id) {
            return redirect()->route('candidates.dashboard')
                ->with('error', 'You are not authorized to delete this video');
        }
        
        // Delete thumbnail if exists
        if ($video->thumbnail && Storage::disk('public')->exists($video->thumbnail)) {
            Storage::disk('public')->delete($video->thumbnail);
        }
        
        $video->delete();
        
        return redirect()->route('candidates.dashboard')
            ->with('success', 'Video deleted successfully');
    }
}
