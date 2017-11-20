@component('mail::message')
# Introduction
Please click the button below for the verification

@component('mail::button', ['url' => route("send.email.verify",$user->token)])
Verify
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
