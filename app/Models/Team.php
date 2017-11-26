<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * Get all the team Users
     */
    public function users() {
        return $this->hasMany('App\Models\User');
    }

    public function resources() {
        return $this->hasMany('App\Models\Team_Resource');
    }
}
