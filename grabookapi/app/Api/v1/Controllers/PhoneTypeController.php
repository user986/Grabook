<?php

namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Models\PhoneType;

class PhoneTypeController extends Controller {

    public function __construct(PhoneType $model) {
        $this->model = $model;
    }

    public function index(Request $request) {
        
    }

    public function store(Request $request) {
        
    }

    public function show($id) {
        
    }

    public function update(Request $request, $id) {
        
    }

    public function destroy(Request $request, $id) {
        
    }

    public function phoneTypeList() {
        $records = PhoneType::select(['PhoneTypeID', 'Description'])->where('Deleted', '=', '0')->orderBy('Description')->get()->toArray();
        return $this->makeResponse($records);
    }

}
