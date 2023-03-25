<x-mail::message>
# Introduction

The body of your message.

<x-mail::button :url="''">
Button Text
</x-mail::button>
{{$user->verification_code}}
Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
