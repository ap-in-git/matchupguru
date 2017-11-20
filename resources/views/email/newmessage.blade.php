@component('mail::message')
Hi,
Mathhew,
You have a new message from {{$message->email}}

Subject: {{$message->subject}}
<br>
Message:
<br>

{{$message->message}}

<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
