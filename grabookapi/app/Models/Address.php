<?php

namespace App\Models;

use DB;

class Address extends Apimodel {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'Address';
    protected $primaryKey = 'AddressID';
    protected $fillable = ['AddressTypeID', 'CityId', 'ProvinceStateId','CountryId','PersonID','MailingAddress','EnteredByID','EnteredDate','ModifiedByID','ModifiedDate','Deleted','Street',
        'Direction','GeographicLocationID','Latitude','Longitude','Street2','Description','County'];
    public function person() {
        return $this->belongsTo('App\Models\Person', 'PersonID');
    }

}
