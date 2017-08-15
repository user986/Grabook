<?php

namespace App\Models;
class MarketingSource extends Apimodel {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'marketingsource';
    protected $primaryKey = 'MarketingSourceID';
    protected $fillable = ['EnteredByID', 'EnteredDate', 'ModifiedByID', 'ModifiedDate', 'Deleted', 'Description'];
    
}
