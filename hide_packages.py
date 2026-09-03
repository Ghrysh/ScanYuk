import re

with open('resources/views/pricing.blade.php', 'r') as f:
    content = f.read()

# Using blade comments {{-- --}} to hide the section
start_tag = r'(<div class="flex items-center justify-center gap-3 mb-12">)'
end_tag = r'(</div>\s+)\{\{-- Sistem Antrian Pricing \(WhatsApp\) --\}\}'

def replacer(match):
    return "{{-- \n" + match.group(0).replace(match.group(2), "") + match.group(2) + " --}}\n{{-- Sistem Antrian Pricing (WhatsApp) --}}"

# Actually simpler: just find the index of start_tag and index of end_tag and insert {{-- and --}}
start_idx = content.find('<div class="flex items-center justify-center gap-3 mb-12">')
end_idx = content.find('{{-- Sistem Antrian Pricing (WhatsApp) --}}')

if start_idx != -1 and end_idx != -1:
    new_content = content[:start_idx] + "{{-- \n" + content[start_idx:end_idx] + "\n--}}\n" + content[end_idx:]
    with open('resources/views/pricing.blade.php', 'w') as f:
        f.write(new_content)
    print("SUCCESS")
else:
    print("NOT FOUND")
