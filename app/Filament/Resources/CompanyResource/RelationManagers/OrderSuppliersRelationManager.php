<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use App\Filament\Resources\Warehouse\OrderResource;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

class OrderSuppliersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $modelLabel = 'Ordine';

    protected static ?string $pluralModelLabel = 'Ordini';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('year')->sortable()->searchable(),
                TextColumn::make('number')->label('Num')->sortable()->searchable(),
                TextColumn::make('company.name')->label('Company')->sortable()->searchable(),
                // TextColumn::make('supplier.name')->label('Supplier')->sortable()->searchable(),
                TextColumn::make('total_prices')->sortable()->searchable(),
                TextColumn::make('order_at')->sortable()->searchable(),
                IconColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // @todo link to order
                // @todo show pdf
                Tables\Actions\EditAction::make()->url(fn (Model $record): string => OrderResource::getUrl('edit', $record)),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
