<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

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
