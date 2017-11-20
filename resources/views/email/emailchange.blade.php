@component('mail::message')

Hello {{$user->new_email}},
You have requested for the change in email address.
Please Click the link below to make this email address as a new one
@component('mail::button', ['url' => route("user.email.change",[$user->old_email,$user->verification_code])])
Change
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
