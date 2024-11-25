<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        "title",
        "description",
        "date",
        "image",
        "donationTotal"
    ];

    public function donation(){
        return $this->hasMany(Donation::class);
    }
}
