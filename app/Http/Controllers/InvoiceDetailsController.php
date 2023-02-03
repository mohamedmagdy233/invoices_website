<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Invoice_attachments;
use App\Models\Invoices_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceDetailsController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(Invoices_details $invoice_details)
    {
        //
    }


    public function edit($id)
    {
        $invoices = Invoice::where('id',$id)->first();
        $details  = Invoices_details::where('id_Invoice',$id)->get();
        $attachments  = Invoice_attachments::where('invoice_id',$id)->get();

        return view('invoices.details_invoice',compact('invoices','details','attachments'));
    }


    public function update(Request $request, Invoices_details $invoice_details)
    {
        //
    }

//    public function destroy(Request $request)
//    {
//        $invoices = invoice_attachments::findOrFail($request->id_file);
//        $invoices->delete();
//        Storage::disk('public_uploads')->delete($request->invoice_number.'/'.$request->file_name);
//        session()->flash('delete', 'تم حذف المرفق بنجاح');
//        return back();
//    }
//
//    public function get_file($invoice_number,$file_name)
//
//    {
//        $contents= Storage::disk('public_uploads')->getDriver()->getAdapter()->applyPathPrefix($invoice_number.'/'.$file_name);
//        return response()->download( $contents);
//    }
//
//
//
//    public function open_file($invoice_number,$file_name)
//
//    {
//        $files = Storage::disk('public_uploads')->getDriver()->getAdapter()->applyPathPrefix($invoice_number.'/'.$file_name);
//        return response()->file($files);
//    }

}
