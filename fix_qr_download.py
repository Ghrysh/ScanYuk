import re

with open('app/Http/Controllers/QueueManagementController.php', 'r') as f:
    content = f.read()

bad_chunk = """    public function downloadQr(QueueLocation $location)
    {
        if ($location->user_id !== Auth::id()) abort(403);

        $url = route('queue.register', $location->uuid);"""

good_chunk = """    public function downloadQr(QueueLocation $location)
    {
        if ($location->user_id !== Auth::id()) abort(403);

        if ($location->ar_qr_code_id && $location->qrCode) {
            // Jika menggunakan AR, QR code akan diarahkan ke scanner AR,
            // dan kita sisipkan parameter queue_location_uuid agar scanner
            // bisa mengarahkan user ke halaman antrian setelah AR selesai.
            if ($location->qrCode->ar_project_id) {
                $url = route('ar.view', ['project' => $location->qrCode->ar_project_id]) . '?queue_uuid=' . $location->uuid;
            } else {
                $url = url('/scan-ar?id=' . $location->qrCode->uuid . '&queue_uuid=' . $location->uuid);
            }
        } else {
            // Tanpa AR, langsung ke halaman registrasi antrian
            $url = route('queue.register', $location->uuid);
        }"""

if bad_chunk in content:
    content = content.replace(bad_chunk, good_chunk)
    with open('app/Http/Controllers/QueueManagementController.php', 'w') as f:
        f.write(content)
    print("SUCCESS")
else:
    print("FAILED")
