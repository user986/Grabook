<?php

namespace App\Models;
use DB;

class Recruiter extends Apimodel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'Recruiter';
	protected $primaryKey = 'RecruiterID';

    public function person()
    {
        return $this->belongsTo('App\Models\Person','PersonID');
    }
	
  }
