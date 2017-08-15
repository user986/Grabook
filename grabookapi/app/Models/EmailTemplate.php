<?php

namespace App\Models;

use DB;

class EmailTemplate extends Apimodel {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'EmailTemplate';
    protected $primaryKey = 'EmailTemplateID';
}
