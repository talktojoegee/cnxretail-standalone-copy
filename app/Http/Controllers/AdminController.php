<?php

namespace App\Http\Controllers;

use App\Models\AdminActivityLog;
use App\Models\AdminNotification;
use App\Models\AdminUser;
use App\Models\BuyerRequest;
use App\Models\ContactUs;
use App\Models\DailyMotivation;
use App\Models\Item;
use App\Models\Pricing;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use App\Support\AdminMetrics;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    protected AdminMetrics $metrics;

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->metrics = new AdminMetrics();
        $this->adminuser = new AdminUser();
        $this->tenant = new Tenant();
        $this->subscription = new Subscription();
        $this->pricing = new Pricing();
        $this->dailymotivation = new DailyMotivation();
        $this->adminnotification = new AdminNotification();
    }

    public function notification()
    {
        return view('admin.notifications', [
            'notifications' => $this->adminnotification->getNotifications(),
        ]);
    }

    public function adminDashboard()
    {
        return view('admin.admin-dashboard', $this->metrics->dashboard());
    }

    public function showAddNewUserForm()
    {
        return view('admin.add-new-admin');
    }

    public function storeAdminUser(Request $request)
    {
        $this->validate($request, [
            'full_name' => 'required',
            'email' => 'required|email|unique:admin_users,email',
            'mobile_no' => 'required',
            'password' => 'required',
        ], [
            'full_name.required' => 'Enter user full name',
            'email.required' => 'Enter a valid email address',
            'email.email' => 'Enter a valid email address',
            'email.unique' => 'This email address is already in use',
            'mobile_no.required' => 'Enter user mobile number',
            'password.required' => 'Choose password for user',
        ]);

        $this->adminuser->createNewAdminUser($request);
        AdminActivityLog::record('Admin user', 'Created admin account for '.$request->email);
        session()->flash('success', 'Admin user was created successfully.');

        return back();
    }

    public function manageTenants(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:120',
            'status' => 'nullable|in:enabled,suspended,subscribed,expired,expiring',
            'plan' => 'nullable|integer|exists:pricings,id',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
            'per_page' => 'nullable|integer|in:10,25,50,100',
        ]);

        $perPage = (int) $request->input('per_page', 25);
        $scoped = $this->scopedTenants($request);

        $tenants = (clone $scoped)
            ->select([
                'id', 'company_name', 'email', 'phone_no', 'slug', 'plan_id',
                'account_status', 'start_date', 'end_date', 'created_at', 'address',
            ])
            ->with('getTenantPlan:id,price_name,price,duration')
            ->withCount([
                'products as products_count' => fn (Builder $query) => $query->where('item_type', 1),
                'products as services_count' => fn (Builder $query) => $query->where('item_type', 2),
                'users as users_count',
                'buyerRequests as buyer_requests_count',
            ])
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.manage-tenants', [
            'tenants' => $tenants,
            'stats' => $this->metrics->tenantSummary(clone $scoped),
            'plans' => Cache::remember('admin.pricing.options', 300, fn () => Pricing::query()->orderBy('duration')->get(['id', 'price_name', 'price', 'duration'])),
            'filters' => $request->only(['q', 'status', 'plan', 'from', 'to', 'per_page']),
        ]);
    }

    public function viewTenant($slug)
    {
        $tenant = Tenant::query()
            ->with(['getTenantPlan', 'getTenantSubscriptions.getSubscriptionPlan'])
            ->withCount([
                'products as products_count' => fn (Builder $query) => $query->where('item_type', 1),
                'products as services_count' => fn (Builder $query) => $query->where('item_type', 2),
                'users as users_count',
                'buyerRequests as buyer_requests_count',
            ])
            ->where('slug', $slug)
            ->first();

        if ($tenant === null) {
            session()->flash('error', 'No record found.');

            return back();
        }

        $recentRequests = BuyerRequest::query()
            ->with('item:id,item_name,slug')
            ->where('tenant_id', $tenant->id)
            ->latest('id')
            ->limit(8)
            ->get();

        return view('admin.view-tenant', [
            'tenant' => $tenant,
            'recentRequests' => $recentRequests,
        ]);
    }

    public function updateTenantStatus(Request $request, $slug)
    {
        $data = $request->validate([
            'status' => 'required|in:0,1',
        ]);

        $tenant = $this->tenant->getTenantBySlug($slug);
        if (empty($tenant)) {
            session()->flash('error', 'No record found.');

            return back();
        }

        $tenant->setAccountStatus((int) $data['status']);
        AdminMetrics::forgetDashboard();
        AdminActivityLog::record(
            'Tenant status',
            ($data['status'] == 1 ? 'Enabled' : 'Suspended').' tenant '.$tenant->company_name
        );
        session()->flash('success', $tenant->company_name.' was '.($data['status'] == 1 ? 'enabled' : 'suspended').'.');

        return back();
    }

    public function extendTenantSubscription(Request $request, $slug)
    {
        $data = $request->validate([
            'days' => 'required|integer|in:7,14,30,90,365',
        ]);

        $tenant = $this->tenant->getTenantBySlug($slug);
        if (empty($tenant)) {
            session()->flash('error', 'No record found.');

            return back();
        }

        $tenant->extendSubscription((int) $data['days']);
        AdminMetrics::forgetDashboard();
        AdminActivityLog::record(
            'Subscription',
            'Extended '.$tenant->company_name.' by '.$data['days'].' days to '.$tenant->end_date
        );
        session()->flash('success', 'Subscription extended by '.$data['days'].' days.');

        return back();
    }

    public function getTenantSubscriptions(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:120',
            'status' => 'nullable|in:active,expired',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
            'per_page' => 'nullable|integer|in:10,25,50,100',
        ]);

        $perPage = (int) $request->input('per_page', 25);
        $today = Carbon::today();
        $query = Subscription::query()
            ->select([
                'id', 'tenant_id', 'plan_id', 'start_date', 'end_date',
                'amount', 'charge', 'status', 'sub_key', 'created_at',
            ])
            ->with([
                'getTenant:id,company_name,slug,email,phone_no',
                'getSubscriptionPlan:id,price_name,price,duration',
            ])
            ->when($request->filled('q'), function (Builder $builder) use ($request) {
                $term = '%'.$request->input('q').'%';
                $builder->whereHas('getTenant', function (Builder $tenant) use ($term) {
                    $tenant->where('company_name', 'like', $term)
                        ->orWhere('email', 'like', $term);
                });
            })
            ->when($request->input('status') === 'active', fn (Builder $builder) => $builder->where('end_date', '>=', $today))
            ->when($request->input('status') === 'expired', fn (Builder $builder) => $builder->where('end_date', '<', $today))
            ->when($request->filled('from'), fn (Builder $builder) => $builder->where('created_at', '>=', Carbon::parse($request->input('from'))->startOfDay()))
            ->when($request->filled('to'), fn (Builder $builder) => $builder->where('created_at', '<=', Carbon::parse($request->input('to'))->endOfDay()));

        $summary = $this->firstAggregate(
            $query,
            'COUNT(*) as total,
             COALESCE(SUM(GREATEST(amount - charge, 0) / 100), 0) as revenue,
             SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as this_month,
             SUM(CASE WHEN end_date >= ? THEN 1 ELSE 0 END) as active,
             SUM(CASE WHEN end_date < ? THEN 1 ELSE 0 END) as expired',
            [Carbon::now()->startOfMonth(), $today, $today]
        );

        return view('admin.subscription', [
            'subscriptions' => (clone $query)->orderByDesc('id')->paginate($perPage)->withQueryString(),
            'stats' => [
                'total' => (int) ($summary->total ?? 0),
                'revenue' => (float) ($summary->revenue ?? 0),
                'this_month' => (int) ($summary->this_month ?? 0),
                'active' => (int) ($summary->active ?? 0),
                'expired' => (int) ($summary->expired ?? 0),
            ],
            'filters' => $request->only(['q', 'status', 'from', 'to', 'per_page']),
        ]);
    }

    public function managePricing()
    {
        return view('admin.manage-pricing', [
            'pricings' => $this->pricing->getAllPricing(),
        ]);
    }

    public function addPricing(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:pricings,price_name',
            'amount' => 'required',
            'duration' => 'required',
        ], [
            'name.required' => 'Enter price name',
            'name.unique' => 'Enter a unique price name',
            'amount.required' => 'Enter amount',
            'duration.required' => 'Enter duration for this pricing plan',
        ]);
        $this->pricing->setNewPricing($request);
        AdminActivityLog::record('Pricing', 'Added plan '.$request->name);
        session()->flash('success', 'New pricing added.');

        return back();
    }

    public function editPricing(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'amount' => 'required',
            'duration' => 'required',
            'price' => 'required',
        ], [
            'name.required' => 'Enter price name',
            'amount.required' => 'Enter amount',
            'duration.required' => 'Enter duration for this pricing plan',
        ]);
        $this->pricing->editPricing($request);
        AdminActivityLog::record('Pricing', 'Updated plan '.$request->name);
        session()->flash('success', 'Changes saved.');

        return back();
    }

    public function manageDailyMotivations()
    {
        return view('admin.manage-daily-motivation', [
            'motivations' => $this->dailymotivation->getAllDailyMotivations(),
        ]);
    }

    public function addDailyMotivation(Request $request)
    {
        $this->validate($request, [
            'time' => 'required',
            'author' => 'required',
            'motivation' => 'required',
        ], [
            'time.required' => 'Select time of day',
            'author.required' => 'Enter the name of the author or type Unknown',
            'motivation.required' => 'Enter motivation here...',
        ]);
        $this->dailymotivation->setNewDailyMotivation($request);
        session()->flash('success', 'Daily motivation added successfully.');

        return back();
    }

    public function updateDailyMotivation(Request $request)
    {
        $this->validate($request, [
            'time' => 'required',
            'author' => 'required',
            'motivation' => 'required',
            'daily' => 'required|integer|exists:daily_motivations,id',
        ], [
            'time.required' => 'Select time of day',
            'author.required' => 'Enter the name of the author or type Unknown',
            'motivation.required' => 'Enter motivation here...',
        ]);
        $this->dailymotivation->editDailyMotivation($request);
        session()->flash('success', 'Changes saved.');

        return back();
    }

    public function manageAdminUsers()
    {
        return view('admin.manage-admin-users', [
            'users' => $this->adminuser->getAllAdminUsers(),
        ]);
    }

    public function buyerRequests(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:120',
            'seller' => 'nullable|string|max:160',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
            'per_page' => 'nullable|integer|in:10,25,50,100',
        ]);

        $perPage = (int) $request->input('per_page', 25);
        $todayStart = Carbon::today();
        $query = BuyerRequest::query()
            ->select([
                'id', 'tenant_id', 'product_id', 'first_name', 'email',
                'phone_no', 'message', 'created_at',
            ])
            ->with([
                'tenant:id,company_name,slug,email',
                'item:id,item_name,slug,selling_price,item_type',
            ])
            ->when($request->filled('q'), function (Builder $builder) use ($request) {
                $term = '%'.$request->input('q').'%';
                $builder->where(function (Builder $inner) use ($term) {
                    $inner->where('first_name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('phone_no', 'like', $term)
                        ->orWhere('message', 'like', $term);
                });
            })
            ->when($request->filled('seller'), function (Builder $builder) use ($request) {
                $term = '%'.$request->input('seller').'%';
                $builder->whereHas('tenant', function (Builder $tenant) use ($term) {
                    $tenant->where('company_name', 'like', $term)
                        ->orWhere('email', 'like', $term);
                });
            })
            ->when($request->filled('from'), fn (Builder $builder) => $builder->where('created_at', '>=', Carbon::parse($request->input('from'))->startOfDay()))
            ->when($request->filled('to'), fn (Builder $builder) => $builder->where('created_at', '<=', Carbon::parse($request->input('to'))->endOfDay()));

        $summary = $this->firstAggregate(
            $query,
            'COUNT(*) as total,
             SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as this_month,
             SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as today,
             COUNT(DISTINCT tenant_id) as sellers,
             COUNT(DISTINCT email) as buyers',
            [Carbon::now()->startOfMonth(), $todayStart]
        );

        return view('admin.buyer-requests', [
            'requests' => (clone $query)->orderByDesc('id')->paginate($perPage)->withQueryString(),
            'stats' => [
                'total' => (int) ($summary->total ?? 0),
                'this_month' => (int) ($summary->this_month ?? 0),
                'today' => (int) ($summary->today ?? 0),
                'sellers' => (int) ($summary->sellers ?? 0),
                'buyers' => (int) ($summary->buyers ?? 0),
            ],
            'filters' => $request->only(['q', 'seller', 'from', 'to', 'per_page']),
        ]);
    }

    public function contactInquiries(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:120',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
            'per_page' => 'nullable|integer|in:10,25,50,100',
        ]);

        $perPage = (int) $request->input('per_page', 25);
        $todayStart = Carbon::today();
        $query = ContactUs::query()
            ->when($request->filled('q'), function (Builder $builder) use ($request) {
                $term = '%'.$request->input('q').'%';
                $builder->where(function (Builder $inner) use ($term) {
                    $inner->where('first_name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('subject', 'like', $term)
                        ->orWhere('message', 'like', $term);
                });
            })
            ->when($request->filled('from'), fn (Builder $builder) => $builder->where('created_at', '>=', Carbon::parse($request->input('from'))->startOfDay()))
            ->when($request->filled('to'), fn (Builder $builder) => $builder->where('created_at', '<=', Carbon::parse($request->input('to'))->endOfDay()));

        $summary = $this->firstAggregate(
            $query,
            'COUNT(*) as total,
             SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as this_month,
             SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as today',
            [Carbon::now()->startOfMonth(), $todayStart]
        );

        return view('admin.contact-inquiries', [
            'messages' => (clone $query)->orderByDesc('id')->paginate($perPage)->withQueryString(),
            'stats' => [
                'total' => (int) ($summary->total ?? 0),
                'this_month' => (int) ($summary->this_month ?? 0),
                'today' => (int) ($summary->today ?? 0),
            ],
            'filters' => $request->only(['q', 'from', 'to', 'per_page']),
        ]);
    }

    public function activityLogs(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:120',
            'per_page' => 'nullable|integer|in:10,25,50,100',
        ]);

        $perPage = (int) $request->input('per_page', 25);
        $query = AdminActivityLog::query()
            ->when($request->filled('q'), function (Builder $builder) use ($request) {
                $term = '%'.$request->input('q').'%';
                $builder->where(function (Builder $inner) use ($term) {
                    $inner->where('subject', 'like', $term)
                        ->orWhere('log', 'like', $term);
                });
            });

        $summary = $this->firstAggregate(
            $query,
            'COUNT(*) as total,
             SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as today',
            [Carbon::today()]
        );

        return view('admin.activity-logs', [
            'logs' => (clone $query)->orderByDesc('id')->paginate($perPage)->withQueryString(),
            'filters' => $request->only(['q', 'per_page']),
            'stats' => [
                'total' => (int) ($summary->total ?? 0),
                'today' => (int) ($summary->today ?? 0),
                'catalog' => Cache::remember('admin.stats.catalog', 60, fn () => Item::query()->count()),
                'users' => Cache::remember('admin.stats.users', 60, fn () => User::query()->count()),
            ],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    private function scopedTenants(Request $request): Builder
    {
        $query = Tenant::query();
        $this->applyTenantFilters($query, $request);

        return $query;
    }

    private function applyTenantFilters(Builder $query, Request $request): void
    {
        $today = Carbon::today();
        $expiringUntil = $today->copy()->addDays(14)->endOfDay();

        $query
            ->when($request->filled('q'), function (Builder $builder) use ($request) {
                $term = '%'.$request->input('q').'%';
                $builder->where(function (Builder $inner) use ($term) {
                    $inner->where('company_name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('phone_no', 'like', $term);
                });
            })
            ->when($request->filled('plan'), fn (Builder $builder) => $builder->where('plan_id', $request->input('plan')))
            ->when($request->filled('from'), fn (Builder $builder) => $builder->where('created_at', '>=', Carbon::parse($request->input('from'))->startOfDay()))
            ->when($request->filled('to'), fn (Builder $builder) => $builder->where('created_at', '<=', Carbon::parse($request->input('to'))->endOfDay()))
            ->when($request->input('status') === 'enabled', fn (Builder $builder) => $builder->where('account_status', 1))
            ->when($request->input('status') === 'suspended', fn (Builder $builder) => $builder->where('account_status', 0))
            ->when($request->input('status') === 'subscribed', fn (Builder $builder) => $builder->where('end_date', '>=', $today))
            ->when($request->input('status') === 'expired', fn (Builder $builder) => $builder->where('end_date', '<', $today))
            ->when($request->input('status') === 'expiring', function (Builder $builder) use ($today, $expiringUntil) {
                $builder->where('end_date', '>=', $today)
                    ->where('end_date', '<=', $expiringUntil);
            });
    }

    private function firstAggregate(Builder $query, string $expression, array $bindings = []): object
    {
        $base = (clone $query)->toBase();
        $base->columns = null;
        $base->bindings['select'] = [];

        return $base->selectRaw($expression, $bindings)->first() ?? (object) [];
    }
}
