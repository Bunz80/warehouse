<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Address;
use App\Models\Category;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\AddressResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;

class AddressResource extends Resource
{
    protected static ?string $model = Address::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Registry';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Forms\Components\Select::make('collection_name')->options(Category::where('collection_name', 'Address')->pluck('name')),
                    Forms\Components\TextInput::make('name'),
                    Forms\Components\TextInput::make('address'),
                    Forms\Components\TextInput::make('street_number'),
                    Forms\Components\TextInput::make('zip'),
                    Forms\Components\TextInput::make('city'),
                    Forms\Components\TextInput::make('province'),
                    Forms\Components\TextInput::make('state'),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('collection_name')->label('Type')->searchable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('address')->searchable(),
                TextColumn::make('street_number'),
                TextColumn::make('zip')->searchable(),
                TextColumn::make('city')->searchable(),
                TextColumn::make('province')->searchable(),
                TextColumn::make('state')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrderAddressesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAddresses::route('/'),
            // 'create' => Pages\CreateAddress::route('/create'),
            'edit' => Pages\EditAddress::route('/{record}/edit'),
        ];
    }
}
