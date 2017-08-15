<?php

namespace App\Models;

use DB;

class Skill extends Apimodel {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'Skill';
    protected $primaryKey = 'SkillID';
    protected $fillable = ['EnteredByID', 'EnteredDate', 'ModifiedByID', 'ModifiedDate', 'Deleted', 'Description', 'DivisionID'];

}
