<x-filament::page>
    <x-filament-panels::form wire:submit="submit">
        {{ $this->form }}
        
        <div class="mt-4 flex justify-end">
            <x-filament::button type="submit" color="primary">
                Mettre à jour le mot de passe
            </x-filament::button>
        </div>
    </x-filament-panels::form>
</x-filament::page>
