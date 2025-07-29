<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // First, find the user by email
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Check password using bcrypt
        if (!password_verify($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Password is correct, now authenticate the user
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Check if this is the super admin
        if ($user->userCode === 'Admin=349262938') {
            // Super admin - redirect to main dashboard
            return redirect()->intended(route('dashboard', absolute: false));
        } else {
            // For regular users, get their access from user_access_links
            $userAccess = \App\Models\UserAccess::where('userCode', $user->userCode)->first();

            if ($userAccess) {
                // Add the user's name to the userAccess object for easy access in views
                $userAccess->name = $user->name;
                session(['userAccess' => $userAccess]);
                
                // Redirect based on user access type
                if (strpos($userAccess->access, 'teacher') !== false) {
                    return redirect()->intended(route('teacher.dashboard', absolute: false));
                } elseif (strpos($userAccess->access, 'parent') !== false) {
                    return redirect()->intended(route('parent.dashboard', absolute: false));
                } elseif (strpos($userAccess->access, 'principal') !== false) {
                    return redirect()->intended(route('principal.dashboard', absolute: false));
                } else {
                    // Default fallback to userDashboard
                    return redirect()->intended(route('userDashboard', absolute: false));
                }
            } else {
                // No access found - logout and show error
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'No access found for this user',
                ]);
            }
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('user');
    }
}
