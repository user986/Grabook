<?php

namespace App\Models;

use DB;

class Division extends Apimodel {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'Division';
    protected $primaryKey = 'DivisionID';

    public function Position() {
        return $this->hasMany('App\Models\Position', 'DivisionID');
    }
    public function Skill() {
        return $this->hasMany('App\Models\Skill', 'DivisionID');
    }
    public function IndustryType() {
        return $this->hasMany('App\Models\IndustryType', 'DivisionID');
    }
}
