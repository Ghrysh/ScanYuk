import re

with open('app/Http/Controllers/QueuePublicController.php', 'r') as f:
    content = f.read()

target = """    public function displayLeaderboardData($userId)
    {
        $customers = \\App\\Models\\QueueCustomer::where('user_id', $userId)
            ->orderBy('points', 'desc')
            ->orderBy('visits', 'desc')
            ->limit(10)
            ->get();
            
        return response()->json([
            'customers' => $customers
        ]);
    }"""

new_target = """    public function displayLeaderboardData($userId)
    {
        $byPoints = \\App\\Models\\QueueCustomer::where('user_id', $userId)
            ->orderBy('points', 'desc')
            ->orderBy('visits', 'desc')
            ->limit(10)
            ->get();
            
        $byViews = \\App\\Models\\QueueCustomer::where('user_id', $userId)
            ->orderBy('views', 'desc')
            ->orderBy('visits', 'desc')
            ->limit(10)
            ->get();
            
        return response()->json([
            'byPoints' => $byPoints,
            'byViews' => $byViews
        ]);
    }"""

if target in content:
    content = content.replace(target, new_target)
    
target2 = """    public function displayLeaderboard($userId)
    {
        $customers = \\App\\Models\\QueueCustomer::where('user_id', $userId)
            ->orderBy('points', 'desc')
            ->orderBy('visits', 'desc')
            ->limit(10)
            ->get();
            
        return view('queue.display-leaderboard', compact('customers', 'userId'));
    }"""

new_target2 = """    public function displayLeaderboard($userId)
    {
        $byPoints = \\App\\Models\\QueueCustomer::where('user_id', $userId)
            ->orderBy('points', 'desc')
            ->orderBy('visits', 'desc')
            ->limit(10)
            ->get();
            
        $byViews = \\App\\Models\\QueueCustomer::where('user_id', $userId)
            ->orderBy('views', 'desc')
            ->orderBy('visits', 'desc')
            ->limit(10)
            ->get();
            
        return view('queue.display-leaderboard', compact('byPoints', 'byViews', 'userId'));
    }"""

if target2 in content:
    content = content.replace(target2, new_target2)

with open('app/Http/Controllers/QueuePublicController.php', 'w') as f:
    f.write(content)
print("SUCCESS")
