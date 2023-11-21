<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ItemResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ItemResource\RelationManagers;

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
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->getStateUsing(function (Item $record) {
                        return Str::limit($record->title, 25);
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('author')
                    ->sortable()
                    ->searchable()
                    ->getStateUsing(function (Item $record) {
                        return Str::limit($record->author, 15);
                    }),
                Tables\Columns\TextColumn::make('year_from')
                    ->getStateUsing(function (Item $record) {
                        return Str::limit($record->year_from, 15);
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('view_count')
                    ->label('Views')
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'primary' : 'gray')
                    ->sortable()
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('detail_count')
                    ->label('Details')
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'gray')
                    ->sortable()
                    ->alignEnd(),
            ])
            ->filters([
                Tables\Filters\Filter::make('tiny_placeholder_not_null')
                    ->toggle()
                    ->label('Has image')
                    ->query(fn (Builder $query) => $query->whereNotNull('tiny_placeholder')),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('Explore')
                    ->url(fn (Item $record): string => url('/') . '?id=' . $record->id, shouldOpenInNewTab: true)
                    ->icon('heroicon-m-arrows-pointing-out')

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
            // 'create' => Pages\CreateItem::route('/create'),
            // 'edit' => Pages\EditItem::route('/{record}/edit'),
            'view' => Pages\ViewItem::route('/{record}'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
                Infolists\Components\ImageEntry::make('image_preview')
                    ->state(function (Item $record): ?string {
                        return $record->getImagePreview();
                    })
                    ->url(fn (Item $record): ?string => $record->getImageRoute())
                    ->openUrlInNewTab(),
                Infolists\Components\TextEntry::make('id'),
                Infolists\Components\TextEntry::make('object'),
                Infolists\Components\TextEntry::make('title'),
                Infolists\Components\TextEntry::make('author'),
                Infolists\Components\TextEntry::make('description'),
                Infolists\Components\TextEntry::make('material'),
                Infolists\Components\TextEntry::make('technique'),
                Infolists\Components\TextEntry::make('iconography'),
                Infolists\Components\TextEntry::make('style'),
                Infolists\Components\TextEntry::make('image')
                    ->state(function (Item $record): ?string {
                        return $record->getImageRoute();
                    })
                    ->url(fn (Item $record): ?string => $record->getImageRoute())
                    ->openUrlInNewTab()
                    ->label('Image source on DAM'),
                Infolists\Components\TextEntry::make('image_src')
                    ->url(fn (Item $record): ?string => $record->image_src)
                    ->openUrlInNewTab()
                    ->label('Image source on digicult'),
                Infolists\Components\TextEntry::make('web_url')
                    ->url(fn (Item $record): ?string => $record->web_url)
                    ->openUrlInNewTab(),
                Infolists\Components\TextEntry::make('collection'),
                Infolists\Components\TextEntry::make('year_from'),
                Infolists\Components\TextEntry::make('year_to'),
                Infolists\Components\TextEntry::make('acquisition_date'),
                Infolists\Components\TextEntry::make('year')
                    ->label('Calculated year')
                    ->hint('Calculated from year_from, year_to and acquisition_date')
                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Not displayed anywhere. Used for sorting'),
                Infolists\Components\TextEntry::make('asset_id'),
                Infolists\Components\TextEntry::make('view_count')->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'primary' : 'gray'),
                Infolists\Components\TextEntry::make('detail_count')->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'gray'),
            ]);
    }
}
