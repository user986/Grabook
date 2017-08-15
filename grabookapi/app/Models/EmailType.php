<?php

namespace App\Models;

use DB;

class EmailType extends Apimodel {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'EmailType';
    protected $primaryKey = 'EmailTypeID';
}
