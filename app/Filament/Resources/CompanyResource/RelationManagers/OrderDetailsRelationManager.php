<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Illuminate\Support\HtmlString;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\RelationManagers\RelationManager;

class OrderDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderDetails';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->columnSpan(6),
                Forms\Components\TextInput::make('brand')->columnSpan(3),
                Forms\Components\TextInput::make('code')->columnSpan(3),
                Forms\Components\MarkdownEditor::make('description') ->toolbarButtons(['bold','bulletList','orderedList','edit','preview',])->columnSpan(6),
                
                // Currency from order
                // Forms\Components\Select::make('currency')
                //     ->label('Currency')
                //     ->options(Category::where('collection_name', 'Currency')->pluck('name', 'name'))
                //     ->reactive()
                //     ->columnSpan(1),
                
                Forms\Components\TextInput::make('tax')->required(),
                Forms\Components\TextInput::make('unit')->required(),
                Forms\Components\TextInput::make('quantity')->required(),
                Forms\Components\TextInput::make('price_unit')->required()->columnSpan(2),
                Forms\Components\Placeholder::make('Discount')->content(new HtmlString('<hr />'))->columnSpan(6),
                Forms\Components\Select::make('discount_currency')
                    ->label('Currency')
                    ->options(Category::where('collection_name', 'Currency')->pluck('name', 'name'))
                    ->reactive(),
                Forms\Components\TextInput::make('discount_price'),
                Forms\Components\TextInput::make('total_price')->required()->label('disabled')->columnSpan(4),
            ])->columns(6);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('brand')->sortable()->searchable(),
                TextColumn::make('code')->sortable()->searchable(),
                TextColumn::make('description')->sortable()->searchable(),
                TextColumn::make('tax')->sortable()->searchable(),
                TextColumn::make('unit')->sortable()->searchable(),
                TextColumn::make('quantity')->sortable()->searchable(),
                TextColumn::make('price')->sortable()->searchable(),
                TextColumn::make('discount_currency')->sortable()->searchable(),
                TextColumn::make('discount_price')->sortable()->searchable(),
                TextColumn::make('total_price')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // @todo link to order
                // @todo show pdf
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
