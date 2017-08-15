<?php

namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Contact\ContactRequest;
use App\Repositories\ContactRepository;
use App\Repositories\UserRepository;
use App\Models\Contact;
use App\Models\User;
use Datatables;
use DB;

class ContactController extends Controller {

    protected $contactRepo;
    protected $model;

    public function __construct(UserRepository $userRepo, ContactRepository $contactRepo, Contact $model) {
        $this->contactRepo = $contactRepo;
        $this->userRepo = $userRepo;
        $this->model = $model;
    }

    public function index(Request $request) {
        $responseArray = array();
        $filter = (array) json_decode($request->get('filter'));
        $industryId = array();
        //$result = $this->contactRepo->getUser()->get();
        $result = $this->contactRepo->getContact();
        //dd($result);
        $record = Datatables::of($result)
                ->filter(function ($result) use ($request) {
                    $filter = (array) json_decode($request->get('filter'));
                    if (isset($filter['firstName']) && !empty($filter['firstName'])) {
                        $result->where('Person.FirstName', 'LIKE', '%' . $filter['firstName'] . '%');
                    }
                    if (isset($filter['lastName']) && !empty($filter['lastName'])) {
                        $result->where('Person.LastName', 'LIKE', '%' . $filter['lastName'] . '%');
                    }
                    if (isset($filter['ClientName']) && !empty($filter['ClientName'])) {
                        $result->where('Contact.ClientID', '=', $filter['ClientName']);
                    }
                    if (isset($filter['ProvinceStateID']) && !empty($filter['ProvinceStateID'])) {
                        $result->where('Address.ProvinceStateID', '=', $filter['ProvinceStateID']);
                    }
                    if (isset($filter['selectedIndustry']) && !empty($filter['selectedIndustry'])) {
                        $industryId = array();
                        foreach ($filter['selectedIndustry'] as $key => $value) {
                            $industryId[] = $value->IndustryTypeID;
                        }
                        $industryIdStr = implode(',', $industryId);
                        if (!empty($industryIdStr))
                            $result->whereRaw(DB::raw('FIND_IN_SET(IndustryTypeID, "' . $industryIdStr . '")'));
                    }
                    if (isset($filter['selectedTechnology']) && !empty($filter['selectedTechnology'])) {
                        $techId = array();
                        foreach ($filter['selectedTechnology'] as $key => $value) {
                            $techId[] = $value->TechnologyID;
                        }
                        $techId = implode(',', $techId);
                        if (!empty($techId))
                            $result->whereRaw(DB::raw('FIND_IN_SET(Contact.TechnologyID, "' . $techId . '")'));
                    }
                    if (isset($filter['email']) && !empty($filter['email'])) {
                        $result->where('Email.EmailAddress', 'LIKE', '%' . $filter['email'] . '%');
                    }
                    if (isset($filter['phoneNumber']) && !empty($filter['phoneNumber'])) {
                        $result->where('Phone.PhoneNumber', '=', $filter['phoneNumber']);
                    }
                    if (isset($filter['ownership']) && !empty($filter['ownership'])) {
                        $result->where('Contact.OwnerID', '=', $filter['ownership']);
                    }
                    if (isset($filter['startDate']) && !empty($filter['startDate'])) {
                        $startDate = date("Y-m-d H:i", strtotime($filter['startDate']));
                        $result->where('Contact.EnteredDate', '>=', $startDate);
                    }
                    if (isset($filter['endDate']) && !empty($filter['endDate'])) {
                        $endDate = date("Y-m-d H:i", strtotime($filter['endDate']));
                        $result->where('Contact.EnteredDate', '<=', $endDate);
                    }
                    if (isset($filter['ContactID']) && !empty($filter['ContactID'])) {
                        $result->where('Contact.ContactID', '=', $filter['ContactID']);
                    }

                    $sort = $request->get('sort');
                    if (isset($sort) && $sort == 'Name') {
                        $result->orderBy('Person.FirstName', 'asc');
                    }
                    $result->orderBy('Person.ModifiedDate', 'desc');
                })->editColumn('EnteredDate', function ($result) {
                    return $result->EnteredDate ? $this->outputDateFormat($result->EnteredDate) : '';
                })->editColumn('LastContactDate', function ($result) {
                    return $result->LastContactDate ? $this->outputDateFormat($result->LastContactDate) : '';
                })->editColumn('NextContactDate', function ($result) {
                    return $result->NextContactDate ? $this->outputDateFormat($result->NextContactDate) : '';
                })
                ->make(true);
        return $this->makeResponse($record);
    }

    /**
     * 
     * @param ContactRequest $request
     * @return type
     */
    public function store(ContactRequest $request) {
        $inputs = $request->input();
        $inputs['authId'] = $request->attributes->get('user')['UserID'];
        $inputs['AccountManagerRecruiterID'] = $request->attributes->get('user')['RecruiterID'];
        $res = $this->contactRepo->addContact($inputs);
        if ($res == true) {
            return $this->makeResponse('Contact has been added successfully.');
        } else {
            return $this->makeResponse('record_save_error', 422);
        }
    }

    /**
     * 
     * @param type $id
     * @return type
     */
    public function show($id) {
        $result = $this->contactRepo->getUser($id)->first();
        if (!empty($result)) {
            $result = $result->toArray();
        }
        return $this->makeResponse($result);
    }

    /**
     * 
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function update(Request $request, $id) {
        $inputs = $request->input();
        //echo "<pre>";
        //print_r($inputs);
        //die;
        $inputs['authId'] = $request->attributes->get('user')['UserID'];
        $inputs['AccountManagerRecruiterID'] = $request->attributes->get('user')['RecruiterID'];
        $this->contactRepo->updateContact($inputs, $id);
        return $this->makeResponse('Contact Updated Successfully');
    }

    /**
     * Used to update the contact status
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function changeContactStatus(Request $request, $id) {
        $inputs = $request->input();
        $contact = $this->model->find($id);
        if ($inputs['IsActive'] == 1) {
            $contact->ContactStatusID = 1001;
        } else {
            $contact->ContactStatusID = 1002;
        }
        $contact->IsActive = $inputs['IsActive'];
        $contact->save();
        return $this->makeResponse('Status Updated Successfully');
    }

    public function destroy(Request $request, $id) {
        
    }

    public function changeUserStatus(Request $request, $id) {
        $inputs = $request->input();
        $note = $this->model->find($id);
        $note->Inactive = $inputs['Inactive'];
        $note->save();
        return $this->makeResponse('Contact Status Updated Successfully');
    }

}
