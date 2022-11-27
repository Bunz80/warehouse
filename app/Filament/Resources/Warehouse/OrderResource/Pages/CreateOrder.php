<?php

namespace App\Filament\Resources\Warehouse\OrderResource\Pages;

use App\Filament\Resources\Warehouse\OrderResource;
use App\Models\Category;
use App\Models\Company;
use App\Models\Supplier;
use App\Models\Warehouse\Product;
use Carbon\Carbon;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Illuminate\Support\HtmlString;

class CreateOrder extends CreateRecord
{
    use HasWizard;

    protected static string $resource = OrderResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Order Details')
            ->description('Data order')
            ->icon('heroicon-o-shopping-bag')
            ->schema([
                Card::make([
                    Select::make('company_id')
                        ->label('My Company')
                        ->options(Company::all()->pluck('name', 'id'))
                        ->searchable()
                        ->required()
                        ->columnSpan(2),
                    Select::make('supplier_id')
                        ->label('Supplier')
                        ->options(Supplier::all()->pluck('name', 'id'))
                        ->searchable()
                        ->required()
                        ->columnSpan(2),
                ])->columns(4),

                Card::make([
                    Select::make('currency')
                        ->label('Currency Default')
                        ->options(Category::where('collection_name', 'Currency')->pluck('name', 'name'))
                        ->default('€'),
                    TextInput::make('Tax')
                        ->default('22')
                        ->label('Tax Default'),
                    DatePicker::make('order_at')
                        ->label('Order\'s date')
                        ->default(Carbon::now())
                        ->displayFormat('d/m/Y'),
                ])->columns(4),
            ]),

            Step::make('Products list')
                ->description('Details products')
                ->icon('heroicon-o-shopping-bag')
                ->schema([
                    Card::make([

                        Select::make('product')
                            ->label('Search and Add Product')
                            ->options(Product::all()->pluck('name', 'id'))
                            ->searchable(),

                        Repeater::make('Product list')
                            ->schema([
                                TextInput::make('name')
                                    ->columnSpan(12)
                                    ->default('Name')
                                    ->label('Product Name'),
                                MarkdownEditor::make('description')
                                    ->toolbarButtons([
                                        'bold',
                                        'bulletList',
                                        'orderedList',
                                        'edit',
                                        'preview',
                                    ])
                                    ->default('Product test')
                                    ->reactive()
                                    ->required()
                                    ->columnSpan(12),
                                TextInput::make('code')
                                    ->columnSpan(3)
                                    ->label('Supplier Code'),
                                TextInput::make('vat')
                                ->columnSpan(2)
                                    ->default('22.00')
                                    ->required()
                                    ->label('Vat %'),
                                TextInput::make('unit')
                                ->columnSpan(2)
                                    ->default('Pz')
                                    ->label('Unit'),
                                TextInput::make('qty')
                                    ->columnSpan(2)
                                    ->default('1')
                                    ->required()
                                    ->label('Quantity'),
                                TextInput::make('price_unit')
                                    ->columnSpan(3)
                                    ->default('10')
                                    ->numeric()
                                    ->required()
                                    ->label('Price'),

                                Select::make('discount_currency')
                                    ->label('Discount Currency')
                                    //->options(Category::where('collection_name', 'Warehouse-Payment')->pluck('name', 'id'))
                                    ->options(['%', '€', '$', '£', '¥'])
                                    ->columnSpan(3),
                                TextInput::make('discount_price')
                                    ->label('Discount Value')
                                    ->columnSpan(3)
                                    ->numeric(),
                                Placeholder::make('Total Price Item')
                                    ->label('Total Price Item: ')
                                    ->content(new HtmlString('<b>€ 100.00</b>'))->columnSpan(6),

                            ])
                            ->collapsible()
                            ->cloneable()
                            ->orderable()
                            ->defaultItems(1)
                            ->createItemButtonLabel('Add Item')
                            ->columns(12),
                    ])->columnSpan(3),

                    Card::make([
                        Placeholder::make('Total Order')
                            ->content(new HtmlString('
                        <table border="1" class="filament-tables-table w-full table-auto">
                        <tr><td>Sub Total</td><td style="float:right">20.17 €</td></tr>
                        <tr><td>Vat</td><td style="float:right">4.43 €</td></tr>
                        <tr><td colspan="2"><hr style="margin:10px" /></td></tr>
                        <tr><td><b>Total</b></td><td style="float:right">24.60 €</td></tr>
                        </table>
                        ')),
                    ])->columnSpan(1),

                ])->columns(4),

            Step::make('Delivery & Payment')
                ->description('Details Delivery and Payment')
                ->icon('heroicon-o-shopping-bag')
                ->schema([
                    Card::make([

                        Placeholder::make('')->content(new HtmlString('')),

                        Select::make('payment_method')
                            ->label('Payment Method')
                            ->options(Category::where('collection_name', 'Warehouse-Payment')->pluck('name', 'id'))
                            ->searchable(),

                        Textarea::make('payment_note')->label('Payment Note'),

                        Placeholder::make('')->content(new HtmlString('<hr />')),

                        Select::make('trasport_method')
                            ->label('Trasport Method')
                            ->options(Category::where('collection_name', 'Warehouse-Transport')->pluck('name', 'id'))
                            ->searchable(),

                        Textarea::make('Trasport_note')->label('Trasport Note'),

                        Placeholder::make('')->content(new HtmlString('<hr />')),

                        Textarea::make('notes')->label('Order notes'),

                        Select::make('status')
                            ->label('Status')
                            ->options(Category::where('collection_name', 'Status')->pluck('name', 'name'))
                            ->default('New')
                            ->searchable(),

                    ]),
                ]),

            Step::make('Summary'),
        ];
    }
}
