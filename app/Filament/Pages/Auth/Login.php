<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as FilamentLogin;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;

class Login extends FilamentLogin
{
    public function getFooter(): string
    {
        $resetPasswordUrl = route('filament.admin.password-reset.request');
        
        return Blade::render('
            <div class="flex justify-center mt-4">
                <a href="' . $resetPasswordUrl . '" class="text-sm text-primary-600 hover:text-primary-500">
                    {{ __("Mot de passe oublié?") }}
                </a>
            </div>
        ');
    }
}
