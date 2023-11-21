<?php

namespace App\Filament\Resources\ItemResource\Widgets;

use App\Models\Item;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ItemsOverview extends BaseWidget
{

    // protected int | string | array $columnSpan = 3;

    protected function getStats(): array
    {
        return [
            Stat::make('Items', Item::count())
                ->description('All imported items in the database')
                ->icon('heroicon-m-rectangle-stack'),
            Stat::make('Items with image', Item::whereNotNull('tiny_placeholder')->count())
                ->description('Only these are shown in the explorer')
                ->icon('heroicon-m-photo'),
            Stat::make('Viewed', Item::where('view_count', '>', 0)->count())
                ->description('At least once in the middle of the explorer')
                ->icon('heroicon-m-eye')
                ->color('primary'),
            Stat::make('Detail opened', Item::where('detail_count', '>', 0)->count())
                ->description('Details opened at least once')
                ->icon('heroicon-m-cursor-arrow-rays')
                ->color('success'),
        ];
    }
}
