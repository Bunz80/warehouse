<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use PDF;
use App\Models\Warehouse\Order;
use App\Models\Warehouse\OrderDetail;
use App\Models\Company;
use App\Models\Supplier;
use App\Models\Address;
use App\Models\Contact;
//use Session;



class DynamicPDFController extends Controller
{
    

    function pdf($id, $status)
    {
        $order = Order :: join('company','company.id','=','shopping_order.companyid')
                        ->join('societa_fornitori','societa_fornitori.id','=','shopping_order.supplierid')
                        ->join('shopping_payment','shopping_payment.id','=','shopping_order.payment')
                        ->where('shopping_order.id','=',$id)
                        ->select(
                                '*',
                                        'shopping_order.id as orderid',
                                        'societa.id as companyid',
                                        'societa.logo as companylogo',
                                        'societa.ragione_sociale as companyname',
                                        'societa.societa_info as companyinfo',
                                        'societa.acquisti_info as companyacquistiinfo',
                                        'societa.acquisti_mail as companyacquistimail',
                                        'societa.indirizzo as companyindirizzo',
                                        'societa.cap as companycap',
                                        'societa.citta as companycitta',
                                        'societa.iva as companyiva',
                                        'societa.sdi as companysdi',
                                        'societa.mail as companymail',
                                        'societa.pec as companypec',
                                        
                                        'societa_fornitori.logo as supplierlogo',
                                        'societa_fornitori.ragione_sociale as suppliername',
                                        'societa_fornitori.indirizzo as supplierindirizzo',
                                        'societa_fornitori.cap as suppliercap',
                                        'societa_fornitori.citta as suppliercitta',
                                        'societa_fornitori.iva as supplieriva',
                                        'societa_fornitori.sdi as suppliersdi',
                                        'societa_fornitori.mail as suppliermail',
                                        'societa_fornitori.pec as supplierpec',
                                        
                                        'shopping_order.userid as userid',
                                        'shopping_order.destination as destination',
                                        'shopping_order.address as address',
                                        'shopping_order.city as city',
                                        'shopping_order.province as province',
                                        'shopping_order.zip as zip',
                                        'shopping_order.state as state',
                                        'shopping_order.referent as referent',
                                        'shopping_order.referent_contact as referent_contact',

                                        'shopping_payment.method as paymentmethod',

                                        'shopping_order.created_at as createdate'
                                        
                                    )
                                ->first();

        $products = EcommerceOrderDetail ::where('orderid','=',$id)->get();
        $note = EcommerceOrderNote :: where('orderid','=',$id)->get();

        $output = '';

        if(!empty($order)){
        $output .= '
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <style>
                    .page-break { 
                        page-break-after: always; 
                    }
                    .row{
                        font-family:Montserrat, Helvetica, Arial, sans-serif;
                        font-size: 16px;
                        color: #000;
                        width:100%;
                    }
                    .order{
                        font-size: 21.14px;
                    }
                    .title{
                        font-size: 16.14px;
                    }
                    .text-right{
                        text-align:right;
                    }
                    .text-left{
                        text-align:left;
                    }
                    .clear{
                        clear: both;
                    }
                    tbody tr:nth-child(even) {
                        background-color: #f2f2f2;
                    }
                    ul {
                        /* list-style-type: none;   /* nessun elemento come marcatore */
                        padding-left: 0;            /* padding nullo */
                        margin-left: 0;             /* margine nullo */
                    }
                    .footer {
                        position: fixed;
                        width: 100%;
                        height: 640px;
                        font-size: 12px;
                        margin: 0;
                        top: auto;
                        right: 0;
                        bottom: 0;
                        left: 0;
                     }
                </style>
                <div class="row">
                    <div style="font-size:14px;height:120px" >
                        <div style="width:33.33%;float:left;margin:10px">';
                    if($status == "Suspended"){
        $output .=          '<h3 style="color:red;text-shadow: 2px 2px blue;border:2px solid;padding:5px">Suspended</h3>';
                    }
                    if($order->companylogo!=""){
        $output .=          '<img src="http://medmar.myworkplan.it/uploads/logo/'.$order->companylogo.'" style="max-width: 200px; max-height: 60px;">'; 
                    }else{
        $output .=          '<h3>'.$order->companyname.'</h3>';
                    }
        $output .=  '
                    </div>
                    <div style="width:33s.33%;float:left;">
                        <b>'.$order->companyname.'</b> <br /> 
                        '.$order->companyindirizzo.' - '.$order->companycap.' '.$order->companycitta.'<br />
                        '.$order->companyiva.' / '.$order->companysdi.' <br />
                        '.$order->companymail.' - '.$order->companypec.'
                    </div>
                    <div style="width:33.33%;float:left;text-align:right">
                        <b class="order" >Ordine nr: '.$order->anno.'.'.$order->no.'</b>
                        <div>
                            Emesso il: '.$order->createdate.' <br /> 
                            '.$order->companyacquistimail.' <br />
                            Eseguito da: '.$order->userid.'
                        </div>
                    </div>
                </div>
                
                <hr style="margin-top:-40px; margin-bottom:10px;" />
                
                <div class="row" style="height:150px">
                    <div style="width:40%;float:left">
                        <b class="title" >Destinazione</b> <br />
                        <b>'.$order->destination.'</b><br /> 
                        '.$order->address.' - '.$order->zip.', '.$order->city.'<br />
                        '.$order->province.' / '.$order->state.' <br />
                        Ref: '.$order->referent.' / '.$order->referent_contact.'
                    </div>
                    <div style="width:40%;float:left;text-align:right">
                        <b class="title" >Fornitore</b> <br />
                        <b>'.$order->suppliername.'</b><br /> 
                        '.$order->supplierindirizzo.' - '.$order->suppliercap.' '.$order->suppliercitta.'<br />
                        '.$order->supplieriva.' / '.$order->suppliersdi.' <br />
                        '.$order->suppliermail.' / '.$order->supplierpec.'
                    </div>';

        if($order->supplierlogo!=""){
        $output .= '
                    <div class="col-2" style="text-align:right">
                        <img src="http://medmar.myworkplan.it/uploads/logo/'.$order->supplierlogo.'" class="pull-right" style="XXXmax-height: 70px; max-width:100px;" >
                    </div>';
        }

        $output .=' 
                </div>';
        };
        
        $output .= '
                <div class="row" >                 
                    <h3>Lista Prodotti</h3>
                    <div>
                        <table style="width:100%">
                            <thead>
                                <tr>                                       
                                    <th class="text-left">ID</th>
                                    <th class="text-left">COD</th>
                                    <th class="text-left">Descrizione</th>                                        
                                    <th class="text-left">Qnt</th>
                                    <th class="text-right">Prezzo</th>
                                    <th class="text-right">Sconto</th>
                                    <th class="text-right">Totale</th>                                
                                </tr>
                            </thead>
                            <tbody>';

                    if(isset($products) && !empty($products)){
                        $total = 0; $vat = 0;
                        foreach($products as $value){
                            
                            $total +=  ((float)($value->price)*(1-(float)($value->discount)/100)*(float)($value->amount)); 
                            $vat += (float)($value->vat)*((float)($value->price)*(1-(float)($value->discount)/100)*(float)($value->amount))/100;
                            
                            $output .= '
                                <tr class="invoicerow">                                           
                                    <td>'.$value->id.'</td>
                                    <td>'.$value->product_code.'</td>
                                    <td>'.$value->product_description.'</td>                                           
                                    <td>'.$value->amount.'</td>
                                    <td class="text-right">'.number_format((float)($value->price),2).' '.$value->priceunit.'</td>
                                    <td class="text-right">';
                                    if($value->discount>0){
                                         $output .= $value->discount.' '.$value->discountunit;
                                    }
        
                                    $output .= '
                                    </td>
                                        <td class="text-right">'.number_format( ((float)($value->price)*(1-(float)($value->discount)/100)*(float)($value->amount)) ,2).' '.$value->priceunit.'</td>
                                    </tr>';
                        };
        
                                    $output .= '
                                    <tr style="border-top: 1px solid #000;" >
                                        <td colspan="6" class="text-right" >Totale imponibile</td>
                                        <td class="text-right" >'. number_format($total,2) .' '.$value->priceunit.'</td>
                                    </tr>
                                    <tr class="text-right" >
                                        <td colspan="6" class="text-right" >Totale iva</td>
                                        <td class="text-right" >'. number_format($vat,2) .' '.$value->priceunit.'</td>
                                    </tr> 
                                    <tr class="text-right" >
                                        <td colspan="6" class="title text-right" ><b class="titolo">Totale Ordine</b></td>
                                        <td ><b class="titolo">'. number_format($total+$vat,2) .' '.$value->priceunit.'</b></td>
                                    </tr>';  
                        };
                    
        $output .=     '
                            </tbody>
                        </table>        
                    </div>  
                </div>';
                    

    $output .= '<div class="row" >
                    <div style="width:33.33%; float:left; margin:5px;">
                        <h3> Pagamento:</h3>
                        Pagamento: '.$order->paymentmethod.'<br /> '.$order->payment_note.'
                    </div>
                    <div style="width:33.33%; float:left; margin:5px;">
                        <h3> Trasporto:</h3>
                        Trasporto: '.$order->transport.'<br />'.$order->transport_note.'
                    </div>
                    <div style="width:33.33%; float:left; margin:5px; ">
                        <h3> Note:</h3>';
                    if(isset($note) && !empty($note)) {                                   
                        foreach($note as $value) {                                                       
                            $output .= '• '.$value->note.'<br/>';
                        }
                    }
    $output .= '    </div>
                </div>
                
                <i class="clear"> &nbsp; </i>    

                <!--div class="row" >
                    <div style="width:33.33%; float:left; margin:5px;"></div>
                    <div style="width:33.33%; float:left; margin:5px;">
                        <br ><br > <hr style="color:#000; border:1px solid #000; ">
                        (Firma - Uff. Acquisti)
                    </div>

                    <div style="width:33.33%; float:left; margin:5px;">
                        <br ><br > <hr style="color:#000; border:1px solid #000; ">
                        (Firma - '.$order->companyname.')
                    </div>
                </div-->
                
                <i class="clear"> &nbsp; </i>    

                <div class="row footer" >

                    <div style="width:33.33%; float:left; margin:5px;"></div>
                    <div style="width:33.33%; float:left; margin:5px;">
                        <br ><br > <hr style="color:#000; border:1px solid #000; ">
                        (Firma - Uff. Acquisti)
                    </div>
                    <div style="width:33.33%; float:left; margin:5px;">
                        <br ><br > <hr style="color:#000; border:1px solid #000; ">
                        (Firma - '.$order->companyname.')
                    </div>
                    
                    <i class="clear"> <br /> </i>  

                    <b class="title text-right">Info e condizioni: </b>
                    <span stile="font-size:10px; margin:0;">'.$order->companyacquistiinfo.'</span>
                    <p style="font-size:10px; margin:0;"> <i class="fa fa-leaf fa-2x success"></i> <strong>Rispetta l\'ambiente: </strong>Non stampare questa pagina se non è necessario </p>
                    <hr />
                    <p style="font-size:10px; text-align:center; "><b>'.$order->companyname.':</b> '.$order->companyinfo.'</p>
                    <div class="anchor_footer" style="margin-bottom:-90px !important;"></div>
                </div>';

        $pdf = \App::make('dompdf.wrapper');
        $customPaper = array(0, 0, 792.00, 1224.00);
        
        //$pdf->set_paper(DEFAULT_PDF_PAPER_SIZE, 'A4');
        $pdf->loadHTML($output)->setPaper($customPaper);
        return $pdf->stream();
    }

    
    
}
