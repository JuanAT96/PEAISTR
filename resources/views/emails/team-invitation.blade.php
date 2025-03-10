@component('mail::message')
    {{ __('Has sido invitado a unirte al equipo :team!', ['team' => $invitation->team->name]) }}

    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::registration()))
        {{ __('Si no tienes una cuenta, puedes crear una haciendo clic en el botón de abajo. Después de crear una cuenta, podrás hacer clic en el botón de aceptación de la invitación en este correo para unirte al equipo:') }}

        @component('mail::button', ['url' => route('register')])
            {{ __('Crear cuenta') }}
        @endcomponent

        {{ __('Si ya tienes una cuenta, puedes aceptar esta invitación haciendo clic en el botón de abajo:') }}
    @else
        {{ __('Puedes aceptar esta invitación haciendo clic en el botón de abajo:') }}
    @endif

    @component('mail::button', ['url' => $acceptUrl])
        {{ __('Aceptar invitación') }}
    @endcomponent

    {{ __('Si no esperabas recibir una invitación para este equipo, puedes ignorar este correo.') }}
@endcomponent
