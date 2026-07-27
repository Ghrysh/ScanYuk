<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TamagotchiSession;
use App\Models\TamagotchiJourney;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TamagotchiWebController extends Controller
{
    /**
     * Tampilkan halaman Login Global
     */
    public function globalLoginView()
    {
        return view('tamagotchi.global_login');
    }

    /**
     * Proses Login Global
     */
    public function globalLoginPost(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $username = strtolower(trim($request->username));
        $session = TamagotchiSession::where('username', $username)->first();

        if (!$session) {
            return back()->with('error', 'Username Tamagotchi tidak ditemukan.');
        }

        if (Hash::check($request->password, $session->password)) {
            session()->put('tamagotchi_logged_in_' . $session->id, true);
            return redirect()->route('tamagotchi.index', $session->username)->with('success', 'Berhasil masuk ke Journey!');
        }

        return back()->with('error', 'Password salah.');
    }

    /**
     * Tampilkan halaman utama Journey (List)
     */
    public function index($username)
    {
        $session = TamagotchiSession::where('username', strtolower($username))->firstOrFail();
        
        // Cek apakah sudah login
        $isLoggedIn = session()->has('tamagotchi_logged_in_' . $session->id);

        if (!$isLoggedIn) {
            return view('tamagotchi.login', compact('session', 'username'));
        }

        $journeys = $session->journeys()->latest()->get();
        return view('tamagotchi.index', compact('session', 'journeys', 'username'));
    }

    /**
     * Proses Login Tamagotchi Web
     */
    public function login(Request $request, $username)
    {
        $request->validate(['password' => 'required|string']);
        $session = TamagotchiSession::where('username', strtolower($username))->firstOrFail();

        if (Hash::check($request->password, $session->password)) {
            session()->put('tamagotchi_logged_in_' . $session->id, true);
            return redirect()->route('tamagotchi.index', $username)->with('success', 'Berhasil login!');
        }

        return back()->with('error', 'Password salah.');
    }

    /**
     * Tampilkan halaman detail spesifik 1 Journey
     */
    public function show($username, $journey_id)
    {
        $session = TamagotchiSession::where('username', strtolower($username))->firstOrFail();
        
        $isLoggedIn = session()->has('tamagotchi_logged_in_' . $session->id);
        if (!$isLoggedIn) {
            return redirect()->route('tamagotchi.index', $username);
        }

        $journey = TamagotchiJourney::where('session_id', $session->id)->where('id', $journey_id)->firstOrFail();

        return view('tamagotchi.journey', compact('session', 'journey', 'username'));
    }

    /**
     * Ubah Username
     */
    public function changeUsername(Request $request, $username)
    {
        $session = TamagotchiSession::where('username', strtolower($username))->firstOrFail();
        if (!session()->has('tamagotchi_logged_in_' . $session->id)) {
            return redirect()->route('tamagotchi.index', $username);
        }

        $request->validate([
            'new_username' => 'required|string|alpha_dash|max:50|unique:tamagotchi_sessions,username'
        ], [
            'new_username.unique' => 'Username sudah digunakan oleh akun lain.'
        ]);

        $newUsername = strtolower($request->new_username);
        $session->username = $newUsername;
        $session->save();

        return redirect()->route('tamagotchi.index', $newUsername)->with('success', 'Username berhasil diubah!');
    }

    /**
     * Reset/Ubah Password
     */
    public function resetPassword(Request $request, $username)
    {
        $session = TamagotchiSession::where('username', strtolower($username))->firstOrFail();
        if (!session()->has('tamagotchi_logged_in_' . $session->id)) {
            return redirect()->route('tamagotchi.index', $username);
        }

        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:4'
        ]);

        if (!Hash::check($request->old_password, $session->password)) {
            return back()->with('error', 'Password lama salah.');
        }

        $session->password = Hash::make($request->new_password);
        $session->save();

        return back()->with('success', 'Password berhasil diubah!');
    }
}
