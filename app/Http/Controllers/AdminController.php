<?php

namespace App\Http\Controllers;

use App\Models\PricingPackage;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        Transaction::where('status', 'Pending')
            ->where('created_at', '<', now()->subHours(24))
            ->update(['status' => 'Batal']);

        $packages = PricingPackage::all();
        $users = User::where('role', '!=', 'admin')->latest()->paginate(10, ['*'], 'users_page');
        $transactions = Transaction::with(['user', 'package'])->latest()->paginate(10, ['*'], 'txn_page');

        $totalUsers = User::where('role', '!=', 'admin')->count();
        $totalQrCodes = User::sum('image'); 
        $totalScans = User::sum('scan'); 
        $grossRevenue = Transaction::whereIn('status', ['Berhasil', 'Paid', 'success'])->sum('amount');
        $totalRevenue = round($grossRevenue / 1.11);

        $filter = $request->query('filter', 'today');
        $query = \App\Models\VisitorLog::query();
        
        if ($filter == 'today') {
            $query->where('date', now()->toDateString());
        } elseif ($filter == 'month') {
            $query->whereMonth('date', now()->month)->whereYear('date', now()->year);
        } elseif ($filter == 'year') {
            $query->whereYear('date', now()->year);
        }

        $visitorLogs = (clone $query)->latest('updated_at')->paginate(10, ['*'], 'journey_page');
        $totalVisitors = (clone $query)->count();

        $chartLabels = [];
        $chartValues = [];

        if ($filter == 'today') {
            $stats = (clone $query)->selectRaw('EXTRACT(HOUR FROM created_at) as hour, count(*) as count')
                ->groupBy('hour')->pluck('count', 'hour')->toArray();
            for ($i = 0; $i < 24; $i++) {
                $chartLabels[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
                $chartValues[] = $stats[$i] ?? 0;
            }
        } elseif ($filter == 'month') {
            $stats = (clone $query)->selectRaw('EXTRACT(DAY FROM created_at) as day, count(*) as count')
                ->groupBy('day')->pluck('count', 'day')->toArray();
            for ($i = 1; $i <= now()->daysInMonth; $i++) {
                $chartLabels[] = $i;
                $chartValues[] = $stats[$i] ?? 0;
            }
        } elseif ($filter == 'year') {
            $stats = (clone $query)->selectRaw('EXTRACT(MONTH FROM created_at) as month, count(*) as count')
                ->groupBy('month')->pluck('count', 'month')->toArray();
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
            for ($i = 1; $i <= 12; $i++) {
                $chartLabels[] = $months[$i-1];
                $chartValues[] = $stats[$i] ?? 0;
            }
        }

        $chartData = [
            'labels' => $chartLabels,
            'values' => $chartValues,
            'labelName' => $filter == 'today' ? 'Pengunjung per Jam' : ($filter == 'month' ? 'Pengunjung per Hari' : 'Pengunjung per Bulan')
        ];

        $contactMessages = \App\Models\Contact::latest()->take(20)->get();

        $chatbotKnowledges = \App\Models\ChatbotKnowledge::orderBy('topic')->get();
        $chatbotLeads = \App\Models\ChatbotLead::with('user')->latest()->paginate(10, ['*'], 'leads_page');

        return view('admin.dashboard', compact(
            'packages', 'users', 'transactions', 'totalUsers', 'totalQrCodes', 'totalScans', 'totalRevenue',
            'contactMessages', 'visitorLogs', 'totalVisitors', 'filter', 'chartData', 'chatbotKnowledges', 'chatbotLeads'
        ));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'package_id' => 'required|exists:pricing_packages,id'
        ]);

        $package = PricingPackage::find($request->package_id);
        $role = strtolower($package->name);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role === 'pemula' ? 'starter' : ($role === 'profesional' ? 'professional' : ($role === 'bisnis' ? 'business' : 'free')),
            'status' => 'active',
            'email_verified_at' => now(),
            'image' => 0,
            'voice' => 0,
            'scan' => 0,
        ]);

        return back()->with(['success' => "Akun {$request->name} berhasil ditambahkan!", 'active_tab' => 'users']);
    }

    public function destroyUser(User $user)
    {
        $name = $user->name;
        $user->delete();
        return back()->with(['success' => "Akun {$name} beserta datanya berhasil dihapus permanen.", 'active_tab' => 'users']);
    }

    public function toggleStatus(User $user)
    {
        $currentStatus = $user->status ?? 'active';
        $user->status = $currentStatus === 'active' ? 'suspended' : 'active';
        $user->save();

        return back()->with([
            'success' => "Status user {$user->name} berhasil diubah menjadi " . ucfirst($user->status),
            'active_tab' => 'users'
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $users = User::where('role', '!=', 'admin')
            ->where(function($q) use ($query) {
                $q->where('name', 'ilike', "%{$query}%")
                ->orWhere('email', 'ilike', "%{$query}%");
            })->paginate(10);

        return view('admin.partials._user_table', compact('users'))->render();
    }

    public function updatePackage(Request $request, \App\Models\PricingPackage $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image_limit' => 'nullable|integer|min:0',
            'voice_limit' => 'nullable|integer|min:0',
            'scan_limit' => 'nullable|integer|min:0',
        ]);

        $package->name = $request->name;
        $package->price = $request->price;

        $package->features = [
            is_null($request->image_limit) ? "Tak Terbatas AR Image" : $request->image_limit . " AR Image",
            is_null($request->voice_limit) ? "Tak Terbatas Voice Narration" : $request->voice_limit . " Voice Narration",
            is_null($request->scan_limit) ? "Tak Terbatas Total Scans" : $request->scan_limit . " Total Scans",
            $package->features[3] ?? 'Basic analytics',
            $package->features[4] ?? 'Download QR',
        ];

        $package->save();

        return back()->with([
            'success' => "Paket {$package->name} berhasil diperbarui!",
            'active_tab' => 'paket'
        ]);
    }

    public function searchTransactions(Request $request)
    {
        $query = $request->get('query');
        
        $transactions = Transaction::with(['user', 'package'])
            ->where(function($q) use ($query) {
                $q->where('id', 'ilike', "%{$query}%")
                  ->orWhere('status', 'ilike', "%{$query}%")
                  ->orWhereHas('user', function($userQuery) use ($query) {
                      $userQuery->where('name', 'ilike', "%{$query}%");
                  });
            })
            ->latest()
            ->paginate(10, ['*'], 'txn_page');

        return view('admin.partials._transaction_table', compact('transactions'))->render();
    }

    public function storeChatbotKnowledge(Request $request)
    {
        $request->validate(['topic' => 'required', 'intent_name' => 'required', 'keywords' => 'required', 'response' => 'required']);
        $keywordsArray = array_map('trim', explode(',', strtolower($request->keywords)));
        
        \App\Models\ChatbotKnowledge::create([
            'topic' => $request->topic, 'intent_name' => Str::slug($request->intent_name, '_'),
            'keywords' => json_encode($keywordsArray), 'response' => $request->response
        ]);
        return back()->with(['success' => 'Respon Chatbot berhasil ditambahkan!', 'active_tab' => 'chatbot']);
    }

    public function updateChatbotKnowledge(Request $request, $id)
    {
        $knowledge = \App\Models\ChatbotKnowledge::findOrFail($id);
        $keywordsArray = array_map('trim', explode(',', strtolower($request->keywords)));
        
        $knowledge->update([
            'topic' => $request->topic, 'intent_name' => Str::slug($request->intent_name, '_'),
            'keywords' => json_encode($keywordsArray), 'response' => $request->response
        ]);
        return back()->with(['success' => 'Respon Chatbot berhasil diperbarui!', 'active_tab' => 'chatbot']);
    }

    public function destroyChatbotKnowledge($id)
    {
        \App\Models\ChatbotKnowledge::findOrFail($id)->delete();
        return back()->with(['success' => 'Respon Chatbot dihapus!', 'active_tab' => 'chatbot']);
    }

    public function toggleLeadStatus($id)
    {
        $lead = \App\Models\ChatbotLead::findOrFail($id);
        $lead->status = $lead->status === 'pending' ? 'contacted' : 'pending';
        $lead->save();
        return back()->with(['success' => 'Status follow up diperbarui!', 'active_tab' => 'chatbot']);
    }

    public function getLeadHistory($id)
    {
        $lead = \App\Models\ChatbotLead::findOrFail($id);
        return response()->json(json_decode($lead->chat_history, true) ?? []);
    }

    public function pollLiveChats() {
        return response()->json([
            'pending' => \App\Models\ChatbotLead::with('user')->where('live_chat_status', 'pending')->latest()->get(),
            'active'  => \App\Models\ChatbotLead::with('user')->where('live_chat_status', 'active')->where('admin_id', auth()->id())->latest()->get(),
            // Tambahkan Riwayat (Ended) - Kita ambil 10 data terakhir saja agar tidak berat
            'ended'   => \App\Models\ChatbotLead::with('user')->where('live_chat_status', 'ended')->where('admin_id', auth()->id())->latest()->get()
        ]);
    }

    public function actionLiveChat(Request $request) {
        $lead = \App\Models\ChatbotLead::find($request->lead_id);
        $adminName = auth()->user()->name;
        
        if ($request->action === 'accept') {
            $history = json_decode($lead->chat_history, true) ?? [];
            $history[] = ['sender' => 'admin', 'text' => "Halo, saya {$adminName}. Ada yang bisa saya bantu?", 'time' => now()->format('d M, H:i')];
            $lead->update(['live_chat_status' => 'active', 'admin_id' => auth()->id(), 'chat_history' => json_encode($history)]);
        } elseif ($request->action === 'reject') {
            $history = json_decode($lead->chat_history, true) ?? [];
            $history[] = ['sender' => 'bot', 'text' => 'Maaf, saat ini semua admin sedang sibuk. Silakan tinggalkan kontak Anda di bawah ini agar kami bisa menghubungi Anda.', 'time' => now()->format('d M, H:i')];
            $lead->update(['live_chat_status' => 'ended', 'chat_history' => json_encode($history)]);
        } elseif ($request->action === 'end') {
            $history = json_decode($lead->chat_history, true) ?? [];
            $history[] = ['sender' => 'bot', 'text' => "Obrolan Live Chat dengan {$adminName} telah berakhir. Anda kembali terhubung dengan ScanYuk Bot.", 'time' => now()->format('d M, H:i')];
            $lead->update(['live_chat_status' => 'ended', 'chat_history' => json_encode($history)]);
        }
        return response()->json(['success' => true]);
    }

    public function sendLiveChatMessage(Request $request) {
        $lead = \App\Models\ChatbotLead::find($request->lead_id);
        
        if ($lead && !empty($request->message)) {
            $history = json_decode($lead->chat_history, true) ?? [];
            $history[] = ['sender' => 'admin', 'text' => $request->message, 'time' => now()->format('d M, H:i')];
            
            $lead->update([
                'chat_history' => json_encode($history),
                'updated_at' => now()
            ]);
        }
        
        return response()->json(['success' => true]);
    }
}
