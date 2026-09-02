import re

with open('resources/views/admin/dashboard.blade.php', 'r') as f:
    content = f.read()

# I need to fix:
#              }
#         }"
# To just:
#         }"
# Or whatever it originally was. But wait! The `paket` tab actually NEEDED the extra `}` !
# Let's just fix everything by doing a smart replacement.

# For lines like:
#              }
#         }"
# We replace it with:
#          }"
# EXCEPT for the paket tab which we will manually fix after.

content = re.sub(r'             \}\n        \}"', r'         }"', content)
content = re.sub(r'                                      \}\n        \}"', r'                                  }"', content)
content = re.sub(r'                 \}\n        \}"', r'             }"', content)

with open('resources/views/admin/dashboard.blade.php', 'w') as f:
    f.write(content)
