<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
                            'orders.currency as order_currency',
                            'orders.total_taxes as order_total_taxes',
                            'orders.total_prices as order_total_prices',
                            'orders.total_order as order_total_order',

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

                            // Delivery

                            // Concact
                        )
                        ->first();

        $products = OrderDetail::where('order_id', '=', $id)->get();

        $style = "";
        $header = "";
        $destination = "";
        $footer_company = "";

        //set output
        $output = '';

        $logoCompany = '';
        if ($order->company_logo != '') {
            $logoCompany = '<img src="/uploads/logo/'.$order->company_logo.'" style="max-width: 300px; max-height: 100px;">';
        } else {
            $logoCompany = '<h3 class="title">'.$order->company_name.'</h3>';
        }

        $logoSupplier = '';
        if ($order->supplier_logo != '') {
            $logoSupplier = '<img src="/uploads/logo/'.$order->supplier_logo.'" style="max-height: 90px; max-width:120px; float: right; padding: 5px 0 15px 15px; " >';
        }

        $page = 'Pagine: 1/1';

        $note = '';
        if (isset($note) && ! empty($note)) {
            foreach ($note as $value) {
                $note .= '• '.$value->note.'<br/>';
            }
        }
        //overwrite note
        $note = $order->ordernote;
        

        if (! empty($order)) {
            //HEADER
            $output .= $style;
            $output .= '
            <div class="row" XXXstyle="height:100px" >'.$header.'</div>
            <div class="row" XXXstyle="height:100px" >'.$destination.'</div>';
        }

        //TABELLA
        $output .= '
            <div class="row">
                <h3>Lista Prodotti</h3>
                <table style="width:100%">
                    <thead>
                        <tr class="tr_clear">
                            <!--th class="text-left">ID</th-->
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
            $total = 0;
            $vat = 0;
            foreach ($products as $value) {
                $total += ((float) ($value->price) * (1 - (float) ($value->discount) / 100) * (float) ($value->amount));
                $vat += (float) ($value->vat) * ((float) ($value->price) * (1 - (float) ($value->discount) / 100) * (float) ($value->amount)) / 100;

                $output .= '<tr class="invoicerow ">
                                    <!--td>'.$value->id.'</td-->
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
            } //foreach

            $output .= ' <tr class="text-right tr_clear">
                                    <td colspan="5" ><hr />Totale imponibile: </td>
                                    <td ><hr />'.number_format($total, 2).' '.$value->priceunit.'</td>
                                </tr>
                                <tr class="text-right tr_clear">
                                    <td colspan="5" >Totale iva: </td>
                                    <td >'.number_format($vat, 2).' '.$value->priceunit.'</td>
                                </tr> 
                                <tr class="text-right title tr_clear">
                                    <td colspan="5" >Totale Ordine: </td>
                                    <td >'.number_format($total + $vat, 2).' '.$value->priceunit.'</td>
                                </tr>';
        } //if product

        $output .= '</tbody>
                </table> 
            </div>';

        //NOTE
        $output .= '
            <div class="row">
                <br />
                <div class="w33">
                    <b>Pagamento:</b><br />'.$order->paymentmethod.'<br />'.$order->payment_note.'
                </div>
                <div class="w33">
                    <b>Trasporto:</b><br />'.$order->transport.'<br />'.$order->transport_note.'
                </div>
                <div class="w33">
                    <b>Note:</b><br />'.$note.'
                </div>
            </div>';

        //SIGNATURE
        $output .= '<br /><br /><br /><br /><br /><br />
            <div class="row footer_signature">
                <div class="w33">
                    .
                </div>
                <div class="w33">
                    <hr style="color:#000; border:1px solid #000; margin:0; width:90%;">
                    (Firma - Uff. Acquisti)
                </div>
                <div class="w33">
                    <hr style="color:#000; border:1px solid #000; margin:0; width:90%;">
                    (Firma - '.$order->companyname.')
                </div>
            </div>';

        //FOOTER
        $output .= '
            <div class="row footer_info">
                <span ><b>Info e condizioni: </b> <span style="font-size:11px; ">'.$order->companyacquistiinfo.'</span></span>
            </div>
            <div class="anchor_footer" style="margin-bottom:-90px !important;"></div>
            
            <div class="row footer">
                '.$footer_company.'
            </div>
            <div class="anchor_footer" style="margin-bottom:-90px !important;"></div>';

        $pdf = \App::make('dompdf.wrapper');
        $customPaper = [0, 0, 792.00, 1224.00];

        //$pdf->set_paper(DEFAULT_PDF_PAPER_SIZE, 'A4');
        $pdf->loadHTML($output)->setPaper($customPaper);

        return $pdf->stream();
    }
}
