<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $email = $this->buildEmail($request->string('first_name')->toString(), $request->string('last_name')->toString());

        $request->merge([
            'email' => $email,
        ]);

        $request->validate([
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-\.]+$/u'],
            'middle_name' => ['nullable', 'string', 'max:100', 'regex:/^[\pL\s\-\.]+$/u'],
            'last_name' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-\.]+$/u'],
            'suffix' => ['nullable', 'string', 'max:20'],
            'terms' => ['accepted'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', Rules\Password::min(8)->mixedCase()->numbers()->symbols()],
        ], [
            'email.unique' => 'An account with this email address already exists.',
        ]);

        $fullName = collect([
            $request->string('first_name')->toString(),
            $request->string('middle_name')->toString(),
            $request->string('last_name')->toString(),
            $request->string('suffix')->toString(),
        ])->filter()->implode(' ');

        $user = User::create([
            'name' => $fullName,
            'first_name' => $request->string('first_name')->toString(),
            'middle_name' => $request->string('middle_name')->toString() ?: null,
            'last_name' => $request->string('last_name')->toString(),
            'suffix' => $request->string('suffix')->toString() ?: null,
            'email' => $email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        $request->session()->flash('success', 'Account created successfully! Welcome to Attendly.');

        return redirect(route('dashboard', absolute: false));
    }

    private function buildEmail(string $firstName, string $lastName): string
    {
        $localPart = Str::of($lastName.' '.$firstName)
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '.')
            ->trim('.')
            ->toString();

        return $localPart.'@gordoncollege.edu.ph';
    }
}
