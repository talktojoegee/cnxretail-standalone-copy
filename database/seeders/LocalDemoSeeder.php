<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Demo vendors, products and services for local development only.
 *
 * Run with: php artisan db:seed --class=LocalDemoSeeder
 * Running it again replaces the demo data. It refuses to run outside the local environment.
 */
class LocalDemoSeeder extends Seeder
{
    private const EMAIL_DOMAIN = '@demo.cnxretail.test';
    private const IMAGE_SOURCE = 'assets/marketplace/assets/images/demos/demo1/products/';
    private const CATEGORY_IMAGE_SOURCE = 'assets/marketplace/assets/images/categories/';
    private const IMAGE_PREFIX = 'demo-';

    private array $vendors = [
        [
            'company_name' => 'Adaeze Fashion House',
            'address' => 'Balogun Market, Lagos Island, Lagos',
            'tagline' => 'Ready-to-wear and everyday fashion',
            'category' => 'Fashion',
            'products' => [
                ['Women’s linen shirt dress', 18500, 'category-16.jpg'],
                ['Classic wool overcoat', 42000, '4-1.jpg'],
                ['Zip-up hoodie, grey', 15000, '4-4-1.jpg'],
                ['Women’s casual top', 9500, 'category-21.jpg'],
            ],
        ],
        [
            'company_name' => 'Ikeja Gadget Hub',
            'address' => 'Computer Village, Ikeja, Lagos',
            'tagline' => 'Phones, laptops and accessories',
            'category' => 'Electronics',
            'products' => [
                ['Wireless over-ear headphones', 45000, '5-7.jpg'],
                ['Smartwatch with fitness tracking', 32000, '2-5.jpg'],
                ['13-inch ultrabook laptop', 650000, '2-8.jpg'],
                ['10,000mAh power bank', 12500, '5-8.jpg'],
                ['Smartphone, 128GB', 285000, '3-3.jpg'],
            ],
        ],
        [
            'company_name' => 'Mama Ngozi Home Essentials',
            'address' => 'Wuse Market, Abuja',
            'tagline' => 'Kitchen and home essentials',
            'category' => 'Home & Kitchen',
            'products' => [
                ['Electric slow cooker, 3.5L', 38900, '2-1.jpg'],
                ['Stainless steel pressure pot', 27500, 'category-11.jpg'],
                ['Digital rice cooker', 34000, '6-5.jpg'],
                ['Microfibre towel set', 8500, '3-4-1.jpg'],
            ],
        ],
        [
            'company_name' => 'Kano Leather Works',
            'address' => 'Kurmi Market, Kano',
            'tagline' => 'Handmade bags and leather goods',
            'category' => 'Bags & Leather',
            'products' => [
                ['Leather weekend travel bag', 28500, '4-5-1.jpg'],
                ['Burgundy duffel bag', 31000, '4-5-2.jpg'],
                ['Crossbody shoulder bag', 16500, 'category-10.jpg'],
                ['Structured handbag', 22000, '3-2.jpg'],
            ],
        ],
        [
            'company_name' => 'FixIt Gadget Repairs',
            'address' => 'Allen Avenue, Ikeja, Lagos',
            'tagline' => 'Same-day phone and laptop repairs',
            'services' => [
                ['Phone screen replacement', 15000, '3-3.jpg'],
                ['Laptop repair and servicing', 20000, '3-5.jpg'],
                ['Washing machine repair', 18000, '2-6.jpg'],
            ],
        ],
        [
            'company_name' => 'SnapShot Studios',
            'address' => 'Lekki Phase 1, Lagos',
            'tagline' => 'Event and product photography',
            'services' => [
                ['Event photography, half day', 120000, '2-2.jpg'],
                ['Product photography for online stores', 45000, 'category-5.jpg'],
            ],
        ],
        [
            'company_name' => 'SafeHome Installations',
            'address' => 'Garki, Abuja',
            'tagline' => 'CCTV, TV mounting and smart home setup',
            'services' => [
                ['CCTV camera installation', 60000, '2-7.jpg'],
                ['TV wall mounting', 15000, '2-9.jpg'],
            ],
        ],
    ];

    public function run(): void
    {
        if (! app()->environment('local')) {
            $this->command->warn('LocalDemoSeeder only runs in the local environment. Nothing was changed.');

            return;
        }

        DB::transaction(function () {
            $this->removeExistingDemoData();

            foreach ($this->vendors as $vendor) {
                $tenantId = $this->createVendor($vendor);

                if (isset($vendor['products'])) {
                    $categoryId = DB::table('categories')->insertGetId([
                        'tenant_id' => $tenantId,
                        'category_name' => $vendor['category'],
                        'slug' => Str::slug($vendor['category']).'-'.Str::lower(Str::random(6)),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    foreach ($vendor['products'] as [$name, $price, $image]) {
                        $this->createItem($tenantId, $categoryId, 1, $name, $price, $image, $vendor['tagline']);
                    }
                }

                foreach ($vendor['services'] ?? [] as [$name, $price, $image]) {
                    $this->createItem($tenantId, null, 2, $name, $price, $image, $vendor['tagline']);
                }
            }
        });

        $this->command->info('Demo vendors, products and services created.');
    }

    private function removeExistingDemoData(): void
    {
        $tenantIds = DB::table('tenants')->where('email', 'like', '%'.self::EMAIL_DOMAIN)->pluck('id');

        DB::table('item_galleries')->whereIn('tenant_id', $tenantIds)->delete();
        DB::table('items')->whereIn('tenant_id', $tenantIds)->delete();
        DB::table('categories')->whereIn('tenant_id', $tenantIds)->delete();
        DB::table('tenants')->whereIn('id', $tenantIds)->delete();

        File::delete(File::glob(public_path('assets/drive/'.self::IMAGE_PREFIX.'*')));
    }

    private function createVendor(array $vendor): int
    {
        $slug = Str::slug($vendor['company_name']);

        return DB::table('tenants')->insertGetId([
            'company_name' => $vendor['company_name'],
            'email' => $slug.self::EMAIL_DOMAIN,
            'phone_no' => '0800 000 0000',
            'address' => $vendor['address'],
            'tagline' => $vendor['tagline'],
            'description' => $vendor['tagline'].'. Demo vendor for local development.',
            'slug' => $slug,
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createItem(int $tenantId, ?int $categoryId, int $type, string $name, int $price, string $image, string $tagline): void
    {
        $itemId = DB::table('items')->insertGetId([
            'tenant_id' => $tenantId,
            'category_id' => $categoryId,
            'item_name' => $name,
            'item_type' => $type,
            'quantity' => $type === 1 ? 25 : 0,
            'cost_price' => $type === 1 ? round($price * 0.7) : 0,
            'selling_price' => $price,
            'description' => $name.'. '.$tagline.'.',
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(6)),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Copy the template image into the drive folder, where item photos are served from.
        $file = self::IMAGE_PREFIX.$itemId.'-'.$image;
        $source = str_starts_with($image, 'category-') ? self::CATEGORY_IMAGE_SOURCE : self::IMAGE_SOURCE;
        File::copy(public_path($source.$image), public_path('assets/drive/'.$file));

        DB::table('item_galleries')->insert([
            'tenant_id' => $tenantId,
            'item_id' => $itemId,
            'attachment' => $file,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
