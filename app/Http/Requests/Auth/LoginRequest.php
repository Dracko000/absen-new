<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'], // Changed from 'email' to 'login' to accept email, nis, or nip_nuptk
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $loginField = $this->getLoginField();
        $credentials = $this->only('password');
        $credentials[$loginField['field']] = $this->input('login');

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Determine which field to use for authentication based on the login value
     */
    private function getLoginField(): array
    {
        $loginValue = $this->input('login');

        // Check if it's an email format
        if (filter_var($loginValue, FILTER_VALIDATE_EMAIL)) {
            // Check if there's a user with this email who is a Superadmin
            $user = \App\Models\User::where('email', $loginValue)->first();
            if ($user && $user->hasRole('Superadmin')) {
                return ['field' => 'email'];
            }
        }

        // Check if it's a valid NIS (numeric value, typically length is 8-12 digits)
        if (is_numeric($loginValue) && strlen($loginValue) >= 8) {
            $user = \App\Models\User::where('nis', $loginValue)->first();
            if ($user && $user->hasRole('User')) { // User role means student
                return ['field' => 'nis'];
            }

            // Also check if it could be a NIP/NUPTK for teachers
            $teacher = \App\Models\User::where('nip_nuptk', $loginValue)->first();
            if ($teacher && $teacher->hasRole('Admin')) { // Admin role means teacher
                return ['field' => 'nip_nuptk'];
            }
        }

        // Default to email field for superadmins
        return ['field' => 'email'];
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login')).'|'.$this->ip());
    }
}
