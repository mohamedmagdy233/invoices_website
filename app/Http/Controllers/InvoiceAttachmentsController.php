<?php

namespace App\Http\Controllers;

use App\Models\Invoice_attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceAttachmentsController extends Controller
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
       $this->validate($request,[

            'file_name'=>'required|mimes:pdf,jpeg,png,jpg'
           ],[
             'file_name.required'=>'ارفق ملف من فضلك ',
//             'file_name.unique'=>'هذا الملف موجود بالفعل ',
             'file_name.mimes'=>'يخب ان يكون بصيغه اما pdf او jpg او png او jpeg'


       ]);

       $images=$request->file('file_name');
       $file_name= $images->getClientOriginalName();

        $attachments= new Invoice_attachments();
        $attachments->file_name=$file_name;
        $attachments->invoice_number=$request->invoice_number;
        $attachments->invoice_id=$request->invoice_id;
        $attachments->Created_by=Auth::user()->name;
        $attachments->save();



        $imageName=$request->file_name->getClientOriginalName();
        $request->file_name->move(public_path('Attachments/'.$request->invoice_number),$imageName);

        session()->flash('Add','تم اضافه المنتج بنجاح ');
        return back();

    }


    public function show(Invoice_attachments $invoice_attachments)
    {
        //
    }


    public function edit(Invoice_attachments $invoice_attachments)
    {
        //
    }


    public function update(Request $request, Invoice_attachments $invoice_attachments)
    {
        //
    }


    public function destroy(Invoice_attachments $invoice_attachments)
    {
        //
    }
}
