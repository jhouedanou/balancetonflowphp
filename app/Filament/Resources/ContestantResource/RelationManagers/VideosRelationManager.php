<?php

namespace App\Filament\Resources\ContestantResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VideosRelationManager extends RelationManager
{
    protected static string $relationship = 'videos';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
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
                Forms\Components\TextInput::make('url')
                    ->label('URL de la vidéo')
                    ->required()
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('thumbnail')
                    ->label('Miniature')
                    ->url()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_published')
                    ->label('Publié')
                    ->required(),
                Forms\Components\DateTimePicker::make('published_at')
                    ->label('Date de publication'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->url(fn (string $state): string => $state)
                    ->openUrlInNewTab(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Publié')
                    ->boolean(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Date de publication')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('published')
                    ->label('Publiées uniquement')
                    ->query(fn (Builder $query): Builder => $query->where('is_published', true)),
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
