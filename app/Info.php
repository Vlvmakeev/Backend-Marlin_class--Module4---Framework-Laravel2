<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    protected $guarded = [];

    protected $table = 'info';

    public function user() {
        return $this->belongsTo('App\User');
    }
}
