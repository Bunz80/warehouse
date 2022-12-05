<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Guava\FilamentIconPicker\Forms\IconPicker;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Forms\Components\Select::make('collection_name')
                        ->options([
                            //Value => Label,
                            'Purchase' => 'Purchase',
                            'Address' => 'Address',
                            'Status' => 'Status',
                            'Currency' => 'Currency',
                            'Warehouse-Product' => 'Warehouse->Product',
                            'Warehouse-Supplier' => 'Warehouse->Supplier',
                            'Warehouse-Payment' => 'Warehouse->Payment',
                            'Warehouse-Transport' => 'Warehouse->Transport',
                            'Warehouse-Delivery' => 'Warehouse->Delivery',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('name')
                        ->label('Name')
                        ->required(),
                    //Forms\Components\TextInput::make('icon')
                    IconPicker::make('icon')
                        ->label('Icon'),
                    Forms\Components\ColorPicker::make('color')
                        ->label('Color'),
                    Forms\Components\Toggle::make('is_default')
                        ->label('Is Default')
                        ->onColor('success')
                        ->offColor('danger'),
                    Forms\Components\Toggle::make('is_activated')
                        ->label('Is Activated')
                        ->onColor('success')
                        ->offColor('danger')
                        ->default(true),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('collection_name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('icon'),
                TextColumn::make('color'),
                IconColumn::make('is_default')
                    ->label('Default')
                    ->boolean(),
                IconColumn::make('is_activated')
                    ->label('Actived')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
