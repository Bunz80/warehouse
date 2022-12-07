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
                                    $set('summary_number', $num[0] + 1);
                                } else {
                                    $set('number', 1);
                                    $set('summary_number', 1);
                                }
                            }
                            $company = Company::where("id", $get('company_id'));
                            if ($company) {
                                $set("summary_company", $company->pluck('name')[0]);
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
                                    $tax = $supplier->pluck('default_tax_rate')[0];
                                    $currency = $supplier->pluck('default_currency')[0];
                                    $defaultPayment = $supplier->pluck('default_payment')[0];
                                    $set('Tax', $tax);
                                    $set('currency', $currency);
                                    $set('default_payment', $defaultPayment);
                                    $set('summary_currency', $supplier->pluck('default_currency')[0]);
                                    $set('summary_supplier', $supplier->pluck('name')[0]);
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
                    Hidden::make('default_payment'),
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
                            $set("summary_date_order", $get('order_at'));
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
                                            'discount_price' => $discount_price
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
                                        ]
                                    );
                                    $set('Product list', $arr);
                                }
                            }),

                        Repeater::make('Product list')
                            ->schema([
                                TextInput::make('name')
                                    ->columnSpan(12)
                                    ->label('Product Name')
                                    ->default(""),

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
                                            ->default("")
                                            ->columnSpan(12),
                                    ])
                                    ->compact()
                                    ->collapsed()
                                    ->collapsible(),

                                TextInput::make('code')
                                    ->columnSpan(3)
                                    ->default("")
                                    ->label('Supplier Code'),
                                TextInput::make('vat')
                                    ->columnSpan(2)
                                    ->required()
                                    ->numeric()
                                    ->default(function (Closure $get) {
                                        if ($get('../../Tax')) {
                                            return $get('../../Tax');
                                        }
                                        return 0;
                                    })
                                    ->label('Vat %'),
                                TextInput::make('unit')
                                    ->columnSpan(2)
                                    ->default("")
                                    ->label('Unit'),
                                TextInput::make('qty')
                                    ->columnSpan(2)
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->default(0)
                                    ->label('Quantity'),
                                TextInput::make('price_unit')
                                    ->columnSpan(3)
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->default(0)
                                    ->label('Price'),

                                Select::make('discount_currency')
                                    ->label('Discount Currency')
                                    ->options(function (Closure $get) {
                                        if ($get('../../currency')) {
                                            return ['%' => '%', $get('../../currency') => $get('../../currency')];
                                        }

                                        return ['%' => '%', '€' => '€', '$' => '$', '£' => '£', '¥' => '¥'];
                                    })
                                    ->reactive()
                                    ->columnSpan(3)
                                    ->default("%")
                                    ->afterStateUpdated(function (Closure $set, Closure $get) {
                                        if (! $get('discount_currency')) {
                                            $set('discount_price', 0);
                                        }
                                    }),
                                TextInput::make('discount_price')
                                    ->label('Discount Value')
                                    ->numeric()
                                    ->columnSpan(3)
                                    ->default(0)
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
                                        $unit = $unit * (1 + (float) $get('vat') / 100);
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
                        ->content(function (Closure $get, Closure $set) {
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

                            $set('total_prices', $priceSum);
                            $set('summary_total_price', $priceSum);
                            $set('total_taxes', $vatSum);
                            $set('summary_total_tax', $vatSum);
                            $set('total_order', $priceSum + $vatSum);

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

                    Hidden::make('total_prices'),
                    Hidden::make('total_taxes'),
                    Hidden::make('total_order'),

                ])->columns(4),

            Step::make('Delivery & Payment')
                ->description('Details Delivery and Payment')
                ->icon('heroicon-o-shopping-bag')
                ->schema([
                    Card::make([
                        Select::make('address_id')
                            ->label('Delivery Address')
                            ->options(function() {
                                $addressField = Address::whereRaw('addressable_type = "App\Models\Company" and collection_name = "Delivery"')->orderBy('name', 'ASC');
                                if ($addressField) {
                                    $arr = [];
                                    for ($i = 0; $i < count($addressField->pluck('id')); $i++) {
                                        $address = $addressField->pluck('name')[$i].' ('.$addressField->pluck('address')[$i].' '.$addressField->pluck('street_number')[$i].' '.$addressField->pluck('zip')[$i].' '.$addressField->pluck('city')[$i].' '.$addressField->pluck('province')[$i].' '.$addressField->pluck('state')[$i].')';
                                        if ($address) {
                                            $index = $addressField->pluck('id')[$i].'.00';
                                            $arr = array_merge($arr, [$index => $address]);
                                        }
                                    }
                                    return $arr;
                                }
                            })
                            ->afterStateUpdated(function (Closure $set, Closure $get) {
                                    $address = Address::where("id", (int) $get('address_id'));
                                    if ($address) {
                                        $val = $address->pluck('name')[0].' ('.$address->pluck('address')[0].' '.$address->pluck('street_number')[0].' '.$address->pluck('zip')[0].' '.$address->pluck('city')[0].' '.$address->pluck('province')[0].' '.$address->pluck('state')[0].')';
                                        if ($val) {
                                            $set("summary_address", $val);
                                        }
                                    }
                            })
                            ->searchable(),
                        // Echo Address
                        Select::make('contact_id')
                            ->label('Delivery Contact')
                            ->options(function() {
                                $contacts = Contact::where('contactable_type', 'App\Models\Company')->orderBy('name', 'ASC');
                                if ($contacts) {
                                    $arr = [];
                                    for ($i=0; $i < count($contacts->pluck('id')); $i++) {
                                        $contact = $contacts->pluck('name')[$i].' ('.$contacts->pluck('collection_name')[$i].' '.$contacts->pluck('address')[$i].')';
                                        if ($contact) {
                                            $index = $contacts->pluck('id')[$i].'.00';
                                            $arr = array_merge($arr, [$index => $contact]);
                                        }
                                    }
                                    return $arr;
                                }
                            })
                            ->afterStateUpdated(function (Closure $set, Closure $get) {
                                $contact = Contact::where("id", (int) $get('contact_id'));
                                if ($contact) {
                                    $val = $contact->pluck('name')[0].' ('.$contact->pluck('collection_name')[0].' '.$contact->pluck('address')[0].')';
                                    if ($val) {
                                        $set("summary_contact", $val);
                                    }
                                }
                            })
                            ->searchable(),
                    ])->columns(2),

                    Card::make([
                        Group::make([
                            Select::make('payment_method')
                                ->label('Payment Method')
                                ->options(Category::where('collection_name', 'Warehouse-Payment')->pluck('name', 'id'))
                                ->default(function(Closure $get) {
                                    return $get("default_payment");
                                })
                                ->afterStateUpdated(function (Closure $set, Closure $get) {
                                    $method = Category::where("id", $get('payment_method'));
                                    if ($method) {
                                        $val = $method->pluck('name')[0];
                                        if ($val) {
                                            $set("summary_payment_method", $val);
                                        }
                                    }
                                })
                                ->searchable(),
                            Textarea::make('payment_note')
                                ->label('Payment Note')
                                ->afterStateUpdated(function (Closure $set, Closure $get) {
                                    $set("summary_payment_note", $get('payment_note'));
                                }),
                        ]),

                        Group::make([
                            Select::make('trasport_method')
                                ->label('Trasport Method')
                                ->options(Category::where('collection_name', 'Warehouse-Transport')->pluck('name', 'id'))
                                ->afterStateUpdated(function (Closure $set, Closure $get) {
                                    $method = Category::where("id", $get('trasport_method'));
                                    if ($method) {
                                        $val = $method->pluck('name')[0];
                                        if ($val) {
                                            $set("summary_trasport_method", $val);
                                        }
                                    }
                                })
                            // ->searchable()
                            ,
                            Textarea::make('trasport_note')->label('Trasport Note')
                                ->afterStateUpdated(function (Closure $set, Closure $get) {
                                    $set("summary_trasport_note", $get('trasport_note'));
                                }),
                        ]),

                        Textarea::make('notes')->label('Order notes')
                            ->afterStateUpdated(function (Closure $set, Closure $get) {
                                $set("summary_note", $get('notes'));
                            }),

                        Group::make([
                            Select::make('status')
                                ->label('Status')
                                // ->options(Category::where('collection_name', 'Status')->pluck('name', 'name'))
                                ->options(['Create', 'Draft'])
                                ->default('Create')
                                ->afterStateUpdated(function (Closure $set, Closure $get) {
                                    $set("summary_status", $get('status'));
                                })
                                ->searchable(),

                            DatePicker::make('deadline_at')
                                ->label('Deadline')
                                ->afterStateUpdated(function (Closure $set, Closure $get) {
                                    $set("summary_deadline", $get('deadline_at'));
                                })
                                ->displayFormat('d/m/Y'),
                        ]),

                    ])->columns(2),
                ]),

            Step::make('Summary')
                ->description('Report')
                ->icon('heroicon-o-shopping-bag')
                ->schema([
                    Card::make([
                        Textarea::make('report')->label('Order report'),
                    ])->columns(4),
                    Card::make([
                        Placeholder::make('summary_company')
                            ->label('Company: ')
                            ->content(function (Closure $get) {
                                return new HtmlString('<b>'.$get("summary_company").'</b>');
                            })
                            ->columnSpan(1),
                        Placeholder::make('summary_supplier')
                            ->label('Supplier: ')
                            ->content(function (Closure $get) {
                                return new HtmlString('<b>'.$get("summary_supplier").'</b>');
                            })
                            ->columnSpan(1),
                        Placeholder::make('summary_number')
                            ->label('Order number: ')
                            ->content(function (Closure $get) {
                                return new HtmlString('<b>'.$get("summary_number").'</b>');
                            })
                            ->columnSpan(1),
                        Placeholder::make('summary_date_order')
                            ->label('Date order: ')
                            ->content(function (Closure $get) {
                                $value = $get("summary_date_order") ? $get("summary_date_order") : Carbon::now();
                                return new HtmlString('<b>'.$value.'</b>');
                            })
                            ->columnSpan(1),
                        Placeholder::make('summary_deadline')
                            ->label('Order deadline: ')
                            ->content(function (Closure $get) {
                                return new HtmlString('<b>'.$get("summary_deadline").'</b>');
                            })
                            ->columnSpan(1),
                        Placeholder::make('summary_status')
                            ->label('Order status: ')
                            ->content(function (Closure $get) {
                                $value = $get("summary_status") ? $get("summary_status") : 'Create';
                                return new HtmlString('<b>'.$value.'</b>');
                            })
                            ->columnSpan(1),
                        Placeholder::make('summary_total_price')
                            ->label('Total price: ')
                            ->content(function (Closure $get) {
                                return new HtmlString('<b>'.$get("summary_total_price").'</b>');
                            })
                            ->columnSpan(1),
                        Placeholder::make('summary_total_tax')
                            ->label('Total tax: ')
                            ->content(function (Closure $get) {
                                return new HtmlString('<b>'.$get("summary_total_tax").'</b>');
                            })
                            ->columnSpan(1),
                        Placeholder::make('summary_address')
                            ->label('Address: ')
                            ->content(function (Closure $get) {
                                return new HtmlString('<b>'.$get("summary_address").'</b>');
                            })
                            ->columnSpan(1),
                        Placeholder::make('summary_contact')
                            ->label('Contact: ')
                            ->content(function (Closure $get) {
                                return new HtmlString('<b>'.$get("summary_contact").'</b>');
                            })
                            ->columnSpan(1),
                        Placeholder::make('summary_delivery_method')
                            ->label('Delivery method: ')
                            ->content(function (Closure $get) {
                                return new HtmlString('<b>'.$get("summary_delivery_method").'</b>');
                            })
                            ->columnSpan(1),
                        Placeholder::make('summary_delivery_note')
                            ->label('Delivery note: ')
                            ->content(function (Closure $get) {
                                return new HtmlString('<b>'.$get("summary_delivery_note").'</b>');
                            })
                            ->columnSpan(1),
                        Placeholder::make('summary_payment_method')
                            ->label('Payment method: ')
                            ->content(function (Closure $get) {
                                return new HtmlString('<b>'.$get("summary_payment_method").'</b>');
                            })
                            ->columnSpan(1),
                        Placeholder::make('summary_payment_note')
                            ->label('Payment note: ')
                            ->content(function (Closure $get) {
                                return new HtmlString('<b>'.$get("summary_payment_note").'</b>');
                            })
                            ->columnSpan(1),
                        Placeholder::make('summary_trasport_method')
                            ->label('Transport method: ')
                            ->content(function (Closure $get) {
                                return new HtmlString('<b>'.$get("summary_trasport_method").'</b>');
                            })
                            ->columnSpan(1),
                        Placeholder::make('summary_transport_note')
                            ->label('Transport note: ')
                            ->content(function (Closure $get) {
                                return new HtmlString('<b>'.$get("summary_trasport_note").'</b>');
                            })
                            ->columnSpan(1),
                        Placeholder::make('summary_note')
                            ->label('Note: ')
                            ->content(function (Closure $get) {
                                return new HtmlString('<b>'.$get("summary_note").'</b>');
                            })
                            ->columnSpan(1),
                        Placeholder::make('summary_products')
                            ->label('Products: ')
                            ->content(function (Closure $get) {

                                $items = $get('Product list');
                                $str = "";

                                foreach ($items as $item) {
                                    $i = 0;
                                    $price = 0;
                                    $vat = 0;
                                    $qty = 0;
                                    $discount_currency = '';
                                    $discount_price = 0;
                                    $unit = '';
                                    $code = '';
                                    $description = '';
                                    $name = '';
                                    $total = 0;

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
                                    $punit = $price;
                                    if ($discount_currency && $discount_price) {
                                        if ($discount_currency == '%') {
                                            $punit = $punit * (1 - $discount_price / 100);
                                        } else {
                                            $punit = $punit - $discount_price;
                                        }
                                    }
                                    $total = $punit * (1 + $vat / 100) * $qty;

                                    $str .= '<tr><td>'.$name.'</td><td></td><td>'.$code.'</td><td>'.$description.'</td><td>'.$get("currency").'</td><td>'.$unit.'</td><td>'.$vat.'</td><td>'.$qty.'</td><td>'.$price.'</td><td>'.$discount_currency.'</td><td>'.$discount_price.'</td><td>'.$total.'</td></tr>';

                                }
                                return new HtmlString('
                                    <style>
                                        td {
                                            padding: 3px 5px;
                                            text-align: center;
                                        }
                                    </style>
                                    <div class="border border-gray-300 p-6 rounded-xl">
                                        <table border="1" class="table w-full">
                                            <tr style="font-weight: bold;border-bottom: 1px solid"><td>Name</td><td>Brand</td><td>Code</td><td>Description</td><td>Currency</td><td>Unit</td><td>Tax</td><td>QTY</td><td>Price unit</td><td>Discount currency</td><td>Discount price</td><td>Total Price</td></tr>
                                            '.$str.'
                                        </table>
                                    </div>
                                ');
                            })
                            ->columnSpan(2),
                    ])->columns(2),
                ])
        ];
    }
}
