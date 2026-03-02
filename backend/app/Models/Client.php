<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;
    protected $table = 'clients';

    protected $fillable = [
        'name',
        'email',
    ];


    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }


     # balance (credits - debits)

    public function getBalanceAttribute()
    {
        return $this->transactions()->sum(
            DB::raw("CASE WHEN type = 'credit' THEN amount ELSE -amount END")
        );
    }
}
