<?php

namespace App\Repositories;

//use App\Models\Contact;
//use App\Models\Country;
use App\Models\Email;
use App\Models\Address;
use App\Models\Phone;
use App\Models\Person;
use App\Models\Note;
use App\Models\Contact;

class CommonRepository extends BaseRepository {

    protected $model;

    /**
     * Used to save Person
     * @param type $inputs
     * @return type
     */
    public function savePerson($inputs) {
        //$person = new Person;
        $id = (isset($inputs['PersonID']) && !empty($inputs['PersonID']))?$inputs['PersonID']:'0';
        $person = Person::firstOrNew(array('PersonID' => $id));
        $person->fill($inputs);
        if ($person->save()) {
            return $person->PersonID;
        }
    }

    /**
     * used to save Address
     * @param type $inputs
     * @return type
     */
    public function saveAddress($inputs) {
        $id = (isset($inputs['AddressID']) && !empty($inputs['AddressID']))?$inputs['AddressID']:'0';        
        //$address = new Address;
        $address = Address::firstOrNew(array('AddressID' => $id));
        $address->fill($inputs);
        if ($address->save()) {
            return $address->AddressID;
        }
    }

    /**
     * Used to save email
     * @param type $inputs
     * @return type
     */
    public function saveEmail($inputs) {
        $id = (isset($inputs['EmailID']) && !empty($inputs['EmailID']))?$inputs['EmailID']:'0';        
        //$email = new Email;
        $email = Email::firstOrNew(array('EmailID' => $id));
        $email->fill($inputs);
        if ($email->save()) {
            return $email->EmailID;
        }
    }

    /**
     * Used to save phone
     * @param type $inputs
     * @return type
     */
    public function savePhone($inputs) {
        $id = (isset($inputs['PhoneID']) && !empty($inputs['PhoneID']))?$inputs['PhoneID']:'0';        
        //$phone = new Phone;
        $phone = Phone::firstOrNew(array('PhoneID' => $id));
        $phone->fill($inputs);
        if ($phone->save()) {
            return $phone->PhoneID;
        }
    }

    /**
     * Used to save Note
     * @param type $inputs
     * @return type
     */
    public function saveNote($inputs) {
        $id = (isset($inputs['NoteID']) && !empty($inputs['NoteID']))?$inputs['NoteID']:'0';        
        //$note = new Note;
        $note = Note::firstOrNew(array('NoteID' => $id));
        $note->fill($inputs);
        if ($note->save()) {
            return $note->NoteID;
        }
    }
    /**
     * Used to save Contact
     * @param type $inputs
     * @return type
     */
    public function saveContact($inputs) {
        $id = (isset($inputs['ContactID']) && !empty($inputs['ContactID']))?$inputs['ContactID']:'0';        
        //$note = new Note;
        $contact = Contact::firstOrNew(array('ContactID' => $id));
        $contact->fill($inputs);
        if ($contact->save()) {
            return $contact->ContactID;
        }
    }

}
