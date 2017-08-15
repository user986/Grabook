<?php

namespace App\Models;
class Education extends Apimodel {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'education';
    protected $primaryKey = 'EducationID';
    protected $fillable = ['EnteredByID', 'EnteredDate', 'ModifiedByID', 'ModifiedDate', 'Deleted', 'Description'];
    
}
