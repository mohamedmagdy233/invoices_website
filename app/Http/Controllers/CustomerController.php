<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Section;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
  public function index(){
      $sections= Section::all();
      return view('reports.customers_report',compact('sections'));
  }

  public function SearchCustomers(Request $request){
      $sections =$request->Section;
      $product =$request->product;
      $start_at =$request->start_at;
      $end_at   =$request->end_at;

      if($sections  &&  $product && $start_at =='' && $end_at ==''){

          $invoices =Invoice::select('*')->where('section_id','=',$sections)->where('product','=',$product)->get();
          $sections= Section::all();
          return view('reports.customers_report',compact('sections'))->withDetails($invoices);



      }else{

          $invoices=Invoice::whereBetween('invoice_Date',[$start_at,$end_at])->where('section_id','=',$sections)->where('product','=',$product)->get();
          $sections= Section::all();
          return view('reports.customers_report',compact('sections'))->withDetails($invoices);
      }
  }
}
