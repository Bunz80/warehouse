<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class ContactsRelationManager extends RelationManager
{
    protected static string $relationship = 'contacts';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'Contatto';

    protected static ?string $pluralModelLabel = 'Contatti';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('collection_name')->options([
                    'phone', 'email', 'web', 'social', 'fax', 'user'
                ]),
                Forms\Components\TextInput::make('name'),
                Forms\Components\TextInput::make('address')->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('collection_name')
                    ->label('Type')
                    ->options([
                        'heroicon-s-phone' => 'phone',
                        'heroicon-s-phone' => 'fax',
                        'heroicon-s-mail' => 'email',
                        'heroicon-s-globe' => 'social',
                        'heroicon-s-globe' => 'web',
                    ])->size('md'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('address'),
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
