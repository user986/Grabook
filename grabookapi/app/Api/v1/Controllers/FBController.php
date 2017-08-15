<?php

namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Repositories\UserRepository;

class FBController extends Controller {

    public function __construct(User $model,UserRepository $userRepo) {
        $this->model = $model;
        $this->userRepo = $userRepo;
    }

    public function index(Request $request) {

    }

    public function store(Request $request) {
        $inputs = $request->input();
        $res = $this->userRepo->addFBUser($inputs);
        return $this->makeResponse($res);  
    }

    public function show($id) {
        
    }

    public function update(Request $request, $id) {
        
    }

    public function destroy(Request $request, $id) {
        
    }
}
