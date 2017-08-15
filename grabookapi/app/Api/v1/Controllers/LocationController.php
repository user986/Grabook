<?php
namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Models\State;
class LocationController extends Controller
{

    

    public function __construct(Location $model)
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
    public function getLocation() {
        $location = State::select(['ProvinceStateID','Description'])->where('Deleted','=','0')->get()->toArray();
        return $this->makeResponse($location);
    }
}
