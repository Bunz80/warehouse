<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Category;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Supplier;
use App\Models\Warehouse\Order;
use App\Models\Warehouse\OrderDetail;
use PDF;

class OrderPrintController extends Controller
{
    //function pdf($id, $status)
    public function pdf($id)
    {
        $order = Order::join('companies', 'companies.id', '=', 'orders.company_id')
                        ->join('suppliers', 'suppliers.id', '=', 'orders.supplier_id')
                        //delivery
                        ->join('addresses', 'addresses.id', '=', 'orders.address_id')
                        ->join('contacts', 'contacts.id', '=', 'orders.contact_id')
                        //where id order
                        ->where('orders.id', '=', $id)
                        ->select('*',
                            'orders.id as order_id',
                            'orders.year as order_year',
                            'orders.number as order_num',
                            'orders.order_at as order_order_at',
                            'orders.close_at as order_close_at',
                            'orders.deadline_at as order_deadline_at',
                            'orders.status as order_status',
                            // Price
                            'orders.currency as order_currency',
                            'orders.total_taxes as order_total_taxes',
                            'orders.total_prices as order_total_prices',
                            'orders.total_order as order_total_order',
                            // Address & Concact for Delivery
                            'orders.address_id as delivery_address_id',
                            'orders.contact_id as delivery_contact_id',
                            // Notes
                            'orders.delivery_note as note_delivery',
                            'orders.trasport_note as note_trasport',
                            'orders.payment_note as note_payment',
                            'orders.notes as note',
                            // Method Delivery/Trasport/Payment
                            'orders.delivery_method as deliverymethod',
                            'orders.trasport_method as trasportmethod',
                            'orders.payment_method as paymentmethod',

                            'companies.id as company_id',
                            'companies.name as company_name',
                            'companies.logo as company_logo',
                            'companies.vat as company_vat',
                            'companies.pec as company_pec',
                            'companies.invoice_code as company_icode',
                            'companies.page_header as company_html_header',
                            'companies.page_footer as company_html_footer',
                            'companies.page_warehouse_info as company_html_wh_info',
                            'companies.page_warehouse_terms as company_html_wh_terms',

                            'suppliers.id as supplier_id',
                            'suppliers.name as supplier_name',
                            'suppliers.logo as supplier_logo',
                            'suppliers.vat as supplier_vat',
                            'suppliers.pec as supplier_pec',
                            'suppliers.invoice_code as supplier_icode',

                        )
                        ->first();

        // LOGO COMPANY
        $logoCompany = '';
        if ($order->company_logo != '') {
            $logoCompany = '<img src="'.$order->company_logo.'" style="max-width: 300px; max-height: 100px;">';
        } else {
            $logoCompany = '<h3 class="title">'.$order->company_name.'</h3>';
        }

        // LOGO SUPPLIER
        $logoSupplier = '';
        if ($order->supplier_logo != '') {
            $logoSupplier = '<img src="/uploads/logo/'.$order->supplier_logo.'" style="max-height: 90px; max-width:120px; float: right; padding: 5px 0 15px 15px; " >';
        }

        // ADDRESS COMPANY
        $company_address = Address::whereRaw('addressable_type LIKE "%Company" and collection_name = "Sede legale" and addressable_id='.$order->company_id)->first();
        $comapnyAddress = '';
        if ($company_address) {
            $comapnyAddress = $company_address->address.' - '.$company_address->zip.' '.$company_address->city;
        }
        // ADDRESS SUPPLIER
        $supplier_address = Address::whereRaw('addressable_type LIKE "%Supplier" and collection_name = "Sede legale" and addressable_id='.$order->supplier_id)->first();
        $supplierAddress = '';
        if ($supplier_address) {
            $supplierAddress = $supplier_address->address.' <br />'.$supplier_address->zip.' '.$supplier_address->city;
        }

        // ADDRESS DELIVERY
        $delivery_address = Address::where('id', $order->delivery_address_id)->first();
        $deliveryAddress = '';
        if ($delivery_address) {
            $deliveryAddress = $delivery_address->name.' <br />'.$delivery_address->address.' <br /> '.$delivery_address->zip.' '.$delivery_address->city;
        }

        // CONTACT DELIVERY
        $delivery_contact = Contact::where('id', $order->delivery_contact_id)->first();
        $deliveryContact = '';
        if ($delivery_contact) {
            $deliveryContact = 'Referente: '.$delivery_contact->name.' '.$delivery_contact->address;
        }

        // CATEGORY
        $deliveryCategory = $paymentCategory = $trasportCategory = '';

        $delivery_category = Category::where('id', $order->deliverymethod)->first();
        if ($delivery_category) {
            $deliveryCategory = $deliveryCategory->name;
        }

        $trasport_category = Category::where('id', $order->trasportmethod)->first();
        if ($trasport_category) {
            $trasportCategory = $trasport_category->name;
        }

        $payment_category = Category::where('id', $order->paymentmethod)->first();
        if ($payment_category) {
            $paymentCategory = $payment_category->name;
        }

        //Initialize variables
        $output = $style = $header = $destination = $footer_company = $table = $cont = '';

        // Table - OrderDetails
        $products = OrderDetail::where('order_id', '=', $id)->get();
        $table .= '
        <div class="row w100">
            <h3>Lista Prodotti</h3>
            <table class="table table-striped" style="width:100%">
                <thead>
                    <tr class="tr_clear">
                        <th>ID</th>
                        <th>Cod</th>
                        <th>Prodotto</th>
                        <th>Qnt</th>
                        <th class="text-right">Prezzo Unit.</th>
                        <th class="text-right">Sconto</th>
                        <th class="text-right">Totale Netto</th>
                    </tr>
                </thead>
                <tbody>';
        if (isset($products) && ! empty($products)) {
            $total = $vat = 0;
            $priceunit = '';
            foreach ($products as $value) {
                $priceunit = $value->currency;

                $unit = (float) ($value->price_unit);
                $discount_price = (float) ($value->discount_price);
                $discount_currency = $value->discount_currency;
                $tax = (float) ($value->tax);
                $qty = (float) ($value->quantity);

                if ($discount_currency == '%') {
                    $unit = $unit * (1 - $discount_price / 100);
                } else {
                    $unit = $unit - $discount_price;
                }

                $total += $unit * $qty;
                $vat += $unit * $tax / 100 * $qty;

                $table .= '<tr class="invoicerow ">
                                <td>'.$value->id.'</td>
                                <td>'.$value->code.'</td>
                                <td><b>'.$value->name.'</b><br/>'.$value->description.'</td>
                                <td class="text-right td-price">'.$value->quantity.' '.$value->unit.'</td>
                                <td class="text-right td-price">'.number_format((float) ($value->price_unit), 2).' '.$value->currency.'</td>
                                <td class="text-right td-price">';

                if ($value->discount > 0) {
                    $table .= ''.$value->discount_price.' '.$value->discount_currency.'';
                } //if

                if ($value->discount_currency === "%") {
                    $table .= '</td>
                        <td class="text-right td-price">'.number_format(((float) ($value->price_unit) * (1 - (float) ($value->discount_price) / 100) * (float) ($value->quantity)), 2).' '.$value->currency.'</td>
                    </tr>';
                } else {
                    $table .= '</td>
                        <td class="text-right td-price">'.number_format((((float) $value->price_unit - (float) $value->discount_price) * (float) ($value->quantity)), 2).' '.$value->currency.'</td>
                    </tr>';
                }

                
            } //foreach
        } //if product

        $table .=   '<tr class="text-right tr_clear">
                        <td colspan="4" ><hr />Totale imponibile: </td>
                        <td colspan="3" class="td-price"><hr />'.number_format($total, 2).' '.$priceunit.'</td>
                    </tr>
                    <tr class="text-right tr_clear">
                        <td colspan="4" >Totale Imposta: </td>
                        <td colspan="3" class="td-price">'.number_format($vat, 2).' '.$priceunit.'</td>
                    </tr>
                    <tr class="text-right title tr_clear">
                        <td colspan="4" ><b>Totale Ordine: </b></td>
                        <td colspan="3" class="td-price"><b>'.number_format($total + $vat, 2).' '.$priceunit.'</b></td>
                    </tr>
                </tbody>
            </table>
        </div>';

        $table .= '
        <br class="clear" style="margin:30px; margin-top:80px" />
        <div class="row w100">
            <table class="table table-bordered" style="width:100%" >
                <tr>
                    <td width="100">Info sulla consegna: </td>
                    <td>'.$deliveryCategory.' <br>'.$order->note_delivery.'</td>
                </tr>
                <tr>
                    <td>Info sul trasporto: </td>
                    <td>'.$trasportCategory.' <br>'.$order->note_trasport.'</td>
                </tr>
                <tr>
                    <td width="100">Info Pagemento: </td>
                    <td>'.$paymentCategory.' <br>'.$order->note_payment.'</td>
                </tr>
                <tr>
                    <td>Note sull\'ordine: </td>
                    <td>'.$order->note.'</td>
                </tr>
            <table>
        </div>
        <br /><br /><br />
        <div class="row w100">
            <div class="w50">
                <hr style="color:#000; border:1px solid #000; margin:0; width:90%;">
                (Firma - Uff. Acquisti)
            </div>
            <div class="w50">
                <hr style="color:#000; border:1px solid #000; margin:5px; width:90%;">
                (Firma - '.$order->company_name.')
            </div>
        </div>';

        // Test page 2
        // for ($i = 0; $i < 200; $i++) {
        //     $cont .= $i.'<br>';
        // }

        $output = '
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>OrderPrint</title>
                <style>
                    body { font-family: \'Nunito\', sans-serif; margin: 0px !important; }
                    .row{ width: 100% !important; font-family: Montserrat,Helvetica,Arial,sans-serif; font-weight: 400; font-size:14px }
                    .w100{ width: 100% !important; }
                    .w50{ width: 50% !important; float:left; }
                    .w33{ width: 33.33% !important; float:left; }
                    .clear{ clear:both; }
                    
                    .title { font-size:18px; font: bold; color: #000000; margin: 0px; padding: 0px; }
                    .text-right{ text-align:right; }
                    .footer_terms { position: fixed; bottom: 0px; margin-bottom: 100px;}

                    @page { margin: 10px; }
                    #header { position: fixed; left: 0px; top: 0px; right: 0px; height: 80px; text-align: center; margin-bottom: 20px }
                    #footer { position: fixed; left: 0px; bottom: 0px; right: 0px; height: 80px; }
                    #footer .page:after { content: counter(page, upper-roman); }
                    #main { margin-top: 20px }
                    
                    .page-break {page-break-after: always;}
					.pageNum:before { content: counter(page); }

                    tr:nth-child(2n+1) { background-color: #ededed; }
                    .tr_clear{ background-color: #fff; }
                    
                    td { vertical-align: top; }
                    .td-price { padding-left:10px; padding-right:10px; text-align:right; width:80px; }
                </style>
            </head>
        <body>
            <div id="header">
                <div class="row w100 " style="height:100px" >
                    <div class="w33">
                        '.$logoCompany.'
                    </div>
                    <div class="w33">
                        <b class="title">'.$order->company_name.'</b> <br />
                        '.$comapnyAddress.'<br />
                        IVA: '.$order->company_vat.' - SDI: '.$order->company_icode.' <br />
                        '.$order->company_mail.' '.$order->company_pec.'
                    </div>
                    <div class="w33 text-right">
                        <b class="title" >Ordine nr: '.$order->order_num.'/'.$order->order_year.'</b>
                        <br /> Emesso il: '.$order->order_order_at.' <br /> '.$order->company_html_wh_info.'
                        <br /> Pagina <span class="pageNum"></span>
                        
                    </div>
                    <hr class="clear" style="margin:2px" >
                </div>
            </div>
            <div id="footer">
				<div class="row w100" style="font-size:12px; text-align: justify;">
                    <hr style="border:1px solid #000; width:100%;">
                    <span>'.$order->company_html_footer.'</span>
                </div>
            </div>

            <div id="main">
                <div class="row w100" style="margin-top:90px" >
                    <div class="w50">
                        Destinazione:<br />
                        <b style="font-size:18px; margin:1px;">'.$deliveryAddress.'</b><br />
                        '.$deliveryContact.'
                    </div>
                    <div class="text-right" >
                        Fornitore:<br />
                        <b style="font-size:18px; margin:1px;">'.$order->supplier_name.'</b><br />
                        <div style="float:right;">
                            '.$supplierAddress.'<br />
                        </div>
                    </div>
                </div>
                <div class="clear" ></div>
                '.$table.'
				<span class="clear" style="margin:20px" ></span>
                <div class="row w100 footer_terms" style="font-size:12px; text-align: justify;">
                    <span ><b>Info e condizioni:</b></span>
                    <span style="font-size:11px; ">'.$order->company_html_wh_terms.'</span>
                </div>
                '.$cont.'
            </div>
        </body>
        </html>'; 

        $pdf = \App::make('dompdf.wrapper');
        $customPaper = [0, 0, 792.00, 1224.00];
        $pdf->loadHTML($output)->setPaper($customPaper);

        return $pdf->stream();
    }
}
