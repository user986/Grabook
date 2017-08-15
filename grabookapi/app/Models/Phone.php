<?php

namespace App\Models;

use DB;

class Phone extends Apimodel {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'Phone';
    protected $primaryKey = 'PhoneID';
    protected $fillable = ['EnteredByID', 'EnteredDate', 'ModifiedByID', 'ModifiedDate', 'Deleted', 'PersonID', 'PhoneTypeID', 'PhoneNumber', 'CountryCode', 'AreaCode', 'Extension', 'Description'];

    public function person() {
        return $this->belongsTo('App\Models\Person', 'PersonID');
    }

}
