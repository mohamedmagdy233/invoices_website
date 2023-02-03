<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceReportController extends Controller
{
   public function index(){


       return view('reports.invoices_report');


   }
   public function SearchInvoices(Request $request){

      $rdio =$request->rdio;


       // في حالة البحث بنوع الفاتورة
        if ($rdio==1){
            if ($request->start_at==''&& $request->end_at==''&& $request->type){

                $invoices =Invoice::select('*')->where('Status','=',$request->type)->get();
                $type=$request->type;
                return view('reports.invoices_report',compact('type'))->withDetails( $invoices);



            }else{

                $start_at =date($request->start_at);
                $end_at   =date($request->end_at);
                $type=$request->type;
                $invoices=Invoice::whereBetween('invoice_Date',[$start_at,$end_at])->where('Status','=',$request->type)->get();
                return view('reports.invoices_report',compact('start_at','end_at','type'))->withDetails( $invoices);


            }



        }
        else{

            $invoices=Invoice::select('*')->where('invoice_number','=',$request->invoice_number)->get();
            return view('reports.invoices_report')->withDetails( $invoices);


        }

   }
}
