<?php

namespace App\Models;

use DB;

class Client extends Apimodel {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'Client';
    protected $primaryKey = 'ClientID';

}
