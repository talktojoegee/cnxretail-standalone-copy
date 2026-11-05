<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Category;
use App\Models\Contact;
use App\Models\InvoiceDetail;
use App\Models\InvoiceMaster;
use App\Models\Item;
use App\Models\ItemGallery;
use App\Models\ReceiptDetail;
use App\Models\ReceiptMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesAndInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->category = new Category();
        $this->item = new Item();
        $this->itemgallery = new ItemGallery();
        $this->contact = new Contact();
        $this->invoice = new InvoiceMaster();
        $this->invoiceitem = new InvoiceDetail();
        $this->receipt = new ReceiptMaster();
        $this->receiptitem = new ReceiptDetail();
        $this->bank = new Bank();
    }

    public function manageInvoices(){
        return view('sales-n-invoice.manage-invoices',['invoices'=>$this->invoice->getTenantInvoices()]);
    }
    public function postInvoice(){
        return view('sales-n-invoice.post-invoice',['invoices'=>$this->invoice->getTenantInvoicesByStatus(0)]);
    }


    public function showAddNewInvoiceForm(){
        return view('sales-n-invoice.add-new-invoice',[
            'contacts'=>$this->contact->getContactsByTenantId(Auth::user()->tenant_id),
            'items'=>$this->item->getAllTenantItems(Auth::user()->tenant_id)
        ]);
    }

    public function saveNewInvoice(Request $request){
        $this->validate($request,[
            'contact_type'=>'required',
            'issue_date'=>'required|date',
            'due_date'=>'required|date',
            'items.*'=>'required'
        ],[
            'contact_type.required'=>'Select contact type',
            'issue_date.required'=>'Choose issue date',
            'issue_date.date'=>'Enter a valid date format',
            'due_date.required'=>'Choose due date',
            'due_date.date'=>'Enter a valid date format'
        ]);
        $invoice = $this->invoice->setNewInvoice($request);
        $this->invoiceitem->setNewInvoiceItems($request, $invoice);
        session()->flash("success", " Invoice generation was carried out successfully.");
        return back();
    }

    public function viewInvoice($slug){
        $invoice = $this->invoice->getInvoiceBySlug($slug);
        if(!empty($invoice)){
            return view('sales-n-invoice.view-invoice',['invoice'=>$invoice]);
        }else{
            session()->flash("error", "<strong>Whoops!</strong> No record found.");
            return back();
        }
    }

    public function declineInvoice($slug){
        $invoice = $this->invoice->getInvoiceBySlug($slug);
        if(!empty($invoice)){
            $this->invoice->updateInvoiceStatus($invoice->id, 'decline');
            session()->flash("success", "<strong>Success!</strong> Invoice declined.");
            return back();
        }else{
            session()->flash("error", "<strong>Whoops!</strong> No record found.");
            return back();
        }
    }
    public function approveInvoice($slug){
        $invoice = $this->invoice->getInvoiceBySlug($slug);
        if(!empty($invoice)){
            $this->invoice->updateInvoiceStatus($invoice->id, 'post');
            session()->flash("success", "<strong>Success!</strong> Invoice posted.");
            return back();
        }else{
            session()->flash("error", "<strong>Whoops!</strong> No record found.");
            return back();
        }
    }


    public function sendInvoice($slug){
        $invoice = $this->invoice->getInvoiceBySlug($slug);
        if(!empty($invoice)){
            #Contact
            $contact = $this->contact->getContactById($invoice->id);
            if(!empty($contact)){
                //return dd($contact);
                $this->invoice->sendInvoiceAsEmailService($contact, $invoice);
                session()->flash("success", "<strong>Success!</strong> Invoice sent.");
                return back();
            }else{
                session()->flash("error", "<strong>There's no record for this contact.");
                return back();
            }
        }else{
            session()->flash("error", "<strong>Whoops!</strong> No record found.");
            return back();
        }
    }

    public function receivePayment($slug){
        $invoice = $this->invoice->getInvoiceBySlug($slug);
        if(!empty($invoice)){
            return view('sales-n-invoice.receive-payment',['invoice'=>$invoice, 'banks'=>$this->bank->getAllTenantBanks()]);
        }else{
            session()->flash("error", "<strong>Whoops!</strong> No record found.");
            return back();
        }
    }

    public function processPayment(Request $request){
        $this->validate($request,[
            'amount'=>'required',
            'payment_date'=>'required|date',
            'reference_no'=>'required',
            'bank'=>'required',
            'invoice'=>'required',
            'payment_method'=>'required'
        ],[
            'amount.required'=>'Enter amount paid.',
            'payment_date.required'=>'Choose payment date',
            'payment_date.date'=>'Choose a valid date format',
            'reference_no.required'=>'Enter a reference number for this transaction',
            'bank.required'=>'Select bank from the list provided',
            'payment_method.required'=>'Select payment method'
        ]);
        $invoice = $this->invoice->getInvoiceById($request->invoice);
        if(!empty($invoice)){
            $balance = $invoice->total - $invoice->paid_amount;
            if($request->amount > $balance){
                session()->flash("error", "Whoops! The amount you entered is more than balance.");
                return back();
            }else{
                $this->invoice->updateInvoicePayment($invoice, $request->amount);
                $counter = $this->receipt->getLatestReceipt();
                $receipt = $this->receipt->createNewReceipt($counter, $invoice, $request);
                if(!empty($receipt)){
                    session()->flash("success", " Your receipt request was generated successfully.");
                    return back();
                }else{
                    session()->flash("error", "We could not process this request. Try again later");
                    return back();
                }
            }
        }else{
            session()->flash("error", " Invoice does not exist. Try again.");
            return back();
        }
    }

    public function manageReceipts(){
        return view('sales-n-invoice.manage-receipts',['receipts'=>$this->receipt->getAllTenantReceipts()]);
    }

    public function showAddNewReceiptForm(){
        return view('sales-n-invoice.add-new-receipt',[
            'contacts'=>$this->contact->getContactsByTenantId(Auth::user()->tenant_id),
            'items'=>$this->item->getAllTenantItems(Auth::user()->tenant_id),
            'banks'=>$this->bank->getAllTenantBanks()
        ]);
    }

    public function saveNewReceipt(Request $request){
        $this->validate($request,[
            'contact'=>'required',
            'payment_date'=>'required|date',
            'reference_no'=>'required',
            'bank'=>'required',
            'payment_method'=>'required',
            'items.*'=>'required'
        ],[
            'contact.required'=>'Select contact',
            'payment_date.required'=>'Choose payment date',
            'payment_date.date'=>'Choose a valid date format',
            'reference_no.required'=>'Enter a reference number for this transaction',
            'bank.required'=>'Select bank from the list provided',
            'payment_method.required'=>'Select payment method'
        ]);
        $receipt = $this->receipt->createNewDirectReceipt($request);
        $this->receiptitem->setNewReceiptItems($request, $receipt);
        session()->flash("success", " Receipt generation was carried out successfully.");
        return back();
    }

    public function viewReceipt($slug){
        $receipt = $this->receipt->getReceiptBySlug($slug);
        if(!empty($receipt)){
            return view('sales-n-invoice.view-receipt',['receipt'=>$receipt]);
        }else{
            session()->flash("error", "No record found.");
            return back();
        }
    }

    public function declineReceipt($slug){
        $receipt = $this->receipt->getReceiptBySlug($slug);
        if(!empty($receipt)){
            $this->receipt->updateReceiptStatus($receipt->id, 'decline');
            session()->flash("success", "Receipt declined.");
            return back();
        }else{
            session()->flash("error", "No record found.");
            return back();
        }
    }
    public function approveReceipt($slug){
        $receipt = $this->receipt->getReceiptBySlug($slug);
        if(!empty($receipt)){
            $this->receipt->updateReceiptStatus($receipt->id, 'post');
            session()->flash("success", " Receipt posted.");
            return back();
        }else{
            session()->flash("error", "No record found.");
            return back();
        }
    }


    public function sendReceipt($slug){
        $receipt = $this->receipt->getInvoiceBySlug($slug);
        if(!empty($receipt)){
            #Contact
            $contact = $this->contact->getContactById($receipt->id);
            if(!empty($contact)){
                $this->receipt->sendReceiptAsEmailService($receipt, $contact);
                session()->flash("success", "Receipt sent.");
                return back();
            }else{
                session()->flash("error", "There's no record for this contact.");
                return back();
            }
        }else{
            session()->flash("error", "No record found.");
            return back();
        }
    }

    public function salesReport(){
        return view('sales-n-invoice.sales-report',[
            'invoices'=>$this->invoice->getTenantInvoices(),
            'receipts'=>$this->receipt->getAllTenantReceipts(),
            'from'=>now(),
            'to'=>now()
        ]);
    }

    public function filterSalesReport(Request $request){
        $this->validate($request,[
            'from'=>'required|date',
            'to'=>'required|date'
        ],[
            'from.required'=>'Select start date',
            'from.date'=>'Choose a valid date',
            'to.required'=>'Select end date',
            'to.date'=>'Choose a valid date'
        ]);
        return view('sales-n-invoice.sales-report',[
            'invoices'=>$this->invoice->getTenantInvoicesByDateRange($request),
            'receipts'=>$this->receipt->getAllTenantReceiptsByDateRange($request),
            'from'=>$request->from,
            'to'=>$request->to
        ]);
    }

    public function showCategories(){
        return view('sales-n-invoice.category',['categories'=>$this->category->getAllCategories()]);
    }

    public function addNewCategory(Request $request){
        $this->validate($request,[
            'category_name'=>'required'
        ],[
            'category_name.required'=>'Enter category name'
        ]);
        $this->category->setNewCategory($request);
        session()->flash("success", "Your new category name was added.");
        return back();
    }

    public function updateCategory(Request $request){
        $this->validate($request,[
            'category_name'=>'required'
        ],[
            'category_name.required'=>'Enter category name'
        ]);
        $this->category->updateCategory($request);
        session()->flash("success", " Your changes were saved.");
        return back();
    }

    public function showNewItemForm(){
        return view('sales-n-invoice.add-new-item',['categories'=>$this->category->getAllCategories()]);
    }

    public function addNewItem(Request $request){
     if($request->item_type == 1){
         $this->validate($request,[
            'item_name'=>'required',
            'category'=>'required',
            'quantity'=>'required',
            'cost_price'=>'required',
            'selling_price'=>'required',
            'attachments'=>'required',
            'attachments.*'=>'required',
         ],[
             'item_name.required'=>'Enter product name',
             'category.required'=>'Select product category',
             'quantity.required'=>'Enter product quantity',
             'cost_price.required'=>"What's the cost of this product?",
             'selling_price.required'=>'How much do you intend selling this product?',
             'attachments.required'=>'Upload product image'
         ]);
     }else{
         $this->validate($request,[
             'item_name'=>'required',
             'service_fee'=>'required'
         ],[
             'item_name.required'=>'Enter service name',
             'service_fee.required'=>'How much do you intend to charge for this service?'
         ]);
     }
     $this->item->setNewItem($request);
     session()->flash("success", "Your new item was added successfully.");
     return back();
    }
    public function updateItem(Request $request){
        if($request->item_type == 1){
            $this->validate($request,[
                'item_name'=>'required',
                'category'=>'required',
                'quantity'=>'required',
                'cost_price'=>'required',
                'selling_price'=>'required',
               // 'attachments'=>'required',
               // 'attachments.*'=>'required',
                'product'=>'required'
            ],[
                'item_name.required'=>'Enter product name',
                'category.required'=>'Select product category',
                'quantity.required'=>'Enter product quantity',
                'cost_price.required'=>"What's the cost of this product?",
                'selling_price.required'=>'How much do you intend selling this product?',
                //'attachments.required'=>'Upload product image'
            ]);
        }
        $this->item->updateProduct($request);
        session()->flash("success", "Your new changes were saved successfully.");
        return back();
    }
    public function showUpdateProductForm($slug){
        $product = $this->item->getTenantItemBySlug($slug);
        if(!empty($product)){
            return view('sales-n-invoice.edit-product',[
                'categories'=>$this->category->getAllCategories(),
                'product'=>$product
            ]);
        }else{
            session()->flash("error", "No record found.");
            return back();
        }
    }

    public function deleteImage($slug){
        $gallery = $this->itemgallery->getProductImageById($slug);
        if(!empty($gallery)){
            $this->itemgallery->deleteImage($slug);
            session()->flash("success", "Image deleted.");
            return back();
        }else{
            session()->flash("error", "No record found.");
            return back();
        }
    }
    public function manageServices(){
        return view('sales-n-invoice.manage-services',['services'=>$this->item->getItemByItemType(2)]);
    }

    public function manageProducts(){
        return view('sales-n-invoice.manage-products',['products'=>$this->item->getItemByItemType(1)]);
    }

    public function productDetails($slug){
        $product = $this->item->getTenantItemBySlug($slug);
        if(!empty($product)){
            return view('sales-n-invoice.product-details',['product'=>$product]);
        }else{
            session()->flash("error", "<strong>Whoops!</strong> No record found.");
            return back();
        }
    }
}
