<?php

namespace App\Http\Controllers;

use App\Exports\InvoiceExport;
use App\Models\Invoice;
use App\Models\Invoice_attachments;
use App\Models\Invoices_details;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{

    public function index()
    {
        $invoices =Invoice::all();
        return view('invoices.invoices',compact('invoices'));
    }


    public function create()
    {
        $sections=Section::all();
        return view('invoices.add_invoice',compact('sections'));
    }


    public function store(Request $request)
    {
        Invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);

        $invoice_id = Invoice::latest()->first()->id;
        Invoices_details::create([
            'id_Invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        if ($request->hasFile('pic')) {

            $invoice_id = Invoice::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new Invoice_attachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }





        session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
        return back();
    }







    public function show($id)
    {
       $invoices=Invoice::where('id',$id)->first();
       return view('invoices.status_update',compact('invoices'));

    }


    public function edit($id)

    {
        $invoices =Invoice::where('id',$id)->first();
        $sections =Section::all();
        return view('invoices.edit_invoice',compact('invoices','sections'));
    }

    public function update(Request $request)
    {
        $invoices =Invoice::findOrFail($request->invoice_id);
        $invoices->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'note' => $request->note,

        ]);

        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return back();
    }


    public function destroy(Request $request)
    {
//        return $request;
        $id = $request->invoice_id;
        $invoices = Invoice::where('id', $id)->first();
        $Details = Invoice_attachments::where('invoice_id', $id)->first();


        $id_page =$request->id_page;


        if (!$id_page==2) {

            if (!empty($Details->invoice_number)) {

                Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
            }

            $invoices->forceDelete();
            session()->flash('delete_invoice');
            return redirect('/invoices');

        }

        else {

            $invoices->delete();
            session()->flash('archive_invoice');
            return redirect('/invoices');
        }


    }

    public function getProduct($id)
    {
        $product =DB::table('products')->where('section_id',$id)->pluck('Product_name','id');
        return json_encode($product);
    }

    public function Status_Update($id,Request $request){

        $invoices =Invoice::findOrFail($id);

        if($request->Status ==='مدفوعة'){

            $invoices->update([

                'Value_Status'=>1,
                'Payment_Date'=>$request->Payment_Date,
                'Status'=>$request->Status
            ]);
            Invoices_details::create([


                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);

        }
        else{

            $invoices->update([

                'Value_Status'=>3,
                'Payment_Date'=>$request->Payment_Date,
                'Status'=>$request->Status
            ]);
            Invoices_details::create([


                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);


        }
        session()->flash('Status_Update');
        return redirect('/invoices');
    }

    public function invoices_paid(){


        $invoices =Invoice::where('Value_Status',1)->get();
        return view('invoices.invoices_paid',compact('invoices'));

    }
    public function invoices_Partial(){


        $invoices =Invoice::where('Value_Status',3)->get();
        return view('invoices.invoices_Partial',compact('invoices'));

    }

    public function invoices_unpaid(){


        $invoices =Invoice::where('Value_Status',2)->get();
        return view('invoices.invoices_unpaid',compact('invoices'));

    }
    public function Print_invoice($id){
      $invoices=Invoice::where('id',$id)->first();
      return view('invoices.Print_invoice',compact('invoices'));


    }
    public function export()
    {

        return Excel::download(new InvoiceExport, 'قائمه الفواتير.xlsx');
    }


}
