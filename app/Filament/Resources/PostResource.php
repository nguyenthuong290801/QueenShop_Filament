<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Category;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Bài viết';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tên bài viết')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug'),
                        RichEditor::make('content')
                            ->label('Nội dung')
                            ->columnSpan(2),
                    ])
                    ->columnSpan(2)
                    ->columns(2),
                Section::make('')
                    ->schema([
                        Select::make('category_id')
                            ->label('Danh mục')
                            ->options(Category::query()->pluck('name', 'id'))
                            ->columnSpanFull(),
                        FileUpload::make('image')
                            ->label('Hình ảnh')
                            ->columnSpan(2)
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->columnSpanFull(),
                        Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'draft' => 'Bản nháp',
                                'reviewing' => 'Đang xem xét',
                                'published' => 'Được phát hành',
                            ])
                            ->required()
                            ->columnSpanFull(),
                        DateTimePicker::make('published_at')
                            ->label('Ngày xuất bản')
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->label('Tên bài viết'),
                TextColumn::make('slug'),
                TextColumn::make('category.name')
                ->label('Danh mục'),
                TextColumn::make('published_at')
                ->label('Ngày xuất bản'),
                TextColumn::make('status')
                ->label('Trạng thái'),
            ])
            ->filters([
                //
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
