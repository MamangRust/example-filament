<?php

namespace App\Filament\Widgets;

use App\Models\Comment;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Filament\Tables;

class TotalCommentsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): Builder|Relation|null
    {
        return Comment::query()->latest()->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('user_name_comment')->label('User'),
            Tables\Columns\TextColumn::make('comment')->label('Comment'),
            Tables\Columns\TextColumn::make('post.title')->label('Post Title')->limit(30),
            Tables\Columns\TextColumn::make('created_at')->label('Date')->date(),
        ];
    }
}
