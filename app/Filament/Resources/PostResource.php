<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Storage;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->required(),
                TextInput::make('slug')->required(),

                FileUpload::make('img')
                    ->label('Image')
                    ->directory('posts/images')
                    ->image()
                    ->required(),

                Textarea::make('body')->required(),

                Select::make('category_id')
                    ->label('Category')
                    ->options(
                        Category::whereNotNull('name')->pluck('name', 'id')
                    )
                    ->required(),

                Select::make('user_id')
                    ->label('Author')
                    ->options(
                        User::whereNotNull('firstname')->pluck('firstname', 'id')
                    )
                    ->reactive() // Make it reactive to trigger updates
                    ->required()
                    ->afterStateUpdated(
                        fn($state, callable $set) =>
                        $set('user_name', User::find($state)?->firstname ?? '')
                    ),

                TextInput::make('user_name')
                    ->label('Author Name')
                    ->required()
                    ->disabled(), // Make it disabled since it's auto-populated
            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('img')
                    ->label('Image')
                    
                    ->square(),
                TextColumn::make('title')->sortable()->searchable(),
                TextColumn::make('slug')->sortable(),
                TextColumn::make('category.name')->label('Category'),
                TextColumn::make('user.firstname')->label('Author'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
