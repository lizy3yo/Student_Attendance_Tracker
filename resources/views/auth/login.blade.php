<x-guest-layout>
    <x-slot name="title">Sign In</x-slot>

    <div class="auth-title">Welcome back 👋</div>
    <div class="auth-sub">Sign in to your teacher account</div>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="status-banner status-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" id="login-form">
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
            <div style="position:relative;">
                <input class="form-control" id="password" type="password" name="password"
                       placeholder="••••••••" required autocomplete="current-password"
                       style="padding-right:3rem;">
                <button
                    type="button"
                    id="toggle-password"
                    aria-label="Show password"
                    aria-pressed="false"
                    style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);background:none;border:none;color:#6b7280;cursor:pointer;display:flex;align-items:center;justify-content:center;padding:.25rem;"
                >
                    <i data-lucide="eye" data-size="18"></i>
                </button>
            </div>
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="checkbox-wrap" style="margin-bottom:1.25rem;">
            <input type="checkbox" id="remember_me" name="remember" value="1" @checked(old('remember'))>
            <label for="remember_me">Remember me</label>
        </div>

        <button class="btn-auth" type="submit" style="display:flex;align-items:center;justify-content:center;position:relative;">
            <i data-lucide="arrow-right" data-size="18" style="position:absolute;left:1rem;"></i>
            <span>Sign In</span>
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('toggle-password');
            const emailInput = document.getElementById('email');
            const rememberInput = document.getElementById('remember_me');

            const storageKeys = {
                email: 'attendly.remember.email',
                remember: 'attendly.remember.checked',
            };

            const rememberedEmail = window.localStorage.getItem(storageKeys.email);
            const rememberedState = window.localStorage.getItem(storageKeys.remember) === '1';

            if (rememberedState && emailInput && rememberedEmail && !emailInput.value) {
                emailInput.value = rememberedEmail;
            }

            if (rememberInput) {
                rememberInput.checked = rememberedState;
            }

            if (emailInput && rememberInput) {
                rememberInput.addEventListener('change', () => {
                    if (rememberInput.checked) {
                        window.localStorage.setItem(storageKeys.remember, '1');
                        window.localStorage.setItem(storageKeys.email, emailInput.value.trim());
                    } else {
                        window.localStorage.removeItem(storageKeys.remember);
                        window.localStorage.removeItem(storageKeys.email);
                    }
                });

                emailInput.addEventListener('input', () => {
                    if (rememberInput.checked) {
                        window.localStorage.setItem(storageKeys.email, emailInput.value.trim());
                    }
                });
            }

            const form = document.getElementById('login-form');
            if (form && emailInput && rememberInput) {
                form.addEventListener('submit', () => {
                    if (rememberInput.checked) {
                        window.localStorage.setItem(storageKeys.remember, '1');
                        window.localStorage.setItem(storageKeys.email, emailInput.value.trim());
                    } else {
                        window.localStorage.removeItem(storageKeys.remember);
                        window.localStorage.removeItem(storageKeys.email);
                    }
                });
            }

            if (!passwordInput || !toggleButton) return;

            toggleButton.addEventListener('click', () => {
                const showing = passwordInput.type === 'text';
                passwordInput.type = showing ? 'password' : 'text';
                toggleButton.setAttribute('aria-pressed', String(!showing));
                toggleButton.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
                toggleButton.innerHTML = showing
                    ? '<i data-lucide="eye" data-size="18"></i>'
                    : '<i data-lucide="eye-off" data-size="18"></i>';

                if (window.lucide && typeof window.lucide.createIcons === 'function') {
                    window.lucide.createIcons();
                }
            });
        });
    </script>
</x-guest-layout>
