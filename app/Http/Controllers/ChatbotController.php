<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatbotKnowledge;
use App\Models\ChatbotLead;
use App\Models\PricingPackage;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function processChat(Request $request)
    {
        $topic = $request->topic ?? 'Umum'; 
        $rawMessage = strtolower(trim($request->message));

        $slangDict = [
            'gmn' => 'bagaimana', 'gimana' => 'bagaimana', 'bgmn' => 'bagaimana', 'gmna' => 'bagaimana',
            'brp' => 'berapa', 'brapa' => 'berapa', 'brpa' => 'berapa', 'brap' => 'berapa', 'piro' => 'berapa',
            'klo' => 'kalau', 'kalo' => 'kalau', 'klau' => 'kalau',
            'bikin' => 'buat', 'bs' => 'bisa', 'gk' => 'tidak', 'ga' => 'tidak', 'gak' => 'tidak', 'ngga' => 'tidak', 'nggak' => 'tidak',
            'tdk' => 'tidak', 'dgn' => 'dengan', 'yg' => 'yang', 'utk' => 'untuk',
            'makasih' => 'terimakasih', 'trims' => 'terimakasih', 'thx' => 'terimakasih', 'mksh' => 'terimakasih',
            'pw' => 'password', 'pass' => 'password', 'loginnya' => 'login',
            
            'hrga' => 'harga', 'hrg' => 'harga', 'haarga' => 'harga', 'harg' => 'harga',
            'pket' => 'paket', 'pkt' => 'paket', 'pakat' => 'paket', 'pakt' => 'paket',
            'dpt' => 'dapat', 'dapet' => 'dapat', 'dapetnya' => 'dapat', 'dptnya' => 'dapat',
            'aja' => 'saja', 'sja' => 'saja', 'doang' => 'saja',
            
            'gartis' => 'gratis', 'grts' => 'gratis', 'free' => 'gratis', 'gratisan' => 'gratis', 'gretong' => 'gratis',
            'pmoela' => 'pemula', 'pmula' => 'pemula', 'pemola' => 'pemula', 'pmla' => 'pemula', 'pemulaa' => 'pemula', 'mula' => 'pemula',
            'propesional' => 'profesional', 'pro' => 'profesional', 'profesinal' => 'profesional', 'prfessional' => 'profesional', 'ptofesional' => 'profesional',
            'bisns' => 'bisnis', 'bsnis' => 'bisnis', 'bsns' => 'bisnis', 'bussines' => 'bisnis', 'business' => 'bisnis', 'biznis' => 'bisnis',
            
            'ftr' => 'fitur', 'isinya' => 'fitur', 'fasilitas' => 'fitur',
            'bda' => 'beda', 'bdanya' => 'beda', 'bedanya' => 'beda', 'perbedaan' => 'beda'
        ];

        $cleanMessage = preg_replace('/[^\w\s]/', '', $rawMessage);
        $words = explode(' ', $cleanMessage);
        foreach($words as &$w) {
            if(isset($slangDict[$w])) $w = $slangDict[$w];
        }
        $message = implode(' ', $words);

        $realIp = $request->ip();
        if ($request->hasHeader('X-Forwarded-For')) {
            $ips = explode(',', $request->header('X-Forwarded-For'));
            $realIp = trim($ips[0]);
        }

        $lead = null;
        if ($request->lead_id) {
            $lead = ChatbotLead::find($request->lead_id);
        }

        if ($lead && in_array($lead->live_chat_status, ['pending', 'active']) && !$request->is_autoclose) {
            $history = json_decode($lead->chat_history, true) ?? [];
            $history[] = ['sender' => 'user', 'text' => $rawMessage, 'time' => now()->format('d M, H:i')];
            
            $lead->update([
                'chat_history' => json_encode($history),
                'last_message' => $rawMessage
            ]);
            
            return response()->json([
                'reply' => null,
                'lead_id' => $lead->id,
                'show_live_chat_btn' => false
            ]);
        }

        if ($request->is_autoclose) {
            if ($lead) {
                $contactInfo = auth()->check() ? auth()->user()->email : 'Diakhiri Otomatis (Guest)';
                $lead->update([
                    'contact_info' => $contactInfo,
                    'chat_history' => json_encode($request->chat_history)
                ]);
            }
            return response()->json(['success' => true]);
        }

        if (!$lead) {
            $lead = ChatbotLead::create([
                'user_id' => auth()->id(),
                'ip_address' => $realIp, 
                'topic_context' => $topic,
                'contact_info' => '-', 
                'chat_history' => json_encode($request->chat_history),
                'last_message' => $rawMessage
            ]);
        } else {
            $lead->update([
                'chat_history' => json_encode($request->chat_history),
                'last_message' => $rawMessage
            ]);
        }

        if ($request->is_followup) {
            $lead->update(['contact_info' => $rawMessage]);
            return response()->json([
                'reply' => 'Terima kasih! Tim ScanYuk akan segera menindaklanjuti kendala Anda melalui kontak tersebut. Sesi chat ini Mimin tutup ya! 👋',
                'is_finished' => true,
                'lead_id' => $lead->id
            ]);
        }

        $reply = "";
        $showLiveChatBtn = false;

        if (
            $topic === 'Paket & Pembayaran' || 
            str_contains($message, 'paket') || str_contains($message, 'harga') || 
            str_contains($message, 'bayar') || str_contains($message, 'fitur') || 
            str_contains($message, 'beda') || str_contains($message, 'gratis') || 
            str_contains($message, 'pemula') || str_contains($message, 'profesional') || 
            str_contains($message, 'bisnis')
        ) {
            
            $prompt = "Analyze this Indonesian message: \"$message\". Return ONLY a valid JSON object without any markdown. Keys must be 'intent' (values: check_price, check_features, compare, unknown) and 'package_name' (must be exactly one of: Gratis, Pemula, Profesional, Bisnis, or null).";
            
            $ollamaUrl = env('OLLAMA_URL', 'http://ollama:11434/api/generate');

            try {
                $llmResponse = Http::timeout(5)->post($ollamaUrl, [
                    'model' => 'qwen2:1.5b',
                    'prompt' => $prompt,
                    'format' => 'json',
                    'stream' => false
                ]);

                if ($llmResponse->successful()) {
                    $extracted = json_decode($llmResponse->json('response'), true);
                    $intent = $extracted['intent'] ?? 'unknown';
                    $pkgName = $extracted['package_name'] ?? null;

                    if (strtolower((string)$pkgName) === 'unknown') $pkgName = null;

                    if (!$pkgName && $intent !== 'compare') {
                        $guessPkg = PricingPackage::whereRaw("? % name", [$message])
                                    ->orWhereRaw("similarity(name, ?) > 0.15", [$message])
                                    ->orderByRaw("similarity(name, ?) DESC", [$message])
                                    ->first();
                        if ($guessPkg) {
                            $pkgName = $guessPkg->name;
                        }
                    }

                    if ($intent === 'compare' || str_contains($message, 'beda') || str_contains($message, 'semua')) {
                        $packages = PricingPackage::orderBy('price', 'asc')->get();
                        $reply = "Tentu! Berikut adalah perbandingan singkat paket kami:<br><br>";
                        foreach($packages as $p) {
                            $features = is_array($p->features) ? implode(', ', $p->features) : (json_decode($p->features, true) ? implode(', ', json_decode($p->features, true)) : $p->features);
                            $reply .= "📦 <b>Paket {$p->name} (Rp" . number_format($p->price, 0, ',', '.') . ")</b><br>Termasuk: $features.<br><br>";
                        }
                        $reply .= "Mana yang kira-kira paling pas untuk kebutuhan Anda saat ini?";
                    } elseif ($pkgName) {
                        $package = PricingPackage::whereRaw("name ILIKE ?", ['%'.$pkgName.'%'])
                                    ->orWhereRaw("similarity(name, ?) > 0.1", [$pkgName])
                                    ->orderByRaw("similarity(name, ?) DESC", [$pkgName])
                                    ->first();

                        if ($package) {
                            $features = is_array($package->features) ? implode(', ', $package->features) : (json_decode($package->features, true) ? implode(', ', json_decode($package->features, true)) : $package->features);
                            if ($intent === 'check_features' || str_contains($message, 'dapat') || str_contains($message, 'fitur')) {
                                $reply = "Untuk <b>Paket {$package->name}</b>, fitur utama yang akan Anda dapatkan antara lain: {$features}. Harganya sendiri hanya Rp" . number_format($package->price, 0, ',', '.') . ".";
                            } else {
                                $reply = "Harga <b>Paket {$package->name}</b> saat ini adalah Rp" . number_format($package->price, 0, ',', '.') . ". Paket ini sudah mencakup {$features}. Ingin lanjut mendaftar paket ini?";
                            }
                        } else {
                            $reply = "Mimin kurang yakin dengan paket yang Anda maksud. Kami menyediakan paket Gratis, Pemula, Profesional, dan Bisnis. Boleh diperjelas paket mana yang ingin dicek?";
                        }
                    } else {
                        $packages = PricingPackage::orderBy('price', 'asc')->get();
                        $reply = "Kami memiliki beberapa pilihan paket: ";
                        $pkgNames = [];
                        foreach($packages as $p) {
                            $pkgNames[] = "<b>" . $p->name . "</b> (Rp" . number_format($p->price, 0, ',', '.') . ")";
                        }
                        $reply .= implode(', ', $pkgNames) . ". Silakan sebutkan nama paket yang ingin Anda ketahui lebih detail spesifikasinya!";
                    }
                } else {
                    throw new \Exception("LLM Response Error");
                }
            } catch (\Exception $e) {
                $package = PricingPackage::whereRaw("? % name", [$message])
                            ->orWhereRaw("name ILIKE ?", ['%'.trim($message).'%'])
                            ->orderByRaw("similarity(name, ?) DESC", [$message])
                            ->first();

                if ($package) {
                    $features = is_array($package->features) ? implode(', ', $package->features) : (json_decode($package->features, true) ? implode(', ', json_decode($package->features, true)) : $package->features);
                    $reply = "Mimin bantu cek ya! Harga <b>Paket {$package->name}</b> adalah Rp" . number_format($package->price, 0, ',', '.') . ". Paket ini sudah mencakup: {$features}.";
                } else {
                    $reply = "Koneksi AI sedang sibuk. Untuk detail harga paket Gratis, Pemula, Profesional, dan Bisnis, Anda bisa cek langsung di menu 'Pricing' ya!";
                }
            }

        } else {
            $knowledges = ChatbotKnowledge::whereIn('topic', [$topic, 'Umum'])->get();
            $bestMatch = null;
            $highestScore = 0;

            foreach ($knowledges as $k) {
                $keywords = json_decode($k->keywords, true);
                $score = 0;

                foreach ($keywords as $kw) {
                    $kw = strtolower(trim($kw));
                    
                    if (str_contains($message, $kw)) {
                        $score += strlen($kw) * 2; 
                    } else {
                        $kwWords = explode(' ', $kw);
                        foreach($kwWords as $kww) {
                            foreach($words as $userWord) {
                                if (strlen($userWord) > 3 && levenshtein($userWord, $kww) <= 1) {
                                    $score += 2;
                                }
                            }
                        }
                    }
                }

                if ($score > $highestScore) {
                    $highestScore = $score;
                    $bestMatch = $k;
                }
            }

            if ($highestScore > 0) {
                $reply = $bestMatch->response;
            } else {
                $reply = "Maaf, Mimin kurang menangkap maksud Anda terkait topik <b>".$topic."</b> ini. Ingin saya hubungkan dengan Tim CS / Admin (Live Chat)?";
                $showLiveChatBtn = true;
            }
        }

        return response()->json([
            'reply' => $reply,
            'lead_id' => $lead->id,
            'show_live_chat_btn' => $showLiveChatBtn
        ]);
    }

    public function requestLiveChat(Request $request) {
        $lead = null;
        if ($request->lead_id) {
            $lead = ChatbotLead::find($request->lead_id);
        }

        $autoMsg = ['sender' => 'bot', 'text' => 'Meneruskan permintaan ke tim Live Chat. Mohon tunggu sebentar...', 'time' => now()->format('d M, H:i')];

        if (!$lead) {
            $realIp = $request->ip();
            if ($request->hasHeader('X-Forwarded-For')) {
                $ips = explode(',', $request->header('X-Forwarded-For'));
                $realIp = trim($ips[0]);
            }

            $lead = ChatbotLead::create([
                'user_id' => auth()->id(),
                'ip_address' => $realIp,
                'topic_context' => 'Bantuan Live Chat (Langsung)',
                'contact_info' => '-',
                'chat_history' => json_encode([$autoMsg]),
                'last_message' => 'Meminta terhubung ke Live Chat',
                'live_chat_status' => 'pending'
            ]);
        } else {
            $history = json_decode($lead->chat_history, true) ?? [];
            $history[] = $autoMsg;
            $lead->update([
                'live_chat_status' => 'pending',
                'chat_history' => json_encode($history)
            ]);
        }

        return response()->json(['success' => true, 'lead_id' => $lead->id]);
    }

    public function pollLiveChat($leadId) {
        $lead = ChatbotLead::find($leadId);
        return response()->json([
            'status' => $lead ? $lead->live_chat_status : 'none',
            'history' => $lead ? json_decode($lead->chat_history) : [],
            'admin_name' => ($lead && $lead->admin_id) ? \App\Models\User::find($lead->admin_id)->name : null
        ]);
    }

    public function sendLiveChatMessage(Request $request) {
        $lead = ChatbotLead::find($request->lead_id);
        if ($lead) {
            $history = json_decode($lead->chat_history, true) ?? [];
            $history[] = ['sender' => 'user', 'text' => $request->message, 'time' => now()->format('d M, H:i')];
            $lead->update(['chat_history' => json_encode($history)]);
        }
        return response()->json(['success' => true]);
    }
}