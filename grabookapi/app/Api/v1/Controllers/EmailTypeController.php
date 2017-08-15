<?php

namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailType;

class EmailTypeController extends Controller {

    public function __construct(EmailType $model) {
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

    public function EmailTypeList() {
        $records = EmailType::select(['EmailTypeID', 'Description'])->where('Deleted', '=', '0')->orderBy('Description')->get()->toArray();
        return $this->makeResponse($records);
    }

}
