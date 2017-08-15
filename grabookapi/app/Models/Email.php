<?php

namespace App\Models;

use DB;

class Email extends Apimodel {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'Email';
    protected $primaryKey = 'EmailID';
    protected $fillable = ['EnteredByID','EnteredDate','ModifiedByID','ModifiedDate','Deleted','PersonID','EmailTypeID','EmailAddress','Description'];

    public function person() {
        return $this->belongsTo('App\Models\Person', 'PersonID');
    }

}
