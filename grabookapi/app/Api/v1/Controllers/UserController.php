<?php
namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\User\UserRequest;
use App\Repositories\UserRepository;
use App\Models\User;
use Datatables;
class UserController extends Controller
{
    protected $userRepo;
    protected $model;
    public function __construct(UserRepository $userRepo,User $model)
    {
        $this->userRepo = $userRepo;
        $this->model = $model;
    }

    public function index(Request $request)
    {
        $responseArray = array();
        $postedData = $request->input();
        $result = $this->userRepo->getUser();

        $record = Datatables::of($result)
                ->filter(function ($result) use ($request) {
                    $filter = (array) json_decode($request->get('filter'));
                    if (isset($filter['firstName'])) {
                        $result->where('Person.firstName', 'LIKE', '%'.$filter['firstName'].'%');
                    }
                    if (isset($filter['lastName'])) {
                        $result->where('Person.lastName', 'LIKE', '%'.$filter['lastName'].'%');
                    }
                    if (isset($filter['email'])) {
                        $result->where('ApplicationUser.email', 'LIKE', '%'.$filter['email'].'%');
                    }

                    $role = array();
                    if (isset($filter['admin']) && $filter['admin'] == true) {
                        array_push($role,1);
                    }
                    if (isset($filter['employee']) && $filter['employee'] == true) {
                        array_push($role,2);
                    }
                    if(!empty($role)){
                        $result->whereIN('ApplicationUser_ApplicationUserGroup.ApplicationUserGroupID', $role);
                    }

                    $sort = $request->get('sort');
                    if (isset($sort) && $sort == 'Name') {
                        $result->orderBy('Person.firstName', 'asc');
                    }
                    $result->orderBy('Person.ModifiedDate', 'desc');
                 })
                ->make(true);
        return $this->makeResponse($record);



    }

    public function store(UserRequest $request)
    {
        $inputs = $request->input();
        $inputs['authId'] = $request->attributes->get('user')['UserID'];
        $this->userRepo->addApplicationUser($inputs);
        return $this->makeResponse('User Added Successfully');
    }

    public function show($id)
    {
        $result = $this->userRepo->getUser($id)->first();
        if(!empty($result)){
            $result = $result->toArray();
        }
        return $this->makeResponse($result);
    }

    public function update(Request $request, $id)
    {
        $inputs = $request->input();
        $inputs['authId'] = $request->attributes->get('user')['UserID'];
        $this->userRepo->updateApplicationUser($inputs,$id);
        return $this->makeResponse('User Updated Successfully');
    }

    public function destroy(Request $request, $id)
    {

    }
    public function changeUserStatus(Request $request, $id)
    {
        $inputs = $request->input();
        $note = $this->model->find($id);
        $note->Inactive = $inputs['Inactive'];
        $note->save();
        return $this->makeResponse('User Status Updated Successfully');
    }
    public function getOwner(Request $request)
    {
        $user = User::with('recruiter','recruiter.person')->orderByRaw('RAND()')->take(1)->first()->toArray();
        return $this->makeResponse($user);
    }
}