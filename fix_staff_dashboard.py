import re

with open('app/Http/Controllers/QueueStaffController.php', 'r') as f:
    content = f.read()

bad_chunk = """    public function dashboard()
    {
        $staffId = session('queue_staff_id');
        
        if (!$staff) {"""

good_chunk = """    public function dashboard()
    {
        $staffId = session('queue_staff_id');
        $staff = \App\Models\QueueStaff::find($staffId);
        
        if (!$staff) {"""

if bad_chunk in content:
    content = content.replace(bad_chunk, good_chunk)
    with open('app/Http/Controllers/QueueStaffController.php', 'w') as f:
        f.write(content)
    print("SUCCESS")
else:
    print("FAILED")
