<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    /**
     * Display browser sessions panel.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Retrieve active database sessions if using database driver, or mock current session
        $sessions = collect();
        if (config('session.driver') === 'database') {
            $sessions = DB::table('sessions')
                ->where('user_id', $user->id)
                ->orderBy('last_activity', 'desc')
                ->get()
                ->map(function ($session) use ($request) {
                    $agent = $this->parseUserAgent($session->user_agent);

                    return (object) [
                        'id' => $session->id,
                        'ip_address' => $session->ip_address,
                        'browser' => $agent['browser'],
                        'os' => $agent['os'],
                        'icon' => $agent['icon'],
                        'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                        'is_current' => $session->id === $request->session()->getId(),
                    ];
                });
        } else {
            // Fallback for file/cookie drivers: show current session
            $agent = $this->parseUserAgent($request->header('User-Agent'));
            $sessions = collect([
                (object) [
                    'id' => $request->session()->getId(),
                    'ip_address' => $request->ip(),
                    'browser' => $agent['browser'],
                    'os' => $agent['os'],
                    'icon' => $agent['icon'],
                    'last_active' => 'Just now',
                    'is_current' => true,
                ],
            ]);
        }

        return view('pages.settings', compact('user', 'sessions'));
    }

    /**
     * Revoke / log out a specific session.
     */
    public function destroy(Request $request, $id)
    {
        if (config('session.driver') !== 'database') {
            return back()->with('error', 'Revoking sessions is only supported with the database session driver.');
        }

        if ($id === $request->session()->getId()) {
            return back()->with('error', 'You cannot revoke your current active session from here.');
        }

        DB::table('sessions')
            ->where('user_id', Auth::id())
            ->where('id', $id)
            ->delete();

        return back()->with('success', 'Session revoked successfully.');
    }

    /**
     * Parse a user agent string to extract basic browser and OS information.
     */
    private function parseUserAgent($userAgent)
    {
        $browser = 'Unknown Browser';
        $os = 'Unknown OS';
        $icon = 'heroicon-o-computer-desktop';

        if (empty($userAgent)) {
            return compact('browser', 'os', 'icon');
        }

        // Parse OS
        if (preg_match('/win/i', $userAgent)) {
            $os = 'Windows';
        } elseif (preg_match('/mac/i', $userAgent)) {
            $os = 'macOS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $os = 'Linux';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            $os = 'iOS';
            $icon = 'heroicon-o-device-phone-mobile';
        } elseif (preg_match('/android/i', $userAgent)) {
            $os = 'Android';
            $icon = 'heroicon-o-device-phone-mobile';
        }

        // Parse Browser
        if (preg_match('/chrome/i', $userAgent)) {
            $browser = 'Google Chrome';
        } elseif (preg_match('/safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/firefox/i', $userAgent)) {
            $browser = 'Mozilla Firefox';
        } elseif (preg_match('/edge/i', $userAgent)) {
            $browser = 'Microsoft Edge';
        } elseif (preg_match('/msie|trident/i', $userAgent)) {
            $browser = 'Internet Explorer';
        }

        return compact('browser', 'os', 'icon');
    }
}
