<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Display the login form.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'country_code' => ['required', 'string'],
            'mobile' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // Combine country code and mobile for authentication
        $userMobile = $request->country_code . $request->mobile;

        // Attempt authentication using user_mobile field
        $credentials = [
            'user_mobile' => $userMobile,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // role_id 0 = superadmin, role_id 1 = admin → admin dashboard
            if ($user->role_id === 0 || $user->role_id === 1) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }

            // role_id 2 = user, role_id 3 = child → user dashboard
            if ($user->role_id === 2 || $user->role_id === 3) {
                $request->session()->regenerate();
                return redirect()->intended(route('user.dashboard'));
            }

            // If not an authorized role, logout and show error
            Auth::logout();

            return back()->withErrors([
                'mobile' => 'Access denied. Invalid user role.',
            ])->withInput($request->only('country_code', 'mobile'));
        }

        return back()->withErrors([
            'mobile' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('country_code', 'mobile'));
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }


}
