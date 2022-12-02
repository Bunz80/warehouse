<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Category;
use App\Models\Customer;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Model;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

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
                    Forms\Components\Toggle::make('is_person')
                        ->columnSpan(2)
                        ->label('Is a person')
                        ->onColor('success')
                        ->offColor('danger')
                        ->reactive(),
                    Forms\Components\TextInput::make('name')
                        ->label('Name / Business name')
                        ->columnSpan(2)
                        ->required(),
                    Forms\Components\TextInput::make('lastname')
                        ->hidden(fn (Closure $get) => ! $get('is_person'))
                        ->columnSpan(2)
                        ->label('Lastname'),
                    Forms\Components\Select::make('gender')
                        ->hidden(fn (Closure $get) => ! $get('is_person'))
                        ->label('Gender')
                        ->options(['Male' => 'Male', 'Female' => 'Female']),
                    Forms\Components\DatePicker::make('date_birth')
                        ->hidden(fn (Closure $get) => ! $get('is_person'))
                        ->label('Date of Birth')
                        ->displayFormat('d/m/Y'),
                    Forms\Components\TextInput::make('code_acronym')
                        ->hidden(fn (Closure $get) => $get('is_person'))
                        ->label('Code (Acronym)'),
                    Forms\Components\TextInput::make('code_accounting')
                        ->hidden(fn (Closure $get) => $get('is_person'))
                        ->label('Code (Accounting)'),
                    Forms\Components\TextInput::make('fiscal_code')
                        ->label('Fiscal Code'),
                    Forms\Components\TextInput::make('vat')
                        ->hidden(fn (Closure $get) => $get('is_person'))
                        ->label('Number VAT'),
                    Forms\Components\TextInput::make('invoice_code')
                        ->hidden(fn (Closure $get) => $get('is_person'))
                        ->label('Electronic Invoice Code (SDI)'),
                    Forms\Components\TextInput::make('pec')
                        ->hidden(fn (Closure $get) => $get('is_person'))
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
                        ->label('Tax rate - default value')
                        ->numeric()
                        ->columnSpan(2)
                        ->default('22'),
                    Forms\Components\Select::make('default_currency')
                        ->label('Currency - default value')
                        ->options(Category::where('collection_name', 'Currency')->pluck('name', 'name'))
                        ->default('€')
                        ->columnSpan(2),
                    Forms\Components\Select::make('default_payment')
                        ->options(Category::where('collection_name', 'Warehouse-Payment')->pluck('name', 'id'))
                        ->label('Payment - default value')
                        ->columnSpan(2),
                ])
                ->hidden(fn (Closure $get) => $get('is_person'))
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
                TextColumn::make('lastname')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('vat')
                    ->searchable(),

                TextColumn::make('category')
                    ->searchable(),

                IconColumn::make('is_person')
                    ->label('Person')
                    ->boolean(),
                IconColumn::make('is_activated')
                    ->label('Act')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_activated')
                    ->label('Is Activated'),
                TernaryFilter::make('is_person')
                    ->label('Company'),
            ])
            ->actions([
                Tables\Actions\ReplicateAction::make()
                ->afterReplicaSaved(
                    function (Model $replica, $record): void {
                        $replica->update([
                            'name' => $replica->name.' (copia)',
                        ]);
                        // Deep Clone
                        /*
                        $newcontacts_ids = [];
                        foreach ($record->contacts as $contact) {
                            $newcontact = $contact->replicate();
                            $newcontact->save();
                            $contacts_ids[] = $newcontact->id;
                            //$replica->save([]);
                        }
                        $replica->contacts()->saveMany($newcontacts_ids);
                        $replica->save();
                        */
                    }
                ),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
