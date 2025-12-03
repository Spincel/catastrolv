@php
    use App\Models\Setting;
    $appLogo = Setting::get('institution_logo', 'img/logo.png'); // Fallback
@endphp

<a href="/">
    <img src="{{ asset('storage/' . $appLogo) }}" alt="Logo de la Aplicación" {{ $attributes }}>
</a>