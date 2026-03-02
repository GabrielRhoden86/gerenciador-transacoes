<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'client_id',
        'type',
        'amount',
        'description',
    ];

    /**
     * A transaction belongs to a client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
