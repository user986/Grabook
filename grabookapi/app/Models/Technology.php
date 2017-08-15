<?php

namespace App\Models;

use DB;

class Technology extends Apimodel {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'Technology';
    protected $primaryKey = 'TechnologyID';
    protected $fillable = ['EnteredByID', 'EnteredDate', 'ModifiedByID', 'ModifiedDate', 'Deleted', 'Description'];


}
