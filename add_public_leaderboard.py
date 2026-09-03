import re

with open('app/Http/Controllers/QueuePublicController.php', 'r') as f:
    content = f.read()

target = "    public function ticketStatus($id)"

new_method = """    public function displayLeaderboard($userId)
    {
        $customers = \App\Models\QueueCustomer::where('user_id', $userId)
            ->orderBy('points', 'desc')
            ->orderBy('visits', 'desc')
            ->limit(10)
            ->get();
            
        return view('queue.display-leaderboard', compact('customers', 'userId'));
    }
    
    public function displayLeaderboardData($userId)
    {
        $customers = \App\Models\QueueCustomer::where('user_id', $userId)
            ->orderBy('points', 'desc')
            ->orderBy('visits', 'desc')
            ->limit(10)
            ->get();
            
        return response()->json([
            'customers' => $customers
        ]);
    }

"""

if target in content and 'displayLeaderboard' not in content:
    content = content.replace(target, new_method + target)
    with open('app/Http/Controllers/QueuePublicController.php', 'w') as f:
        f.write(content)
    print("SUCCESS")
else:
    print("NOT FOUND OR ALREADY ADDED")
