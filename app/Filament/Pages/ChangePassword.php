<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ChangePassword extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';
    protected static ?string $navigationLabel = 'Changer mot de passe';
    protected static ?string $title = 'Changer votre mot de passe';
    protected static ?string $slug = 'change-password';
    protected static ?int $navigationSort = 90;
    
    // Définir la vue à utiliser
    protected static string $view = 'filament.pages.change-password';
    
    // Variables pour le formulaire
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';
    
    // Configuration du formulaire
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('current_password')
                    ->label('Mot de passe actuel')
                    ->password()
                    ->required(),
                Forms\Components\TextInput::make('new_password')
                    ->label('Nouveau mot de passe')
                    ->password()
                    ->required()
                    ->minLength(8)
                    ->same('new_password_confirmation'),
                Forms\Components\TextInput::make('new_password_confirmation')
                    ->label('Confirmer le nouveau mot de passe')
                    ->password()
                    ->required(),
            ]);
    }
    
    // Vérification du mot de passe actuel
    protected function verifyCurrentPassword(): bool
    {
        return Hash::check($this->current_password, Auth::user()->password);
    }
    
    // Actions de la page
    protected function getFormActions(): array
    {
        return [
            Action::make('change_password')
                ->label('Mettre à jour le mot de passe')
                ->submit('change_password'),
        ];
    }
    
    // Action de sauvegarde
    public function submit()
    {
        $this->form->validate();
        
        // Vérifier le mot de passe actuel
        if (!$this->verifyCurrentPassword()) {
            throw ValidationException::withMessages([
                'current_password' => 'Le mot de passe actuel est incorrect.',
            ]);
        }
        
        // Mettre à jour le mot de passe
        $user = Auth::user();
        $user->password = Hash::make($this->new_password);
        $user->save();
        
        // Notification de succès
        Notification::make()
            ->title('Mot de passe mis à jour')
            ->body('Votre mot de passe a été modifié avec succès.')
            ->success()
            ->send();
        
        // Réinitialiser le formulaire
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        
        // Rediriger vers le tableau de bord
        return redirect()->to('/admin');
    }
}
