<?php

namespace App\Filament\Resources\Warehouse\OrderResource\Pages;

use App\Filament\Resources\Warehouse\OrderResource;
use App\Models\Address;
use App\Models\Category;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Supplier;
use App\Models\Warehouse\Order;
use App\Models\Warehouse\Product;
use Carbon\Carbon;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
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
                        ->columnSpan(2)
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, Closure $get) {
                            $year = date('Y', strtotime($get('order_at')));
                            $order = Order::whereRaw('company_id = '.$get('company_id').' and year = '.$year)->orderBy('number', 'desc');
                            if ($order) {
                                $num = $order->pluck('number');
                                if (count($num) > 0) {
                                    $set('number', $num[0] + 1);
                                } else {
                                    $set('number', 1);
                                }
                            }
                        }),
                    Select::make('supplier_id')
                        ->label('Supplier')
                        ->options(Supplier::all()->pluck('name', 'id'))
                        ->searchable()
                        ->required()
                        ->columnSpan(2)
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, Closure $get) {
                            if ($get('supplier_id')) {
                                $supplier = Supplier::where('id', $get('supplier_id'));
                                if ($supplier) {
                                    $tax = $supplier->pluck('vat')[0];
                                    $currency = $supplier->pluck('default_currency')[0];
                                    $set('Tax', $tax);
                                    $set('currency', $currency);
                                }
                            }
                        }),
                ])->columns(4),

                Card::make([
                    Select::make('currency')
                        ->label('Currency Default')
                        ->options(Category::where('collection_name', 'Currency')->pluck('name', 'name')),
                    TextInput::make('Tax')
                        ->label('Tax Default'),
                    Hidden::make('number')
                        ->label('Order number')
                        ->default(1),
                    Hidden::make('year')
                        ->label('Year')
                        ->default(function () {
                            $year = Carbon::now()->format('Y');

                            return $year;
                        }),
                    DatePicker::make('order_at')
                        ->label('Order\'s date')
                        ->default(Carbon::now())
                        ->displayFormat('d/m/Y')
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, Closure $get) {
                            $year = date('Y', strtotime($get('order_at')));
                            $set('year', $year);
                            $companyId = $get('company_id');
                            if ($companyId) {
                                $order = Order::whereRaw('company_id = '.$companyId.' and year = '.$year)->orderBy('number', 'desc');
                                if ($order) {
                                    $num = $order->pluck('number');
                                    if (count($num) > 0) {
                                        $set('number', $num[0] + 1);
                                    } else {
                                        $set('number', 1);
                                    }
                                }
                            }
                        }),
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
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function (Closure $set, Closure $get) {
                                $arr = [];

                                $pros = $get('Product list');

                                foreach ($pros as $pro) {
                                    $i = 0;

                                    foreach ($pro as $value) {
                                        if ($i == 9) {
                                            $Total_price_item = $value;
                                        }
                                        if ($i == 8) {
                                            $discount_price = $value;
                                        }
                                        if ($i == 7) {
                                            $discount_currency = $value;
                                        }
                                        if ($i == 6) {
                                            $price = $value;
                                        }
                                        if ($i == 5) {
                                            $qty = $value;
                                        }
                                        if ($i == 4) {
                                            $unit = $value;
                                        }
                                        if ($i == 3) {
                                            $vat = $value;
                                        }
                                        if ($i == 2) {
                                            $code = $value;
                                        }
                                        if ($i == 1) {
                                            $description = $value;
                                        }
                                        if ($i == 0) {
                                            $name = $value;
                                        }
                                        $i++;
                                    }

                                    array_push($arr,
                                        [
                                            'name' => $name,
                                            'description' => $description,
                                            'code' => $code,
                                            'vat' => $vat,
                                            'unit' => $unit,
                                            'qty' => $qty,
                                            'price_unit' => $price,
                                            'discount_currency' => $discount_currency,
                                            'discount_price' => $discount_price,
                                            'Total_price_item' => $Total_price_item,
                                        ]
                                    );
                                }
                                if ($get('product')) {
                                    $item = Product::where('id', $get('product'));
                                    array_push($arr,
                                        [
                                            'name' => $item->pluck('name')[0],
                                            'description' => $item->pluck('description')[0],
                                            'code' => $item->pluck('code')[0],
                                            'vat' => $item->pluck('tax')[0],
                                            'unit' => $item->pluck('unit')[0],
                                            'qty' => 1,
                                            'price_unit' => $item->pluck('price')[0],
                                            'discount_currency' => $item->pluck('currency')[0],
                                            'discount_price' => 0,
                                            'Total_price_item' => $get('currency') ? $get('currency') : '$',
                                        ]
                                    );
                                    $set('Product list', $arr);
                                }
                            }),

                        Repeater::make('Product list')
                            ->schema([
                                TextInput::make('name')
                                    ->columnSpan(12)
                                    ->label('Product Name'),

                                Section::make('More Info')
                                    ->schema([
                                        MarkdownEditor::make('description')
                                            ->toolbarButtons([
                                                'bold',
                                                'bulletList',
                                                'orderedList',
                                                'edit',
                                                'preview',
                                            ])
                                            ->reactive()
                                            ->columnSpan(12),
                                    ])
                                    ->compact()
                                    ->collapsed()
                                    ->collapsible(),

                                TextInput::make('code')
                                    ->columnSpan(3)
                                    ->label('Supplier Code'),
                                TextInput::make('vat')
                                    ->columnSpan(2)
                                    ->required()
                                    ->numeric()
                                    ->default(function (Closure $get) {
                                        return $get('../../Tax');
                                    })
                                    ->label('Vat %'),
                                TextInput::make('unit')
                                    ->columnSpan(2)
                                    ->label('Unit'),
                                TextInput::make('qty')
                                    ->columnSpan(2)
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->label('Quantity'),
                                TextInput::make('price_unit')
                                    ->columnSpan(3)
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->label('Price'),

                                Select::make('discount_currency')
                                    ->label('Discount Currency')
                                    ->options(function (Closure $get) {
                                        if ($get('Total_price_item')) {
                                            return ['%' => '%', $get('Total_price_item') => $get('Total_price_item')];
                                        }

                                        return ['%' => '%', '€' => '€', '$' => '$', '£' => '£', '¥' => '¥'];
                                    })
                                    ->reactive()
                                    ->columnSpan(3)
                                    ->afterStateUpdated(function (Closure $set, Closure $get) {
                                        if (! $get('discount_currency')) {
                                            $set('discount_price', 0);
                                        }
                                    }),
                                TextInput::make('discount_price')
                                    ->label('Discount Value')
                                    ->numeric()
                                    ->columnSpan(3)
                                    ->disabled(function (Closure $get) {
                                        return $get('discount_currency') ? false : true;
                                    }),
                                Placeholder::make('Total_price_item')
                                    ->label('Total Price Item: ')
                                    ->content(function (Closure $get) {
                                        $unit = $get('price_unit');
                                        if ($get('discount_currency') && $get('discount_price')) {
                                            if ($get('discount_currency') == '%') {
                                                $unit = $unit * (1 - $get('discount_price') / 100);
                                            } else {
                                                $unit = $unit - $get('discount_price');
                                            }
                                        }
                                        $unit = $unit * (1 + $get('vat') / 100);
                                        $sum = $unit * $get('qty');

                                        return new HtmlString('<b>'.$get('Total_price_item').' '.$sum.'</b>');
                                    })
                                    ->columnSpan(6),

                            ])
                            ->collapsible()
                            ->cloneable()
                            ->orderable()
                            ->defaultItems(0)
                            ->createItemButtonLabel('Add Item')
                            ->columns(12)
                            ->reactive(),
                    ])->columnSpan(3),

                    Placeholder::make('Total Order')
                        ->content(function (Closure $get) {
                            $items = $get('Product list');
                            $priceSum = 0;
                            $vatSum = 0;
                            foreach ($items as $item) {
                                $i = 0;
                                $price = 0;
                                $vat = 0;
                                $qty = 0;
                                $discount_currency = '';
                                $discount_price = 0;

                                foreach ($item as $value) {
                                    if ($i == 8) {
                                        $discount_price = $value;
                                    }
                                    if ($i == 7) {
                                        $discount_currency = $value;
                                    }
                                    if ($i == 6) {
                                        $price = $value;
                                    }
                                    if ($i == 5) {
                                        $qty = $value;
                                    }
                                    if ($i == 3) {
                                        $vat = $value;
                                    }
                                    $i++;
                                }

                                $unit = $price;
                                if ($discount_currency && $discount_price) {
                                    if ($discount_currency == '%') {
                                        $unit = $unit * (1 - $discount_price / 100);
                                    } else {
                                        $unit = $unit - $discount_price;
                                    }
                                }
                                $priceSum += $unit * $qty;
                                $vatSum += $unit * ($vat / 100) * $qty;
                            }

                            return new HtmlString('
                            <style>
                                #total {
                                    width: 100%;
                                }
                                @media (min-width: 1024px) {
                                    #total {
                                        position: fixed;
                                        width: 215px !important;
                                    }
                                }
                            </style>
                            <div class="rounded-xl p-6 bg-white border border-gray-300" id="total">
                            <table border="1" class="filament-tables-table table-auto w-full">
                            <tr><td>Sub Total</td><td style="float:right">'.$priceSum.' '.$get('currency').'</td></tr>
                            <tr><td>Vat</td><td style="float:right">'.$vatSum.' '.$get('currency').'</td></tr>
                            <tr><td colspan="2"><hr style="margin:10px" /></td></tr>
                            <tr><td><b>Total</b></td><td style="float:right">'.$priceSum + $vatSum.' '.$get('currency').'</td></tr>
                            </table></div>');
                        })->columnSpan(1),

                ])->columns(4),

            Step::make('Delivery & Payment')
                ->description('Details Delivery and Payment')
                ->icon('heroicon-o-shopping-bag')
                ->schema([
                    Card::make([

                        Select::make('address_id')
                            ->label('Delivery Address')
                            ->options(Address::where('addressable_type', 'App\Models\Company')
                                ->where('collection_name', 'Warehouse-Address')
                                ->orderBy('name', 'ASC')
                                ->pluck('name', 'id'))
                            ->searchable(),
                        Select::make('contact_id')
                            ->label('Delivery Contact')
                            ->options(Contact::where('contactable_type', 'App\Models\Company')
                                ->orderBy('name', 'ASC')
                                ->pluck('name', 'id'))
                            ->searchable(),

                        // Select::make('address')
                        //         ->label('Address')
                        //         ->options(function (Closure $get) {
                        //             if ($get('company_id')) {
                        //                 $addressField = Address::where('addressable_id', $get('company_id'));
                        //                 if ($addressField) {
                        //                     $arr = [];
                        //                     for ($i = 0; $i < count($addressField->pluck('name')); $i++) {
                        //                         $address = $addressField->pluck('name')[$i].' '.$addressField->pluck('address')[$i].' '.$addressField->pluck('street_number')[$i].' '.$addressField->pluck('zip')[$i].' '.$addressField->pluck('city')[$i].' '.$addressField->pluck('province')[$i].' '.$addressField->pluck('state')[$i];
                        //                         if ($address) {
                        //                             $arr = array_merge($arr, [$address => $address]);
                        //                         }
                        //                     }
                        //                     return $arr;
                        //                 }
                        //             }
                        //         })
                        //         ->searchable(),

                        // Select::make('contact')
                        //     ->label('Contact')
                        //     ->options(function (Closure $get) {
                        //         if ($get('company_id')) {
                        //             $contactField = Contact::where('contactable_id', $get('company_id'));
                        //             if ($contactField) {
                        //                 $arr = [];
                        //                 for ($i = 0; $i < count($contactField->pluck('name')); $i++) {
                        //                     $contact = $contactField->pluck('name')[$i].' '.$contactField->pluck('address')[$i];
                        //                     if ($contact) {
                        //                         $arr = array_merge($arr, [$contact => $contact]);
                        //                     }
                        //                 }
                        //                 return $arr;
                        //             }
                        //         }
                        //     })
                        //     ->searchable(),

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
                                ->options(Category::where('collection_name', 'Warehouse-Transport')->pluck('name', 'id'))
                            // ->searchable()
                            ,
                            Textarea::make('trasport_note')->label('Trasport Note'),
                        ]),

                        Textarea::make('notes')->label('Order notes'),

                        Select::make('status')
                            ->label('Status')
                            // ->options(Category::where('collection_name', 'Status')->pluck('name', 'name'))
                            ->options(['Create', 'Draft'])
                            ->default('Create')
                            ->searchable(),
                    ])->columns(2),
                ]),

            Step::make('Summary')
                ->description('Report')
                ->icon('heroicon-o-shopping-bag')
                ->schema([
                    Card::make([
                        Textarea::make('report')->label('Order report'),
                    ])->columns(4),
                ]),
        ];
    }
}
