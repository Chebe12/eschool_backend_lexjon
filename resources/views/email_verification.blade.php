@component('mail::message')
# OTP for Email Verification

Your OTP for email verification is: {{ $otp }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
