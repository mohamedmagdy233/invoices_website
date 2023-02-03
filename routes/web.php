<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('invoices',\App\Http\Controllers\InvoiceController::class);
Route::resource('sections',\App\Http\Controllers\SectionController::class);
Route::resource('products',\App\Http\Controllers\ProductController::class);
Route::get('invoiceDetails/{id}',[\App\Http\Controllers\InvoiceDetailsController::class,'edit']);
Route::get('download/{invoice_number}/{file_name}', [\App\Http\Controllers\InvoiceDetailsController::class,'get_file']);
Route::get('View_file/{invoice_number}/{file_name}', [\App\Http\Controllers\InvoiceDetailsController::class,'open_file']);
Route::post('delete_file', 'InvoicesDetailsController@destroy')->name('delete_file');
Route::get('edit_invoice/{id}',[\App\Http\Controllers\InvoiceController::class,'edit']);
Route::get('invoices/update/{id}',[\App\Http\Controllers\InvoiceController::class,'update']);
Route::get('invoices/destroy',[\App\Http\Controllers\InvoiceController::class,'destroy']);
Route::get('Status_show/{id}',[\App\Http\Controllers\InvoiceController::class,'show'])->name("Status_show");
Route::post('Status_Update/{id}',[\App\Http\Controllers\InvoiceController::class,'Status_Update'])->name("Status_Update");
Route::get('invoices_paid',[\App\Http\Controllers\InvoiceController::class,'invoices_paid']);
Route::get('invoices_Partial',[\App\Http\Controllers\InvoiceController::class,'invoices_Partial']);
Route::get('invoices_unpaid',[\App\Http\Controllers\InvoiceController::class,'invoices_unpaid']);
Route::get('Archive_Invoices',[\App\Http\Controllers\ArchiveController::class,'index']);
Route::resource('Archive', \App\Http\Controllers\ArchiveController::class);
Route::get('Print_invoice/{id}',[\App\Http\Controllers\InvoiceController::class,'Print_invoice']);
Route::get('export_invoices', [\App\Http\Controllers\InvoiceController::class, 'export']);
Route::group(['middleware' => ['auth']], function() {

    Route::resource('roles',\App\Http\Controllers\RoleController::class);

    Route::resource('users', \App\Http\Controllers\UserController::class);

});
Route::get('invoices_report',[\App\Http\Controllers\InvoiceReportController::class,'index']);
Route::post('Search_invoices',[\App\Http\Controllers\InvoiceReportController::class,'SearchInvoices']);
Route::get('customers_report',[\App\Http\Controllers\CustomerController::class,'index']);
Route::post('Search_customers',[\App\Http\Controllers\CustomerController::class,'SearchCustomers']);










Route::get('section/{id}',[\App\Http\Controllers\InvoiceController::class,'getProduct']);
Route::post('InvoiceAttachments',[\App\Http\Controllers\InvoiceAttachmentsController::class,'store']);


//git update






























































Route::get('/{page}', [\App\Http\Controllers\AdminController::class,'index']);
