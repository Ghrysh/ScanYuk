import re

with open('resources/views/dashboard/queue/index.blade.php', 'r') as f:
    content = f.read()

# Replace "route..." with 'route...'
content = content.replace(
    '"{{\\ route(\'queue.locations.manage\', $location->id) }}?add_service=1"',
    "'{{ route('queue.locations.manage', $location->id) }}?add_service=1'"
)

with open('resources/views/dashboard/queue/index.blade.php', 'w') as f:
    f.write(content)
print("SUCCESS")
