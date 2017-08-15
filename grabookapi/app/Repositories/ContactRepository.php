<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Models\Country;
//use App\Models\Email;
//use App\Models\Address;
//use App\Models\Phone;
use App\Models\Person;
//use App\Models\Note;
use App\Repositories\CommonRepository;

class ContactRepository extends BaseRepository {

    protected $model;

    public function __construct(Contact $model, CommonRepository $commonRepository) {
        $this->model = $model;
        $this->commonRepo = $commonRepository;
        $this->currentDateTime = $this->currentDateTime();
    }

    function getContact() {
        $result = $this->model->select(['Contact.ContactID', 'Contact.Title', 'Person.FirstName',
                    'Person.LastName', 'Person.MiddleName', 'Address.Street', 'Address.PostalZip',
                    'ProvinceState.Description AS State', 'Phone.PhoneNumber', 'Person.EnteredDate as RegistrationDate',
                    'Note.NoteID', 'Note.NoteText', 'Email.EmailAddress', 'Client.ClientName', 'IndustryType.Description as Industry', 'Contact.Keywords', 'Contact.IsActive',
                    'Technology.TechnologyID', 'Contact.OwnershipID', 'Contact.DivisionID', 'Contact.PositionID', 'Contact.LastContactDate', 'Contact.EnteredDate', 'Contact.NextContactDate',
                    'Contact.OwnerName', 'Contact.OwnerID', 'Contact.ClientID', 'Contact.PersonID', 'Email.EmailID', 'Phone.PhoneID', 'Address.AddressID','Division.Description as DivisionName',
                    'Position.Description as PositionName','Ownership.Description as OwnershipDescription','Contact.IndustryTypeID','Technology.Description as TechnologyName'])
                ->join('Person', 'Person.PersonID', '=', 'Contact.PersonID')
                ->join('Client', 'Client.ClientID', '=', 'Contact.ClientID', 'left')
                ->join('Email', 'Person.DefaultEmailID', '=', 'Email.EmailID', 'left')
                ->join('Address', 'Address.AddressID', '=', 'Person.DefaultAddressID', 'left')
                ->join('ProvinceState', 'Address.ProvinceStateId', '=', 'ProvinceState.ProvinceStateID', 'left')
                ->join('Phone', 'Phone.PhoneID', '=', 'Person.DefaultPhoneID', 'left')
                ->join('IndustryType', 'IndustryType.IndustryTypeID', '=', 'Contact.IndustryTypeID', 'left')
                ->join('Ownership', 'Ownership.OwnershipID', '=', 'Contact.OwnershipID', 'left')
                ->join('Technology', 'Technology.TechnologyID', '=', 'Contact.TechnologyID', 'left')
                ->join('Division', 'Division.DivisionID', '=', 'Contact.DivisionID', 'left')
                ->join('Position', 'Position.PositionID', '=', 'Contact.PositionID', 'left')
                ->join('Note', 'Note.PersonID', '=', 'Person.PersonID', 'left');

        $result->groupBy('Person.PersonID');
        return $result;
    }

    /**
     * used to save the Contacts
     * @param type $inputs
     */
    function addContact($inputs) {

        $inputs['EnteredByID'] = $inputs['authId'];
        $inputs['EnteredDate'] = $this->currentDateTime;
        $inputs['ModifiedByID'] = $inputs['authId'];
        $inputs['ModifiedDate'] = $this->currentDateTime;
        $inputs['Deleted'] = 0;
        $personId = $this->commonRepo->savePerson($inputs); /* Insert into Person Table */
        if (!empty($personId)) {
            $inputs['PersonID'] = $personId;

            $addressId = 0;
            if (isset($inputs['MailingAddress']) && !empty($inputs['MailingAddress'])) {
                $inputs['AddressTypeID'] = 1;
                $addressId = $this->commonRepo->saveAddress($inputs);
            }
            $emailID = 0;
            if (isset($inputs['EmailAddress']) && !empty($inputs['EmailAddress'])) {
                $inputs['EmailTypeID'] = 1;
                $emailID = $this->commonRepo->saveEmail($inputs);
            }
            $phoneId = 0;
            if (isset($inputs['PhoneNumber']) && !empty($inputs['PhoneNumber'])) {
                $inputs['PhoneTypeID'] = 1;
                $phoneId = $this->commonRepo->savePhone($inputs);
            }
            $inputs['NoteTypeID'] = 300;
            $this->commonRepo->saveNote($inputs);

            $person = Person::find($personId);
            $person->DefaultAddressID = $addressId;
            $person->DefaultEmailID = $emailID;
            $person->DefaultPhoneID = $phoneId;
            $person->save();

            $inputs['ContactStatusID'] = 1001; // Active
            $inputs['ContactRatingID'] = 1002; // Phone
            $inputs['CommunicationMethodID'] = 1002; // Phone

            $contact = new Contact();
            $contact->fill($inputs);
            $contact->save();
            return true;
        } else {
            return false;
        }
    }

    function updateContact($inputs, $id) {
        //dd($inputs);
        $inputs['ContactID'] = $id;
        $inputs['ModifiedByID'] = $inputs['authId'];
        $inputs['ModifiedDate'] = $this->currentDateTime;
        $personId = $this->commonRepo->savePerson($inputs); /* Insert into Person Table */

        if (!empty($personId)) {
            $inputs['PersonID'] = $personId;

            $addressId = 0;
            if (isset($inputs['Street']) && !empty($inputs['Street'])) {
                $inputs['AddressTypeID'] = 1;
                $addressId = $this->commonRepo->saveAddress($inputs);
            }
            $emailID = 0;
            if (isset($inputs['EmailAddress']) && !empty($inputs['EmailAddress'])) {
                $inputs['EmailTypeID'] = 1; // Home
                $emailID = $this->commonRepo->saveEmail($inputs);
            }
            $phoneId = 0;
            if (isset($inputs['PhoneNumber']) && !empty($inputs['PhoneNumber'])) {
                $inputs['PhoneTypeID'] = 1; // Home
                $phoneId = $this->commonRepo->savePhone($inputs);
            }
            $inputs['NoteTypeID'] = 300; // 300 = Contact
            $this->commonRepo->saveNote($inputs);

            $person = Person::find($personId);
            $person->DefaultAddressID = $addressId;
            $person->DefaultEmailID = $emailID;
            $person->DefaultPhoneID = $phoneId;
            $person->save();

            $inputs['ContactStatusID'] = 1001; // Active
            $inputs['ContactRatingID'] = 1002; // Phone
            $inputs['CommunicationMethodID'] = 1002; // Phone
            $this->commonRepo->saveContact($inputs);
            return true;
        } else {
            return false;
        }
    }

}
