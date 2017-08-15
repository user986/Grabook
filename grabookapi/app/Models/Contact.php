<?php

namespace App\Models;

class Contact extends Apimodel {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'Contact';
    protected $primaryKey = 'ContactID';
    protected $fillable = ['EnteredByID', 'EnteredDate', 'ModifiedByID', 'ModifiedDate', 'Deleted', 
        'PersonID', 'ClientID', 'DivisionID', 'DepartmentID', 'PositionID', 'Title', 'ContactRatingID','CommunicationMethodID',
        'LastContactDate','AccountManagerRecruiterID','OnExchange', 'ContactFolderID','ContactOrganizedLevelID','ContactStatusID',
        'BusinessUnitID','CompanyHierachyID','OfficeID', 'Keywords', 'IndustryTypeID', 'OwnershipID', 'TechnologyID', 'OwnerID','NextContactDate','OwnerName'];

    public function person() {
        return $this->belongsTo('App\Models\Person', 'PersonID');
    }

}
