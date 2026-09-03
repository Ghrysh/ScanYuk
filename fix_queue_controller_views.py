import re

with open('app/Http/Controllers/QueueManagementController.php', 'r') as f:
    content = f.read()

target = "    public function addPoints(Request $request, \\App\\Models\\QueueCustomer $customer)"

new_method = """    public function addViews(Request $request, \\App\\Models\\QueueCustomer $customer)
    {
        if ($customer->user_id !== Auth::id()) abort(403);
        $request->validate(['views' => 'required|integer|min:1']);
        $customer->increment('views', $request->views);
        return back()->with('success', 'Viewers berhasil ditambahkan ke ' . $customer->name);
    }

"""

if target in content and 'addViews' not in content:
    content = content.replace(target, new_method + target)
    with open('app/Http/Controllers/QueueManagementController.php', 'w') as f:
        f.write(content)
    print("SUCCESS")
else:
    print("NOT FOUND OR ALREADY ADDED")
