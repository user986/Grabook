<?php

namespace App\Models;
class ApplicationLanguage extends Apimodel {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'ApplicationLanguage';
    protected $primaryKey = 'ApplicationLanguageID';
    protected $fillable = ['EnteredByID', 'EnteredDate', 'ModifiedByID', 'ModifiedDate', 'Deleted', 'Description'];
    
}
