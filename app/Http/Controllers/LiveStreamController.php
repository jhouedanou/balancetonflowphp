<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\LiveStream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiveStreamController extends Controller
{
    /**
     * Display the active livestream page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $livestream = LiveStream::where('is_active', true)->with('candidates')->first();
        
        if (!$livestream) {
            return view('livestream.offline');
        }
        
        $candidates = $livestream->candidates;
        $userVoted = false;
        
        if (Auth::check()) {
            $userVoted = Auth::user()->votes()
                ->where('vote_type', 'live')
                ->whereIn('contestant_id', $candidates->pluck('id'))
                ->exists();
        }
        
        return view('livestream.index', compact('livestream', 'candidates', 'userVoted'));
    }
    
    /**
     * Display the livestream results.
     *
     * @return \Illuminate\View\View
     */
    public function results()
    {
        $livestream = LiveStream::where('is_active', true)->with('candidates')->first();
        
        if (!$livestream) {
            return redirect()->route('livestream.index');
        }
        
        $candidates = $livestream->candidates;
        $voteCounts = [];
        
        foreach ($candidates as $candidate) {
            // Utiliser contestant_id au lieu de candidate_id pour compter les votes
            $voteCounts[$candidate->id] = $candidate->votes()->where('vote_type', 'live')->count();
        }
        
        return view('livestream.results', compact('livestream', 'candidates', 'voteCounts'));
    }
    
    /**
     * Display the admin dashboard for livestreams.
     *
     * @return \Illuminate\View\View
     */
    public function adminDashboard()
    {
        $this->authorize('admin');
        
        $livestreams = LiveStream::orderBy('created_at', 'desc')->get();
        $activeLivestream = LiveStream::where('is_active', true)->first();
        $candidates = Candidate::all();
        
        return view('admin.livestream', compact('livestreams', 'activeLivestream', 'candidates'));
    }
    
    /**
     * Store a new livestream.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->authorize('admin');
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'embed_url' => 'required|url',
            'thumbnail' => 'nullable|image|max:2048',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'phase' => 'required|in:semi-final,final',
            'candidates' => 'required|array',
            'candidates.*' => 'exists:candidates,id'
        ]);
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('livestreams', 'public');
            $validated['thumbnail'] = $path;
        }
        
        // Create livestream
        $livestream = LiveStream::create($validated);
        
        // Attach candidates
        $livestream->candidates()->attach($validated['candidates']);
        
        return redirect()->route('admin.livestream')->with('success', 'Livestream created successfully');
    }
    
    /**
     * Update a livestream.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LiveStream  $livestream
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, LiveStream $livestream)
    {
        $this->authorize('admin');
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'embed_url' => 'required|url',
            'thumbnail' => 'nullable|image|max:2048',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'phase' => 'required|in:semi-final,final',
            'is_active' => 'boolean',
            'candidates' => 'required|array',
            'candidates.*' => 'exists:candidates,id'
        ]);
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('livestreams', 'public');
            $validated['thumbnail'] = $path;
        }
        
        // If activating this livestream, deactivate all others
        if ($request->has('is_active') && $request->is_active) {
            LiveStream::where('id', '!=', $livestream->id)->update(['is_active' => false]);
        }
        
        // Update livestream
        $livestream->update($validated);
        
        // Sync candidates
        $livestream->candidates()->sync($validated['candidates']);
        
        return redirect()->route('admin.livestream')->with('success', 'Livestream updated successfully');
    }
    
    /**
     * Delete a livestream.
     *
     * @param  \App\Models\LiveStream  $livestream
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(LiveStream $livestream)
    {
        $this->authorize('admin');
        
        $livestream->delete();
        
        return redirect()->route('admin.livestream')->with('success', 'Livestream deleted successfully');
    }
}
