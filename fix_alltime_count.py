import re

with open('app/Http/Controllers/QueueManagementController.php', 'r') as f:
    content = f.read()

content = content.replace("'todayTickets as waiting_count'", "'tickets as waiting_count'")
content = content.replace("'todayTickets as serving_count'", "'tickets as serving_count'")
content = content.replace("'todayTickets as completed_count'", "'tickets as completed_count'")

with open('app/Http/Controllers/QueueManagementController.php', 'w') as f:
    f.write(content)
print("SUCCESS")
