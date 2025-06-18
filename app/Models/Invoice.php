<?php

namespace App\Models;

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
        'account_id',
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

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeForCustomer($query, int $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeIssueDate($query, string $date)
    {
        return $query->whereDate('issue_date', '>=', $date);
    }

    public function scopeDueDate($query, string $date)
    {
        return $query->whereDate('due_date', '>=', $date);
    }

    public static function generateInvoiceNumber(): string
    {
        $latest = self::orderByDesc('id')->first();
        $nextNumber = $latest ? $latest->id + 1 : 1;

        return 'INV-'.str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
}
