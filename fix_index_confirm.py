import re

with open('resources/views/dashboard/queue/index.blade.php', 'r') as f:
    content = f.read()

# Replace the null with the URL
pattern = r"showAppConfirm\('Layanan Kosong', '(.*?)', null\)"

def replacer(match):
    return "showAppConfirm('Layanan Kosong', '{}', \"{{{{ route('queue.locations.manage', $location->id) }}}}?add_service=1\")".format(match.group(1))

if 'showAppConfirm(\'Layanan Kosong\'' in content:
    content = re.sub(pattern, replacer, content)
    with open('resources/views/dashboard/queue/index.blade.php', 'w') as f:
        f.write(content)
    print("SUCCESS")
else:
    print("NOT FOUND")
