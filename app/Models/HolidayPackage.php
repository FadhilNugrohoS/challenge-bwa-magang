<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class HolidayPackage extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'slug',
        'price',
        'duration',
        'location',
        'category_id',
        'available'
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function Category()
    {
        return $this->belongsTo(Category::class);
    }

    public function Transaction()
    {
        return $this->hasMany(Transaction::class);
    }
}
