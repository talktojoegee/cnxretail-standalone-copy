<?php

namespace App\Support;

use App\Models\Item;
use Illuminate\Support\Collection;

class MarketplaceCart
{
    public const SESSION_KEY = 'marketplace.cart';

    public function items(): array
    {
        return session(self::SESSION_KEY, []);
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
        session([self::SESSION_KEY => $cart]);
    }

    public function update(int $itemId, int $qty, ?int $max = null): void
    {
        $cart = $this->items();
        if ($qty < 1) {
            unset($cart[$itemId]);
        } else {
            $cart[$itemId] = ($max !== null && $max > 0) ? min($qty, $max) : $qty;
        }
        session([self::SESSION_KEY => $cart]);
    }

    public function remove(int $itemId): void
    {
        $cart = $this->items();
        unset($cart[$itemId]);
        session([self::SESSION_KEY => $cart]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
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
}
