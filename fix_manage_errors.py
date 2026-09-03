import re

with open('resources/views/dashboard/queue/manage.blade.php', 'r') as f:
    content = f.read()

target = "<form action=\"{{ $location ? route('queue.locations.update', $location->id) : route('queue.locations.store') }}\" method=\"POST\">"

error_block = """<form action="{{ $location ? route('queue.locations.update', $location->id) : route('queue.locations.store') }}" method="POST">
                @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl">
                    <p class="text-red-700 font-bold text-sm">{{ session('error') }}</p>
                </div>
                @endif
                
                @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl">
                    <ul class="text-red-700 text-sm list-disc pl-4 font-semibold">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif"""

if target in content and '@if(session(\'error\'))' not in content:
    content = content.replace(target, error_block)
    with open('resources/views/dashboard/queue/manage.blade.php', 'w') as f:
        f.write(content)
    print("SUCCESS")
else:
    print("NOT FOUND OR ALREADY ADDED")
