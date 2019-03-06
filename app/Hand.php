<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hand extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['hand'];

    public function actions()
    {
        return $this->belongsToMany('App\Action', 'hands_to_situations_to_actions')->withPivot('percentage');
    }
    public function situations()
    {
        return $this->belongsToMany('App\Situation', 'hands_to_situations_to_actions')->withPivot('percentage');
    }

}
