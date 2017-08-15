<?php

namespace App\Models;

use DB;

class Position extends Apimodel {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'Position';
    protected $primaryKey = 'PositionID';
    protected $fillable = ['EnteredByID', 'EnteredDate', 'ModifiedByID', 'ModifiedDate', 'Deleted', 'Description', 'DivisionID'];

}
