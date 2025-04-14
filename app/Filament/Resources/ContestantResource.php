<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContestantResource\Pages;
use App\Filament\Resources\ContestantResource\RelationManagers;
use App\Models\Contestant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContestantResource extends Resource
{
    protected static ?string $model = Contestant::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Candidats';
    protected static ?string $modelLabel = 'Candidat';
    protected static ?string $pluralModelLabel = 'Candidats';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('bio')
                    ->label('Description')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('profile_photo')
                    ->label('Photo')
                    ->image()
                    ->disk('public')
                    ->directory('contestants')
                    ->visibility('public')
                    ->imageEditor()
                    ->maxSize(2048)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif']),
                Forms\Components\Toggle::make('is_finalist')
                    ->label('Finaliste')
                    ->helperText('Cochez si le candidat est qualifié pour la finale')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label('Utilisateur associé')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo')
                    ->label('Photo')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_finalist')
                    ->label('Finaliste')
                    ->boolean(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('videos_count')
                    ->label('Vidéos')
                    ->counts('videos')
                    ->sortable(),
                Tables\Columns\TextColumn::make('votes_count')
                    ->label('Votes')
                    ->counts('votes')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_finalist')
                    ->label('Finalistes')
                    ->placeholder('Tous les candidats')
                    ->trueLabel('Finalistes uniquement')
                    ->falseLabel('Non finalistes uniquement')
                    ->queries(
                        true: fn (Builder $query) => $query->where('is_finalist', true),
                        false: fn (Builder $query) => $query->where('is_finalist', false),
                        blank: fn (Builder $query) => $query
                    )
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
            RelationManagers\VideosRelationManager::class,
            RelationManagers\VotesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContestants::route('/'),
            'create' => Pages\CreateContestant::route('/create'),
            'edit' => Pages\EditContestant::route('/{record}/edit'),
        ];
    }
}
