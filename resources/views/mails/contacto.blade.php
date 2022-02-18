@component('mail::message')
# Formulario de contacto

## Nombre

{{$datos['nombre']}}

## Correo

__{{$email}}__

***
## Mensaje

{{$datos['mensaje']}}

@endcomponent