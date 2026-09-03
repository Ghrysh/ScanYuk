import re

with open('routes/web.php', 'r') as f:
    content = f.read()

target = "Route::post('/dashboard/queue/leaderboard/{customer}/add-points', [QueueManagementController::class, 'addPoints'])->name('queue.leaderboard.add-points');"

if target in content and 'add-views' not in content:
    content = content.replace(target, target + "\n        Route::post('/dashboard/queue/leaderboard/{customer}/add-views', [QueueManagementController::class, 'addViews'])->name('queue.leaderboard.add-views');")
    with open('routes/web.php', 'w') as f:
        f.write(content)
    print("SUCCESS")
else:
    print("NOT FOUND OR ALREADY ADDED")
