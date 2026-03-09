<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
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
            'admin_code' => ['nullable', 'string', 'max:255'],
        ]);

        $isAdmin = false;
        $adminCode = trim((string) $request->input('admin_code', ''));
        $configuredAdminCode = trim((string) config('admin.registration_code', ''));
        $adminSelfRegistrationEnabled = (bool) config('admin.self_registration_enabled', false);

        if ($adminCode !== '') {
            if (! $adminSelfRegistrationEnabled) {
                throw ValidationException::withMessages([
                    'admin_code' => 'Admin registration is disabled.',
                ]);
            }

            if ($configuredAdminCode === '' || ! hash_equals($configuredAdminCode, $adminCode)) {
                throw ValidationException::withMessages([
                    'admin_code' => 'Invalid admin registration code.',
                ]);
            }

            $isAdmin = true;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($isAdmin) {
            $user->is_admin = true;
            $user->save();
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
