<?php

namespace App\Filament\Resources\Warehouse;

use App\Filament\Resources\Warehouse\OrderResource\Pages;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Warehouse\Order;
use App\Models\Warehouse\Product;
use Carbon\Carbon;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\HtmlString;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Warehouse';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Group::make([
                    // Info Order
                    Card::make([
                        TextInput::make('number')
                            ->label('Number Order')
                            ->disabled(),
                        DatePicker::make('order_at')
                            ->label('Order\'s date')
                            ->default(Carbon::now())
                            ->displayFormat('d/m/Y'),
                        TextInput::make('company_id')
                            ->label('Company')
                            ->disabled(),
                        TextInput::make('supplier_id')
                            ->label('Supplier')
                            ->disabled(),
                    ])->columns(2),

                    //Details Order
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
                    ]),

                    // Info Payment & Trasport
                    Card::make([
                        Group::make([
                            Select::make('payment_method')
                                ->label('Payment Method')
                                ->options(Category::where('collection_name', 'Warehouse-Payment')->pluck('name', 'id'))
                                ->searchable(),
                            Textarea::make('payment_note')->label('Payment Note'),
                        ]),

                        Group::make([
                            Select::make('trasport_method')
                                ->label('Trasport Method')
                                ->options(Category::where('collection_name', 'Warehouse-Transport')->pluck('name', 'id')),
                            Textarea::make('Trasport_note')->label('Trasport Note'),
                        ]),

                        Placeholder::make('')->content(new HtmlString('<hr />'))->columnSpan(2),

                        Textarea::make('notes')->label('Order notes')->columnSpan(2),

                        Textarea::make('report')->label('Order report')->columnSpan(2),
                    ])->columns(2),

                ])->columnSpan(2),

                // Sidebar Info Order cost and action order
                Group::make([
                    Card::make([
                        Placeholder::make('Total Order')
                                ->content(new HtmlString('
                            <table border="1" class="overflow-auto md:overflow-scroll filament-tables-table w-full table-auto" style="position: fixed;top: 20px;" >
                            <tr><td>Sub Total</td><td style="float:right">0.00 €</td></tr>
                            <tr><td>Vat</td><td style="float:right">0.00 €</td></tr>
                            <tr><td colspan="2"><hr style="margin:10px" /></td></tr>
                            <tr><td><b>Total</b></td><td style="float:right">00.00 €</td></tr>
                            </table>
                        ')),
                    ]),

                    Card::make([
                        Placeholder::make('Total Order')
                            ->content(new HtmlString('
                                Created at: 1 second ago <br >
                                Last modified at: 1 second ago
                            ')),
                    ]),

                    Card::make([

                        Select::make('status')
                            ->label('Status')
                            ->options(Category::where('collection_name', 'Status')->pluck('name', 'name'))
                            ->columnSpan(2),

                        Placeholder::make('Action')->content(new HtmlString('<hr />'))->columnSpan(2),

                        TextInput::make('email')->columnSpan(2),

                        Placeholder::make('Send order by email')
                            ->content(new HtmlString('<button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center"><svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/></svg><span>Send Mail</span></button>'))
                            ->columnSpan(2),

                        Placeholder::make('Download Document')
                            ->content(new HtmlString('<button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center"><svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/></svg><span>Download</span></button>'))
                            ->columnSpan(2),

                        Placeholder::make('View Document')
                            ->content(new HtmlString('<button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center"><svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/></svg><span>View Document</span></button>'))
                            ->columnSpan(2),
                    ]),
                ])->columnSpan(1),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('year')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('number')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('company_id')
                    ->label('Company')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('supplier_id')
                    ->label('Supplier')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('order_at')
                    ->sortable()
                    ->searchable(),
                IconColumn::make('status')
                    ->options([
                        'heroicon-o-x-circle' => 'New',
                        'heroicon-o-pencil' => 'Processing',
                        'heroicon-o-clock' => 'Shipped',
                        'heroicon-o-check-circle' => 'Delivered',
                        'heroicon-o-check-circle' => 'Cancelled',
                        'heroicon-o-check-circle' => 'Draft',
                    ])
                    ->colors([
                        'primary' => 'New',
                        'secondary' => 'Processing',
                        'danger' => 'Draft',
                        'warning' => 'Reviewing',
                        'success' => 'Published',
                    ]),
                /*
                TextColumn::make('status')
                    ->sortable()
                    ->searchable(),
                */
            ])
            ->filters([
                SelectFilter::make('Company')->options([
                    // @todo
                    // Customer::where('is_my_company', true)->pluck('name', 'name')
                ]),
                SelectFilter::make('status')
                    ->options([
                        'New' => 'New',
                        'Processing' => 'Processing',
                        'Shipped' => 'Shipped',
                        'Delivered' => 'Delivered',
                        'Cancelled' => 'Cancelled',
                        'Draft' => 'Draft',
                    ]),

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
