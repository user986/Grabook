<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\SendMailRepository;
use DB;

class UserRepository extends BaseRepository {

    protected $model;
    public function __construct(User $model,SendMailRepository $sendMailRepo) {
        $this->model    = $model;    
        $this->currentDateTime = $this->currentDateTime();
       $this->sendMailRepo = $sendMailRepo; 
    }

    function addFBUser($inputs) {
        $user = new User;
        $user->deleted  = 0;
        $user->email  = $inputs['email'];
        $user->first_name  = $inputs['first_name'];
        $user->last_name  = $inputs['last_name'];
        $user->dob  = $inputs['birthday'];
        $user->gender  = $inputs['gender'];
        $user->save();
        return $user->id;
    }

    function addApplicationUser($inputs) {

            /* Insert into Person Table */
            $person = new Person;
            $person->EnteredByID = $inputs['authId'];
            $person->EnteredDate = $this->currentDateTime;
            $person->ModifiedByID = $inputs['authId'];
            $person->ModifiedDate = $this->currentDateTime;
            $person->Deleted = 0;
            $person->FirstName = (isset($inputs['firstName']) && !empty($inputs['firstName']))?$inputs['firstName']:'';
            $person->LastName = (isset($inputs['lastName']) && !empty($inputs['lastName']))?$inputs['lastName']:'';
            $person->save();


            $password = $this->randomPassword();
            //$password = 123456;
            $bcrypt = bcrypt($password);
            /* Insert into Recruiter Table */
            $recruiter = new Recruiter;
            $recruiter->EnteredByID = $inputs['authId'];
            $recruiter->EnteredDate = $this->currentDateTime;
            $recruiter->ModifiedByID = $inputs['authId'];
            $recruiter->ModifiedDate = $this->currentDateTime;
            $recruiter->Deleted = 0;
            $recruiter->person()->associate($person);
            $recruiter->EchoSignUser = $inputs['email'];
            $recruiter->EchoSignPassword = $bcrypt;
            $recruiter->save();

            $roleId = 1;
            if(isset($inputs['employee']) && $inputs['employee'] == true) {
                $roleId = 2;
            }
            if(isset($inputs['admin']) && $inputs['admin'] == true) {
                $roleId = 1;
            }

            /* Insert into ApplicationUser Table */
            $user = new User;
            $user->Deleted  = 0;
            $user->email  = $inputs['email'];
            $user->Password  = $bcrypt;
            $user->ApplicationUserGroupID  = $roleId;
            $user->recruiter()->associate($recruiter);
            $user->save();

            if($roleId == 1){
                /* Insert into ApplicationUser_ApplicationUserGroup Table for admin*/
                $applicationUserApplicationUserGroup = new ApplicationUserApplicationUserGroup;
                $applicationUserApplicationUserGroup->applicationUser()->associate($user);
                $applicationUserApplicationUserGroup->ApplicationUserGroupID  = 1;
                $applicationUserApplicationUserGroup->EnteredByID  = 1;
                $applicationUserApplicationUserGroup->EnteredDate  = $this->currentDateTime;
                $applicationUserApplicationUserGroup->ModifiedByID  = 1;
                $applicationUserApplicationUserGroup->ModifiedDate  = $this->currentDateTime;
                $applicationUserApplicationUserGroup->Deleted  = 0;
                $applicationUserApplicationUserGroup->save(); 
            }

            if(isset($inputs['employee']) && $inputs['employee'] == true) {
                /* Insert into ApplicationUser_ApplicationUserGroup Table for admin*/
                $applicationUserApplicationUserGroup = new ApplicationUserApplicationUserGroup;
                $applicationUserApplicationUserGroup->applicationUser()->associate($user);
                $applicationUserApplicationUserGroup->ApplicationUserGroupID  = 2;
                $applicationUserApplicationUserGroup->EnteredByID  = 1;
                $applicationUserApplicationUserGroup->EnteredDate  = $this->currentDateTime;
                $applicationUserApplicationUserGroup->ModifiedByID  = 1;
                $applicationUserApplicationUserGroup->ModifiedDate  = $this->currentDateTime;
                $applicationUserApplicationUserGroup->Deleted  = 0;
                $applicationUserApplicationUserGroup->save(); 
            }

            /* Insert into Email Table */
            $email = new Email;
            $email->EnteredByID = "1";
            $email->EnteredDate = $this->currentDateTime;
            $email->ModifiedByID = '1';
            $email->ModifiedDate = $this->currentDateTime;
            $email->Deleted = 0;
            $email->EmailTypeID = 1;
            $email->EmailAddress = $inputs['email'];
            $email->person()->associate($person);
            $email->save();

            /* Insert into Address Table */
            $address = new Address;
            $address->EnteredByID = $inputs['authId'];
            $address->EnteredDate = $this->currentDateTime;
            $address->ModifiedByID = $inputs['authId'];
            $address->ModifiedDate = $this->currentDateTime;
            $address->Deleted = 0;
            $address->AddressTypeID = 1;
            $address->CityId = (isset($inputs['CityId']) && !empty($inputs['CityId']))?$inputs['CityId']:'';
            $address->ProvinceStateId = (isset($inputs['ProvinceStateId']) && !empty($inputs['ProvinceStateId']))?$inputs['ProvinceStateId']:'';
            $address->CountryId = (isset($inputs['CountryId']) && !empty($inputs['CountryId']))?$inputs['CountryId']:'';
            $address->person()->associate($person);
            $address->save();

            /* Insert into Phone Table */
            $phone = new phone;
            $phone->EnteredByID = $inputs['authId'];
            $phone->EnteredDate = $this->currentDateTime;
            $phone->ModifiedByID = $inputs['authId'];
            $phone->ModifiedDate = $this->currentDateTime;
            $phone->Deleted = 0;
            $phone->PhoneTypeID = 1;
            $phone->PhoneNumber = (isset($inputs['PhoneNumber']) && !empty($inputs['PhoneNumber']))?$inputs['PhoneNumber']:'';
            $phone->CountryCode = (isset($inputs['countryCode']) && !empty($inputs['countryCode']))?$inputs['countryCode']:'';
            $phone->person()->associate($person);
            $phone->save();


            /* Insert into Note Table */
            $note = new Note;
            $note->EnteredByID = $inputs['authId'];
            $note->EnteredDate = $this->currentDateTime;
            $note->ModifiedByID = $inputs['authId'];
            $note->ModifiedDate = $this->currentDateTime;
            $note->Deleted = 0;
            $note->NoteTypeID = 800  ;
            $note->NoteText = (isset($inputs['NoteText']) && !empty($inputs['NoteText']))?$inputs['NoteText']:'';
            $note->person()->associate($person);
            $note->save();

            $person->DefaultAddressID = $address->AddressID;
            $person->DefaultEmailID = $email->EmailID;
            $person->DefaultPhoneID = $phone->PhoneID;
            $person->save();


            $content = EmailTemplate::find(1);

            $search  = array('[!firstname!]', '[!lastname!]', '[!email!]', '[!password!]');
            $replace = array($person->FirstName, $person->LastName, $user->email, $password);
            $content = str_replace($search, $replace, $content->Description);

            $this->sendMailRepo->sendMail(array(
                'to' => $user->email,
                'subject' =>'User name and password',
                'view' => 'mail.commonemail',
                'data'=>array('content' => $content)
            ));


    }

    function updateApplicationUser($inputs,$id) {

        /*  ApplicationUser Detail */
        $user = User::find($id);

        $bcrypt = '';
        if((isset($inputs['password']) && !empty($inputs['password']))){
            $bcrypt = bcrypt($inputs['password']);
            $user->password = $bcrypt;
            $user->save();
        }


        $roleId = 1;
        if(isset($inputs['employee']) && $inputs['employee'] == true) {
            $roleId = 2;
        }
        if(isset($inputs['admin']) && $inputs['admin'] == true) {
            $roleId = 1;
        }

        ApplicationUserApplicationUserGroup::where('ApplicationUserID','=',$user->UserID)->delete();

        if($roleId == 1){
            /* Insert into ApplicationUser_ApplicationUserGroup Table for admin*/
            $applicationUserApplicationUserGroup = new ApplicationUserApplicationUserGroup;
            $applicationUserApplicationUserGroup->applicationUser()->associate($user);
            $applicationUserApplicationUserGroup->ApplicationUserGroupID  = 1;
            $applicationUserApplicationUserGroup->EnteredByID  = 1;
            $applicationUserApplicationUserGroup->EnteredDate  = $this->currentDateTime;
            $applicationUserApplicationUserGroup->ModifiedByID  = 1;
            $applicationUserApplicationUserGroup->ModifiedDate  = $this->currentDateTime;
            $applicationUserApplicationUserGroup->Deleted  = 0;
            $applicationUserApplicationUserGroup->save(); 
        }

        if(isset($inputs['employee']) && $inputs['employee'] == true) {
            /* Insert into ApplicationUser_ApplicationUserGroup Table for admin*/
            $applicationUserApplicationUserGroup = new ApplicationUserApplicationUserGroup;
            $applicationUserApplicationUserGroup->applicationUser()->associate($user);
            $applicationUserApplicationUserGroup->ApplicationUserGroupID  = 2;
            $applicationUserApplicationUserGroup->EnteredByID  = 1;
            $applicationUserApplicationUserGroup->EnteredDate  = $this->currentDateTime;
            $applicationUserApplicationUserGroup->ModifiedByID  = 1;
            $applicationUserApplicationUserGroup->ModifiedDate  = $this->currentDateTime;
            $applicationUserApplicationUserGroup->Deleted  = 0;
            $applicationUserApplicationUserGroup->save(); 
        }


        /* Insert into Recruiter Table */
        $recruiter = Recruiter::find($user->RecruiterID);
        $recruiter->ModifiedByID = $inputs['authId'];
        $recruiter->ModifiedDate = $this->currentDateTime;
        //$recruiter->EchoSignUser = $inputs['email'];
        if($bcrypt!=''){
          $recruiter->EchoSignPassword = $bcrypt;  
        }
        
        $recruiter->save();

        /* Insert into Person Table */
        $person = Person::find($recruiter->PersonID);
        $person->ModifiedByID = $inputs['authId'];
        $person->ModifiedDate = $this->currentDateTime;
        $person->FirstName = (isset($inputs['firstName']) && !empty($inputs['firstName']))?$inputs['firstName']:'';
        $person->LastName = (isset($inputs['lastName']) && !empty($inputs['lastName']))?$inputs['lastName']:'';
        $person->save();

        /* Insert into Address Table */
        $address = Address::find($person->DefaultAddressID);
        $address->ModifiedByID = $inputs['authId'];
        $address->ModifiedDate = $this->currentDateTime;
        $address->AddressTypeID = 1;
        $address->CityId = (isset($inputs['CityId']) && !empty($inputs['CityId']))?$inputs['CityId']:'';
        $address->ProvinceStateId = (isset($inputs['ProvinceStateId']) && !empty($inputs['ProvinceStateId']))?$inputs['ProvinceStateId']:'';
        $address->CountryId = (isset($inputs['CountryId']) && !empty($inputs['CountryId']))?$inputs['CountryId']:'';

        if(isset($inputs['Street'])){
            $address->Street = $inputs['Street'];
        }
        if(isset($inputs['PostalZip'])){
            $address->PostalZip = $inputs['PostalZip'];
        }
        $address->save();

        /* Insert into Phone Table */
        $phone = phone::find($person->DefaultPhoneID);;
        $phone->ModifiedByID = $inputs['authId'];
        $phone->ModifiedDate = $this->currentDateTime;
        $phone->Deleted = 0;
        $phone->PhoneTypeID = 1;
        $phone->PhoneNumber = (isset($inputs['PhoneNumber']) && !empty($inputs['PhoneNumber']))?$inputs['PhoneNumber']:'';
        $phone->CountryCode = (isset($inputs['CountryCode']) && !empty($inputs['CountryCode']))?$inputs['CountryCode']:'';
        $phone->save();


        /* Insert into Note Table */
        $note = Note::find($inputs['NoteID']);
        $note->ModifiedByID = $inputs['authId'];
        $note->ModifiedDate = $this->currentDateTime;
        $note->NoteTypeID = 1;
        $note->NoteText = (isset($inputs['NoteText']) && !empty($inputs['NoteText']))?$inputs['NoteText']:'';
        $note->save();


    }

    function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}