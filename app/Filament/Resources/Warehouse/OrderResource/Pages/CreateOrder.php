<?php

namespace App\Filament\Resources\Warehouse\OrderResource\Pages;

use App\Filament\Resources\Warehouse\OrderResource;

use App\Models\Category;
use App\Models\Company;
use App\Models\Supplier;
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
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Illuminate\Support\HtmlString;
use Closure;

class CreateOrder extends CreateRecord
{
    use HasWizard;

    protected static string $resource = OrderResource::class;

    public $arr;

    function __construct() {
        $this->arr = [];
    } 

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
                        ->afterStateUpdated(function(Closure $set, Closure $get){
                            if ($get("company_id")) {
                                $company = Company::where("id", $get("company_id"));
                                if ($company) {
                                    $tax = $company->pluck('default_tax_rate')[0];
                                    $currency = $company->pluck('default_currency')[0];
                                    $set("Tax", $tax);
                                    $set("currency", $currency);
                                }
    
                                $year = date('Y', strtotime($get("order_at")));
                                $order = Order::whereRaw("company_id = ".$get("company_id")." and year = ".$year)->orderBy("number", "desc");
                                if ($order) {
                                    $num = $order->pluck('number');
                                    if (count($num) > 0) {
                                        $set("number", $num[0] + 1);
                                    } else {
                                        $set("number", 1);
                                    }
                                } 
                            }
                        }),
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
                        ->options(Category::where('collection_name', 'Currency')->pluck('name', 'name')),
                    TextInput::make('Tax')
                        ->label('Tax Default'),
                    Hidden::make('number')
                        ->label('Order number')
                        ->default(1),
                    Hidden::make('year')
                        ->label('Year')
                        ->default(function(){
                            $year = Carbon::now()->format("Y");
                            return $year;
                        }),
                    DatePicker::make('order_at')
                        ->label('Order\'s date')
                        ->default(Carbon::now())
                        ->displayFormat('d/m/Y')
                        ->reactive()
                        ->afterStateUpdated(function(Closure $set, Closure $get){
                            $year = date('Y', strtotime($get("order_at")));
                            $set("year", $year);
                            $companyId = $get("company_id");
                            if ($companyId) {
                                $order = Order::whereRaw("company_id = ".$companyId." and year = ".$year)->orderBy("number", "desc");
                                if ($order) {
                                    $num = $order->pluck('number');
                                    if (count($num) > 0) {
                                        $set("number", $num[0] + 1);
                                    } else {
                                        $set("number", 1);
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
                            ->afterStateUpdated(function(Closure $set, Closure $get){
                                if ($get("product")) {
                                    $item = Product::where("id", $get("product"));
                                    array_push($this->arr, 
                                        [
                                            'name' => $item->pluck('name')[0], 
                                            'description' => $item->pluck('description')[0],
                                            'code' => $item->pluck('code')[0],
                                            'vat' => $item->pluck('tax')[0],
                                            'unit' => $item->pluck('unit')[0],
                                            'qty' => 1,
                                            'price_unit' => $item->pluck('price')[0],
                                            'discount_currency' => $item->pluck('currency')[0]
                                        ]
                                    );
                                }
                                $set('Product list', $this->arr);
                            }),

                        Repeater::make('Product list')
                            ->schema([
                                TextInput::make('name')
                                    ->columnSpan(12)
                                    ->label('Product Name'),
                                MarkdownEditor::make('description')
                                    ->toolbarButtons([
                                        'bold',
                                        'bulletList',
                                        'orderedList',
                                        'edit',
                                        'preview',
                                    ])
                                    ->reactive()
                                    ->required()
                                    ->columnSpan(12),
                                TextInput::make('code')
                                    ->columnSpan(3)
                                    ->label('Supplier Code'),
                                TextInput::make('vat')
                                    ->columnSpan(2)
                                    ->required()
                                    ->numeric()
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
                                    //->options(Category::where('collection_name', 'Warehouse-Payment')->pluck('name', 'id'))
                                    ->options(['%', '€', '$', '£', '¥'])
                                    ->columnSpan(3),
                                TextInput::make('discount_price')
                                    ->label('Discount Value')
                                    ->columnSpan(3)
                                    ->numeric(),
                                Placeholder::make('Total Price Item')
                                    ->label('Total Price Item: ')
                                    ->content(function (Closure $get) {
                                        $sum = (float)$get('price_unit') * (int)$get('qty');
                                        return new HtmlString('<b>€ '.$sum.'</b>');
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

                    Card::make([
                        Placeholder::make('Total Order')
                            ->content(function (Closure $get) {
                                $items = $get('Product list');
                                $priceSum = 0;
                                $vatSum = 0;
                                foreach($items as $item){
                                    $i = 0;
                                    $price = 0;
                                    $vat = 0;
                                    $qty = 0;
                                    foreach ($item as $value){
                                        if ($i == 6) {
                                           $price = $value; 
                                        };
                                        if ($i == 5) {
                                            $qty = $value; 
                                        };
                                        if ($i == 3) {
                                            $vat = $value; 
                                        };
                                        $i++;
                                    }
                                    $priceSum += $price * $qty;
                                    $vatSum += $vat;
                                }
                                return new HtmlString('
                                    <table border="1" class="filament-tables-table w-full table-auto">
                                    <tr><td>Sub Total</td><td style="float:right">'.$priceSum.' €</td></tr>
                                    <tr><td>Vat</td><td style="float:right">'.$vatSum.' €</td></tr>
                                    <tr><td colspan="2"><hr style="margin:10px" /></td></tr>
                                    <tr><td><b>Total</b></td><td style="float:right">'.$priceSum + $vatSum.' €</td></tr>
                                    </table>');
                            }),
                    ])->columnSpan(1),

                ])->columns(4),

            Step::make('Delivery & Payment')
                ->description('Details Delivery and Payment')
                ->icon('heroicon-o-shopping-bag')
                ->schema([
                    Card::make([
                        // Placeholder::make('')->content(new HtmlString('')),
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
                            Textarea::make('Trasport_note')->label('Trasport Note'),
                        ]),

                        // Placeholder::make('')->content(new HtmlString('<hr />'))->columnSpan(2),

                        Textarea::make('notes')->label('Order notes'),

                        Select::make('status')
                            ->label('Status')
                            ->options(Category::where('collection_name', 'Status')->pluck('name', 'name'))
                            ->default('New')
                            ->searchable(),

                    ])->columns(2),
                ]),

            Step::make('Summary')
                ->description('Report')
                ->icon('heroicon-o-shopping-bag')
                ->schema([
                    Card::make([
                        Textarea::make('report')->label('Order report'),
                    ])->columns(4)
                ]),
        ];
    }
}
