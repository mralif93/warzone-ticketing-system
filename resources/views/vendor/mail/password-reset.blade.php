@component('mail::message')
# Password Reset Request

Hello {{ $user->name }},

We received a request to reset your password for your Warzone World Championship account.

If you requested this password reset, click the button below to create a new password:

@component('mail::button', ['url' => $resetUrl, 'color' => 'primary'])
Reset Password
@endcomponent

**This link will expire in {{ config('auth.passwords.users.expire') }} minutes.**

If you didn't request a password reset, you can safely ignore this email. Your password will remain unchanged.

If you're having trouble clicking the button, copy and paste the URL below into your web browser:
{{ $resetUrl }}

Thanks,<br>
{{ config('app.name') }} Team

---

**Security Notice:** If you didn't request this password reset, please contact our support team immediately at {{ config('mail.from.address') }}.
@endcomponent
