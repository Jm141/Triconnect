<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Check if this is the super admin
        if ($user->userCode === 'Admin=349262938') {
            // Super admin - redirect to main dashboard
            return redirect()->route('dashboard');
        } else {
            // For regular users, get their access from user_access_links
            $userAccess = \App\Models\UserAccess::where('userCode', $user->userCode)->first();

            if ($userAccess) {
                session(['userAccess' => $userAccess]);
                
                // Redirect based on user access type
                if (strpos($userAccess->access, 'teacher') !== false) {
                    return redirect()->route('teacher.dashboard');
                } elseif (strpos($userAccess->access, 'parent') !== false) {
                    return redirect()->route('parent.dashboard');
                } elseif (strpos($userAccess->access, 'principal') !== false) {
                    return redirect()->route('principal.dashboard');
                } else {
                    // Default fallback to userDashboard
                    return redirect()->route('userDashboard');
                }
            } else {
                // No access found - redirect to userDashboard as fallback
                return redirect()->route('userDashboard');
            }
        }
    }
}
