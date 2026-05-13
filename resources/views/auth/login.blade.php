<x-guest-layout>
    <x-slot name="title">Sign In</x-slot>

    <div class="auth-title">Welcome back 👋</div>
    <div class="auth-sub">Sign in to your teacher account</div>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="status-banner status-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <input class="form-control" id="email" type="email" name="email"
                   value="{{ old('email') }}" placeholder="teacher@school.edu.ph"
                   required autofocus autocomplete="username">
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input class="form-control" id="password" type="password" name="password"
                   placeholder="••••••••" required autocomplete="current-password">
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="checkbox-wrap" style="margin-bottom:1.25rem;">
            <input type="checkbox" id="remember_me" name="remember">
            <label for="remember_me">Remember me for 30 days</label>
        </div>

        <button class="btn-auth" type="submit">
            Sign In
            <i data-lucide="arrow-right" data-size="18" style="margin-left:.35rem;vertical-align:middle;"></i>
        </button>
    </form>

    <div class="auth-footer">
        @if (Route::has('password.request'))
            <a class="auth-link" href="{{ route('password.request') }}">Forgot your password?</a>
            &nbsp;·&nbsp;
        @endif
        Don't have an account?
        <a class="auth-link" href="{{ route('register') }}">Register</a>
    </div>
</x-guest-layout>
