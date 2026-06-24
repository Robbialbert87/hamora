<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nip' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $nip = $this->input('nip');
        $password = $this->input('password');
        $sijagaUrl = config('services.sijaga.url');

        // Try API login to Sijaga first
        if ($sijagaUrl) {
            try {
                $response = Http::timeout(10)
                    ->withoutVerifying()
                    ->post($sijagaUrl . '/api/login', [
                        'nip' => $nip,
                        'password' => $password,
                    ]);

                if ($response->ok()) {
                    $data = $response->json();
                    $sijagaUser = $data['user'];

                    // Create or update local user from Sijaga data
                    $user = User::where('nip', $sijagaUser['nip'])->first();

                    if ($user) {
                        $user->update([
                            'name' => $sijagaUser['name'],
                        ]);
                    } else {
                        $user = User::create([
                            'name' => $sijagaUser['name'],
                            'password' => $password,
                            'nip' => $sijagaUser['nip'],
                            'is_active' => true,
                        ]);
                    }

                    // Sync roles from Sijaga
                    $sijagaRoles = $sijagaUser['roles'] ?? [];
                    $roleMap = [
                        'super_admin' => 'Super Admin',
                        'admin' => 'Admin',
                        'user' => 'User',
                        'kepala_ruangan' => 'User',
                        'staff' => 'User',
                    ];

                    $hamoraRoles = [];
                    foreach ($sijagaRoles as $role) {
                        $mapped = $roleMap[$role] ?? 'User';
                        if (!in_array($mapped, $hamoraRoles)) {
                            $hamoraRoles[] = $mapped;
                        }
                    }

                    if (empty($hamoraRoles)) {
                        $hamoraRoles = ['User'];
                    }

                    $user->syncRoles($hamoraRoles);

                    // Login locally
                    Auth::login($user, $this->boolean('remember'));
                    RateLimiter::clear($this->throttleKey());

                    return;
                }

                // API returned 401 — credentials wrong
                if ($response->status() === 401) {
                    RateLimiter::hit($this->throttleKey());
                    throw ValidationException::withMessages([
                        'nip' => 'NIP atau password tidak cocok.',
                    ]);
                }
            } catch (ValidationException $e) {
                throw $e;
            } catch (\Exception $e) {
                // Network error — fall through to local auth
            }
        }

        // Fallback: local authentication
        if (!Auth::attempt(['nip' => $nip, 'password' => $password], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'nip' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'nip' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate($this->string('nip').'|'.$this->ip());
    }
}
