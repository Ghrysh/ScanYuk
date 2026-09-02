import re

with open('app/Http/Controllers/QueueStaffController.php', 'r') as f:
    content = f.read()

bad_chunk = """        if (!$staff || !$staff->verifyPassword($request->password)) {
            return back()->with('error', 'Username atau password salah.');

        if (!$staff->is_active) {
            return back()->with('error', 'Akun petugas tidak aktif.');
        session([
            'queue_staff_id' => $staff->id,
            'queue_location_id' => $staff->queue_location_id
        ]);
    }"""

good_chunk = """        if (!$staff || !$staff->verifyPassword($request->password)) {
            return back()->with('error', 'Username atau password salah.');
        }

        if (!$staff->is_active) {
            return back()->with('error', 'Akun petugas tidak aktif.');
        }
        
        session([
            'queue_staff_id' => $staff->id,
            'queue_location_id' => $staff->queue_location_id
        ]);
        
        return redirect()->route('queue.staff.dashboard');
    }"""

if bad_chunk in content:
    content = content.replace(bad_chunk, good_chunk)
    with open('app/Http/Controllers/QueueStaffController.php', 'w') as f:
        f.write(content)
    print("SUCCESS")
else:
    print("FAILED")
