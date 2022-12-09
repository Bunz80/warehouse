<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use App\Models\Category;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'Indirizzo';

    protected static ?string $pluralModelLabel = 'Indirizzi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('collection_name')->options(
                    Category::where('collection_name', 'Address')->pluck('name', 'name'),
                )->required(),
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('address')->required(),
                Forms\Components\TextInput::make('street_number'),
                Forms\Components\TextInput::make('zip'),
                Forms\Components\TextInput::make('city')->required(),
                Forms\Components\TextInput::make('province'),
                Forms\Components\TextInput::make('state')->default('Italia'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('collection_name')
                    ->label('Type'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('address'),
                Tables\Columns\TextColumn::make('street_number')
                    ->label('Nr'),
                Tables\Columns\TextColumn::make('zip'),
                Tables\Columns\TextColumn::make('city'),
                Tables\Columns\TextColumn::make('province'),
                Tables\Columns\TextColumn::make('state'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
