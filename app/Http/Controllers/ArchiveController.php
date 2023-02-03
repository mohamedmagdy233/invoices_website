<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{

    public function index()
    {
        $invoices =Invoice::onlyTrashed()->get();
        return view('invoices.Archive_Invoices',compact('invoices'));
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request)
    {

        $id = $request->invoice_id;
        $flight = Invoice::withTrashed()->where('id', $id)->restore();
        session()->flash('restore_invoice');
        return redirect('/invoices');
    }



    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $flight = Invoice::withTrashed()->where('id', $id)->first();
        $flight->forceDelete();
        session()->flash('delete_invoice');
        return redirect('/invoices');
    }
}
