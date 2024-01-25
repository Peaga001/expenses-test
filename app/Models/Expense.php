<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    public $primaryKey = 'id';
    public $fillable   = [
        'description',
        'date',
        'user_id',
        'value'
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public $casts = [
        'date' => 'date'
    ];
}
