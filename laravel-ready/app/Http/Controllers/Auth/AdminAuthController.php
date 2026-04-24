<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function create(): View
    {
        return view('auth.admin-login', [
            'adminPath' => trim(config('portal.admin_path', 'studio-panel'), '/'),
        ]);
    }

    public function store(AdminLoginRequest $request): RedirectResponse
    {
        $key = Str::lower($request->string('email')).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors([
                'email' => 'Terlalu banyak percobaan login. Coba lagi beberapa menit lagi.',
            ])->onlyInput('email');
        }

        if (! Auth::attempt($request->only('email', 'password'), (bool) $request->boolean('remember'))) {
            RateLimiter::hit($key, 300);

            return back()->withErrors([
                'email' => 'Email atau password tidak cocok.',
            ])->onlyInput('email');
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();

        if (! $request->user()->is_admin) {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Akun ini tidak memiliki akses admin.',
            ]);
        }

        return redirect()->route('admin.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
