<?php

namespace App\Models;
use DB;

class State extends Apimodel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'ProvinceState';
	protected $primaryKey = 'ProvinceStateID';

        public function city()
        {
            return $this->hasMany('App\Models\City','ProvinceStateID');
        }
  }
