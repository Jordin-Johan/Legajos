<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Iniciar sesión')" :description="__('Ingrese sus datos a continuación para iniciar sesión')" />

    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="login" class="flex flex-col gap-6">
        <flux:input
            wire:model="email"
            :label="__('Correo electrónico')"
            type="email"
            required
            autofocus
            autocomplete="email"
            placeholder="correo@ejemplo.com"
        />

        <div class="relative">
            <flux:input
                wire:model="password"
                :label="__('Contraseña')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Contraseña')"
                viewable
            />

            @if (Route::has('password.request'))
                <flux:link class="absolute end-0 top-0 text-sm" :href="route('password.request')" wire:navigate>
                    {{ __('¿Olvidaste tu contraseña?') }}
                </flux:link>
            @endif
        </div>

        <flux:checkbox wire:model="remember" :label="__('Recordarme')" />

        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full">
                {{ __('Iniciar sesión') }}
            </flux:button>
        </div>
    </form>

    @if (Route::has('register'))
        <div class="text-center text-sm text-zinc-600 dark:text-zinc-400">
            {{ __('¿No tienes una cuenta?') }}
            <flux:link :href="route('register')" wire:navigate>
                {{ __('Registrarse') }}
            </flux:link>
        </div>
    @endif
    
</div>
