<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\LiveStream;
use App\Models\User;
use App\Models\Video;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get stats for dashboard
        $stats = [
            'users' => User::count(),
            'candidates' => Candidate::count(),
            'finalists' => Candidate::where('is_finalist', true)->count(),
            'videos' => Video::count(),
            'votes' => [
                'total' => Vote::count(),
                'live' => Vote::where('vote_type', 'live')->count(),
                'post' => Vote::where('vote_type', 'post')->count(),
            ],
            'livestreams' => LiveStream::count(),
            'active_livestream' => LiveStream::where('is_active', true)->first(),
        ];
        
        return view('admin.dashboard', compact('stats'));
    }
    
    /**
     * Show the candidates management page.
     *
     * @return \Illuminate\View\View
     */
    public function candidates()
    {
        $candidates = Candidate::withCount('votes')->orderBy('votes_count', 'desc')->get();
        $users = User::whereDoesntHave('candidate')->get();
        
        return view('admin.candidates', compact('candidates', 'users'));
    }
    
    /**
     * Store a new candidate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCandidate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'is_finalist' => 'boolean',
            'user_id' => 'nullable|exists:users,id',
        ]);
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('candidates', 'public');
            $validated['photo'] = $path;
        }
        
        Candidate::create($validated);
        
        return redirect()->route('admin.candidates')->with('success', 'Candidate created successfully');
    }
    
    /**
     * Update a candidate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCandidate(Request $request, Candidate $candidate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'is_finalist' => 'boolean',
            'user_id' => 'nullable|exists:users,id',
        ]);
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('candidates', 'public');
            $validated['photo'] = $path;
        }
        
        $candidate->update($validated);
        
        return redirect()->route('admin.candidates')->with('success', 'Candidate updated successfully');
    }
    
    /**
     * Delete a candidate.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyCandidate(Candidate $candidate)
    {
        $candidate->delete();
        
        return redirect()->route('admin.candidates')->with('success', 'Candidate deleted successfully');
    }
    
    /**
     * Show the users management page.
     *
     * @return \Illuminate\View\View
     */
    public function users()
    {
        $users = User::withCount('votes')->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.users', compact('users'));
    }
    
    /**
     * Update a user's admin status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUserAdmin(Request $request, User $user)
    {
        $validated = $request->validate([
            'is_admin' => 'required|boolean',
        ]);
        
        $user->update($validated);
        
        return redirect()->route('admin.users')->with('success', 'User updated successfully');
    }
    
    /**
     * Show the videos management page.
     *
     * @return \Illuminate\View\View
     */
    public function videos()
    {
        $videos = Video::with('candidate')->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.videos', compact('videos'));
    }
    
    /**
     * Update a video's published status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateVideoStatus(Request $request, Video $video)
    {
        $validated = $request->validate([
            'is_published' => 'required|boolean',
        ]);
        
        // Set published_at if video is being published for the first time
        if ($validated['is_published'] && !$video->is_published) {
            $validated['published_at'] = now();
        }
        
        $video->update($validated);
        
        return redirect()->route('admin.videos')->with('success', 'Video status updated successfully');
    }
    
    /**
     * Show the votes statistics page.
     *
     * @return \Illuminate\View\View
     */
    public function voteStats()
    {
        $liveVoteStats = $this->getVoteStatsByType('live');
        $postVoteStats = $this->getVoteStatsByType('post');
        
        $voteHistory = Vote::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                'vote_type'
            )
            ->groupBy('date', 'vote_type')
            ->orderBy('date', 'asc')
            ->get()
            ->groupBy('vote_type');
        
        return view('admin.vote-stats', compact('liveVoteStats', 'postVoteStats', 'voteHistory'));
    }
    
    /**
     * Get vote statistics by type.
     *
     * @param string $voteType
     * @return array
     */
    private function getVoteStatsByType($voteType)
    {
        $candidates = Candidate::withCount(['votes' => function ($query) use ($voteType) {
                $query->where('vote_type', $voteType);
            }])
            ->orderBy('votes_count', 'desc')
            ->get();
        
        $totalVotes = Vote::where('vote_type', $voteType)->count();
        $uniqueVoters = Vote::where('vote_type', $voteType)
            ->distinct('user_id')
            ->count('user_id');
        
        return [
            'candidates' => $candidates,
            'total_votes' => $totalVotes,
            'unique_voters' => $uniqueVoters
        ];
    }
}
