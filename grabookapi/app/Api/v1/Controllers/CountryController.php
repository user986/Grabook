<?php
namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
class CountryController extends Controller
{

    

    public function __construct(Country $model)
    {
        $this->model    = $model; 
    }

    public function index(Request $request)
    {
        $country = Country::with('state','state.city')->get()->toArray();
        return $this->makeResponse($country);
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

}
