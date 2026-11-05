<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/all-reminders',  [App\Http\Controllers\API\ReminderController::class, 'allReminders']);

Route::post('/register', [App\Http\Controllers\API\RegisterController::class, 'createAccount']);
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'authenticate']);

Route::group(['middleware' => ['jwt.verify'], 'prefix'=>'auth' ], function() {
    Route::post('/dashboard', [App\Http\Controllers\API\DashboardController::class, 'summary']);
    Route::post('/invoices', [App\Http\Controllers\API\InvoiceController::class, 'getInvoices']);
    Route::post('/invoices/create', [App\Http\Controllers\API\InvoiceController::class, 'createInvoice']);
    Route::post('/bills', [App\Http\Controllers\API\BillController::class, 'getBills']);
    Route::get('/contacts', [App\Http\Controllers\API\ContactsController::class, 'allContacts']);
    Route::get('/items', [App\Http\Controllers\API\ProductsController::class, 'getItems']);
    Route::get('/invoices/details/{id}', [App\Http\Controllers\API\InvoiceController::class, 'getInvoiceDetails']);
    Route::post('/invoices/send', [App\Http\Controllers\API\InvoiceController::class, 'sendInvoice']);
    Route::post('/invoices/approve', [App\Http\Controllers\API\InvoiceController::class, 'approveInvoice']);
    Route::post('/invoices/decline', [App\Http\Controllers\API\InvoiceController::class, 'declineInvoice']);
    Route::get('/banks', [App\Http\Controllers\API\InvoiceController::class, 'getBanks']);
    Route::post('/invoices/payment', [App\Http\Controllers\API\InvoiceController::class, 'processPayment']);
    Route::get('/bills/details/{id}', [App\Http\Controllers\API\BillController::class, 'getBillDetails']);
    Route::post('/bills/payment', [App\Http\Controllers\API\BillController::class, 'makePayment']);
    Route::post('/bills/create', [App\Http\Controllers\API\BillController::class, 'createBill']);
    Route::get('/settings/tenant', [App\Http\Controllers\API\SettingsController::class, 'getTenantInfo']);
    Route::post('/settings/bank/create', [App\Http\Controllers\API\SettingsController::class, 'saveBank']);
    Route::post('/settings/payment/update', [App\Http\Controllers\API\SettingsController::class, 'savePaymentIntegration']);
    Route::post('/settings/update', [App\Http\Controllers\API\SettingsController::class, 'saveSettings']);
    Route::post('/settings/mail/update', [App\Http\Controllers\API\SettingsController::class, 'saveMailchimpSetting']);
    Route::post('/settings/sender/update', [App\Http\Controllers\API\SettingsController::class, 'updateBulkSmsSettings']);
    Route::get('/items/categories', [App\Http\Controllers\API\ProductsController::class, 'getCategories']);
    Route::post('/items/create', [App\Http\Controllers\API\ProductsController::class, 'createItem']);
    Route::post('/items/category/create', [App\Http\Controllers\API\ProductsController::class, 'createCategory']);
    Route::post('/contact/invoices', [App\Http\Controllers\API\InvoiceController::class, 'getContactInvoice']);
    Route::post('/contact/receipts', [App\Http\Controllers\API\ReceiptController::class, 'getContactReceipts']);
    Route::post('/contact/conversations', [App\Http\Controllers\API\ContactsController::class, 'getContactConversations']);
    Route::post('/contact/conversations/create', [App\Http\Controllers\API\ContactsController::class, 'newConversation']);
    Route::post('/contact/create', [App\Http\Controllers\API\ContactsController::class, 'createContact']);
    Route::post('/imprests', [App\Http\Controllers\API\ImprestController::class, 'getImprests']);
    Route::get('/users', [App\Http\Controllers\API\ImprestController::class, 'getUsers']);
    Route::post('/imprests/create', [App\Http\Controllers\API\ImprestController::class, 'storeNewImprest']);
    Route::post('/imprests/approve', [App\Http\Controllers\API\ImprestController::class, 'approveImprest']);
    Route::post('/imprests/decline', [App\Http\Controllers\API\ImprestController::class, 'declineImprest']);
    Route::post('/receipts', [App\Http\Controllers\API\ReceiptController::class, 'getReceipts']);
    Route::post('/receipts/create', [App\Http\Controllers\API\ReceiptController::class, 'createReceipt']);
    Route::get('/receipts/details/{id}', [App\Http\Controllers\API\ReceiptController::class, 'getReceiptDetails']);
    Route::post('/receipts/approve', [App\Http\Controllers\API\ReceiptController::class, 'approveReceipt']);
    Route::post('/receipts/decline', [App\Http\Controllers\API\ReceiptController::class, 'declineReceipt']);
    Route::get('/reminders', [App\Http\Controllers\API\ReminderController::class, 'getReminders']);
    Route::post('/reminders/create', [App\Http\Controllers\API\ReminderController::class, 'createReminders']);
    Route::post('/sales-report', [App\Http\Controllers\API\ReportController::class, 'salesReport']);
    Route::post('/sales-report/range', [App\Http\Controllers\API\ReportController::class, 'filterSalesReport']);
    Route::post('/payment-report', [App\Http\Controllers\API\ReportController::class, 'paymentReport']);
    Route::post('/payment-report/range', [App\Http\Controllers\API\ReportController::class, 'filterPaymentReport']);
    Route::post('/imprest-report', [App\Http\Controllers\API\ReportController::class, 'imprestsReport']);
    Route::post('/imprest-report/range', [App\Http\Controllers\API\ReportController::class, 'filterImpressReport']);
});
