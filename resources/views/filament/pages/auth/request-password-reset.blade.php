<x-filament-panels::page.simple>
    <x-filament-panels::form wire:submit="request">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    <x-slot name="footer">
        <div class="flex justify-center mt-4">
            <a href="{{ route('filament.admin.auth.login') }}" class="text-sm text-primary-600 hover:text-primary-500">
                {{ __("Retour à la connexion") }}
            </a>
        </div>
    </x-slot>
</x-filament-panels::page.simple>
