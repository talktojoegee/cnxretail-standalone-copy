<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Category;
use App\Models\Contact;
use App\Models\InvoiceDetail;
use App\Models\InvoiceMaster;
use App\Models\Item;
use App\Models\ItemGallery;
use App\Models\MarginReport;
use App\Models\ReceiptDetail;
use App\Models\ReceiptMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{

    public function __construct()
    {
        $this->category = new Category();
        $this->item = new Item();
        $this->itemgallery = new ItemGallery();
        $this->contact = new Contact();
        $this->invoice = new InvoiceMaster();
        $this->invoiceitem = new InvoiceDetail();
        $this->receipt = new ReceiptMaster();
        $this->receiptitem = new ReceiptDetail();
        $this->bank = new Bank();
        $this->marginreport = new MarginReport();
    }

    public function getItems(Request $request)
    {
        try {
            $items = $this->item->getAllTenantItems(Auth::user()->tenant_id);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success Items",
                'data' => [
                    "items" => $items
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }

    public function createItem(Request $request)
    {
        if ($request->item_type == 1) {
            $validator = Validator::make($request->all(), [
                'item_name' => 'required',
                'category' => 'required',
                'quantity' => 'required',
                'cost_price' => 'required',
                'selling_price' => 'required',
                'attachments' => 'required',
                'attachments.*' => 'required',
            ], [
                'item_name.required' => 'Enter product name',
                'category.required' => 'Select product category',
                'quantity.required' => 'Enter product quantity',
                'cost_price.required' => "What's the cost of this product?",
                'selling_price.required' => 'How much do you intend selling this product?',
                'attachments.required' => 'Upload product image'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => $validator->errors()->first(),
                    'data' => ""
                ]);
            }

            try {
                $item = $this->item->setNewItem($request);
                return response()->json([
                    'success' => "Success",
                    'code' => 200,
                    'message' => "Item created successfully",
                    'data' => [
                        "items" => $item
                    ]
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'code' => 500,
                    'message' => "Oops something bad happened, Please try again! ",
                    'data' => ''
                ]);
            }
        } else {
            $validator = Validator::make($request->all(), [
                'item_name' => 'required',
                'service_fee' => 'required'
            ], [
                'item_name.required' => 'Enter service name',
                'service_fee.required' => 'How much do you intend to charge for this service?'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => $validator->errors()->first(),
                    'data' => ""
                ]);
            }
            try {
                $item = $this->item->setNewItem($request);
                return response()->json([
                    'success' => "Success",
                    'code' => 200,
                    'message' => "Item Created Successfully",
                    'data' => [
                        "items" => $item
                    ]
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'code' => 500,
                    'message' => "Oops something bad happened, Please try again! ",
                    'data' => ''
                ]);
            }
        }

    }

    public function updateItem(Request $request)
    {
        if ($request->item_type == 1) {
            $validator = Validate::make($request, [
                'item_name' => 'required',
                'category' => 'required',
                'quantity' => 'required',
                'cost_price' => 'required',
                'selling_price' => 'required',
                // 'attachments'=>'required',
                // 'attachments.*'=>'required',
                'product' => 'required'
            ], [
                'item_name.required' => 'Enter product name',
                'category.required' => 'Select product category',
                'quantity.required' => 'Enter product quantity',
                'cost_price.required' => "What's the cost of this product?",
                'selling_price.required' => 'How much do you intend selling this product?',
                //'attachments.required'=>'Upload product image'
            ]);
        }
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
                'data' => ""
            ]);
        }

        try {
            $item = $this->item->updateProduct($request);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success",
                'data' => [
                    "item" => $item
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }


    }

    public function getCategories(Request $request)
    {
        try {
            //$id = $request->id ?? 0;
            $categories = $this->category->getAllCategories();
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success categories",
                'data' => [
                    "categories" => $categories,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }

    public function createCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required'
        ], [
            'category_name.required' => 'Enter category name'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
                'data' => ""
            ]);
        }
        try {
            $cat = $this->category->setNewCategory($request);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success Category Created!",
                'data' => [
                    "categories" => $cat,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }

    public function updateCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required'
        ], [
            'category_name.required' => 'Enter category name'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
                'data' => ""
            ]);
        }
        try {
            $cat = $this->category->updateCategory($request);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success",
                'data' => [
                    "category" => $cat,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }

}
