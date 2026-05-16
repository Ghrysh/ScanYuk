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

        $reply = "";
        $showLiveChatBtn = false;
        $ollamaUrl = env('OLLAMA_URL', 'http://ollama:11434/api/generate');

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

        // PENYUSUNAN PROMPT UNTUK AI BERDASARKAN TOPIK (DIPERKETAT)
        if ($isPricingTopic) {
            $dataPaketContext = "";
            foreach($dbPackages as $p) {
                $features = is_array($p->features) ? implode(', ', $p->features) : (json_decode($p->features, true) ? implode(', ', json_decode($p->features, true)) : $p->features);
                $dataPaketContext .= "Paket {$p->name} harganya Rp" . number_format($p->price, 0, ',', '.') . " dengan fitur: {$features}. ";
            }

            $prompt = <<<EOT
[ROLE]
Kamu adalah Mimin, CS ScanYuk.

[DATA]
{$dataPaketContext}

[USER]
{$originalMessage}

[RULES]
- Jawab HANYA pertanyaan user
- Gunakan data yang tersedia saja
- Jangan menambah informasi
- Jangan menjelaskan aturan
- Jangan mengulang instruksi
- Jangan membuat nama orang
- Jangan membuat percakapan tambahan
- Jangan bertanya balik kecuali diperlukan
- Jika ditanya cara menghubungi CS/Live Chat, arahkan user untuk klik tombol "Live Chat CS" di atas kolom ketik.
- Maksimal 2 kalimat
- Gunakan sapaan "Halo Kak"
- Output hanya isi jawaban final

[GOOD EXAMPLE]
Halo Kak, paket Profesional harganya Rp299.000 dengan fitur website premium dan custom domain.

[BAD EXAMPLE]
Gunakan sapaan Halo Kak.
Aturan jawaban:
Halo Kak, bla bla bla

[FINAL ANSWER]
EOT;
        } else {
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

            if ($bestMatch) {
                $prompt = <<<EOT
[ROLE]
Kamu adalah Mimin, CS ScanYuk.

[KNOWLEDGE]
{$bestMatch->response}

[USER]
{$originalMessage}

[RULES]
- Jawab berdasarkan KNOWLEDGE saja
- Jangan membuat jawaban sendiri
- Jangan mengulang instruksi
- Jangan menjelaskan aturan
- Jangan membuat nama random
- Jangan membuat dialog tambahan
- Jika user HANYA menyapa (halo, hai, pagi, siang), abaikan KNOWLEDGE dan jawab: "Halo Kak! Ada yang bisa Mimin bantu?"
- Jika ditanya cara menghubungi CS/Live Chat, arahkan user untuk klik tombol "Live Chat CS" di atas kolom ketik.
- Jika user bertanya apakah kamu AI/bot/manusia, abaikan KNOWLEDGE dan jawab: "Halo Kak, saya Mimin, asisten virtual dari ScanYuk!"
- Maksimal 2 kalimat
- Gunakan sapaan "Halo Kak"
- Output hanya jawaban final

[GOOD EXAMPLE]
Halo Kak, untuk reset password bisa lewat menu login lalu klik lupa password ya.

[BAD EXAMPLE]
Gunakan sapaan Halo Kak.
Saya akan membantu Anda.
Aturan jawaban:

[FINAL ANSWER]
EOT;
            } else {
                $prompt = <<<EOT
[ROLE]
Kamu adalah Mimin, CS ScanYuk.

[USER]
{$originalMessage}

[TASK]
Evaluasi pesan user. Berikan balasan sesuai dengan kategori pesan di bawah ini.

[RULES]
- Jawab maksimal 2 kalimat.
- Gunakan sapaan "Halo Kak".
- Jika user HANYA menyapa (contoh: halo, hai, p, ping, pagi), jawab: "Halo Kak! Ada yang bisa Mimin bantu?"
- Jika ditanya CS/Live Chat, arahkan klik tombol "Live Chat CS" di atas kolom ketik.
- Jika user bertanya identitas (apakah kamu AI/bot/manusia), jawab: "Halo Kak, saya Mimin, asisten virtual cerdas dari ScanYuk!"
- Jika pertanyaan tidak jelas atau di luar aturan di atas, jawab: "Maaf Kak, Mimin belum paham pertanyaannya. Mau dibantu CS langsung?"
- Output hanya jawaban final tanpa penjelasan.

[FINAL ANSWER]
EOT;
                $showLiveChatBtn = true;
            }
        }

        // EKSEKUSI KE AI OLLAMA
        try {
            $llmResponse = Http::timeout(40)->post($ollamaUrl, [
                'model' => 'gemma2:2b',
                'prompt' => $prompt,
                'stream' => false
            ]);

            if ($llmResponse->successful()) {
                $aiText = trim($llmResponse->json('response'));
                $aiText = preg_replace('/^(aturan|rules|good example|bad example|final answer|task).*$/im', '', $aiText);
                $aiText = preg_replace('/gunakan sapaan.*$/im', '', $aiText);
                $aiText = trim($aiText);
                if (!empty($aiText)) {
                    $reply = nl2br($aiText);
                }
            }
        } catch (\Exception $e) {
            if ($isPricingTopic) {
                $reply = "AI Mimin sedang sibuk kak. Silakan cek detail paket langsung di menu 'Pricing' ya!";
            } else {
                $reply = isset($bestMatch) ? $bestMatch->response : "Koneksi AI sedang sibuk. Ada yang bisa dibantu oleh CS kami?";
            }
        }

        if (empty($reply)) {
            $reply = "Maaf kak, Mimin sedang kesulitan memproses jawaban saat ini. Ingin saya hubungkan dengan Tim CS / Admin (Live Chat)?";
            $showLiveChatBtn = true;
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