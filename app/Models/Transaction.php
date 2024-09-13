<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'phone_number',
        'transaction_trx',
        'holiday_package_id',
        'payment_method_id',
        'total_amount',
        'duration',
        'transaction_date',
        'is_paid'
    ];

    public static function generateUniqueText()
    {
        $prefix = 'TRX';
        do {
            $randomString = $prefix . mt_rand(10000, 99999);
        }
        while (self::where('transaction_trx', $randomString)->exists());
        return $randomString;
    }

    public function getIsPaidAttribute($value)
    {
        return $value == 0 ? 'Unpaid' : 'Paid'; 
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function HolidayPackage()
    {
        return $this->belongsTo(HolidayPackage::class);
    }

    public function PaymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
