import re

with open('resources/views/dashboard/queue/leaderboard.blade.php', 'r') as f:
    content = f.read()

# Add padding to the top of the container
# Find: <div class="max-w-7xl mx-auto" x-data="{
# Replace with: <div class="max-w-7xl mx-auto pt-8 pb-12 px-4 sm:px-6 lg:px-8" x-data="{

content = content.replace('<div class="max-w-7xl mx-auto" x-data="{', '<div class="max-w-7xl mx-auto pt-8 pb-12 px-4 sm:px-6 lg:px-8" x-data="{')

with open('resources/views/dashboard/queue/leaderboard.blade.php', 'w') as f:
    f.write(content)
print("SUCCESS")
