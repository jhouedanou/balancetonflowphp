<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoteResource\Pages;
use App\Filament\Resources\VoteResource\RelationManagers;
use App\Models\Vote;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VoteResource extends Resource
{
    protected static ?string $model = Vote::class;

    protected static ?string $navigationIcon = 'heroicon-o-hand-thumb-up';
    protected static ?string $navigationLabel = 'Votes';
    protected static ?string $modelLabel = 'Vote';
    protected static ?string $pluralModelLabel = 'Votes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Utilisateur')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('contestant_id')
                    ->label('Candidat')
                    ->relationship('contestant', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('vote_type')
                    ->label('Type de vote')
                    ->options([
                        'live' => 'Vote en direct',
                        'post' => 'Vote après diffusion'
                    ])
                    ->required(),
                Forms\Components\TextInput::make('ip_address')
                    ->label('Adresse IP')
                    ->maxLength(45),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contestant.name')
                    ->label('Candidat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vote_type')
                    ->label('Type de vote')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'live' ? 'Vote en direct' : 'Vote après diffusion')
                    ->color(fn (string $state): string => $state === 'live' ? 'success' : 'info'),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('Adresse IP')
                    ->searchable()
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
                        'post' => 'Vote après diffusion'
                    ]),
                Tables\Filters\SelectFilter::make('contestant_id')
                    ->label('Candidat')
                    ->relationship('contestant', 'name'),
                Tables\Filters\Filter::make('created_at')
                    ->label('Date de vote')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Depuis'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Jusqu\'à'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVotes::route('/'),
            'create' => Pages\CreateVote::route('/create'),
            'edit' => Pages\EditVote::route('/{record}/edit'),
        ];
    }
}
