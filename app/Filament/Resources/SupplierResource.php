<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Filament\Resources\SupplierResource\Pages;
use App\Models\Category;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Registry';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Card::make([
                Forms\Components\Toggle::make('is_activated')
                    ->columnSpan(2)
                    ->label('Is Activated')
                    ->onColor('success')
                    ->offColor('danger'),
                Forms\Components\TextInput::make('name')
                    ->label('Name / Business name')
                    ->columnSpan(4)
                    ->required(),
                Forms\Components\TextInput::make('code_acronym')
                    ->label('Code (Acronym)'),
                Forms\Components\TextInput::make('code_accounting')
                    ->label('Code (Accounting)'),
                Forms\Components\TextInput::make('fiscal_code')
                    ->label('Fiscal Code'),
                Forms\Components\TextInput::make('vat')
                    ->label('Number VAT'),
                Forms\Components\TextInput::make('invoice_code')
                    ->label('Electronic Invoice Code (SDI)'),
                Forms\Components\TextInput::make('pec')
                    ->label('Certified Mail (CM/PEC)'),
                Forms\Components\TagsInput::make('category')
                    ->label('Category')
                    ->columnSpan(4)
                    ->separator(', '),
                Forms\Components\Textarea::make('note')
                    ->label('Note')
                    ->columnSpan(4),
            ])->columns(4),

            Card::make([
                Forms\Components\TextInput::make('default_tax_rate')
                    ->columnSpan(2)
                    ->default('22')
                    ->numeric()
                    ->label('Tax rate - default value'),
                Forms\Components\Select::make('default_currency')
                    ->columnSpan(2)
                    ->options(Category::where('collection_name', 'Currency')->pluck('name', 'name'))
                    ->label('Currency - default value'),
                Forms\Components\Select::make('default_payment')
                    ->columnSpan(2)
                    ->options(Category::where('collection_name', 'Warehouse-Payment')->pluck('name', 'id'))
                    ->label('Payment - default value'),
            ])
            ->columns(6),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('vat')
                    ->searchable(),
                TextColumn::make('category')
                    ->searchable(),
                IconColumn::make('is_activated')
                    ->label('Act')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_activated')
                    ->label('Is Activated'),
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
            RelationManagers\AddressesRelationManager::class,
            RelationManagers\ContactsRelationManager::class,
            RelationManagers\ProductsRelationManager::class,
            RelationManagers\BanksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
