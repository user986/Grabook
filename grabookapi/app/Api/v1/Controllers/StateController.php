<?php
namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Models\State;
class StateController extends Controller
{

    

    public function __construct(State $model)
    {
        $this->model    = $model; 
    }

    public function index(Request $request)
    {
    }

    public function store(Request $request)
    {
            
    }

    public function show($id)
    {

    }

    public function update(Request $request, $id)
    {

    }

    public function destroy(Request $request, $id)
    {

    }
    public function getStates() {
        $location = State::select(['ProvinceStateID','Description'])->where('Deleted','=','0')->orderBy('Description')->get()->toArray();
        return $this->makeResponse($location);
    }
}
