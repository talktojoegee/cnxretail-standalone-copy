<?php

use Illuminate\Support\Facades\Route;

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




Route::get('/',[App\Http\Controllers\IndexController::class, 'index'])->name('homepage');
Route::get('/marketplace',[App\Http\Controllers\IndexController::class, 'marketplace'])->name('marketplace');
Route::get('/marketplace/cart',[App\Http\Controllers\IndexController::class, 'cart'])->name('marketplace.cart');
Route::post('/marketplace/cart',[App\Http\Controllers\IndexController::class, 'addToCart'])->name('marketplace.cart.add');
Route::post('/marketplace/cart/update',[App\Http\Controllers\IndexController::class, 'updateCart'])->name('marketplace.cart.update');
Route::post('/marketplace/cart/remove',[App\Http\Controllers\IndexController::class, 'removeFromCart'])->name('marketplace.cart.remove');
Route::get('/marketplace/checkout',[App\Http\Controllers\IndexController::class, 'checkout'])->name('marketplace.checkout');
Route::post('/marketplace/checkout',[App\Http\Controllers\IndexController::class, 'placeOrder'])->name('marketplace.checkout.place');
Route::get('/store/{slug}',[App\Http\Controllers\IndexController::class, 'vendorStore'])->name('vendor-store');
Route::get('/product-category/{slug}',[App\Http\Controllers\IndexController::class, 'productCategories'])->name('product-category');
Route::get('/search-product',[App\Http\Controllers\IndexController::class, 'searchProduct'])->name('product-search');
/*Route::get('/marketplace',function(){
    return view('layouts.marketplace-layout');
})->name('marketplace');*/

Route::get('/marketplace/{slug}',[App\Http\Controllers\IndexController::class, 'viewItem'])->name('view-item');
Route::get('/contact-us',[App\Http\Controllers\IndexController::class, 'contactUs'])->name('contact-us');
Route::post('/contact-us',[App\Http\Controllers\IndexController::class, 'saveContactUs']);
Route::post('/buyer-request',[App\Http\Controllers\IndexController::class, 'buyerRequest'])->name('buyer-request');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');


Route::prefix('/settings')->group(function(){
    Route::get('/', [App\Http\Controllers\AppSettingsController::class, 'showSettingsView'])->name('app-settings');
    Route::post('/', [App\Http\Controllers\AppSettingsController::class, 'saveSettings']);
    Route::post('/payment-integration', [App\Http\Controllers\AppSettingsController::class, 'savePaymentIntegration'])->name('app-payment-integration');
    Route::post('/new-mailchimp-settings', [App\Http\Controllers\AppSettingsController::class, 'saveMailchimpSetting'])->name('new-mailchimp-settings');
    Route::post('/update-mailchimp-settings', [App\Http\Controllers\AppSettingsController::class, 'updateMailchimpSetting'])->name('update-mailchimp-settings');
    Route::post('/save-bank', [App\Http\Controllers\AppSettingsController::class, 'saveBank'])->name('save-bank');
    Route::post('/update-bank', [App\Http\Controllers\AppSettingsController::class, 'updateBank'])->name('update-bank');
    Route::post('/update-bulk-sms-settings', [App\Http\Controllers\AppSettingsController::class, 'updateBulkSmsSettings'])->name('update-bulk-settings');
});


Route::prefix('/contacts')->group(function(){
    Route::get('/', [App\Http\Controllers\ContactController::class, 'allContacts'])->name('all-contacts');
    Route::get('/leads', [App\Http\Controllers\ContactController::class, 'allLeads'])->name('all-leads');
    Route::get('/deals', [App\Http\Controllers\ContactController::class, 'allDeals'])->name('all-deals');
    Route::get('/add-new-contact', [App\Http\Controllers\ContactController::class, 'showAddNewContactForm'])->name('add-new-contact');
    Route::get('/edit-contact/{slug}', [App\Http\Controllers\ContactController::class, 'showEditContactForm'])->name('show-edit-contact');
    Route::post('/add-new-contact', [App\Http\Controllers\ContactController::class, 'saveContact']);
    Route::post('/edit-contact', [App\Http\Controllers\ContactController::class, 'editContact'])->name('edit-contact');
    Route::get('/{slug}',[App\Http\Controllers\ContactController::class, 'viewContact'] )->name('view-contact');
    Route::post('/conversation',[App\Http\Controllers\ContactController::class, 'newConversation'])->name('new-conversation');
});

Route::prefix('/sales-n-invoice')->group(function(){
    Route::get('/new-invoice', [App\Http\Controllers\SalesAndInvoiceController::class, 'showAddNewInvoiceForm'])->name('add-new-invoice');
    Route::post('/new-invoice',[App\Http\Controllers\SalesAndInvoiceController::class,'saveNewInvoice']);
    Route::get('/manage-invoices',[App\Http\Controllers\SalesAndInvoiceController::class, 'manageInvoices'])->name('manage-invoices');
    Route::get('/post-invoice',[App\Http\Controllers\SalesAndInvoiceController::class, 'postInvoice'])->name('post-invoice');
    Route::get('/manage-invoices/{slug}',[App\Http\Controllers\SalesAndInvoiceController::class, 'viewInvoice'])->name('view-invoice');
    Route::get('/decline-invoice/{slug}',[App\Http\Controllers\SalesAndInvoiceController::class, 'declineInvoice'])->name('decline-invoice');
    Route::get('/approve-invoice/{slug}',[App\Http\Controllers\SalesAndInvoiceController::class, 'approveInvoice'])->name('approve-invoice');
    Route::get('/send-invoice/{slug}',[App\Http\Controllers\SalesAndInvoiceController::class, 'sendInvoice'])->name('send-invoice');

    Route::get('/receive-payment/{slug}',[App\Http\Controllers\SalesAndInvoiceController::class, 'receivePayment'])->name('receive-payment');
    Route::post('/process-payment/',[App\Http\Controllers\SalesAndInvoiceController::class, 'processPayment'])->name('process-payment');

    Route::get('/manage-receipts',[App\Http\Controllers\SalesAndInvoiceController::class, 'manageReceipts'])->name('manage-receipts');
    Route::get('/add-new-receipt',[App\Http\Controllers\SalesAndInvoiceController::class, 'showAddNewReceiptForm'])->name('add-new-receipt');
    Route::post('/add-new-receipt',[App\Http\Controllers\SalesAndInvoiceController::class, 'saveNewReceipt']);
    Route::get('/manage-receipts/{slug}',[App\Http\Controllers\SalesAndInvoiceController::class, 'viewReceipt'])->name('view-receipt');
    Route::get('/decline-receipt/{slug}',[App\Http\Controllers\SalesAndInvoiceController::class, 'declineReceipt'])->name('decline-receipt');
    Route::get('/approve-receipt/{slug}',[App\Http\Controllers\SalesAndInvoiceController::class, 'approveReceipt'])->name('approve-receipt');
    Route::get('/send-receipt/{slug}',[App\Http\Controllers\SalesAndInvoiceController::class, 'sendReceipt'])->name('send-receipt');
    Route::get('/sales-report', [App\Http\Controllers\SalesAndInvoiceController::class, 'salesReport'])->name('sales-report');
    Route::get('/filter-sales-report', [App\Http\Controllers\SalesAndInvoiceController::class, 'filterSalesReport'])->name('filter-sales-report');

    Route::get('/categories', [App\Http\Controllers\SalesAndInvoiceController::class, 'showCategories'])->name('item-categories');
    Route::post('/add-new-category', [App\Http\Controllers\SalesAndInvoiceController::class, 'addNewCategory'])->name('add-new-category');
    Route::post('/update-category', [App\Http\Controllers\SalesAndInvoiceController::class, 'updateCategory'])->name('update-category');
    Route::get('/add-new-item', [App\Http\Controllers\SalesAndInvoiceController::class, 'showNewItemForm'])->name('add-new-item');
    Route::post('/add-new-item', [App\Http\Controllers\SalesAndInvoiceController::class, 'addNewItem']);
    Route::get('/services', [App\Http\Controllers\SalesAndInvoiceController::class, 'manageServices'])->name('manage-services');
    Route::get('/products', [App\Http\Controllers\SalesAndInvoiceController::class, 'manageProducts'])->name('manage-products');
    Route::get('/products/{slug}', [App\Http\Controllers\SalesAndInvoiceController::class, 'productDetails'])->name('product-details');
    Route::get('/update-item/{slug}', [App\Http\Controllers\SalesAndInvoiceController::class, 'showUpdateProductForm'])->name('update-item-details');
    Route::post('/update-product/', [App\Http\Controllers\SalesAndInvoiceController::class, 'updateItem'])->name('update-product');
    Route::get('/delete-image/{slug}/product-gallery', [App\Http\Controllers\SalesAndInvoiceController::class, 'deleteImage'])->name('delete-image');

});

Route::prefix('vendors')->group(function(){
    Route::get('/', [App\Http\Controllers\VendorController::class, 'allVendors'])->name('all-vendors');
    Route::get('/add-new-vendor', [App\Http\Controllers\VendorController::class, 'showAddNewVendorForm'])->name('add-new-vendor');
    Route::post('/add-new-vendor', [App\Http\Controllers\VendorController::class, 'saveVendor']);
});

Route::prefix('/bills-n-payment')->group(function(){
    Route::get('/manage-bills', [App\Http\Controllers\BillsAndPaymentController::class, 'manageBills'])->name('manage-bills');
    Route::get('/manage-bills/{slug}', [App\Http\Controllers\BillsAndPaymentController::class, 'viewBill'])->name('view-bill');
    Route::get('/add-new-bill', [App\Http\Controllers\BillsAndPaymentController::class, 'showAddNewBillForm'])->name('add-new-bill');
    Route::post('/add-new-bill', [App\Http\Controllers\BillsAndPaymentController::class, 'saveNewBill']);
    Route::get('/decline-bill/{slug}',[App\Http\Controllers\BillsAndPaymentController::class, 'declineBill'])->name('decline-bill');
    Route::get('/approve-bill/{slug}',[App\Http\Controllers\BillsAndPaymentController::class, 'approveBill'])->name('approve-bill');
    Route::get('/payment-report', [App\Http\Controllers\BillsAndPaymentController::class, 'paymentReport'])->name('payment-report');
    Route::get('/filter-payment-report', [App\Http\Controllers\BillsAndPaymentController::class, 'filterPaymentReport'])->name('filter-payment-report');

    #Make payment route
    Route::get('/manage-payments', [App\Http\Controllers\BillsAndPaymentController::class, 'managePayments'])->name('manage-payments');
    Route::get('/make-payment/{slug}', [App\Http\Controllers\BillsAndPaymentController::class, 'showMakePaymentForm'])->name('make-payment');
    Route::post('/make-payment', [App\Http\Controllers\BillsAndPaymentController::class, 'makePayment'])->name('process-make-payment');
    Route::get('/view-payment/{slug}', [App\Http\Controllers\BillsAndPaymentController::class, 'viewPayment'])->name('view-payment');

    Route::get('/decline-payment/{slug}',[App\Http\Controllers\BillsAndPaymentController::class, 'declinePayment'])->name('decline-payment');
    Route::get('/approve-payment/{slug}',[App\Http\Controllers\BillsAndPaymentController::class, 'approvePayment'])->name('approve-payment');

});


Route::prefix('/mail')->group(function(){
    Route::get('/manage-campaigns', [App\Http\Controllers\MailController::class, 'manageCampaigns'])->name('manage-campaigns');
    Route::get('/manage-campaigns/{web_id}', [App\Http\Controllers\MailController::class, 'viewCampaign'])->name('view-campaign');
    Route::get('/manage-audiences', [App\Http\Controllers\MailController::class, 'manageAudiences'])->name('manage-audiences');
    Route::get('/manage-audiences/{id}', [App\Http\Controllers\MailController::class, 'viewAudience'])->name('view-audience');
    Route::get('/add-new-audience', [App\Http\Controllers\MailController::class, 'showAddNewAudienceForm'])->name('add-new-audience');
});

Route::prefix('/messages')->group(function(){
    Route::get('/phone-group',[App\Http\Controllers\SMSController::class, 'showPhoneGroupForm'])->name('phone-group');
    Route::post('/phone-group',[App\Http\Controllers\SMSController::class, 'setNewPhoneGroup']);
    Route::get('/top-up',[App\Http\Controllers\SMSController::class, 'showTopUpForm'])->name('top-up');
    Route::post('/top-up',[App\Http\Controllers\SMSController::class, 'processTopUpRequest']);
    Route::get('/compose-message',[App\Http\Controllers\SMSController::class, 'showComposeMessageForm'])->name('compose-message');
    Route::get('/preview-message',[App\Http\Controllers\SMSController::class, 'previewMessage'])->name('preview-message');
    Route::post('/send-text-message',[App\Http\Controllers\SMSController::class, 'sendTextMessage'])->name('send-text-message');
    Route::get('/bulk-messages',[App\Http\Controllers\SMSController::class, 'getBulkMessages'])->name('bulk-messages');
    Route::get('/bulk-messages/{slug}',[App\Http\Controllers\SMSController::class, 'viewBulkMessage'])->name('view-bulk-message');
});

Route::prefix('cnxdrive')->group(function(){
    Route::get('/storage',[App\Http\Controllers\CNXDriveController::class, 'manageStorage'])->name('manage-storage');
    Route::post('/manage-files', [App\Http\Controllers\CNXDriveController::class, 'storeFiles'] )->name('upload-files');
    Route::post('/create-folder', [App\Http\Controllers\CNXDriveController::class, 'createFolder'] )->name('create-folder');
    Route::get('/folder/{slug}', [App\Http\Controllers\CNXDriveController::class, 'openFolder'] )->name('open-folder');
    Route::get('/download/{slug}', [App\Http\Controllers\CNXDriveController::class, 'downloadAttachment'] )->name('download-attachment');
    Route::post('/delete-file', [App\Http\Controllers\CNXDriveController::class, 'deleteAttachment'])->name('delete-file');
    Route::post('/delete-folder', [App\Http\Controllers\CNXDriveController::class, 'deleteFolder'])->name('delete-folder');
});

Route::prefix('/reminders')->group(function(){
    Route::get('/',[App\Http\Controllers\ReminderController::class, 'showReminders'])->name('manage-reminders');
    Route::post('/add-new-reminder',[App\Http\Controllers\ReminderController::class, 'addNewReminder'])->name('add-new-reminder');
    Route::get('/notifications',[App\Http\Controllers\ReminderController::class, 'notifications'])->name('notifications');
});

Route::prefix('/imprest')->group(function(){
    Route::get('/my-imprest',[App\Http\Controllers\ImprestController::class, 'showMyImprest'])->name('my-imprest');
    Route::get('/manage-imprests',[App\Http\Controllers\ImprestController::class, 'manageImprests'])->name('manage-imprests');
    Route::post('/add-new-imprest',[App\Http\Controllers\ImprestController::class, 'storeNewImprest'])->name('add-new-imprest');
    Route::get('/process-imprest/{action}/{slug}',[App\Http\Controllers\ImprestController::class, 'processImprest'])->name('process-imprest');
    Route::get('/impress-report', [App\Http\Controllers\ImprestController::class, 'impressReport'])->name('impress-report');
    Route::get('/filter-impress-report', [App\Http\Controllers\ImprestController::class, 'filterImpressReport'])->name('filter-impress-report');

});
Route::prefix('/workforce')->group(function(){
    Route::get('/team-members',[App\Http\Controllers\WorkforceController::class, 'manageWorkforce'])->name('manage-workforce');
    Route::get('/team-members/{slug}',[App\Http\Controllers\WorkforceController::class, 'viewProfile'])->name('view-profile');
    Route::get('/new-member',[App\Http\Controllers\WorkforceController::class, 'showNewTeamMemberForm'])->name('add-new-team-member');
    Route::post('/new-member',[App\Http\Controllers\WorkforceController::class, 'saveNewTeamMember']);
    Route::post('/update-profile',[App\Http\Controllers\WorkforceController::class, 'updateProfile'])->name('update-profile');
    Route::post('/change-password',[App\Http\Controllers\WorkforceController::class, 'changePassword'])->name('change-password');
    Route::post('/change-avatar',[App\Http\Controllers\WorkforceController::class, 'changeAvatar'])->name('change-avatar');
});

Route::get('/process/payment',[App\Http\Controllers\OnlinePaymentController::class, 'processOnlinePayment']);
Route::get('/online-payment/{slug}', [App\Http\Controllers\OnlinePaymentController::class, 'onlinePayment'])->name('online-payment');
Route::post('/charge-invoice-online',[App\Http\Controllers\OnlinePaymentController::class, 'chargeInvoiceOnline'])->name('charge-invoice-online');

Route::get('/renew-subscription/{tenant_slug}/{user_slug}',[App\Http\Controllers\Auth\LoginController::class, 'showRenewSubscriptionForm'])->name('renew-subscription');
Route::post('/process-subscription-renewal',[App\Http\Controllers\Auth\LoginController::class, 'processRenewSubscription'])->name('process-subscription-renewal');



Route::prefix('/tunnel')->group(function(){
    Route::get('/',[App\Http\Controllers\AdminAuth\LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/',[App\Http\Controllers\AdminAuth\LoginController::class, 'login']);
    Route::get('/add-new-user',[App\Http\Controllers\AdminController::class, 'showAddNewUserForm'])->name('add-new-admin-user');
    Route::get('/manage-admin-users',[App\Http\Controllers\AdminController::class, 'manageAdminUsers'])->name('manage-admin-users');
    Route::post('/add-new-user',[App\Http\Controllers\AdminController::class, 'storeAdminUser']);
    Route::get('/dashboard',[App\Http\Controllers\AdminController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/manage-tenants',[App\Http\Controllers\AdminController::class, 'manageTenants'])->name('manage-tenants');
    Route::get('/view-tenant/{slug}',[App\Http\Controllers\AdminController::class, 'viewTenant'])->name('view-tenant');
    Route::get('/subscription',[App\Http\Controllers\AdminController::class, 'getTenantSubscriptions'])->name('subscriptions');
    Route::get('/manage-pricing',[App\Http\Controllers\AdminController::class, 'managePricing'])->name('manage-pricing');
    Route::post('/add-pricing',[App\Http\Controllers\AdminController::class, 'addPricing'])->name('add-pricing');
    Route::get('/admin-notifications',[App\Http\Controllers\AdminController::class, 'notification'])->name('admin-notifications');
    Route::post('/update-pricing',[App\Http\Controllers\AdminController::class, 'editPricing'])->name('update-pricing');
    Route::get('/daily-motivation',[App\Http\Controllers\AdminController::class, 'manageDailyMotivations'])->name('daily-motivation');
    Route::post('/add-daily-motivation',[App\Http\Controllers\AdminController::class, 'addDailyMotivation'])->name('add-daily-motivation');
    Route::post('/update-daily-motivation',[App\Http\Controllers\AdminController::class, 'updateDailyMotivation'])->name('update-daily-motivation');
});

Auth::routes();
Route::get('logout', [App\Http\Controllers\Auth\LoginController::class,'logout']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
