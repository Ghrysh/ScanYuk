<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatbotLead;
use App\Models\PricingPackage;
use App\Models\ChatbotKnowledge;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function processChat(Request $request)
    {
        $topic = $request->topic ?? 'Umum'; 
        $rawMessage = strtolower(trim($request->message));
        $originalMessage = trim($request->message);

        // 1. DICTIONARY SLANG
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

        // 2. CLEANSING PESAN UNTUK PENCOCOKAN KEYWORD (Pesan asli tetap disimpan di $originalMessage)
        $cleanMessage = preg_replace('/[^\w\s]/', '', $rawMessage);
        $words = explode(' ', $cleanMessage);
        foreach($words as &$w) {
            if(isset($slangDict[$w])) $w = $slangDict[$w];
        }
        $message = implode(' ', $words);

        // 3. GET IP & IDENTIFIKASI LEAD
        $realIp = $request->ip();
        if ($request->hasHeader('X-Forwarded-For')) {
            $ips = explode(',', $request->header('X-Forwarded-For'));
            $realIp = trim($ips[0]);
        }

        $lead = null;
        if ($request->lead_id) {
            $lead = ChatbotLead::find($request->lead_id);
        }

        // Jika Sedang Live Chat
        if ($lead && in_array($lead->live_chat_status, ['pending', 'active']) && !$request->is_autoclose) {
            $history = json_decode($lead->chat_history, true) ?? [];
            $history[] = ['sender' => 'user', 'text' => $originalMessage, 'time' => now()->format('d M, H:i')];
            $lead->update(['chat_history' => json_encode($history), 'last_message' => $originalMessage]);
            return response()->json(['reply' => null, 'lead_id' => $lead->id, 'show_live_chat_btn' => false]);
        }

        if ($request->is_autoclose) {
            if ($lead) {
                $contactInfo = auth()->check() ? auth()->user()->email : 'Diakhiri Otomatis (Guest)';
                $lead->update(['contact_info' => $contactInfo, 'chat_history' => json_encode($request->chat_history)]);
            }
            return response()->json(['success' => true]);
        }

        if (!$lead) {
            $lead = ChatbotLead::create([
                'user_id' => auth()->id(), 'ip_address' => $realIp, 'topic_context' => $topic,
                'contact_info' => '-', 'chat_history' => json_encode($request->chat_history), 'last_message' => $originalMessage
            ]);
        } else {
            $lead->update(['chat_history' => json_encode($request->chat_history), 'last_message' => $originalMessage]);
        }

        if ($request->is_followup) {
            $lead->update(['contact_info' => $originalMessage]);
            return response()->json([
                'reply' => 'Terima kasih! Tim ScanYuk akan segera menindaklanjuti kendala Anda melalui kontak tersebut. Sesi chat ini Mimin tutup ya! 👋',
                'is_finished' => true, 'lead_id' => $lead->id
            ]);
        }

        // =========================================================================
        // 4. RULE-BASED FAST RESPONSE (Menangani Sapaan & Terima Kasih Tanpa AI)
        // =========================================================================
        
        // Cek Sapaan (Lebih fleksibel, tidak harus persis 1 kata. Max 4 kata agar "Halo mimin pagi" tetap masuk)
        if (preg_match('/\b(halo|hallo|hai|p|ping|pagi|siang|sore|malam|test|tes)\b/i', $cleanMessage) && str_word_count($cleanMessage) <= 4) {
            return response()->json([
                'reply' => 'Halo Kak! 👋 Mimin di sini. Ada yang bisa Mimin bantu terkait ScanYuk?',
                'lead_id' => $lead->id,
                'show_live_chat_btn' => false
            ]);
        }

        // Cek Terima Kasih
        if (preg_match('/\b(makasih|terima kasih|terimakasih|thanks|thx|thank you|oke|ok|sip|baik|baiklah)\b/i', $cleanMessage) && str_word_count($cleanMessage) <= 5) {
            return response()->json([
                'reply' => 'Sama-sama Kak! 😊 Apakah ada hal lain yang bisa Mimin bantu lagi?',
                'lead_id' => $lead->id,
                'show_live_chat_btn' => false
            ]);
        }

        // =========================================================================
        // 5. PENYIAPAN KONTEKS & KNOWLEDGE UNTUK AI
        // =========================================================================

        $showLiveChatBtn = false;
        // PENTING: GANTI endpoint generate menjadi chat
        $ollamaUrl = env('OLLAMA_URL', 'http://ollama:11434/api/chat');

        $dbPackages = PricingPackage::all();
        $dbPackageNames = $dbPackages->pluck('name')->toArray();
        
        $isPricingTopic = ($topic === 'Paket & Pembayaran');
        if (!$isPricingTopic) {
            foreach (array_merge(['paket', 'harga', 'bayar', 'fitur', 'beda', 'gratis', 'pemula', 'profesional', 'bisnis'], array_map('strtolower', $dbPackageNames)) as $keyword) {
                if (str_contains($message, $keyword)) {
                    $isPricingTopic = true;
                    break;
                }
            }
        }

        // Siapkan System Prompt
        $systemContent = "Kamu adalah Mimin, asisten virtual (Customer Service) dari ScanYuk yang ramah dan profesional. Selalu awali dengan sapaan 'Halo Kak'. Jawab dengan bahasa Indonesia yang santai tapi sopan. Jawablah secara singkat, maksimal 2 kalimat.\n\n";

        if ($isPricingTopic) {
            $dataPaketContext = "";
            foreach($dbPackages as $p) {
                $features = is_array($p->features) ? implode(', ', $p->features) : (json_decode($p->features, true) ? implode(', ', json_decode($p->features, true)) : $p->features);
                $dataPaketContext .= "- Paket {$p->name}: Harga Rp" . number_format($p->price, 0, ',', '.') . ", Fitur: {$features}.\n";
            }
            $systemContent .= "Berikut adalah DATA PAKET HARGA yang valid:\n{$dataPaketContext}\nJawab pertanyaan user HANYA berdasarkan data di atas. Jangan mengarang harga. Jika user bertanya di luar paket, sarankan untuk klik tombol Live Chat CS.";
        
        } else {
            // Pencarian Knowledge Base
            $knowledges = ChatbotKnowledge::all();
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

            // Jika ada Knowledge yang cocok (Threshold score > 0)
            if ($bestMatch && $highestScore > 2) {
                $systemContent .= "Berikut adalah INFORMASI (SOP) untuk menjawab pertanyaan user:\n" . $bestMatch->response . "\n\nJawab HANYA berdasarkan informasi di atas. Jika informasi kurang jelas, beritahu user untuk klik tombol Live Chat CS.";
            } else {
                // Jika tidak paham / Knowledge tidak ada
                $systemContent .= "Kamu TIDAK TAHU jawaban dari pertanyaan user karena tidak ada di database kamu. Tugasmu adalah meminta maaf dengan sopan, dan wajib mengarahkan user untuk menekan tombol 'Live Chat CS' agar bisa dibantu oleh agen manusia.";
                $showLiveChatBtn = true;
            }
        }

        // =========================================================================
        // 6. BUILD CHAT MESSAGES ARRAY (Sangat krusial untuk mencegah AI error)
        // =========================================================================
        $chatMessages = [];
        
        // A. Masukkan System Prompt
        $chatMessages[] = [
            'role' => 'system',
            'content' => $systemContent
        ];

        // B. Masukkan Chat History (Max 3 terakhir) agar AI paham konteks dialog
        $chatHistoryArr = json_decode($lead->chat_history, true) ?? [];
        $recentHistory = array_slice($chatHistoryArr, -3); 
        foreach ($recentHistory as $h) {
            $chatMessages[] = [
                'role' => ($h['sender'] === 'user') ? 'user' : 'assistant',
                'content' => $h['text']
            ];
        }

        // C. Masukkan Pesan User Saat Ini
        $chatMessages[] = [
            'role' => 'user',
            'content' => $originalMessage
        ];

        // =========================================================================
        // 7. REQUEST KE OLLAMA AI
        // =========================================================================
        $reply = "";
        try {
            $llmResponse = Http::timeout(40)->post($ollamaUrl, [
                'model' => 'gemma2:2b',
                'messages' => $chatMessages, // Menggunakan messages array, BUKAN string prompt
                'stream' => false,
                'options' => [
                    'temperature' => 0.1, // Rendah = jawaban konsisten sesuai SOP
                    'top_p' => 0.8,
                    'repeat_penalty' => 1.2
                ]
            ]);

            if ($llmResponse->successful()) {
                // Mengambil response dari format endpoint /api/chat
                $aiText = trim($llmResponse->json('message.content'));
                
                // Cleanup text jika ada sisa-sisa prompt (jarang terjadi di api/chat)
                $aiText = preg_replace('/^(aturan|rules|system|mimin:).*$/im', '', $aiText);
                $aiText = trim($aiText);
                if (!empty($aiText)) {
                    $reply = nl2br($aiText);
                }
            } else {
                throw new \Exception("LLM Error");
            }
        } catch (\Exception $e) {
            if ($isPricingTopic) {
                $reply = "Halo Kak! AI Mimin sedang sibuk kak. Silakan cek detail paket langsung di menu 'Pricing' ya, atau hubungi Live Chat.";
            } else {
                $reply = isset($bestMatch) ? "Halo Kak! " . $bestMatch->response : "Halo Kak, koneksi AI sedang sibuk. Ada yang bisa dibantu oleh Tim Live Chat kami?";
            }
            $showLiveChatBtn = true;
        }

        // Safety fallback
        if (empty($reply)) {
            $reply = "Maaf Kak, Mimin sedang kesulitan memproses jawaban saat ini. Ingin Mimin hubungkan dengan Tim CS / Admin (Live Chat)?";
            $showLiveChatBtn = true;
        }

        // Jika di response AI terdeteksi AI menyerah, otomatis munculkan tombol
        if (preg_match('/(live chat|agen manusia|cs|customer service|admin)/i', $reply)) {
            $showLiveChatBtn = true;
        }

        return response()->json([
            'reply' => $reply,
            'lead_id' => $lead->id,
            'show_live_chat_btn' => $showLiveChatBtn
        ]);
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