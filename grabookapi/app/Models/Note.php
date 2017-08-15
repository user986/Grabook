<?php

namespace App\Models;

use DB;

class Note extends Apimodel {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'Note';
    protected $primaryKey = 'NoteID';
    protected $fillable = ['EnteredByID', 'EnteredDate', 'ModifiedByID', 'ModifiedDate', 'Deleted', 'PersonID', 'NoteTypeID', 'NoteText', 'NoteHtml'];

    public function person() {
        return $this->belongsTo('App\Models\Person', 'PersonID');
    }

}
