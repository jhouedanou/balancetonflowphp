/**
 * Balance Ton Flow - Vote Management
 * 
 * This script handles the voting functionality and real-time updates for the
 * "Balance Ton Flow" platform.
 */

// Function to cast a vote for a candidate
function castVote(candidateId) {
    // Disable all vote buttons during the request
    const voteButtons = document.querySelectorAll('.vote-btn');
    voteButtons.forEach(button => {
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Traitement...';
    });
    
    // Send vote request
    axios.post('/vote', {
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
        showAlert('success', response.data.message);
        
        // Update results if available
        if (typeof updateResults === 'function') {
            updateResults();
        }
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
        
        showAlert('danger', errorMessage);
    });
}

// Function to update vote results
function updateResults() {
    axios.get('/api/votes/stats', {
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
        if (resultsContainer) {
            const candidateElements = resultsContainer.querySelectorAll('.candidate-result');
            
            candidateElements.forEach(element => {
                const candidateId = element.getAttribute('data-candidate-id');
                const voteCount = voteCounts[candidateId] ? voteCounts[candidateId].count : 0;
                const percentage = totalVotes > 0 ? Math.round((voteCount / totalVotes) * 100) : 0;
                const barWidth = maxVotes > 0 ? Math.round((voteCount / maxVotes) * 100) : 0;
                
                // Update vote count
                const voteCountElement = element.querySelector('.vote-count');
                if (voteCountElement) {
                    voteCountElement.textContent = `${voteCount} vote(s)`;
                }
                
                // Update percentage badge
                const percentageBadge = element.querySelector('.percentage-badge');
                if (percentageBadge) {
                    percentageBadge.textContent = `${percentage}%`;
                }
                
                // Update progress bar
                const progressBar = element.querySelector('.results-bar-fill');
                if (progressBar) {
                    progressBar.style.width = `${barWidth}%`;
                    progressBar.textContent = percentage > 10 ? `${percentage}%` : '';
                }
            });
            
            // Update total votes
            const totalVotesElement = document.getElementById('total-votes');
            if (totalVotesElement) {
                totalVotesElement.textContent = totalVotes;
            }
        }
    })
    .catch(error => {
        console.error('Error updating results:', error);
    });
}

// Helper function to show alerts
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show mt-3`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Find a suitable container for the alert
    const container = document.querySelector('.vote-container') || document.querySelector('.container');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
    }
    
    // Auto-dismiss alert after 5 seconds
    setTimeout(() => {
        alertDiv.classList.remove('show');
        setTimeout(() => {
            alertDiv.remove();
        }, 150);
    }, 5000);
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Set up auto-refresh for results if on results page
    const resultsContainer = document.getElementById('results-container');
    if (resultsContainer) {
        // Initial update
        setTimeout(updateResults, 2000);
        
        // Set interval for updates
        setInterval(updateResults, 10000);
    }
});
