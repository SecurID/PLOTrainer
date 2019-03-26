<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Situation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'active'];

    public function actions()
    {
        return $this->belongsToMany('App\Action', 'hands_to_situations_to_actions')->withPivot('percentage');
    }
    public function hands()
    {
        return $this->belongsToMany('App\Hand', 'hands_to_situations_to_actions')->withPivot('percentage');
    }

    public function answers()
    {
        return $this->hasMany('App\Answer');
    }
}
