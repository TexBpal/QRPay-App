<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionSetting extends Model
{
    use HasFactory;
    protected $guarded = ['id','slug'];
    protected $with = ['admin'];
    protected $casts = [
        'admin_id' => 'integer',
        'slug' => 'string',
        'title' => 'string',
        'fixed_charge' => 'double',
        'percent_charge' => 'double',
        'min_limit' => 'double',
        'max_limit' => 'double',
        'monthly_limit' => 'double',
        'daily_limit' => 'double',
        'status' => 'integer',
        'agent_fixed_commissions' => 'double',
        'agent_percent_commissions' => 'double',
        'agent_profit' => 'boolean',
    ];



    public function admin() {
        return $this->belongsTo(Admin::class);
    }
}
