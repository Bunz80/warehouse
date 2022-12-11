<?php

namespace App\Filament\Resources\Warehouse;

use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Filament\Resources\Warehouse\OrderResource\Pages;
use App\Models\Address;
use App\Models\Category;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Supplier;
use App\Models\Warehouse\Order;
use App\Models\Warehouse\Product;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
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
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\HtmlString;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Warehouse';

    protected static ?string $modelLabel = 'Ordine';

    protected static ?string $pluralModelLabel = 'Ordini';

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
                        Select::make('company_id')
                            ->label('Company')
                            ->disabled()
                            ->options(Company::all()->pluck('name', 'id')),
                        Select::make('supplier_id')
                            ->label('Supplier')
                            ->disabled()
                            ->options(Supplier::where('is_activated', true)->pluck('name', 'id')),
                    ])->columns(2),

                    Card::make([
                        Select::make('address_id')
                            ->label('Delivery Address')
                            ->options(Address::where('addressable_type', 'App\Models\Company')
                                ->where('collection_name', 'Delivery')
                                ->orderBy('name', 'ASC')
                                ->pluck('name', 'id'))
                            ->searchable(),
                        Select::make('contact_id')
                            ->label('Delivery Contact')
                            ->options(Contact::where('contactable_type', 'App\Models\Company')
                                ->orderBy('name', 'ASC')
                                ->pluck('name', 'id'))
                            ->searchable(),
                    ]),

                    //Details Order
                    // Card::make([
                    //     Select::make('product')
                    //         ->label('Search and Add Product')
                    //         ->options(Product::all()->pluck('name', 'id'))
                    //         ->searchable(),

                    //     Repeater::make('Product list')
                    //         ->schema([
                    //             TextInput::make('name')
                    //                 ->columnSpan(12)
                    //                 ->default('Name')
                    //                 ->label('Product Name'),
                    //             MarkdownEditor::make('description')
                    //                 ->toolbarButtons([
                    //                     'bold',
                    //                     'bulletList',
                    //                     'orderedList',
                    //                     'edit',
                    //                     'preview',
                    //                 ])
                    //                 ->default('Product test')
                    //                 ->reactive()
                    //                 ->required()
                    //                 ->columnSpan(12),
                    //             TextInput::make('code')
                    //                 ->columnSpan(3)
                    //                 ->label('Supplier Code'),
                    //             TextInput::make('vat')
                    //             ->columnSpan(2)
                    //                 ->default('22.00')
                    //                 ->required()
                    //                 ->label('Vat %'),
                    //             TextInput::make('unit')
                    //             ->columnSpan(2)
                    //                 ->default('Pz')
                    //                 ->label('Unit'),
                    //             TextInput::make('qty')
                    //                 ->columnSpan(2)
                    //                 ->default('1')
                    //                 ->required()
                    //                 ->label('Quantity'),
                    //             TextInput::make('price_unit')
                    //                 ->columnSpan(3)
                    //                 ->default('10')
                    //                 ->numeric()
                    //                 ->required()
                    //                 ->label('Price'),

                    //             Select::make('discount_currency')
                    //                 ->label('Discount Currency')
                    //                 //->options(Category::where('collection_name', 'Warehouse-Payment')->pluck('name', 'id'))
                    //                 ->options(['%', '€', '$', '£', '¥'])
                    //                 ->columnSpan(3),
                    //             TextInput::make('discount_price')
                    //                 ->label('Discount Value')
                    //                 ->columnSpan(3)
                    //                 ->numeric(),
                    //             Placeholder::make('Total Price Item')
                    //                 ->label('Total Price Item: ')
                    //                 ->content(new HtmlString('<b>€ 100.00</b>'))->columnSpan(6),

                    //         ])
                    //         ->collapsible()
                    //         ->cloneable()
                    //         ->orderable()
                    //         ->defaultItems(1)
                    //         ->createItemButtonLabel('Add Item')
                    //         ->columns(12),
                    // ]),

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
                            Textarea::make('trasport_note')->label('Trasport Note'),
                        ]),

                        Placeholder::make('')->content(new HtmlString('<hr />'))->columnSpan(2),

                        Textarea::make('notes')->label('Order notes')->columnSpan(2),

                        Textarea::make('report')->label('Order report')->columnSpan(2),
                    ])->columns(2),

                ])->columnSpan(2),

                // Sidebar Info Order cost and action order
                Group::make([
                    Hidden::make('total_prices'),
                    Hidden::make('total_taxes'),
                    Hidden::make('total_order'),

                    Card::make([
                        Placeholder::make('Total Order')
                                ->content(function (Closure $get) {
                                    return new HtmlString('

                            <div class="rounded-xl p-6 bg-white border border-gray-300" id="total">
                            <table border="1" class="filament-tables-table table-auto w-full">
                            <tr><td>Sub Total</td><td style="float:right">'.round($get('total_prices'), 2).' '.$get('currency').'</td></tr>
                            <tr><td>Vat</td><td style="float:right">'.round($get('total_taxes'), 2).'</td></tr>
                            <tr><td colspan="2"><hr style="margin:10px" /></td></tr>
                            <tr><td><b>Total</b></td><td style="float:right">'.round($get('total_order'), 2).' '.$get('currency').'</td></tr>
                            </table></div>');
                                })->columnSpan(1),

                    ]),

                    Card::make([
                        Forms\Components\Placeholder::make('created_at')->label('')
                            ->content(fn (Order $record): ?string => 'Created at: '.$record->created_at?->format('d-m-Y').' ('.$record->created_at?->diffForHumans().')'),
                        Forms\Components\Placeholder::make('updated_at')->label('')
                            ->content(fn (Order $record): ?string => 'Update at: '.$record->updated_at?->diffForHumans()),
                        // Forms\Components\Placeholder::make('close_at')->label('')
                        //     ->content(fn (Order $record): ?string => "Close at: ".$record->close_at?->diffForHumans()),
                        // Forms\Components\Placeholder::make('deadline_at')->label('')
                        //     ->content(fn (Order $record): ?string => "Close at: ".$record->deadline_at?->diffForHumans()),
                    ]),

                    Card::make([
                        Select::make('currency')
                            ->options(Category::where('collection_name', 'Currency')->pluck('name', 'name')),
                        DatePicker::make('deadline_at')
                            ->label('Deadline')
                            ->displayFormat('d/m/Y'),
                        Select::make('status')
                            ->label('Status')
                            ->options(Category::where('collection_name', 'Status')->pluck('name', 'name')),
                    ]),

                    Card::make([
                        Placeholder::make('View Document')
                            ->content(
                                function (Closure $get) {
                                    return new HtmlString('
                                        <a target="_blank" href = "/admin/warehouse/orders/print/'.$get('id').'" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                                            <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/>
                                            </svg>
                                            <span>View Document</span>
                                        </a>');
                                }
                            )
                            ->columnSpan(2),
                    ]),
                ])->columnSpan(1),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('year')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('number')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('company.name')
                    ->label('Company')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('address.name')
                    ->label('Delivery')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('order_at')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('total_order')
                    ->label('Price')
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
            ])
            ->filters([
                SelectFilter::make('Company')->relationship('company', 'name'),

                SelectFilter::make('status')
                    ->options([
                        'New' => 'New',
                        'Processing' => 'Processing',
                        'Shipped' => 'Shipped',
                        'Delivered' => 'Delivered',
                        'Cancelled' => 'Cancelled',
                        'Draft' => 'Draft',
                    ]),

                Filter::make('created_at')
                ->form([
                    Forms\Components\DatePicker::make('created_at')->displayFormat('d/m/Y')->label('Data Inizio'),
                    Forms\Components\DatePicker::make('created_at')->displayFormat('d/m/Y')->label('Data Fine')->default(now()),
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
            RelationManagers\OrderDetailsRelationManager::class,
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
