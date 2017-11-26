<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $table = 'categories';

    protected $fillable = ['label', 'slug', 'description'];

    protected $hidden = [
        'created_at', 'updated_at', 'former_id'
    ];

    public function collections()
    {
        return $this->hasMany('App\Models\Collection');
    }

    public function resources($user_id = null)
    {
        return $this->hasMany('App\Models\Resource')->with(['likesCount', 'category', 'user', 'like' => function($q)use($user_id){
            if(!empty($user_id) && $user_id !=null)
            $q->where('user_id', $user_id);
        }]);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
