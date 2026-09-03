import re

with open('routes/web.php', 'r') as f:
    content = f.read()

target1 = "Route::get('/antrian/display/{uuid}', [QueuePublicController::class, 'display'])->name('queue.display');"
target2 = "Route::get('/api/queue/display/{uuid}', [QueuePublicController::class, 'displayData']);"

if target1 in content and 'queue.leaderboard.display' not in content:
    content = content.replace(target1, target1 + "\nRoute::get('/antrian/display/leaderboard/{user_id}', [QueuePublicController::class, 'displayLeaderboard'])->name('queue.leaderboard.display');")
    content = content.replace(target2, target2 + "\nRoute::get('/api/queue/display/leaderboard/{user_id}', [QueuePublicController::class, 'displayLeaderboardData']);")
    with open('routes/web.php', 'w') as f:
        f.write(content)
    print("SUCCESS")
else:
    print("NOT FOUND OR ALREADY ADDED")
