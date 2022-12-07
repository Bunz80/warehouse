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
use App\Models\Warehouse\OrderDetail;
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
                        Placeholder::make('Print Document')
                            ->content(
                                function (Closure $get) {
                                    $company = Company::where('id', $get('company_id'));
                                    $company_name = $company ? $company->pluck('name')[0] : '';

                                    $address = Address::where('id', $get('address_id'));
                                    $address_name = $address ? $address->pluck('name')[0] : '';
                                    $address_street = $address ? $address->pluck('address')[0].'-'.$address->pluck('street_number')[0].', '.$address->pluck('city')[0] : '';
                                    $address_state = $address ? $address->pluck('province')[0].'/'.$address->pluck('state')[0] : '';

                                    $supplier = Supplier::where('id', $get('supplier_id'));
                                    $supplier_name = $supplier ? $supplier->pluck('name')[0] : '';

                                    $orderDetail = OrderDetail::where('order_id', $get('id'));
                                    $totalPrice = 0;
                                    $totalVat = 0;

                                    $tablestr = '<tr style=\'font-weight: bold\'><td>ID</td><td>COD</td><td>Descrizione</td><td>Qnt</td><td>Prezzo</td><td>Sconto</td><td>Totale</td></tr>';
                                    if ($orderDetail) {
                                        for ($i = 0; $i < count($orderDetail->pluck('id')); $i++) {
                                            $unit = (float) $orderDetail->pluck('price_unit')[$i];
                                            $discount_currency = (float) $orderDetail->pluck('discount_currency')[$i];
                                            $discount_price = (float) $orderDetail->pluck('discount_price')[$i];
                                            $tax = (float) $orderDetail->pluck('tax')[$i];
                                            if ($discount_currency && $discount_price) {
                                                if ($discount_currency == '%') {
                                                    $unit = $unit * (1 - $discount_price / 100);
                                                } else {
                                                    $unit = $unit - $discount_price;
                                                }
                                            }
                                            $sum = $unit * $orderDetail->pluck('quantity')[$i];
                                            $totalPrice += $sum;
                                            $totalVat += $unit * ($tax / 100) * $orderDetail->pluck('quantity')[$i];
                                            $tablestr .= '<tr><td>'.$orderDetail->pluck('id')[$i].'</td><td>'.$orderDetail->pluck('code')[$i].'</td><td>'.$orderDetail->pluck('description')[$i].'</td><td>'.round($orderDetail->pluck('quantity')[$i]).'</td><td>'.$orderDetail->pluck('price_unit')[$i].'</td><td>'.$orderDetail->pluck('discount_price')[$i].'</td><td>'.$sum.'</td></tr>';
                                        }
                                    }

                                    return new HtmlString('
                                            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
                                            <script>
                                                var printContents = 
                                                    "<style>td{padding: 5px 15px}</style>"+
                                                    "<div id=\"printcontent\" style=\"padding-right: 30px; padding-left: 30px;margin: 0;\">"+ 
                                                        "<div class=\"grid grid-cols-3\" style=\"border-bottom:1px solid #c3c3c3;padding:20px\">"+
                                                            "<div class=\"text-left\">"+
                                                                "<img src=\"../../../../images/logo.png\" style=\"width: 120px;\">"+
                                                            "</div>"+
                                                            "<div class=\"text-center\" style=\"font-size: 20px\">"+
                                                                "<h1>'.$company_name.'</h1> "+
                                                            "</div>"+
                                                            "<div class=\"text-right\">"+
                                                                "<h3 style=\"font-size: 15px\">Ordine nr: '.$get('year').'.'.$get('number').'</h3>"+
                                                            "</div>"+
                                                        "</div>"+
                                                        "<div style=\"border-bottom:1px solid #c3c3c3;padding:20px;height: 200px\">"+
                                                            "<div class=\"text-left\" style=\"font-size: 15px;width: 170px;float:left;\">"+
                                                                "<h3>Destinazione</h3>"+
                                                                "<div style=\"font-weight:bold\">'.$address_name.'</div>"+
                                                                "<div>'.$address_street.'</div>"+
                                                                "<div>'.$address_state.'</div>"+
                                                            "</div>"+
                                                            "<div class=\"text-right\" style=\"font-size: 15px;width: 170px;float:right;\">"+
                                                                "<h3>Fornitore</h3>"+
                                                                "<div style=\"font-weight:bold\">'.$supplier_name.'</div>"+
                                                            "</div>"+
                                                        "</div>"+
                                                        "<h3 style=\"font-size: 15pxfont-weight: bold;margin-top: 20px\">Lista Prodotti</h3>"+
                                                        "<table style=\"font-size: 15px; font-weight: normal;border-bottom: 2px solid;\">'.$tablestr.'</table>"+
                                                        "<div class=\"grid grid-cols-1\" style=\"font-size: 20px; font-weight: normal\">"+
                                                            "<div style=\"text-align: right\">Totale imponibile<span style=\"width: 140px;display: inline-block\">'.round($totalPrice, 2).'</span></div>"+
                                                            "<div style=\"text-align: right\">Totale iva<span style=\"width: 140px;display: inline-block\">'.round($totalVat, 2).'</span></div>"+
                                                            "<div style=\"text-align: right;font-weight: bold;\">Totale Ordine<span style=\"width: 140px;display: inline-block;\">'.round($totalPrice + $totalVat, 2).'</span></div>"+
                                                        "</div>"+
                                                        "<div class=\"grid grid-cols-3\" style=\"padding:20px\">"+
                                                            "<div style=\"padding: 20px\">"+
                                                                "<div>Pagamento:</div>"+
                                                                "<div>'.$get('payment_note').'</div>"+
                                                            "</div>"+
                                                            "<div style=\"padding: 20px\">"+
                                                                "<div>Trasporto:</div>"+
                                                                "<div>'.$get('trasport_note').'</div>"+
                                                            "</div>"+
                                                            "<div style=\"padding: 20px\">"+
                                                                "<div>Note:</div>"+
                                                                "<div>'.$get('notes').'</div>"+
                                                            "</div>"+
                                                        "</div>"+
                                                        "<div style=\"font-size:15px;font-weight:bold; padding-left: 20px\">Info e condizioni:</div>"+
                                                        "<ul style=\"font-size: 10px; padding: 20px 50px;list-style:inside;\">"+
                                                            "<li>Ogni deroga alle condizioni generali e particolari indicate in ordine sarà valida soltanto se accettate per iscritto dall’Acquirente.</li>"+
                                                            "<li>L’ordine si intende perfezionato con il ricevimento da parte dell’acquirente della conferma integrale dello stesso, che dovrà pervenirci entro il 10° giorno dalla data dell’ordine, sottoscritta dal Fornitore per accettazione. Al riguardo si fa rinvio alle disposizioni di cui all’Art.1326 del Codice Civile.</li>"+
                                                            "<li>L’ordine integralmente accettato produce gli effetti previsti dalla legge.</li>"+
                                                            "<li>I termini di consegna s’intendono essenziali: il mancato rispetto degli stessi oltre a legittimare la richiesta di risarcimento ai sensi degli articoli 1218 e 1223 C.C. dà il diritto all’Acquirente di risolvere il contratto per inadempimento.</li>"+
                                                            "<li>Nelle spedizioni eseguite con qualsiasi mezzo di trasporto il Fornitore, o chi per lui, dovrà tempestivamente comunicare gli estremi di spedizione, il numero e la data dell’ordine.</li>"+
                                                            "<li>Le consegne eseguite non in accordo ai termini fissati saranno considerate, ai fini del pagamento, come avvenute nei termini più favorevoli all’Acquirente.</li>"+
                                                            "<li>Normalmente, se non sia stato convenuto altrimenti nella ordinazione, il costo dell’imballaggio non viene riconosciuto intendendosi compreso nel prezzo della merce. Come pure, a meno che sia stato convenuto differentemente nell’ordinazione, il prezzo pattuito si intende riferito al peso netto della merce escluso imballo. Avarie o dispersione della merce causate da imballaggio inidoneo o difettoso sono a carico del Fornitore, il quale è tenuto a provvedere all’imballaggio nel modo più conveniente ed economico.</li>"+
                                                            "<li>Le forniture debbono essere spedite all’indirizzo indicato dall’Acquirente e con le modalità riportate nell’ordine. Ogni maggiore onere derivante da inosservanza di questa modalità, sarà addebitato al fornitore.</li>"+
                                                            "<li>Le merci debbono esser accompagnate da una bolla di consegna sulla quale oltre alla descrizione della fornitura ed il numero di specifica dell’Acquirente, deve essere indicato il numero dell’ordine di acquisto.</li>"+
                                                            "<li>Ove richiesto, le merci debbono essere accompagnate dal Certificato di Conformità in due esemplari.</li>"+
                                                            "<li>Entro i termini e con le modalità previste dalle vigenti disposizioni, deve essere inviata all’Acquirente regolare fattura, indicante gli estremi della bolla di consegna e dell’ordine di acquisto.</li>"+
                                                            "<li>Le fatture non rispondenti alle norme di legge vigenti saranno restituite per le necessarie rettifiche e dovranno pervenire regolarizzate entro i termini previsti dal D.P.R. 26/10/72 n. 633. In caso contrario verrà emessa autofattura ai sensi dell’art. 41 del D.P.R. citato.</li>"+
                                                            "<li>Le merci contrassegnate all’Acquirente si intendono consegnate in deposito e custodia fino ad avvenuto controllo e conseguente accettazione</li>"+
                                                            "<li>Il controllo qualitativo e quantitativo delle merci sarà effettuato dall’Acquirente, salvo casi particolari e previamente indicati nell’ordine. L’acquirente notificherà eventuali scarti entro 60 gg. dalla presa in consegna della merce.</li>"+
                                                            "<li>Il fornitore s’impegna ad accettare tutti i controlli che l’acquirente effettuerà sulle forniture al fine di stabilire la loro rispondenza alle caratteristiche previste dall’ordine.</li>"+
                                                            "<li>Le merci non accettate in seguito al controllo da parte dell’Acquirente saranno rispedite al Fornitore in porto assegnato. Le relative fatture potranno essere tenute in sospeso fino al reintegro oppure liquidate, a discrezione dell’Acquirente, per la quota parte accettata.</li>"+
                                                            "<li>Il Fornitore garantisce le proprie merci: in particolare che siano di ottima qualità, esenti da difetti, palesi e occulti, che rispondano a tutti i requisiti indicati nell’offerta o nell’ordine confermato.</li>"+
                                                            "<li>Con l’accettazione del presente ordine, si rinuncia espressamente al beneficio di cui D.Lgs 231/2002 riservandosi la facoltà di procedere, in caso di inadempimento, con specifica messa in mora.</li>"+
                                                            "<li>E’ tassativamente escluso il pagamento mediante cessione del credito (art. 1260 C.C.) nonché con procura irrevocabile (art. 1723 C.C.).</li>"+
                                                        "</ul>"+
                                                        "<div style=\"padding: 20px;border-bottom: 1px solid #c3c3c3;\">"+
                                                            "<b>Rispetta l’ambiente:</b> Non stampare questa pagina se non è necessario"+
                                                        "</div>"+
                                                        "<div style=\"padding: 20px;text-align:center;\">"+
                                                            "<b>Medmar Navi SPA:</b> Società soggetta a direzione e coordinamento della Mediterranea Marittima S.p.A. <b>Sede Legale e Uffici:</b> Via Alcide De Gasperi, 55 - 80133 Napoli Tel. 081.5801223 - Fax 081.5512770 - Capitale Sociale € 6,000,000.00 int. vers. Codice Fiscale, Partita IVA e numero iscrizione Registro delle Imprese di Napoli 05984260637 R.E.A. 473782 - <b>Call Center:</b> Tel. 081.3334411 -info@medmarnavi.it - www.medmarnavi.it"+
                                                        "</div>"+
                                                    "</div>";
                                                const viewPDF = () => {
                                                    var originalContents = document.body.innerHTML;
                                                    document.body.innerHTML = printContents;
                                                    window.print();
                                                    document.body.innerHTML = originalContents;
                                                    location.reload();
                                                }
                                                const generatePDF = () => {
                                                    var originalContents = document.body.innerHTML;
                                                    window.scrollTo(0, 0);
                                                    document.body.innerHTML = printContents;
                                                    const element = document.getElementById("printcontent");

                                                    html2pdf(element, {
                                                        margin:       1,
                                                        filename:     "order.pdf",
                                                        image:        { type: "jpeg", quality: 0.98 },
                                                        html2canvas:  { scale: 2 },
                                                        jsPDF:        { unit: "in", format: "letter", orientation: "portrait" }
                                                      })
                                                    setTimeout(function(){
                                                        document.body.innerHTML = originalContents;
                                                        location.reload();
                                                    }, 4000);
                                                  }
                                            </script>
                                            <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center" onclick="generatePDF()">
                                                <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/>
                                                </svg>
                                                <span>Print</span>
                                            </button>
                                        ');
                                })
                            ->columnSpan(2),

                        Placeholder::make('View Document')
                            ->content(new HtmlString('
                                <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center" onClick="viewPDF()">
                                    <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/>
                                    </svg>
                                    <span>View Document</span>
                                </button>'))
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
