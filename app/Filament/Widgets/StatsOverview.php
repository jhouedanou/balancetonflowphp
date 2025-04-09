<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Contestant;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $stats = [
            Stat::make('Utilisateurs', User::count())
                ->description('Nombre total d\'utilisateurs')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),
        ];
        
        // Vérifier si les tables existent avant de les compter
        if (Schema::hasTable('contestants')) {
            $stats[] = Stat::make('Candidats', DB::table('contestants')->count())
                ->description('Nombre total de candidats')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success');
        }
        
        if (Schema::hasTable('videos')) {
            $stats[] = Stat::make('Vidéos', DB::table('videos')->count())
                ->description('Nombre total de vidéos')
                ->descriptionIcon('heroicon-o-video-camera')
                ->color('warning');
        }
        
        if (Schema::hasTable('votes')) {
            $stats[] = Stat::make('Votes', DB::table('votes')->count())
                ->description('Nombre total de votes')
                ->descriptionIcon('heroicon-o-hand-thumb-up')
                ->color('danger');
        }
        return $stats;
    }
}
