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
        $output = $style = $header = $destination = $footer_company = '';
        $style = '
        <style>
            body { font-family: \'Nunito\', sans-serif; margin: 5px !important; }
            .row{ width: 100% !important; font-family: Montserrat,Helvetica,Arial,sans-serif; font-weight: 400; font-size:14px }
            .clear{ clear:both; }
            .w100{ width: 100% !important; }
            .w50{ width: 50% !important; float:left; }
            .w33{ width: 33.33% !important; float:left; }
            .title { font-size:18px; font: bold; color: #000000; margin: 0px; padding: 0px; }
            .text-right{ text-align:right; }
            
            .header {
                position: fixed;
                top: -60px;
                left: 0px;
                right: 0px;
                height: 50px;
             }
            .footer {
                position: fixed;
                bottom: 280px;
                left: 0px;
                right: 0px;
                height: 50px;
             }

            tr:nth-child(2n+1) { background-color: #c9c9c9; }
            .tr_clear{ background-color: #fff; }
            /* tr { border-bottom: 1pt solid black; } */

             .page-break {page-break-after: always;}
        </style>';

        $output = '
        <html>
            <head>'.
                $style.'
            </head>
            <body>
                <!-- start container-->
                <div class="container">';

        // HEADER COMPANY
        $output .= '
        <div class="row w100 " style="height:100px" >
            <div class="w33">'.$logoCompany.'</div>
            <div class="w33">
                <b class="title">'.$order->company_name.'</b> <br />
                '.$comapnyAddress.'<br />
                IVA: '.$order->company_vat.' - SDI: '.$order->company_icode.' <br />
                '.$order->companymail.' - '.$order->companypec.'
            </div>
            <div class="w33 text-right">
                <b class="title" >Ordine nr: '.$order->order_num.'/'.$order->order_year.'</b>
                <br /> Emesso il: '.$order->order_order_at.' <br /> '.$order->company_html_wh_info.'
            </div>
            <hr class="clear" style="margin-top:-1px" >
        </div>';

        // BODY MAIN
        $output .= '<main>';
        // SUPPLIER + DELIVERY
        $output .= '
        <div class="row w100" >
            <div class="w50">
                Consegna:<br />
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
        <div class="clear" ></div>';

        // Table - OrderDetails
        $products = OrderDetail::where('order_id', '=', $id)->get();
        $output .= '
        <div class="row w100">
            <h3>Lista Prodotti</h3>
            <table class="table table-striped" style="width:100%">
                <thead>
                    <tr class="tr_clear">
                        <th class="text-left">ID</th>
                        <th class="text-left">Cod</th>
                        <th class="text-left">Descrizione</th>
                        <th class="text-left">Qnt</th>
                        <th class="text-right">Prezzo</th>
                        <th class="text-right">Sconto</th>
                        <th class="text-right">Totale</th>
                    </tr>
                </thead>
                <tbody>';
        if (isset($products) && ! empty($products)) {
            $total = $vat = 0;
            $priceunit = '';
            foreach ($products as $value) {
                $total += ((float) ($value->price) * (1 - (float) ($value->discount) / 100) * (float) ($value->amount));
                $vat += (float) ($value->vat) * ((float) ($value->price) * (1 - (float) ($value->discount) / 100) * (float) ($value->amount)) / 100;
                $priceunit = $value->priceunit;

                $output .= '<tr class="invoicerow ">
                                <td>'.$value->id.'</td>
                                <td>'.$value->product_code.'</td>
                                <td>'.$value->product_description.'</td>
                                <td>'.$value->amount.'</td>
                                <td class="text-right">'.number_format((float) ($value->price), 2).' '.$value->priceunit.'</td>
                                <td class="text-right">';
                if ($value->discount > 0) {
                    $output .= $value->discount.' '.$value->discountunit;
                } //if

                $output .= '</td>
                                <td class="text-right">'.number_format(((float) ($value->price) * (1 - (float) ($value->discount) / 100) * (float) ($value->amount)), 2).' '.$value->priceunit.'</td>
                            </tr>';

                $output .= '<tr class="text-right tr_clear">
                                <td colspan="5" ><hr />Totale imponibile: </td>
                                <td ><hr />'.number_format($total, 2).' '.$priceunit.'</td>
                            </tr>
                            <tr class="text-right tr_clear">
                                <td colspan="5" >Totale iva: </td>
                                <td >'.number_format($vat, 2).' '.$priceunit.'</td>
                            </tr>
                            <tr class="text-right title tr_clear">
                                <td colspan="5" >Totale Ordine: </td>
                                <td >'.number_format($total + $vat, 2).' '.$priceunit.'</td>
                            </tr>';
            } //foreach
        } //if product

        $output .= '
                </tbody>
            </table>
        </div>';

        // Test page 2
        // for($i=0; $i<200; $i++){
        // $output .= $i.'<br>';
        // }

        //NOTE
        $output .= '
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
                    <hr style="color:#000; border:1px solid #000; margin:0; width:90%;">
                    (Firma - '.$order->company_name.')
                </div>
            </div>';

        //SIGNATURE
        $output .= '
        <p style="height:30px" > . </p>
        <div class="row footer" style="font-size:12px; text-align: justify;">
            <span ><b>Info e condizioni:</b></span>
            <span style="font-size:11px; ">'.$order->company_html_wh_terms.'</span>
            <hr style="border:1px solid #000; width:100%;">
            <span>'.$order->company_html_footer.'</span>
        </div>';

        $output .= '
                <!-- page-break end container-->
                </div>
            </main>
        </body>
    </html>';

        $pdf = \App::make('dompdf.wrapper');
        $customPaper = [0, 0, 792.00, 1224.00];
        $pdf->loadHTML($output)->setPaper($customPaper);

        return $pdf->stream();
    }
}
