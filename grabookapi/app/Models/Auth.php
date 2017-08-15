<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Auth extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	public $timestamps = false;
    protected $table = 'ApplicationUser';
	protected $primaryKey = 'UserID';
   

}
