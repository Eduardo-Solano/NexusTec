<x-mail::message>
# Invitación a unirse al equipo: {{ $teamName }}

¡Hola **{{ $userName }}**!

Has sido invitado a formar parte del equipo "**{{ $teamName }}**" en el evento "**{{ $eventName }}**".

Si aceptas, te unirás inmediatamente al equipo.

<x-mail::button :url="$acceptUrl" color="success">
Aceptar Invitación
</x-mail::button>

Si no deseas unirte, puedes rechazar la invitación:

<x-mail::button :url="$rejectUrl" color="error">
Rechazar Invitación
</x-mail::button>

¡Gracias!<br>
{{ config('app.name') }}
</x-mail::message>
