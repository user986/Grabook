<?php

namespace App\Models;
class User extends Apimodel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'users';
	protected $primaryKey = 'id';

  }
