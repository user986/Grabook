<?php

namespace App\Models;

use DB;

class Ownership extends Apimodel {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'Ownership';
    protected $primaryKey = 'OwnershipID';

}
