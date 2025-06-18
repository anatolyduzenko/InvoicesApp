<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'intermediary',
        'institution',
        'beneficiary',
        'account',
        'currency',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
