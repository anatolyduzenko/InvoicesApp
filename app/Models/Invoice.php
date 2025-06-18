<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'number',
        'status',
        'issue_date',
        'due_date',
        'company_id',
        'customer_id',
        'total_amount',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($invoice) {
    //         if (!$invoice->number) {
    //             $invoice->number = self::generateInvoiceNumber();
    //         }
    //         if(!$invoice->company_id) {
    //             $invoice->company_id = Company::first()->id;
    //         }
    //         if(!$invoice->customer_id) {
    //             $invoice->customer_id = Customer::first()->id;
    //         }
    //         if(!$invoice->account_id) {
    //             $invoice->account_id = Account::first()->id;
    //         }
    //         if (!$invoice->issue_date) {
    //             $invoice->issue_date = Carbon::now()->format('Y-m-d');
    //         }
    //         if (!$invoice->due_date) {
    //             $invoice->due_date = Carbon::now()->format('Y-m-d');
    //         }
    //     });
    // }

    public static function generateInvoiceNumber(): string
    {
        $latest = self::orderByDesc('id')->first();
        $nextNumber = $latest ? $latest->id + 1 : 1;

        return 'INV-'.str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
}
