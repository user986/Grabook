<?php

namespace App\Models;
use DB;

class Country extends Apimodel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'Country';
	protected $primaryKey = 'CountryID';
   
       /**
         * Get the comments for the blog post.
         */
        public function state()
        {
            return $this->hasMany('App\Models\State','CountryID');
        }
  }
