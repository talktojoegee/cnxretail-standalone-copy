<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AdminActivityLog extends Model
{
    use HasFactory;

    protected $fillable = ['subject', 'log'];

    public static function record(string $subject, string $log): void
    {
        $admin = Auth::guard('admin')->user();
        $actor = $admin ? ($admin->full_name ?: $admin->email) : 'System';

        static::query()->create([
            'subject' => $subject,
            'log' => '['.$actor.'] '.$log,
        ]);
    }
}
