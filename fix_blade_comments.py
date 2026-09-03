import re

with open('resources/views/pricing.blade.php', 'r') as f:
    content = f.read()

# Replace my {{-- \n with @if(false)
content = content.replace("{{-- \n<div class=\"flex items-center justify-center gap-3 mb-12\">", "@if(false)\n<div class=\"flex items-center justify-center gap-3 mb-12\">")

# Replace \n--}}\n{{-- Sistem Antrian Pricing (WhatsApp) --}} with @endif
content = content.replace("\n--}}\n{{-- Sistem Antrian Pricing (WhatsApp) --}}", "\n@endif\n{{-- Sistem Antrian Pricing (WhatsApp) --}}")

with open('resources/views/pricing.blade.php', 'w') as f:
    f.write(content)
print("SUCCESS")
