<?php

namespace App\Models;

use DB;

class PhoneType extends Apimodel {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'PhoneType';
    protected $primaryKey = 'PhoneTypeID';
}
