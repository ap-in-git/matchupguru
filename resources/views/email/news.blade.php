@component('mail::message')
# Introduction
News from {{ config('app.name') }}

<div style="-webkit-box-shadow: 3px 3px 5px 6px #ccc; -moz-box-shadow:    3px 3px 5px 6px #ccc; box-shadow:         3px 3px 5px 6px #ccc; padding: 20px; margin: 20px;">
    {!! $message !!}

</div>

 Click the button  to unsubscribe
@component('mail::button', ['url' => route("news.unsubscribe",$link)])
    Unsubscribe
@endcomponent
Thanks,<br>

{{ config('app.name') }}
@endcomponent
