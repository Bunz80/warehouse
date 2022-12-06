<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

// namespace App\Filament\Resources\SupplierResource\RelationManagers;

use App\Models\Category;
use App\Models\Supplier;
use Closure;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'Prodotto';

    protected static ?string $pluralModelLabel = 'Prodotti';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('supplier_id')
                    ->label('Supplier or move product to supplier')
                    ->options(Supplier::all()->pluck('name', 'id'))
                    // ->default(fn (Closure $get) => ! $get('supplier_id'))
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
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('category'),
                Tables\Columns\TextColumn::make('tax'),
                Tables\Columns\TextColumn::make('unit'),
                Tables\Columns\TextColumn::make('currency'),
                Tables\Columns\TextColumn::make('price'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
