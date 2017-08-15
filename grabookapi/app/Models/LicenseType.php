<?php

namespace App\Models;
class LicenseType extends Apimodel {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'licensetype';
    protected $primaryKey = 'LicenseTypeID';
    protected $fillable = ['EnteredByID', 'EnteredDate', 'ModifiedByID', 'ModifiedDate', 'Deleted', 'Description'];
    
}
