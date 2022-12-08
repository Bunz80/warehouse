<!DOCTYPE html>
<html lang="{ { str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Print Order</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <!-- Styles -->
        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--tw-bg-opacity: 1;background-color:rgb(255 255 255 / var(--tw-bg-opacity))}.bg-gray-100{--tw-bg-opacity: 1;background-color:rgb(243 244 246 / var(--tw-bg-opacity))}.border-gray-200{--tw-border-opacity: 1;border-color:rgb(229 231 235 / var(--tw-border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{--tw-shadow: 0 1px 3px 0 rgb(0 0 0 / .1), 0 1px 2px -1px rgb(0 0 0 / .1);--tw-shadow-colored: 0 1px 3px 0 var(--tw-shadow-color), 0 1px 2px -1px var(--tw-shadow-color);box-shadow:var(--tw-ring-offset-shadow, 0 0 #0000),var(--tw-ring-shadow, 0 0 #0000),var(--tw-shadow)}.text-center{text-align:center}.text-gray-200{--tw-text-opacity: 1;color:rgb(229 231 235 / var(--tw-text-opacity))}.text-gray-300{--tw-text-opacity: 1;color:rgb(209 213 219 / var(--tw-text-opacity))}.text-gray-400{--tw-text-opacity: 1;color:rgb(156 163 175 / var(--tw-text-opacity))}.text-gray-500{--tw-text-opacity: 1;color:rgb(107 114 128 / var(--tw-text-opacity))}.text-gray-600{--tw-text-opacity: 1;color:rgb(75 85 99 / var(--tw-text-opacity))}.text-gray-700{--tw-text-opacity: 1;color:rgb(55 65 81 / var(--tw-text-opacity))}.text-gray-900{--tw-text-opacity: 1;color:rgb(17 24 39 / var(--tw-text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--tw-bg-opacity: 1;background-color:rgb(31 41 55 / var(--tw-bg-opacity))}.dark\:bg-gray-900{--tw-bg-opacity: 1;background-color:rgb(17 24 39 / var(--tw-bg-opacity))}.dark\:border-gray-700{--tw-border-opacity: 1;border-color:rgb(55 65 81 / var(--tw-border-opacity))}.dark\:text-white{--tw-text-opacity: 1;color:rgb(255 255 255 / var(--tw-text-opacity))}.dark\:text-gray-400{--tw-text-opacity: 1;color:rgb(156 163 175 / var(--tw-text-opacity))}.dark\:text-gray-500{--tw-text-opacity: 1;color:rgb(107 114 128 / var(--tw-text-opacity))}}
        </style>

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }

            @page {
                /* margin:20px; */
            }
            .row{
                width: 100% !important;
                font-family: Montserrat,Helvetica,Arial,sans-serif;
                font-weight: 400;
                font-size:14px
            }
            .clear{
                clear:both;
            }
            .w100{
                width: 100% !important;
            }
            .w50{
                width: 50% !important;
                float:left;
            }
            .w33{
                width: 33.33% !important;
                float:left;
            }
            .title {
                font-size:18px;
                font: bold;
                color: #000000;
                margin: 0px;
                padding: 0px;
            }
            .text-right{
                text-align:right;
            }
            .alert{
                width: 100%;
                color: #fff;
                padding: 3px;
                background-color: #ea5455;
                border-color: #ea5455;
                margin:0px;
            }
            ul li {
                list-style-position: outside;
                margin-left:-30px;
                text-align: justify;
                text-align-last: justify; /* IE */
            }
            .footer {
                position: fixed;
                bottom: 40;
            }
            .footer_info {
                position: fixed;
                bottom: 320;
            }
            .footer_signature {
                position: fixed;
                bottom: 360;
            }
            tr:nth-child(2n+1) {
                background-color: #bfbecf;
            }
            .tr_clear{
                background-color: #fff;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="">
           
            <!-- Header / Company -->
            <div class="w33">
                { {logoCompany}}
            </div>
            <div class="w33">
                <b class="title">{ {company_name}}</b> <br /> 
                { {company_address}} - { {company_zip}} { {company_city}}<br />
                IVA: { {company_vat}} - SDI: { {company_icode}}}} <br />
                { {companymail}} - { {companypec}}
            </div>
            <div class="w33 text-right">
                <b class="title" >Ordine nr: { {year}}.{ {number)}}</b>  
                <br /> Emesso il: { {order_at}} <br /> { {company_html_wh_info}} 
            </div>
            <hr class="clear" style="margin-top:-1px" >
            
            <!-- Delivery / Supplier -->
            <div class="w50">
                <b style="font-size:18px; margin:1px;">Destinazione</b><br /> 
                <b>{ {destination}}</b><br /> 
                { {address}} - { {zip}}, { {city}}<br />
                { {province}} / { {state}} <br />
                Ref: { {referent}} / { {referent_contact}}
            </div>
            <div class="text-right" >
                <b style="font-size:18px; margin:1px;">Fornitore</b><br /> 
                { {logoSupplier}}
                <div style="float:right;">
                    <b>{ {supplier_name}}</b><br /> 
                    { {supplier_address}} - { {supplier_zip}} { {supplier_city}}<br />
                    { {supplier_vat}} <br />
                    { {supplier_icode}} / { {supplier_pec}}
                </div>
            </div>
            <hr class="clear" style="margin-top:-10px" >';
            
            <!-- List Product / Order Details -->
            <div class="table-responsive col-12">
                <hr>
                <!--START TABLE -->
                <h3>Lista Prodotti</h3>
                <div class="table-responsive">
                    <table class="table table-hover-animation table-striped">
                        <thead>
                            <tr>                                       
                                <th class="text-left">ID</th>
                                <th class="text-left">COD</th>
                                <th class="text-left">Name</th>                                        
                                <th class="text-left">Qnt</th>
                                <th class="text-right">Prezzo</th>
                                <th class="text-right">Sconto</th>
                                <th class="text-right">Totale</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="invoicerow" id="invoicerow24">                                           
                                <td>24</td>
                                <td></td>
                                <td>fsdfsdfsd</td>                                           
                                <td>20</td>
                                <td class="text-right">10.00 €</td>
                                <td class="text-right">2%</td>
                                <td class="text-right">196.00 €</td>
                            </tr>
                            <tr class="invoicerow" id="invoicerow25">                                           
                                <td>25</td>
                                <td></td>
                                <td>cssfdd</td>                                           
                                <td>10</td>
                                <td class="text-right">30.00 €</td>
                                <td class="text-right">6%</td>
                                <td class="text-right">282.00 €</td>
                            </tr>
                                
                            <tr class="text-right" style="border-top:1px solid #000">
                                <td colspan="6">Totale imponibile</td>
                                <td>478.00 €</td>
                            </tr>
                            <tr class="text-right ">
                                <td colspan="6">Totale iva</td>
                                <td>105.16 €</td>
                            </tr> 
                            <tr class="text-right text-bold-700 ">
                                <td colspan="6">Totale Ordine</td>
                                <td>583.16 €</td>
                            </tr>  
                                                    
                        </tbody>
                    </table>        
                </div>  
                <!--END TABLE -->
            </div>
            
            
            <!-- Footer -->
            <span style="font-size:10px; margin:0; width:100%; "> <i class="fa fa-leaf fa-2x success"></i> <strong>Rispetta lambiente: </strong>Non stampare questa pagina se non è necessario </span>
            <hr />
            <span style="font-size:10px; text-align: center; "><b>{ {companyname}}:</b> { {companyinfo}}</span>';


        </div>
    </body>
</html>
