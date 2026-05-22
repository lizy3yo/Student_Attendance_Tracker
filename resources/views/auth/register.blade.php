<x-guest-layout>
    <x-slot name="title">Register</x-slot>

    <div class="auth-title" style="display:flex;align-items:center;justify-content:center;gap:.4rem;">
        <i data-lucide="graduation-cap" data-size="18"></i>
        <span>Create an account</span>
    </div>
    <div class="auth-sub">Register a new teacher account to get started</div>

    <form method="POST" action="{{ route('register') }}" id="register-form">
        @csrf

        <div style="padding:1rem 1rem 1.15rem;border:1px solid #d7f3df;border-radius:18px;background:#fff;box-shadow:var(--shadow-card);margin-bottom:1rem;">
            <div style="height:8px;border-radius:999px;background:#ececec;overflow:hidden;margin-bottom:1rem;">
                <div id="register-progress" style="width:33.333%;height:100%;border-radius:999px;background:linear-gradient(90deg,#22c55e,#16a34a);"></div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:1rem;align-items:start;">
                <div data-step-indicator="1" style="text-align:center;">
                    <div style="width:46px;height:46px;margin:0 auto .65rem;border-radius:999px;display:flex;align-items:center;justify-content:center;background:linear-gradient(180deg,#22c55e,#16a34a);color:#fff;font-weight:700;box-shadow:0 8px 18px rgba(34,197,94,.22);">1</div>
                    <div style="font-size:.82rem;font-weight:700;color:#2b2b2b;line-height:1.2;">Personal Info</div>
                </div>

                <div data-step-indicator="2" style="text-align:center;">
                    <div style="width:46px;height:46px;margin:0 auto .65rem;border-radius:999px;display:flex;align-items:center;justify-content:center;background:#efefef;color:#9ca3af;font-weight:700;">2</div>
                    <div style="font-size:.82rem;font-weight:700;color:#a3a3a3;line-height:1.2;">Account Setup</div>
                </div>

                <div data-step-indicator="3" style="text-align:center;">
                    <div style="width:46px;height:46px;margin:0 auto .65rem;border-radius:999px;display:flex;align-items:center;justify-content:center;background:#efefef;color:#9ca3af;font-weight:700;">3</div>
                    <div style="font-size:.82rem;font-weight:700;color:#a3a3a3;line-height:1.2;">Review &amp; Agree</div>
                </div>

            </div>
        </div>

        <div style="padding:1.1rem 1rem 1.15rem;border:1px solid var(--border-color);border-radius:14px;background:var(--surface);box-shadow:var(--shadow-card);">
            <div data-step-panel="1">
                <div style="font-size:1.1rem;font-weight:800;color:var(--text);margin-bottom:.35rem;">Personal Information</div>
                <div style="color:var(--text-muted);margin-bottom:1rem;">Let's start with your basic details</div>

                <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" for="first_name">Given Name <span style="color:#ef4444;">*</span></label>
                        <input class="form-control" id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Juan" required autofocus autocomplete="given-name" inputmode="text" pattern="[A-Za-zÀ-ÿ\s\-.]+" title="Use letters, spaces, hyphens, or periods only">
                        <div style="margin-top:.35rem;font-size:.78rem;color:var(--text-muted);">Letters, spaces, hyphens only</div>
                        @error('first_name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" for="last_name">Last Name <span style="color:#ef4444;">*</span></label>
                        <input class="form-control" id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Dela Cruz" required autocomplete="family-name" inputmode="text" pattern="[A-Za-zÀ-ÿ\s\-.]+" title="Use letters, spaces, hyphens, or periods only">
                        <div style="margin-top:.35rem;font-size:.78rem;color:var(--text-muted);">Letters, spaces, hyphens only</div>
                        @error('last_name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;margin-top:1rem;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" for="middle_name">Middle Name</label>
                        <input class="form-control" id="middle_name" type="text" name="middle_name" value="{{ old('middle_name') }}" placeholder="Dela" autocomplete="additional-name" inputmode="text" pattern="[A-Za-zÀ-ÿ\s\-.]+" title="Use letters, spaces, hyphens, or periods only">
                        @error('middle_name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" for="suffix">Suffix</label>
                        <select class="form-control" id="suffix" name="suffix" autocomplete="honorific-suffix">
                            <option value="" {{ old('suffix') === null || old('suffix') === '' ? 'selected' : '' }}>No suffix</option>
                            <option value="Jr." @selected(old('suffix') === 'Jr.')>Jr.</option>
                            <option value="Sr." @selected(old('suffix') === 'Sr.')>Sr.</option>
                            <option value="I" @selected(old('suffix') === 'I')>I</option>
                            <option value="II" @selected(old('suffix') === 'II')>II</option>
                            <option value="III" @selected(old('suffix') === 'III')>III</option>
                            <option value="IV" @selected(old('suffix') === 'IV')>IV</option>
                            <option value="V" @selected(old('suffix') === 'V')>V</option>
                        </select>
                        @error('suffix')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;margin-top:1.5rem;">
                    <button class="btn-auth" type="button" id="next-step-1" style="min-width:140px;display:flex;align-items:center;justify-content:center;position:relative;">
                        <span>Continue</span>
                        <i data-lucide="chevron-right" data-size="18" style="position:absolute;right:1rem;"></i>
                    </button>
                </div>
            </div>

            <div data-step-panel="2" hidden>
                <div style="font-size:1.1rem;font-weight:800;color:var(--text);margin-bottom:.35rem;">Account Setup</div>
                <div style="color:var(--text-muted);margin-bottom:1rem;">Set your account email and security details</div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" for="email">Email Address</label>
                    <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="dejesus.juan@gordoncollege.edu.ph" readonly aria-readonly="true" autocomplete="username">
                    <div style="margin-top:.35rem;font-size:.78rem;color:var(--text-muted);">Auto-generated from your last and first name.</div>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div style="position:relative;">
                        <input class="form-control" id="password" type="password" name="password" placeholder="Password" required autocomplete="new-password" style="padding-right:3rem;" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}" title="Use at least 8 characters with uppercase, lowercase, number, and special character.">
                        <button type="button" id="toggle-password" aria-label="Show password" aria-pressed="false" style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);background:none;border:none;color:#6b7280;cursor:pointer;display:flex;align-items:center;justify-content:center;padding:.25rem;">
                            <i data-lucide="eye" data-size="18"></i>
                        </button>
                    </div>
                    @error('password')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                    <div style="position:relative;">
                        <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" placeholder="Repeat your password" required autocomplete="new-password" style="padding-right:3rem;">
                        <button type="button" id="toggle-password-confirm" aria-label="Show password confirmation" aria-pressed="false" style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);background:none;border:none;color:#6b7280;cursor:pointer;display:flex;align-items:center;justify-content:center;padding:.25rem;">
                            <i data-lucide="eye" data-size="18"></i>
                        </button>
                    </div>
                    @error('password_confirmation')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div style="display:flex;justify-content:space-between;gap:.75rem;margin-top:1.5rem;">
                    <button class="btn-secondary" type="button" data-prev-step="2" style="min-width:120px;display:flex;align-items:center;justify-content:center;position:relative;">
                        <i data-lucide="chevron-left" data-size="18" style="position:absolute;left:1rem;"></i>
                        <span>Back</span>
                    </button>
                    <button class="btn-auth" type="button" id="next-step-2" style="min-width:140px;display:flex;align-items:center;justify-content:center;position:relative;">
                        <span>Continue</span>
                        <i data-lucide="chevron-right" data-size="18" style="position:absolute;right:1rem;"></i>
                    </button>
                </div>
            </div>

            <div data-step-panel="3" hidden>
                <div style="font-size:1.1rem;font-weight:800;color:var(--text);margin-bottom:.35rem;">Review &amp; Agree</div>
                <div style="color:var(--text-muted);margin-bottom:1rem;">Confirm your details before creating the account</div>

                <div style="padding:1rem;border:1px solid var(--border-color);border-radius:12px;background:#fafafa;display:grid;gap:.75rem;">
                    <div><strong>First Name:</strong> <span data-summary="first_name"></span></div>
                    <div><strong>Middle Name:</strong> <span data-summary="middle_name"></span></div>
                    <div><strong>Last Name:</strong> <span data-summary="last_name"></span></div>
                    <div><strong>Suffix:</strong> <span data-summary="suffix"></span></div>
                    <div><strong>Email:</strong> <span data-summary="email"></span></div>
                </div>

                <div style="margin-top:1rem;padding:1rem;border:1px solid var(--border-color);border-radius:12px;background:#fff;">
                    <label for="terms" style="display:flex;gap:.75rem;align-items:flex-start;cursor:pointer;line-height:1.45;">
                        <input id="terms" type="checkbox" name="terms" value="1" required style="margin-top:.2rem;flex:0 0 auto;">
                        <span>
                            I agree to the
                            <button type="button" class="auth-link" data-open-legal-modal="terms" style="border:0;background:none;padding:0;font:inherit;cursor:pointer;vertical-align:baseline;">Terms &amp; Conditions</button>
                            and
                            <button type="button" class="auth-link" data-open-legal-modal="privacy" style="border:0;background:none;padding:0;font:inherit;cursor:pointer;vertical-align:baseline;">Privacy Policy</button>
                            and confirm that my details are accurate.
                        </span>
                    </label>
                    @error('terms')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div style="display:flex;justify-content:space-between;gap:.75rem;margin-top:1.5rem;">
                    <button class="btn-secondary" type="button" data-prev-step="3" style="min-width:120px;display:flex;align-items:center;justify-content:center;position:relative;">
                        <i data-lucide="chevron-left" data-size="18" style="position:absolute;left:1rem;"></i>
                        <span>Back</span>
                    </button>
                    <button class="btn-auth" type="submit" style="min-width:160px;display:flex;align-items:center;justify-content:center;position:relative;">
                        <i data-lucide="arrow-right" data-size="18" style="position:absolute;left:1rem;"></i>
                        <span>Create Account</span>
                    </button>
                </div>
            </div>
        </div>
    </form>

    <div class="auth-footer">
        Already have an account?
        <a class="auth-link" href="{{ route('login') }}">Sign In</a>
    </div>

    @php
        $initialStep = 1;
        if ($errors->has('terms')) {
            $initialStep = 3;
        } elseif ($errors->hasAny(['email', 'password', 'password_confirmation'])) {
            $initialStep = 2;
        } elseif ($errors->hasAny(['first_name', 'middle_name', 'last_name', 'suffix'])) {
            $initialStep = 1;
        }
    @endphp

    <div id="legal-modal-backdrop" hidden style="position:fixed;inset:0;background:rgba(15,23,42,.55);backdrop-filter:blur(4px);z-index:60;padding:1.5rem;align-items:center;justify-content:center;">
        <div role="dialog" aria-modal="true" aria-labelledby="legal-modal-title" style="width:min(100%,760px);max-height:min(100vh - 3rem, 760px);overflow:auto;background:#fff;border-radius:20px;box-shadow:0 24px 60px rgba(15,23,42,.25);border:1px solid rgba(148,163,184,.25);">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;padding:1.25rem 1.35rem;border-bottom:1px solid var(--border-color);position:sticky;top:0;background:#fff;z-index:1;">
                <div>
                    <div id="legal-modal-title" style="font-size:1.15rem;font-weight:800;color:var(--text);">Legal Notice</div>
                    <div id="legal-modal-subtitle" style="color:var(--text-muted);font-size:.92rem;margin-top:.2rem;">Please review before continuing.</div>
                </div>
                <button type="button" id="legal-modal-close" aria-label="Close legal notice" style="width:38px;height:38px;border-radius:999px;border:1px solid var(--border-color);background:#fff;color:var(--text-muted);display:flex;align-items:center;justify-content:center;cursor:pointer;flex:0 0 auto;">
                    <i data-lucide="x" data-size="18"></i>
                </button>
            </div>

            <div id="legal-modal-body" style="padding:1.35rem;display:grid;gap:1rem;color:var(--text);line-height:1.65;">
            </div>
        </div>
    </div>

    <template id="legal-template-terms">
        <section style="display:grid;gap:1rem;">
            <div>
                <h3 style="margin:0 0 .35rem;font-size:1rem;font-weight:800;color:var(--text);">Terms &amp; Conditions</h3>
                <p style="margin:0;color:var(--text-muted);">By creating an account, you agree to use Attendly responsibly and in accordance with institutional policies.</p>
            </div>

            <div style="padding:1rem;border:1px solid var(--border-color);border-radius:14px;background:#fafafa;display:grid;gap:.75rem;">
                <div><strong>Account Use:</strong> Keep your credentials secure and use your account only for authorized academic activities.</div>
                <div><strong>Accuracy:</strong> Provide complete and accurate information. Misrepresentation may affect access.</div>
                <div><strong>Acceptable Conduct:</strong> Do not misuse attendance records, impersonate others, or attempt unauthorized access.</div>
                <div><strong>Compliance:</strong> Follow school rules, applicable policies, and system notifications related to attendance management.</div>
                <div><strong>Updates:</strong> These terms may be updated to reflect operational, security, or policy changes.</div>
            </div>
        </section>
    </template>

    <template id="legal-template-privacy">
        <section style="display:grid;gap:1rem;">
            <div>
                <h3 style="margin:0 0 .35rem;font-size:1rem;font-weight:800;color:var(--text);">Privacy Policy</h3>
                <p style="margin:0;color:var(--text-muted);">Attendly collects and processes limited information needed to operate the attendance tracking system securely and effectively.</p>
            </div>

            <div style="padding:1rem;border:1px solid var(--border-color);border-radius:14px;background:#fafafa;display:grid;gap:.75rem;">
                <div><strong>Data Collected:</strong> Name, institutional email, account credentials, and attendance-related activity.</div>
                <div><strong>Purpose:</strong> To manage authentication, attendance records, reports, and account administration.</div>
                <div><strong>Protection:</strong> We apply reasonable administrative and technical safeguards to protect your data.</div>
                <div><strong>Sharing:</strong> Information is used within the system for authorized school functions and is not sold to third parties.</div>
                <div><strong>Retention:</strong> Data is retained only as long as necessary for legitimate academic and operational purposes.</div>
            </div>
        </section>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const firstNameInput = document.getElementById('first_name');
            const middleNameInput = document.getElementById('middle_name');
            const lastNameInput = document.getElementById('last_name');
            const suffixInput = document.getElementById('suffix');
            const emailInput = document.getElementById('email');
            const pw = document.getElementById('password');
            const pwConfirm = document.getElementById('password_confirmation');
            const toggleA = document.getElementById('toggle-password');
            const toggleB = document.getElementById('toggle-password-confirm');
            const form = document.getElementById('register-form');
            const progressBar = document.getElementById('register-progress');
            const panels = Array.from(document.querySelectorAll('[data-step-panel]'));
            const indicators = Array.from(document.querySelectorAll('[data-step-indicator]'));
            const nextButtons = [document.getElementById('next-step-1'), document.getElementById('next-step-2')].filter(Boolean);
            const prevButtons = Array.from(document.querySelectorAll('[data-prev-step]'));
            const termsInput = document.getElementById('terms');
            const legalBackdrop = document.getElementById('legal-modal-backdrop');
            const legalBody = document.getElementById('legal-modal-body');
            const legalTitle = document.getElementById('legal-modal-title');
            const legalSubtitle = document.getElementById('legal-modal-subtitle');
            const legalClose = document.getElementById('legal-modal-close');
            const legalTriggers = Array.from(document.querySelectorAll('[data-open-legal-modal]'));
            const initialStep = <?php echo $initialStep; ?>;
            const emailDomain = 'gordoncollege.edu.ph';
            let currentStep = 1;

            function normalizePart(value) {
                return value
                    .toString()
                    .trim()
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9]+/g, '')
                    .replace(/^\.+|\.+$/g, '');
            }

            function updateEmailPreview() {
                if (!emailInput) return;

                const firstName = normalizePart(firstNameInput?.value || '');
                const lastName = normalizePart(lastNameInput?.value || '');
                const emailLocal = [lastName, firstName].filter(Boolean).join('.');

                emailInput.value = emailLocal ? `${emailLocal}@${emailDomain}` : '';
                updateSummary();
            }

            function sanitizeNameInput(input) {
                if (!input) return;

                const cleaned = input.value
                    .replace(/\d+/g, '')
                    .replace(/[^A-Za-zÀ-ÿ\s\-.]/g, '');

                if (cleaned !== input.value) {
                    input.value = cleaned;
                }
            }

            function updateSummary() {
                const summaryMap = {
                    first_name: firstNameInput?.value || '',
                    middle_name: middleNameInput?.value || '',
                    last_name: lastNameInput?.value || '',
                    suffix: suffixInput?.value || '',
                    email: emailInput?.value || '',
                };

                Object.entries(summaryMap).forEach(([key, value]) => {
                    const el = document.querySelector(`[data-summary="${key}"]`);
                    if (el) el.textContent = value || '—';
                });
            }

            function updateControls(show) {
                const type = show ? 'text' : 'password';
                if (pw) pw.type = type;
                if (pwConfirm) pwConfirm.type = type;

                const icon = show ? '<i data-lucide="eye-off" data-size="18"></i>' : '<i data-lucide="eye" data-size="18"></i>';

                if (toggleA) {
                    toggleA.setAttribute('aria-pressed', String(show));
                    toggleA.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
                    toggleA.innerHTML = icon;
                }
                if (toggleB) {
                    toggleB.setAttribute('aria-pressed', String(show));
                    toggleB.setAttribute('aria-label', show ? 'Hide password confirmation' : 'Show password confirmation');
                    toggleB.innerHTML = icon;
                }

                if (window.lucide && typeof window.lucide.createIcons === 'function') {
                    window.lucide.createIcons();
                }
            }

            function currentShowing() {
                return (pw && pw.type === 'text') || (pwConfirm && pwConfirm.type === 'text');
            }

            function setRequired(step) {
                [firstNameInput, lastNameInput, middleNameInput, suffixInput, emailInput, pw, pwConfirm].forEach((input) => {
                    if (input) input.required = false;
                });

                if (step === 1) {
                    if (firstNameInput) firstNameInput.required = true;
                    if (lastNameInput) lastNameInput.required = true;
                }

                if (step === 2) {
                    if (pw) pw.required = true;
                    if (pwConfirm) pwConfirm.required = true;
                }
            }

            function setStep(step) {
                currentStep = step;

                panels.forEach((panel) => {
                    panel.hidden = Number(panel.getAttribute('data-step-panel')) !== step;
                });

                indicators.forEach((indicator) => {
                    const index = Number(indicator.getAttribute('data-step-indicator'));
                    const circle = indicator.querySelector('div');
                    const label = indicator.querySelector('div:last-child');
                    const active = index === step;

                    if (circle) {
                        circle.style.background = active ? 'linear-gradient(180deg,#22c55e,#16a34a)' : '#efefef';
                        circle.style.color = active ? '#fff' : '#9ca3af';
                        circle.style.boxShadow = active ? '0 8px 18px rgba(34,197,94,.22)' : 'none';
                    }

                    if (label) {
                        label.style.color = active ? '#2b2b2b' : '#a3a3a3';
                    }
                });

                if (progressBar) {
                    progressBar.style.width = `${(step / 3) * 100}%`;
                }

                setRequired(step);
                updateSummary();

                const focusTarget = step === 1 ? firstNameInput : step === 2 ? pw : null;
                if (focusTarget && typeof focusTarget.focus === 'function') {
                    window.requestAnimationFrame(() => {
                        focusTarget.focus({ preventScroll: true });
                    });
                }
            }

            function validateStep(step) {
                const requiredInputs = [];

                if (step === 1) {
                    if (firstNameInput) requiredInputs.push(firstNameInput);
                    if (lastNameInput) requiredInputs.push(lastNameInput);
                }

                if (step === 2) {
                    if (pw) requiredInputs.push(pw);
                    if (pwConfirm) requiredInputs.push(pwConfirm);

                    const passwordValue = pw ? pw.value : '';
                    const passwordValid = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/.test(passwordValue);

                    if (pw) {
                        pw.setCustomValidity(passwordValid ? '' : 'Password must include uppercase, lowercase, number, and special character.');
                    }

                    if (passwordValid && pwConfirm) {
                        pwConfirm.setCustomValidity(pwConfirm.value === passwordValue ? '' : 'Passwords do not match.');
                    }

                    if (!passwordValid) {
                        if (window.Toast && typeof window.Toast.error === 'function') {
                            window.Toast.error('Password must include uppercase, lowercase, number, and special character.', 'Weak Password');
                        }
                        return false;
                    }

                    if (pwConfirm && pwConfirm.value !== passwordValue) {
                        if (window.Toast && typeof window.Toast.error === 'function') {
                            window.Toast.error('Password confirmation does not match.', 'Invalid Details');
                        }
                        return false;
                    }
                }

                if (step === 3 && termsInput && !termsInput.checked) {
                    termsInput.setCustomValidity('Please accept the Terms & Conditions.');
                    termsInput.reportValidity();

                    if (window.Toast && typeof window.Toast.error === 'function') {
                        window.Toast.error('Please accept the Terms & Conditions before creating your account.');
                    }

                    return false;
                }

                if (step === 3 && termsInput && termsInput.checked) {
                    termsInput.setCustomValidity('');
                }

                return requiredInputs.every((input) => input.checkValidity());
            }

            function syncPasswordValidity() {
                if (!pw || !pwConfirm) return;

                const passwordValue = pw.value;
                const passwordValid = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/.test(passwordValue);

                pw.setCustomValidity(passwordValid ? '' : 'Password must include uppercase, lowercase, number, and special character.');
                pwConfirm.setCustomValidity(pwConfirm.value === passwordValue ? '' : 'Passwords do not match.');
            }

            function showInvalidStepToast(step) {
                if (window.Toast && typeof window.Toast.error === 'function') {
                    const message = step === 1
                        ? 'Please complete the personal information correctly before continuing.'
                        : 'Please complete the account setup correctly before continuing.';

                    window.Toast.error(message, 'Invalid Details');
                }
            }

            function openLegalModal(type) {
                const template = document.getElementById(`legal-template-${type}`);
                if (!template || !legalBackdrop || !legalBody || !legalTitle || !legalSubtitle) return;

                legalTitle.textContent = type === 'privacy' ? 'Privacy Policy' : 'Terms & Conditions';
                legalSubtitle.textContent = type === 'privacy'
                    ? 'How your information is collected and used.'
                    : 'Rules and expectations for using the system.';
                legalBody.innerHTML = template.innerHTML;
                legalBackdrop.hidden = false;
                legalBackdrop.style.display = 'flex';
                document.body.style.overflow = 'hidden';

                if (window.lucide && typeof window.lucide.createIcons === 'function') {
                    window.lucide.createIcons();
                }
            }

            function closeLegalModal() {
                if (!legalBackdrop) return;

                legalBackdrop.hidden = true;
                legalBackdrop.style.display = '';
                document.body.style.overflow = '';
            }

            function advanceStep() {
                if (!validateStep(currentStep)) {
                    showInvalidStepToast(currentStep);

                    const invalidInput = (currentStep === 1
                        ? [firstNameInput, lastNameInput, middleNameInput]
                        : [emailInput, pw, pwConfirm]
                    ).find((input) => input && !input.checkValidity());

                    if (invalidInput && typeof invalidInput.focus === 'function') {
                        invalidInput.focus();
                        if (typeof invalidInput.reportValidity === 'function') {
                            invalidInput.reportValidity();
                        }
                    }

                    return;
                }

                setStep(Math.min(currentStep + 1, 3));
            }

            [firstNameInput, middleNameInput, lastNameInput, suffixInput].forEach((input) => {
                if (input) input.addEventListener('input', updateEmailPreview);
            });

            [firstNameInput, middleNameInput, lastNameInput].forEach((input) => {
                if (!input) return;

                input.addEventListener('keydown', (event) => {
                    if (event.key.length === 1 && /\d/.test(event.key)) {
                        event.preventDefault();
                    }
                });

                input.addEventListener('input', () => {
                    sanitizeNameInput(input);
                    updateEmailPreview();
                });

                input.addEventListener('paste', (event) => {
                    const pastedText = event.clipboardData?.getData('text') || '';
                    if (/\d/.test(pastedText)) {
                        event.preventDefault();
                        const cleaned = pastedText.replace(/\d+/g, '').replace(/[^A-Za-zÀ-ÿ\s\-.]/g, '');
                        const selectionStart = input.selectionStart ?? input.value.length;
                        const selectionEnd = input.selectionEnd ?? input.value.length;
                        input.setRangeText(cleaned, selectionStart, selectionEnd, 'end');
                        sanitizeNameInput(input);
                        updateEmailPreview();
                    }
                });
            });

            [pw, pwConfirm].forEach((input) => {
                if (!input) return;

                input.addEventListener('input', () => {
                    syncPasswordValidity();
                });
            });

            if (termsInput) {
                termsInput.addEventListener('change', () => {
                    termsInput.setCustomValidity(termsInput.checked ? '' : 'Please accept the Terms & Conditions.');
                });
            }

            nextButtons.forEach((button) => {
                button.addEventListener('click', advanceStep);
            });

            if (form) {
                form.addEventListener('keydown', (event) => {
                    if (event.key !== 'Enter') return;
                    if (currentStep >= 3) return;
                    if (event.target && event.target.tagName === 'TEXTAREA') return;

                    event.preventDefault();
                    advanceStep();
                });
            }

            prevButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    setStep(Math.max(currentStep - 1, 1));
                });
            });

            if (toggleA) toggleA.addEventListener('click', () => updateControls(!currentShowing()));
            if (toggleB) toggleB.addEventListener('click', () => updateControls(!currentShowing()));

            legalTriggers.forEach((trigger) => {
                trigger.addEventListener('click', () => {
                    openLegalModal(trigger.getAttribute('data-open-legal-modal') || 'terms');
                });
            });

            if (legalClose) legalClose.addEventListener('click', closeLegalModal);
            if (legalBackdrop) {
                legalBackdrop.addEventListener('click', (event) => {
                    if (event.target === legalBackdrop) closeLegalModal();
                });
            }

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && legalBackdrop && !legalBackdrop.hidden) {
                    closeLegalModal();
                }
            });

            updateEmailPreview();
            setStep(initialStep);
        });
    </script>
</x-guest-layout>
