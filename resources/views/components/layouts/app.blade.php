<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white antialiased dark:bg-gradient-to-b dark:from-neutral-950 dark:to-neutral-900">
    <div
        class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-[6fr_4fr] lg:px-0">
        <!-- Fondo con imagen -->
        <div class="relative hidden h-full flex-col p-10 text-white lg:flex dark:border-e dark:border-neutral-800"
            style="background-image: url('{{ asset('assets/images/morales-bg.jpg') }}'); background-size: cover; background-position: left;">
            <div class="absolute inset-0 bg-black/20"></div>

            <!-- Logo e Inicio con animación de entrada -->
            <a href="{{ route('login') }}"
                class="group relative z-20 flex items-center font-semibold text-white text-lg lg:text-2xl transition-all duration-300 ease-in-out hover:brightness-110 animate-logo-entrance">

                <!-- Logo sin fondo -->
                <span class="flex h-16 w-16 items-center justify-center">
                        <img src="/assets/images/morales.png" alt="Logo" class="h-14 w-14 object-contain">
                </span>

                <!-- Nombre con subrayado animado y brillo -->
                <span
                    class="ms-3 relative font-[Playfair_Display] text-xl lg:text-3xl text-white tracking-[0.04em] transition-all duration-300 group-hover:brightness-125">
                    {{ config('app.name', 'Laravel') }}
                    <span
                        class="pointer-events-none absolute left-0 -bottom-1 h-[2px] w-0 bg-gradient-to-r from-[#1f80e0] to-[#63c3ff] rounded-full transition-all duration-700 ease-in-out group-hover:w-full group-hover:shadow-[0_0_12px_2px_rgba(31,128,224,0.6)]"></span>
                </span>

            </a>

            <!-- Frase inspiradora -->
            @php
                use App\Support\InspiringEs;
                use Illuminate\Support\Str;

                $fraseCompleta = InspiringEs::quotes()->random();
                [$message, $author] = Str::of($fraseCompleta)->explode(' - ');
            @endphp

            <div class="relative z-20 mt-auto">
                <blockquote class="space-y-2">
                    <h2 class="text-lg font-semibold leading-tight">&ldquo;{{ trim($message) }}&rdquo;</h2>
                    <footer>
                        <p class="text-sm font-light text-white">— {{ trim($author) }}</p>
                    </footer>
                </blockquote>
            </div>

        </div>

        <!-- Panel derecho -->
        <div class="w-full lg:p-8">
            <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                <!-- Logo solo en móviles -->
                <a href="{{ route('login') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden">
                    <span class="inline-flex h-20 w-20 items-center justify-center ">
                        <img src="{{ asset('assets/images/morales.png') }}" alt="Logo"
                            class="mx-auto h-20 w-20 object-contain">
                    </span>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>

                <!-- Contenido dinámico (formulario u otro) -->
                {{ $slot }}

            </div>
        </div>
    </div>

    <!-- NProgress JS -->

    @livewireScripts
    @filamentScripts
    @fluxScripts

</body>

</html>
