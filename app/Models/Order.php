<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function customer(){
        return $this->belongsTo(User::class,'customer_id','id');
    }
    protected $fillable = [
        'order_number',
        'reservation_id',
        'status',
        'total',
        'paid_amount',
        'payment_method',
        'customer_id',
        'created_by'
    ];
}
