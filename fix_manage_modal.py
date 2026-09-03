import re

with open('resources/views/dashboard/queue/manage.blade.php', 'r') as f:
    content = f.read()

old_state = "showAddServiceModal: false,"
new_state = "showAddServiceModal: new URLSearchParams(window.location.search).get('add_service') === '1',"

if old_state in content:
    content = content.replace(old_state, new_state)
    with open('resources/views/dashboard/queue/manage.blade.php', 'w') as f:
        f.write(content)
    print("SUCCESS")
else:
    print("NOT FOUND")
