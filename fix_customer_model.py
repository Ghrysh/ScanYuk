import re

with open('app/Models/QueueCustomer.php', 'r') as f:
    content = f.read()

content = content.replace("'visits',", "'visits',\n        'views',")

with open('app/Models/QueueCustomer.php', 'w') as f:
    f.write(content)
print("SUCCESS")
