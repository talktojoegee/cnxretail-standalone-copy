<?php

namespace App\Services\Marketplace;

use App\Models\Item;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CartService
{
    private const TTL_SECONDS = 60 * 60 * 24 * 14;

    private string $token;

    public function __construct(?string $token = null)
    {
        $this->token = $token ?: (string) Str::uuid();
    }

    public static function fromRequestHeader(?string $header): self
    {
        $token = is_string($header) && $header !== '' ? $header : null;

        return new self($token);
    }

    public function token(): string
    {
        return $this->token;
    }

    public function items(): array
    {
        return Cache::get($this->cacheKey(), []);
    }

    public function count(): int
    {
        return (int) array_sum($this->items());
    }

    public function add(int $itemId, int $qty = 1, ?int $max = null): void
    {
        $cart = $this->items();
        $next = ($cart[$itemId] ?? 0) + max(1, $qty);
        if ($max !== null && $max > 0) {
            $next = min($next, $max);
        }
        $cart[$itemId] = $next;
        $this->persist($cart);
    }

    public function update(int $itemId, int $qty, ?int $max = null): void
    {
        $cart = $this->items();
        if ($qty < 1) {
            unset($cart[$itemId]);
        } else {
            $cart[$itemId] = ($max !== null && $max > 0) ? min($qty, $max) : $qty;
        }
        $this->persist($cart);
    }

    public function remove(int $itemId): void
    {
        $cart = $this->items();
        unset($cart[$itemId]);
        $this->persist($cart);
    }

    public function clear(): void
    {
        Cache::forget($this->cacheKey());
    }

    public function lines(): Collection
    {
        $quantities = $this->items();
        if ($quantities === []) {
            return collect();
        }

        $products = Item::query()
            ->with(['getTenant', 'getCategory', 'getItemGalleryImages'])
            ->whereIn('id', array_keys($quantities))
            ->where('item_type', 1)
            ->get()
            ->keyBy('id');

        return collect($quantities)->map(function ($qty, $id) use ($products) {
            $item = $products->get($id);
            if ($item === null) {
                return null;
            }

            $qty = (int) $qty;

            return (object) [
                'item' => $item,
                'qty' => $qty,
                'line' => (float) $item->selling_price * $qty,
            ];
        })->filter()->values();
    }

    public function total(): float
    {
        return (float) $this->lines()->sum('line');
    }

    private function persist(array $cart): void
    {
        Cache::put($this->cacheKey(), $cart, self::TTL_SECONDS);
    }

    private function cacheKey(): string
    {
        return 'marketplace.cart.'.$this->token;
    }
}
