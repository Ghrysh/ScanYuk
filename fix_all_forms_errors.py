import re

with open('resources/views/dashboard/queue/manage.blade.php', 'r') as f:
    content = f.read()

error_block = """            @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-r-xl">
                <p class="text-red-700 font-bold text-sm">{{ session('error') }}</p>
            </div>
            @endif
            @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-r-xl">
                <ul class="text-red-700 text-sm list-disc pl-4 font-semibold">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
"""

# We want to insert the error block after @csrf in the locations.store form
if 'route(\'queue.locations.store\')' in content and 'session(\'error\')' not in content:
    content = re.sub(r'(<form action="\{\{ route\(\'queue\.locations\.store\'\) \}\}".*?>\s*@csrf\n)', r'\1' + error_block, content)
    # And for update form
    content = re.sub(r'(<form action="\{\{ route\(\'queue\.locations\.update\', \$location->id\) \}\}".*?>\s*@csrf\s*@method\(\'PUT\'\)\n)', r'\1' + error_block, content)
    with open('resources/views/dashboard/queue/manage.blade.php', 'w') as f:
        f.write(content)
    print("SUCCESS")
else:
    print("NOT FOUND OR ALREADY ADDED")

