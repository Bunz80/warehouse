<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Models\Category;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Section;
use Filament\GlobalSearch\Actions\Action;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Registry';

    protected static ?string $recordTitleAttribute = 'name';

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

    // GLOBAL SEARCH
    // Titolo Record
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name.' ('.$record->vat.')';
    }

    // Attributi ricercabili
    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name', 'vat', 'addresses.address', 'contacts.name',
        ];
    }

    // Sottotitolo con dettagli
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [
            'Partita IVA' => $record->vat,
        ];

        if ($record->contacts->count() > 0) {
            $details['Contatti'] = $record->contacts->implode('name', ', ');
        }

        return $details;
    }

    // Query Relazioni
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['addresses', 'contacts']);
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
