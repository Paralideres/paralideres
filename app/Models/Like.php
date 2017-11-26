<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Like extends Model
{

    use SoftDeletes;

    protected $table = 'resource_likes';

    protected $fillable = ['user_id'];

    protected $hidden = ['id', 'updated_at', 'created_at', 'deleted_at'];

    public function resource()
    {
        return $this->belongsTo('App\Models\Resource');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
