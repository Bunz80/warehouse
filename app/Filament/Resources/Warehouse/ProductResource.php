<?php

namespace App\Filament\Resources\Warehouse;

use App\Filament\Resources\Warehouse\ProductResource\Pages;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Warehouse\Product;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Warehouse';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Forms\Components\Select::make('supplier_id')
                        ->label('Supplier or move product to supplier')
                        ->options(Supplier::all()->pluck('name', 'id'))
                        ->default(2)
                        ->searchable()
                        ->columnSpan(6)
                        ->reactive(),
                    Forms\Components\TextInput::make('brand')
                        ->columnSpan(6)
                        ->label('Brand'),
                    Forms\Components\TextInput::make('name')
                        ->columnSpan(12)
                        ->label('Name Product')
                        ->required(),
                    Forms\Components\MarkdownEditor::make('description')
                        ->toolbarButtons(['bold', 'bulletList', 'orderedList', 'edit', 'preview'])
                        ->label('Description Product')
                        ->columnSpan(12),
                    Forms\Components\TagsInput::make('category')
                        ->columnSpan(12)
                        ->label('Category')
                        ->suggestions(Category::where('collection_name', 'Warehouse->Category')->pluck('name', 'name'))
                        ->separator(', '),
                    Forms\Components\TextInput::make('code')
                        ->columnSpan(3)
                        ->label('Supplier Code'),
                    Forms\Components\Select::make('currency')
                        ->columnSpan(2)
                        ->options(Category::where('collection_name', 'Currency')->pluck('name', 'name'))
                        ->default('€')
                        ->label('Currency'),
                    Forms\Components\TextInput::make('tax')
                        ->columnSpan(2)
                        ->label('Tax %')
                        ->default('22.00')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('unit')
                        ->columnSpan(2)
                        ->default('Pz')
                        ->label('Unit')
                        ->required(),
                    Forms\Components\TextInput::make('price')
                        ->columnSpan(3)
                        ->numeric()
                        ->required()
                        ->label('Price'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->sortable()
                    ->searchable(),
                // Tables\Columns\TextColumn::make('tax'),
                // Tables\Columns\TextColumn::make('unit'),
                // Tables\Columns\TextColumn::make('currency'),
                Tables\Columns\TextColumn::make('price')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ReplicateAction::make()
                ->afterReplicaSaved(
                    function (Model $replica, $record): void {
                        $replica->update([
                            'name' => $replica->name.' (copia)',
                        ]);
                    }
                ),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    // GLOBAL SEARCH
    // Titolo Record
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        $b = '';

        if ($record->brand != '') {
            $b = ' ('.$record->brand.') ';
        }

        return $record->name.$b.$record->price.' '.$record->currency;
    }

    // Attributi ricercabili
    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name', 'brand', 'description', 'category', 'price', 'currency', 'supplier.name',
        ];
    }

    // Query Relazioni
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['supplier']);
    }

    // Aggiungi Azioni, Icone, Ecc
    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            // Action::make('edit')
            //     ->iconButton()
            //     ->icon('heroicon-s-pencil')
            //     ->url(static::getUrl('edit', ['record' => $record])),
        ];
    }
}
