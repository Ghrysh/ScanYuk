import re

with open('resources/views/queue/register.blade.php', 'r') as f:
    content = f.read()

bad_form = """<form action="{{ route('queue.register.store', $location->uuid) }}" method="POST" class="space-y-6">
            @csrf"""
good_form = """<form action="{{ route('queue.register.store', $location->uuid) }}" method="POST" class="space-y-6">
            @csrf
            
            @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                <p class="text-red-700 font-medium">{{ session('error') }}</p>
            </div>
            @endif
            
            @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                <ul class="text-red-700 text-sm list-disc pl-4">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif"""

if bad_form in content:
    content = content.replace(bad_form, good_form)
    with open('resources/views/queue/register.blade.php', 'w') as f:
        f.write(content)
    print("SUCCESS")
else:
    print("FAILED")
