<?php

namespace App\Models;

use DB;

class Salutation extends Apimodel {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'salutation';
    protected $primaryKey = 'SalutationID';
}
