<?php

namespace App\Http\Controllers;

use App\Models\BuyerRequest;
use App\Models\Category;
use App\Models\Contact;
use App\Models\ContactUs;
use App\Models\Item;
use App\Models\Tenant;
use App\Support\MarketplaceCart;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->item = new Item();
        $this->category = new Category();
        $this->buyerrequest = new BuyerRequest();
        $this->contactus = new ContactUs();
        $this->tenant = new Tenant();
    }

    public function index(Request $request)
    {
        $productCategories = $this->topGroups(
            Item::query()->where('item_type', 1)->whereNotNull('category_id'),
            'category_id',
            Category::class
        );
        $serviceSellers = $this->topGroups(
            Item::query()->where('item_type', 2),
            'tenant_id',
            Tenant::class
        );

        $activeProduct = $productCategories->firstWhere('slug', $request->input('products'));
        $activeSeller = $serviceSellers->firstWhere('slug', $request->input('services'));

        $products = Item::query()
            ->where('item_type', 1)
            ->with(['getCategory', 'getTenant', 'getItemGalleryImages'])
            ->when($activeProduct, function (Builder $query) use ($activeProduct) {
                $query->where('category_id', $activeProduct->id);
            })
            ->inRandomOrder()
            ->limit(20)
            ->get();

        $services = Item::query()
            ->where('item_type', 2)
            ->with(['getTenant', 'getItemGalleryImages'])
            ->when($activeSeller, function (Builder $query) use ($activeSeller) {
                $query->where('tenant_id', $activeSeller->id);
            })
            ->inRandomOrder()
            ->limit(20)
            ->get();

        return view('frontend.index', [
            'products' => $products,
            'services' => $services,
            'productCategories' => $productCategories,
            'serviceSellers' => $serviceSellers,
            'activeProduct' => $activeProduct,
            'activeSeller' => $activeSeller,
        ]);
    }

    public function marketplace(Request $request)
    {
        if ($request->boolean('cards')) {
            return $this->presentCatalog($request, []);
        }

        $filtered = $request->hasAny(['keyword', 'category', 'vendor', 'sort']);
        $slides = ! $filtered && (int) $request->input('page', 1) === 1
            ? $this->catalogQuery(new Request())->whereHas('getItemGalleryImages')->latest()->take(4)->get()
            : collect();

        return $this->presentCatalog($request, [
            'slides' => $slides,
            'heading' => 'Products',
            'activeVendor' => $request->input('vendor'),
        ]);
    }

    public function viewItem($slug)
    {
        $item = Item::query()
            ->with(['getCategory', 'getTenant', 'getItemGalleryImages'])
            ->where('slug', $slug)
            ->whereIn('item_type', [1, 2])
            ->first();

        if ($item === null) {
            return redirect()->route('marketplace')->with('error', 'That product is no longer listed.');
        }

        $related = Item::query()
            ->with(['getCategory', 'getTenant', 'getItemGalleryImages'])
            ->where('item_type', $item->item_type)
            ->where('id', '!=', $item->id)
            ->when($item->category_id, function (Builder $query) use ($item) {
                $query->where('category_id', $item->category_id);
            })
            ->latest()
            ->take(4)
            ->get();

        return view('frontend.view-item', [
            'item' => $item,
            'related' => $related,
        ]);
    }

    public function vendorStore(Request $request, $slug)
    {
        $vendor = $this->tenant->getTenantBySlug($slug);
        if (empty($vendor)) {
            return redirect()->route('marketplace')->with('error', 'That store could not be found.');
        }

        $request->merge(['vendor' => $vendor->slug]);

        return $this->presentCatalog($request, [
            'slides' => collect(),
            'heading' => $vendor->company_name,
            'activeVendor' => $vendor->slug,
        ]);
    }

    public function productCategories(Request $request, $slug)
    {
        $category = $this->category->getCategoryBySlug($slug);
        if (empty($category)) {
            return redirect()->route('marketplace')->with('error', 'That category could not be found.');
        }

        $request->merge(['category' => $category->slug]);

        return $this->presentCatalog($request, [
            'slides' => collect(),
            'heading' => $category->category_name,
            'activeVendor' => $request->input('vendor'),
        ]);
    }

    public function searchProduct(Request $request)
    {
        $request->validate([
            'keyword' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:120',
            'vendor' => 'nullable|string|max:160',
            'sort' => 'nullable|in:newest,price_asc,price_desc,name',
        ]);

        $heading = $request->filled('keyword')
            ? 'Results for “'.$request->input('keyword').'”'
            : 'Products';

        return $this->presentCatalog($request, [
            'slides' => collect(),
            'heading' => $heading,
            'activeVendor' => $request->input('vendor'),
        ]);
    }

    public function cart(MarketplaceCart $cart)
    {
        return view('frontend.cart', [
            'lines' => $cart->lines(),
            'total' => $cart->total(),
        ]);
    }

    public function addToCart(Request $request, MarketplaceCart $cart)
    {
        $data = $request->validate([
            'item' => 'required|integer|exists:items,id',
            'qty' => 'nullable|integer|min:1|max:99',
        ]);

        $item = Item::query()->where('id', $data['item'])->where('item_type', 1)->firstOrFail();
        $qty = (int) ($data['qty'] ?? 1);
        $max = (int) $item->quantity > 0 ? (int) $item->quantity : null;
        if ($max !== null) {
            $qty = min($qty, $max);
        }

        $cart->add($item->id, $qty, $max);

        return back()->with('success', $item->item_name.' was added to your cart.');
    }

    public function updateCart(Request $request, MarketplaceCart $cart)
    {
        $data = $request->validate([
            'item' => 'required|integer|exists:items,id',
            'qty' => 'required|integer|min:0|max:99',
        ]);

        $item = Item::query()->findOrFail($data['item']);
        $max = (int) $item->quantity > 0 ? (int) $item->quantity : null;
        $cart->update((int) $item->id, (int) $data['qty'], $max);

        return back()->with('success', 'Cart updated.');
    }

    public function removeFromCart(Request $request, MarketplaceCart $cart)
    {
        $data = $request->validate([
            'item' => 'required|integer',
        ]);
        $cart->remove((int) $data['item']);

        return back()->with('success', 'Item removed from your cart.');
    }

    public function checkout(MarketplaceCart $cart)
    {
        $lines = $cart->lines();
        if ($lines->isEmpty()) {
            return redirect()->route('marketplace.cart')->with('error', 'Your cart is empty.');
        }

        return view('frontend.checkout', [
            'lines' => $lines,
            'total' => $cart->total(),
        ]);
    }

    public function placeOrder(Request $request, MarketplaceCart $cart)
    {
        $lines = $cart->lines();
        if ($lines->isEmpty()) {
            return redirect()->route('marketplace.cart')->with('error', 'Your cart is empty.');
        }

        $data = $request->validate([
            'first_name' => 'required|string|max:80',
            'email' => 'required|email|max:120',
            'mobile_no' => 'required|string|max:30',
            'address' => 'required|string|max:180',
            'note' => 'nullable|string|max:180',
        ]);

        $reference = 'CNX-'.strtoupper(Str::random(6));
        foreach ($lines as $line) {
            $request->merge([
                'item' => $line->item->id,
                'first_name' => $data['first_name'],
                'email' => $data['email'],
                'mobile_no' => $data['mobile_no'],
                'message' => Str::limit(
                    $reference.' · Qty '.$line->qty.' · Deliver to '.$data['address'].($data['note'] ? ' · '.$data['note'] : ''),
                    240
                ),
            ]);
            $this->buyerrequest->setNewBuyerRequest($request, $line->item->tenant_id);
        }

        $cart->clear();

        return redirect()->route('marketplace')->with('success', 'Order '.$reference.' was sent to the sellers. They will contact you to confirm delivery.');
    }

    public function contactUs(){
        return view('frontend.contact-us');
    }

    public function buyerRequest(Request $request){
        $this->validate($request,[
            'item'=>'required',
            'first_name'=>'required',
            'email'=>'required|email',
            'mobile_no'=>'required',
            'message'=>'required'
        ],[
            'first_name.required'=>'Enter your first name',
            'email.required'=>'Enter your email address',
            'email.email'=>'Enter a valid email address',
            'mobile.required'=>'Enter your mobile number',
            'message.required'=>'Type your message in the field provided',
        ]);

        $item = $this->item->getItemById($request->item);
        if(!empty($item)){
            $this->buyerrequest->setNewBuyerRequest($request, $item->tenant_id);
            session()->flash("success", "Your request was submitted successfully.");
            return back();
        }else{
            return back();
        }
    }

    public function saveContactUs(Request $request){
        $this->validate($request,[
            'first_name'=>'required',
            'email'=>'required|email',
            'subject'=>'required',
            'mobile_no'=>'required',
            'message'=>'required'
        ],[
            'first_name.required'=>'Enter your first name',
            'email.required'=>'Enter your email address',
            'email.email'=>'Enter a valid email address',
            'mobile_no.required'=>'Enter mobile no.',
            'message.required'=>'Help us understand your concerns. Type your message.'
        ]);
        $this->contactus->setNewContactUs($request);
        session()->flash("success", "<strong>Thank you!</strong> Your message was sent successfully. You'll hear from us soonest.");
        return back();
    }

    private function topGroups(Builder $items, string $column, string $model)
    {
        $ids = (clone $items)
            ->select($column)
            ->selectRaw('count(*) as aggregate')
            ->groupBy($column)
            ->orderByDesc('aggregate')
            ->limit(4)
            ->pluck($column);

        return $model::query()
            ->whereIn('id', $ids)
            ->get()
            ->sortBy(function ($row) use ($ids) {
                return $ids->search($row->id);
            })
            ->values();
    }

    private function presentCatalog(Request $request, array $data)
    {
        $items = $this->catalogQuery($request)->paginate(12)->withQueryString();
        if ($request->boolean('cards')) {
            return view('partials.marketplace._product-cards', ['items' => $items]);
        }

        $data['items'] = $items;

        return view('frontend.marketplace', $data);
    }

    private function catalogQuery(Request $request): Builder
    {
        $query = Item::query()
            ->where('item_type', 1)
            ->with(['getCategory', 'getTenant', 'getItemGalleryImages']);

        if ($request->filled('keyword')) {
            $term = '%'.$request->input('keyword').'%';
            $query->where(function (Builder $query) use ($term) {
                $query->where('item_name', 'like', $term)
                    ->orWhere('description', 'like', $term);
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('getCategory', function (Builder $query) use ($request) {
                $query->where('slug', $request->input('category'));
            });
        }

        if ($request->filled('vendor')) {
            $query->whereHas('getTenant', function (Builder $query) use ($request) {
                $query->where('slug', $request->input('vendor'));
            });
        }

        return match ($request->input('sort')) {
            'price_asc' => $query->orderBy('selling_price'),
            'price_desc' => $query->orderByDesc('selling_price'),
            'name' => $query->orderBy('item_name'),
            default => $query->latest(),
        };
    }
}
