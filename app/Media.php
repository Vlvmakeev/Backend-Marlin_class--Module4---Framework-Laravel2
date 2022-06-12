<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $guarded = [];

    protected $table = 'media';

    public function user() {
        return $this->belongsTo('App\User');
    }
}
