<?php
namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Models\PhoneType;
use App\Models\EmailType;
use App\Models\Salutation;
class IndexController extends Controller
{

    public function __construct()
    {
        $this->currentDateTime = $this->currentDateTime();
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

    public function commonTags() {

        $response = array();
        $response['phoneTypes'] = PhoneType::select(['PhoneTypeID','Description'])->where('Deleted','=','0')->get()->toArray();
        $response['emailTypes'] = EmailType::select(['EmailTypeID','Description'])->where('Deleted','=','0')->get()->toArray();
        $response['salutations'] = Salutation::select(['SalutationID','Description'])->where('Deleted','=','0')->get()->toArray();

        return $this->makeResponse($response);
    }

    
}