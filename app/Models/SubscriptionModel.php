<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionModel extends Model
{
    protected $table = 'subscriptions';
    protected $fillable = [
        'company_name',
        'site_url',
        'customer_secret',
        'customer_key',
        'annual_price',
        'notes',
        'start_date',
        'end_date',
        'status',
    ];

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isOngoing()
    {
        return now()->betweenIncluded($this->start_date, $this->end_date);
    }


}
