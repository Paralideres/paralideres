<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use SoftDeletes;

    protected $table = 'collections';

    protected $fillable = ['label', 'slug', 'description', 'category_id'];

    protected $hidden = [
        'user_id', 'category_id', 'created_at', 'updated_at', 'deleted_at'
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function resources()
    {
        return $this->belongsToMany('App\Models\Resource')->withTimestamps();
    }
}
