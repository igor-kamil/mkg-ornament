<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_preview')
                    ->getStateUsing(function (Item $record) {
                        return $record->getImagePreview();
                    })
                    ->url(fn (Item $record): string => url('/') . '?id=' . $record->id, shouldOpenInNewTab: true)
                    ->square()
                    ->label('Image'),
                Tables\Columns\TextColumn::make('id')
                    ->url(fn (Item $record): string => url('/') . '?id=' . $record->id, shouldOpenInNewTab: true)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('author')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('year_from')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('tiny_placeholder_not_null')
                    ->toggle()
                    ->label('Has image')
                    ->query(fn (Builder $query) => $query->whereNotNull('tiny_placeholder')),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('Explore')
                    ->url(fn (Item $record): string => url('/') . '?id=' . $record->id, shouldOpenInNewTab: true)

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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
