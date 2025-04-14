<?php

namespace App\Http\Controllers;

use App\Models\Contestant;
use App\Models\User;
use App\Models\Video;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContestantController extends Controller
{
    /**
     * Display a listing of all contestants.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $contestants = Contestant::all();
        return view('contestants.index', compact('contestants'));
    }
    
    /**
     * Display the specified contestant's profile.
     *
     * @param  \App\Models\Contestant  $contestant
     * @return \Illuminate\View\View
     */
    public function show(Contestant $contestant)
    {
        // Utiliser la relation videos
        $videos = $contestant->videos()->where('is_published', true)->orderBy('published_at', 'desc')->get();
        
        // Utiliser contestant_id pour compter les votes
        $voteCount = Vote::where('contestant_id', $contestant->id)->count();
        
        return view('contestants.show', compact('contestant', 'videos', 'voteCount'));
    }
    
    /**
     * Show the form for editing the contestant's profile.
     *
     * @param  \App\Models\Contestant  $contestant
     * @return \Illuminate\View\View
     */
    public function edit(Contestant $contestant)
    {
        // Check if user is authorized to edit this contestant
        $this->authorize('update', $contestant);
        
        return view('contestants.edit', compact('contestant'));
    }
    
    /**
     * Update the specified contestant's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contestant  $contestant
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Contestant $contestant)
    {
        // Check if user is authorized to update this contestant
        $this->authorize('update', $contestant);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($contestant->photo && Storage::disk('public')->exists($contestant->photo)) {
                Storage::disk('public')->delete($contestant->photo);
            }
            
            $path = $request->file('photo')->store('contestants', 'public');
            $validated['photo'] = $path;
        }
        
        $contestant->update($validated);
        
        return redirect()->route('contestants.show', $contestant)
            ->with('success', 'Profile updated successfully');
    }
    
    /**
     * Display the contestant's dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $user = Auth::user();
        $contestant = $user->contestant;
        
        if (!$contestant) {
            return redirect()->route('contestants.index')
                ->with('error', 'You do not have a contestant profile');
        }
        
        $videos = $contestant->videos()->orderBy('created_at', 'desc')->get();
        $publishedVideos = $videos->where('is_published', true)->count();
        $draftVideos = $videos->where('is_published', false)->count();
        
        $voteStats = [
            'total' => $contestant->votes()->count(),
            'live' => $contestant->votes()->where('vote_type', 'live')->count(),
            'post' => $contestant->votes()->where('vote_type', 'post')->count(),
        ];
        
        return view('contestants.dashboard', compact('contestant', 'videos', 'publishedVideos', 'draftVideos', 'voteStats'));
    }
    
    /**
     * Show the form for creating a new video.
     *
     * @return \Illuminate\View\View
     */
    public function createVideo()
    {
        $user = Auth::user();
        $contestant = $user->contestant;
        
        if (!$contestant) {
            return redirect()->route('contestants.index')
                ->with('error', 'You do not have a contestant profile');
        }
        
        return view('contestants.videos.create', compact('contestant'));
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
        $contestant = $user->contestant;
        
        if (!$contestant) {
            return redirect()->route('contestants.index')
                ->with('error', 'You do not have a contestant profile');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|url',
            'thumbnail' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
        ]);
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('videos', 'public');
            $validated['thumbnail'] = $path;
        }
        
        // Set default values
        $validated['contestant_id'] = $contestant->id;
        
        // Determine if the video should be published
        $isPublished = $request->has('is_published') ? (bool) $request->input('is_published') : false;
        $validated['is_published'] = $isPublished;
        
        // Set publish date if the video is published
        if ($isPublished) {
            $validated['published_at'] = now();
        }
        
        $video = Video::create($validated);
        
        return redirect()->route('contestants.dashboard')
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
        $contestant = $user->contestant;
        
        if (!$contestant || $video->contestant_id !== $contestant->id) {
            return redirect()->route('contestants.dashboard')
                ->with('error', 'You are not authorized to edit this video');
        }
        
        return view('contestants.videos.edit', compact('video', 'contestant'));
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
        $contestant = $user->contestant;
        
        if (!$contestant || $video->contestant_id !== $contestant->id) {
            return redirect()->route('contestants.dashboard')
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
            // Si is_published est présent dans la requête, même avec valeur false
            $isPublished = (bool) $request->input('is_published');
            $validated['is_published'] = $isPublished;
            
            // Si on publie pour la première fois, définir la date de publication
            if ($isPublished && !$video->is_published) {
                $validated['published_at'] = now();
            }
        }
        
        $video->update($validated);
        
        return redirect()->route('contestants.dashboard')
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
        $contestant = $user->contestant;
        
        if (!$contestant || $video->contestant_id !== $contestant->id) {
            return redirect()->route('contestants.dashboard')
                ->with('error', 'You are not authorized to delete this video');
        }
        
        // Delete thumbnail if exists
        if ($video->thumbnail && Storage::disk('public')->exists($video->thumbnail)) {
            Storage::disk('public')->delete($video->thumbnail);
        }
        
        $video->delete();
        
        return redirect()->route('contestants.dashboard')
            ->with('success', 'Video deleted successfully');
    }
}
