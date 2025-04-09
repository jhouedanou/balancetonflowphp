<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoResource\Pages;
use App\Filament\Resources\VideoResource\RelationManagers;
use App\Models\Video;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static ?string $navigationIcon = 'heroicon-o-film';
    protected static ?string $navigationLabel = 'Vidéos';
    protected static ?string $modelLabel = 'Vidéo';
    protected static ?string $pluralModelLabel = 'Vidéos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('contestant_id')
                    ->label('Candidat')
                    ->relationship('contestant', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->label('Titre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('url')
                    ->label('URL de la vidéo')
                    ->helperText('URL YouTube, TikTok, etc.')
                    ->required()
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('thumbnail')
                    ->label('Miniature')
                    ->helperText('URL de l\'image de miniature')
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('duration')
                    ->label('Durée (secondes)')
                    ->numeric()
                    ->nullable(),
                Forms\Components\Select::make('status')
                    ->label('Statut')
                    ->options([
                        'draft' => 'Brouillon',
                        'published' => 'Publiée',
                        'archived' => 'Archivée'
                    ])
                    ->default('draft')
                    ->required(),
                Forms\Components\DateTimePicker::make('publish_date')
                    ->label('Date de publication')
                    ->nullable(),
                Forms\Components\Toggle::make('is_featured')
                    ->label('Mise en avant')
                    ->helperText('Mettre cette vidéo en avant sur la page d\'accueil')
                    ->default(false)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('contestant.name')
                    ->label('Candidat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->url(fn (string $state): string => $state)
                    ->openUrlInNewTab()
                    ->limit(30),
                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'draft' => 'Brouillon',
                        'published' => 'Publiée',
                        'archived' => 'Archivée',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'archived' => 'danger',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('publish_date')
                    ->label('Date de publication')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Mise en avant')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créée le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'draft' => 'Brouillon',
                        'published' => 'Publiée',
                        'archived' => 'Archivée',
                    ]),
                Tables\Filters\SelectFilter::make('contestant_id')
                    ->label('Candidat')
                    ->relationship('contestant', 'name'),
                Tables\Filters\Filter::make('is_featured')
                    ->label('Mises en avant uniquement')
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true)),
                Tables\Filters\Filter::make('publish_date')
                    ->label('Date de publication')
                    ->form([
                        Forms\Components\DatePicker::make('published_from')
                            ->label('Depuis'),
                        Forms\Components\DatePicker::make('published_until')
                            ->label('Jusqu\'à'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['published_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('publish_date', '>=', $date),
                            )
                            ->when(
                                $data['published_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('publish_date', '<=', $date),
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
            'index' => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'edit' => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
}
