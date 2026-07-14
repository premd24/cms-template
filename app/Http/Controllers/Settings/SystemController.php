<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SystemController extends Controller
{
    /**
     * Display the system diagnostics details.
     */
    public function show()
    {
        $user = Auth::user();

        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug') ? 'Enabled' : 'Disabled',
            'session_driver' => config('session.driver'),
            'app_url' => config('app.url'),
            'timezone' => config('app.timezone'),
        ];

        return view('pages.settings', compact('user', 'systemInfo'));
    }
}
