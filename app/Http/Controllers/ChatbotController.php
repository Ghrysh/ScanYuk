<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatbotKnowledge;
use App\Models\ChatbotLead;

class ChatbotController extends Controller
{
    public function processChat(Request $request)
    {
        $topic = $request->topic ?? 'Umum'; 
        $message = strtolower(trim($request->message));
        
        $slangDict = [
            'gmn' => 'bagaimana', 'gimana' => 'bagaimana', 'bgmn' => 'bagaimana',
            'brp' => 'berapa', 'klo' => 'kalau', 'kalo' => 'kalau',
            'bikin' => 'buat', 'bs' => 'bisa', 'gk' => 'tidak', 'ga' => 'tidak',
            'tdk' => 'tidak', 'dgn' => 'dengan', 'yg' => 'yang', 'utk' => 'untuk',
            'makasih' => 'terimakasih', 'trims' => 'terimakasih', 'thx' => 'terimakasih',
            'pw' => 'password', 'pass' => 'password', 'loginnya' => 'login'
        ];

        $cleanMessage = preg_replace('/[^\w\s]/', '', $message);
        $words = explode(' ', $cleanMessage);
        foreach($words as &$w) {
            if(isset($slangDict[$w])) $w = $slangDict[$w];
        }
        $cleanMessage = implode(' ', $words);

        $lead = null;
        if ($request->lead_id) {
            $lead = ChatbotLead::find($request->lead_id);
        }

        if (!$lead) {
            $lead = ChatbotLead::create([
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
                'topic_context' => $topic,
                'contact_info' => '-',
                'chat_history' => json_encode($request->chat_history),
                'last_message' => $message
            ]);
        } else {
            $lead->update([
                'chat_history' => json_encode($request->chat_history),
                'last_message' => $message
            ]);
        }

        if ($request->is_followup) {
            $lead->update(['contact_info' => $message]);
            return response()->json([
                'reply' => 'Terima kasih! Tim ScanYuk akan segera menindaklanjuti kendala Anda melalui kontak tersebut. Sesi chat ini Mimin tutup ya! 👋',
                'is_finished' => true,
                'lead_id' => $lead->id
            ]);
        }

        $knowledges = ChatbotKnowledge::whereIn('topic', [$topic, 'Umum'])->get();
        $bestMatch = null;
        $highestScore = 0;

        foreach ($knowledges as $k) {
            $keywords = json_decode($k->keywords, true);
            $score = 0;

            foreach ($keywords as $kw) {
                $kw = strtolower(trim($kw));
                
                if (str_contains($cleanMessage, $kw)) {
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

        $reply = $highestScore > 0 
            ? $bestMatch->response 
            : "Maaf, kata tersebut sedikit kurang jelas untuk topik <b>".$topic."</b> ini. Bisa dijelaskan dengan kata kunci yang lebih sederhana?";

        return response()->json([
            'reply' => $reply,
            'lead_id' => $lead->id
        ]);
    }
}
