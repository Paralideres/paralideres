<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $table = 'polls';
    protected $hidden = [ 'pivot', 'created_at', 'updated_at', 'deleted_at' ];
    protected $fillable = [ 'question', 'active', 'date_from', 'date_to' ];

    // protected $dateFormat = 'd/m/Y H:i:s';

    protected $dateFormat = 'Y-m-d H:i:s';

    public function options()
    {
        return $this->hasMany('App\Models\PollOption');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }

    public function setDateFromAttribute($value)
    {
        $this->attributes['date_from'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function setDateToAttribute($value)
    {
        $this->attributes['date_to'] = Carbon::parse($value)->format('Y-m-d');
    }
}

