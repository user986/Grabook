<?php

namespace App\Models;

use DB;

class IndustryType extends Apimodel {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'IndustryType';
    protected $primaryKey = 'IndustryTypeID';
    protected $fillable = ['EnteredByID', 'EnteredDate', 'ModifiedByID', 'ModifiedDate', 'Deleted', 'Description', 'DivisionID'];
}
