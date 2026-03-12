<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatbotKnowledge;
use App\Models\ChatbotLead;

class ChatbotController extends Controller
{
    public function processChat(Request $request)
    {
        $topic = $request->topic; 
        
        if ($request->is_followup) {
            ChatbotLead::create([
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
                'contact_info' => trim($request->message),
                'topic_context' => $topic ?? 'Umum',
                'last_message' => $request->last_chat ?? '-',
                'chat_history' => json_encode($request->chat_history)
            ]);
            return response()->json([
                'reply' => 'Terima kasih banyak! Tim teknis ScanYuk akan segera menghubungi Anda melalui kontak tersebut. Sesi chat ini Mimin tutup ya! 👋',
                'is_finished' => true
            ]);
        }

        $message = strtolower(trim($request->message));
        $cleanMessage = preg_replace('/[^\w\s]/', '', $message); 

        $knowledges = ChatbotKnowledge::where('topic', $topic)->get();
        
        $bestMatch = null;
        $highestScore = 0;

        foreach ($knowledges as $k) {
            $keywords = json_decode($k->keywords, true);
            $score = 0;

            foreach ($keywords as $kw) {
                $kw = strtolower(trim($kw));
                if (str_contains($cleanMessage, $kw)) {
                    $score += strlen($kw); 
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
            $reply = "Maaf, Mimin kurang menangkap maksud Anda terkait topik <b>" . $topic . "</b>. Coba gunakan kata kunci yang lebih singkat, atau klik tombol 'Akhiri Chat & Hubungi CS' di bawah jika butuh bantuan langsung.";
        }

        return response()->json(['reply' => $reply]);
    }
}