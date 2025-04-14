<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LiveStreamResource\Pages;
use App\Filament\Resources\LiveStreamResource\RelationManagers;
use App\Models\LiveStream;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LiveStreamResource extends Resource
{
    protected static ?string $model = LiveStream::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationLabel = 'Événements Live';
    protected static ?string $modelLabel = 'Événement Live';
    protected static ?string $pluralModelLabel = 'Événements Live';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Titre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('embed_url')
                    ->label('URL d\'intégration')
                    ->helperText('URL YouTube, Twitch ou autre plateforme de streaming')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('thumbnail')
                    ->label('Miniature')
                    ->helperText('Image de miniature pour l\'événement (formats JPEG ou PNG)')
                    ->image()
                    ->disk('public')
                    ->directory('livestream-thumbnails')
                    ->visibility('public')
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1280')
                    ->imageResizeTargetHeight('720')
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ->maxSize(5120) // 5 Mo max
                    ->imageEditor(),
                Forms\Components\DateTimePicker::make('start_time')
                    ->label('Date et heure de début')
                    ->required(),
                Forms\Components\DateTimePicker::make('end_time')
                    ->label('Date et heure de fin')
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Actif')
                    ->helperText('Activer ou désactiver cet événement')
                    ->required(),
                Forms\Components\Select::make('phase')
                    ->label('Phase')
                    ->options([
                        'semi-final' => 'Demi-finale',
                        'final' => 'Finale'
                    ])
                    ->required(),
                Forms\Components\FileUpload::make('literature_file')
                    ->label('Fichier de littérature')
                    ->helperText('Téléchargez le fichier de littérature (PDF)')
                    ->disk('public')
                    ->directory('literature-files')
                    ->visibility('public')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(10240), // 10 Mo max
                Forms\Components\Select::make('contestants')
                    ->label('Candidats')
                    ->relationship('contestants', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phase')
                    ->label('Phase')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'semi-final' ? 'Demi-finale' : 'Finale')
                    ->color(fn (string $state): string => $state === 'semi-final' ? 'warning' : 'success'),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Début')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('Fin')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('phase')
                    ->label('Phase')
                    ->options([
                        'semi-final' => 'Demi-finale',
                        'final' => 'Finale',
                    ]),
                Tables\Filters\Filter::make('is_active')
                    ->label('Actifs uniquement')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true))
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
            'index' => Pages\ListLiveStreams::route('/'),
            'create' => Pages\CreateLiveStream::route('/create'),
            'edit' => Pages\EditLiveStream::route('/{record}/edit'),
        ];
    }
}
