<?php

namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Division;
use App\Models\Position;

class ClientController extends Controller {

    public function __construct(Client $model) {
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

    public function getClient() {
        $client = Client::select(['ClientID','ClientName'])->where('Deleted','=','0')->get()->toArray();
        return $this->makeResponse($client);
    }

    public function getPosition($divisionId=0) {
        $query = Position::select(['PositionID','Description']);
        
        if($divisionId != 0 && $divisionId != '')
            $query->where('DivisionID','=',$divisionId);
        
        $records = $query->where('Deleted', '=', '0')->orderBy('Description')->get()->toArray();
        
        return $this->makeResponse($records);
    }

}
