<?php

namespace App\Models;
use DB;

class ApplicationUserApplicationUserGroup extends Apimodel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'ApplicationUser_ApplicationUserGroup';
	
   
     public function applicationUser()
    {
        return $this->belongsTo('App\Models\User','ApplicationUserID');
    }
  }
