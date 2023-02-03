<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;

class InvoiceExport implements FromCollection
{

    public function collection()
    {
        return Invoice::all();
        //return Invoice::select('invoice_number','Status')->get();
    }
}
