<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = ['description', 'amount', 'user_id', 'category'];

    public function getAmountAttribute($value)
    {
        return (float) $value; // Explicitly cast the value to a float
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}