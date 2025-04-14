<?php

namespace App\Http\Controllers;

use App\Models\Contestant;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    /**
     * Store a new vote.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }
        
        $validated = $request->validate([
            'candidate_id' => 'required|exists:contestants,id',
            'vote_type' => 'required|in:live,post',
        ]);
        
        // Renommer candidate_id en contestant_id pour correspondre à la structure de la table votes
        $validated['contestant_id'] = $validated['candidate_id'];
        unset($validated['candidate_id']);
        
        $user = Auth::user();
        
        // Check if user has already voted for this type
        $existingVote = $user->votes()
            ->where('vote_type', $validated['vote_type'])
            ->first();
        
        DB::beginTransaction();
        
        try {
            // If user has already voted, update the vote
            if ($existingVote) {
                // If voting for the same candidate, return early
                if ($existingVote->contestant_id == $validated['contestant_id']) {
                    DB::rollBack();
                    return response()->json(['message' => 'You have already voted for this candidate']);
                }
                
                $existingVote->update([
                    'contestant_id' => $validated['contestant_id'],
                    'ip_address' => $request->ip(),
                ]);
                
                $message = 'Your vote has been updated';
            } else {
                // Create new vote
                Vote::create([
                    'user_id' => $user->id,
                    'contestant_id' => $validated['contestant_id'],
                    'vote_type' => $validated['vote_type'],
                    'ip_address' => $request->ip(),
                ]);
                
                $message = 'Your vote has been recorded';
            }
            
            DB::commit();
            
            // Get updated vote counts for all candidates
            $voteCounts = $this->getVoteCounts($validated['vote_type']);
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'vote_counts' => $voteCounts
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred while processing your vote'], 500);
        }
    }
    
    /**
     * Get vote counts for all candidates.
     *
     * @param string $voteType
     * @return array
     */
    private function getVoteCounts($voteType)
    {
        return Contestant::select('contestants.id', 'contestants.name', DB::raw('COUNT(votes.id) as vote_count'))
            ->leftJoin('votes', function ($join) use ($voteType) {
                $join->on('contestants.id', '=', 'votes.contestant_id')
                    ->where('votes.vote_type', '=', $voteType);
            })
            ->groupBy('contestants.id', 'contestants.name')
            ->get()
            ->keyBy('id')
            ->map(function ($candidate) {
                return [
                    'name' => $candidate->name,
                    'count' => (int) $candidate->vote_count
                ];
            })
            ->toArray();
    }
    
    /**
     * Get vote statistics for admin dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats(Request $request)
    {
        $this->authorize('admin');
        
        $voteType = $request->input('vote_type', 'live');
        $voteCounts = $this->getVoteCounts($voteType);
        
        // Get total votes
        $totalVotes = Vote::where('vote_type', $voteType)->count();
        
        // Get unique voters
        $uniqueVoters = Vote::where('vote_type', $voteType)
            ->distinct('user_id')
            ->count('user_id');
        
        return response()->json([
            'vote_counts' => $voteCounts,
            'total_votes' => $totalVotes,
            'unique_voters' => $uniqueVoters
        ]);
    }
}
