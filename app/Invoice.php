<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /**
     * Attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'number',
        'supplied_at',
        'comment'
    ];

    /**
     * @return string
     */
    public function getSuppliedAtAttribute()
    {
        return Carbon::parse($this->attributes["supplied_at"])->toDateString();
    }

    /**
     * @return string
     */
    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes["created_at"])->toDateString();
    }
}
