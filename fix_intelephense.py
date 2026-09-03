import re

with open('app/Http/Controllers/QueueManagementController.php', 'r') as f:
    content = f.read()

# Add docblock before $user = Auth::user();
content = re.sub(r'([ \t]+)\$user = Auth::user\(\);', r'\1/** @var \\App\\Models\\User $user */\n\1$user = Auth::user();', content)

with open('app/Http/Controllers/QueueManagementController.php', 'w') as f:
    f.write(content)
print("SUCCESS")
