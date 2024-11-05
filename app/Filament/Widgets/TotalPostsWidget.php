<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;


class TotalPostsWidget extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Posts', Post::count())
                ->description('Total blog posts')
                ->color('success'),
        ];
    }
}
