<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    public function hands()
    {
        return $this->belongsToMany('App\Hand', 'hands_to_situations_to_actions')->withPivot('percentage');
    }
    public function situations()
    {
        return $this->belongsToMany('App\Situation', 'hands_to_situations_to_actions')->withPivot('percentage');
    }

}
