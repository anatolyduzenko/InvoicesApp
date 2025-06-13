<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'company_name',
        'company_address',
        'company_email',
        'company_phone',
        'company_terms',
        'logo_path',
    ];
}
