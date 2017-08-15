<?php

namespace App\Models;

use DB;

class Person extends Apimodel {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'Person';
    protected $primaryKey = 'PersonID';
    protected $fillable = ['FirstName', 'LastName', 'MiddleName','EnteredByID','EnteredDate','ModifiedByID','ModifiedDate','Deleted'];

    public function phone() {
        return $this->belongsTo('App\Models\Phone', 'DefaultPhoneID');
    }

    public function address() {
        return $this->belongsTo('App\Models\Address', 'DefaultAddressID');
    }

    public function email() {
        return $this->belongsTo('App\Models\Email', 'DefaultEmailID');
    }

}
