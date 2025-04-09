<?php

namespace App\Filament\Resources\ContestantResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VotesRelationManager extends RelationManager
{
    protected static string $relationship = 'votes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Utilisateur')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('vote_type')
                    ->label('Type de vote')
                    ->options([
                        'live' => 'Vote en direct',
                        'post' => 'Vote après diffusion',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('ip_address')
                    ->label('Adresse IP')
                    ->maxLength(45),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vote_type')
                    ->label('Type de vote')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'live' ? 'Vote en direct' : 'Vote après diffusion')
                    ->color(fn (string $state): string => $state === 'live' ? 'success' : 'info'),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('Adresse IP')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date du vote')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('vote_type')
                    ->label('Type de vote')
                    ->options([
                        'live' => 'Vote en direct',
                        'post' => 'Vote après diffusion',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
