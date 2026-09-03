import re

with open('routes/web.php', 'r') as f:
    content = f.read()

target = "Route::delete('/dashboard/queue/staff/{staff}', [QueueManagementController::class, 'deleteStaff'])->name('queue.staff.delete');"

if target in content and 'queue.leaderboard' not in content:
    content = content.replace(target, target + "\n        Route::get('/dashboard/queue/leaderboard', [QueueManagementController::class, 'leaderboard'])->name('queue.leaderboard');\n        Route::post('/dashboard/queue/leaderboard/{customer}/add-points', [QueueManagementController::class, 'addPoints'])->name('queue.leaderboard.add-points');")
    with open('routes/web.php', 'w') as f:
        f.write(content)
    print("SUCCESS")
else:
    print("NOT FOUND OR ALREADY ADDED")
