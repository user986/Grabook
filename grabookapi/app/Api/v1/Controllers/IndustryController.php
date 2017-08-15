<?php

namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Models\IndustryType;

class IndustryController extends Controller {

    public function __construct(IndustryType $model) {
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

    public function getIndustryType($divisionId = 0) {
        $query = IndustryType::select(['IndustryTypeID', 'Description']);
        if ($divisionId != 0 && $divisionId != '')
            $query->where('DivisionID', '=', $divisionId);

        $records = $query->where('Deleted', '=', '0')->orderBy('Description')->get()->toArray();
        
        return $this->makeResponse($records);
    }

}
