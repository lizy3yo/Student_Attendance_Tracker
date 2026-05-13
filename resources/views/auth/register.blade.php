<x-guest-layout>
    <x-slot name="title">Register</x-slot>

    <div class="auth-title">
        Create an account
        <i data-lucide="graduation-cap" data-size="18" style="margin-left:.35rem;vertical-align:middle;"></i>
    </div>
    <div class="auth-sub">Register a new teacher account to get started</div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="name">Full Name</label>
            <input class="form-control" id="name" type="text" name="name"
                   value="{{ old('name') }}" placeholder="Juan Dela Cruz"
                   required autofocus autocomplete="name">
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <input class="form-control" id="email" type="email" name="email"
                   value="{{ old('email') }}" placeholder="teacher@school.edu.ph"
                   required autocomplete="username">
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input class="form-control" id="password" type="password" name="password"
                   placeholder="Min. 8 characters" required autocomplete="new-password">
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirm Password</label>
            <input class="form-control" id="password_confirmation" type="password"
                   name="password_confirmation" placeholder="Repeat your password"
                   required autocomplete="new-password">
            @error('password_confirmation')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <button class="btn-auth" type="submit">
            Create Account
            <i data-lucide="arrow-right" data-size="18" style="margin-left:.35rem;vertical-align:middle;"></i>
        </button>
    </form>

    <div class="auth-footer">
        Already have an account?
        <a class="auth-link" href="{{ route('login') }}">Sign In</a>
    </div>
</x-guest-layout>
