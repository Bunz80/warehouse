<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Company;
use App\Models\Category;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

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
                        ->label('Electronic Invoice Code (SDI)')
                        ->columnSpan(2),
                    Forms\Components\TextInput::make('pec')
                        ->label('Certified Mail (CM/PEC)')
                        ->columnSpan(2),
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
                        ->label('Tax rate - default value')
                        ->default('22')
                        ->numeric()
                        ->columnSpan(2),
                    Forms\Components\Select::make('default_currency')
                        ->label('Currency - default value')
                        ->options(Category::where('collection_name', 'Currency')->pluck('name', 'name'))
                        ->default('€')
                        ->columnSpan(2),
                    Forms\Components\Select::make('default_payment')
                        ->columnSpan(2)
                        ->options(Category::where('collection_name', 'Warehouse-Payment')->pluck('name', 'id'))
                        ->label('Payment - default value'),
                ])
                ->columns(6),

                Section::make('Letterhead (Html)')
                ->schema([
                    Forms\Components\MarkdownEditor::make('page_header')
                        ->toolbarButtons([
                            'bold',
                            'bulletList',
                            'orderedList',
                            'edit',
                            'preview',
                        ]),
                    Forms\Components\MarkdownEditor::make('page_footer')
                            ->toolbarButtons([
                                'bold',
                                'bulletList',
                                'orderedList',
                                'edit',
                                'preview',
                            ]),
                    Forms\Components\MarkdownEditor::make('page_warehouse_terms')
                            ->toolbarButtons([
                                'bold',
                                'bulletList',
                                'orderedList',
                                'edit',
                                'preview',
                            ]),
                    Forms\Components\MarkdownEditor::make('page_warehouse_info')
                            ->toolbarButtons([
                                'bold',
                                'bulletList',
                                'orderedList',
                                'edit',
                                'preview',
                            ]),
                ])
                ->compact()
                ->collapsed()
                ->collapsible(),

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
                IconColumn::make('is_activated')
                    ->label('Act')
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
            RelationManagers\AddressesRelationManager::class,
            RelationManagers\ContactsRelationManager::class,
            RelationManagers\BanksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
