<?php
namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Models\LicenseType;
use Datatables;
class LicenseTypeController extends Controller
{

    protected $model;
    public function __construct(LicenseType $model)
    {
        $this->model = $model;
        $this->currentDateTime = $this->currentDateTime();
    }

    public function index(Request $request)
    {
        $query = $this->model->where('deleted','=','0');
        $record = Datatables::of($query)
                ->filter(function ($result) use ($request) {
                    $result->orderBy('odr', 'desc');
                    $result->orderBy('ModifiedDate', 'desc');
                 })->addColumn('id', function ($model) {
                            return $model->LicenseTypeID;
                })
                ->make(true);
        return $this->makeResponse($record);
    }

    public function store(Request $request)
    {
        $inputs = $request->input();

        if($this->model->where('Description','=',$inputs['Description'])->where('deleted','=','0')->count()){
            return $this->makeResponse('dublicate_licensetype',422);
        }
        $authId = $request->attributes->get('user')['UserID'];
        $inputs['EnteredByID'] = $authId;
        $inputs['EnteredDate'] = $this->currentDateTime;
        $inputs['ModifiedByID'] = $authId;
        $inputs['ModifiedDate'] = $this->currentDateTime;
        $inputs['Deleted'] = 0;

        $licenseType = new LicenseType();
        $licenseType->fill($inputs);
        $licenseType->save();
        return $this->makeResponse('License Type Added Successfully');
    }

    public function show($id)
    {
        
    }

    public function update(Request $request, $id)
    {
        $inputs = $request->input();
        if(isset($inputs['Description']) && !empty($inputs['Description'])){
            if($this->model->where('Description','=',$inputs['Description'])->where('deleted','=','0')->where('LicenseTypeID','!=',$id)->count()){
                return $this->makeResponse('dublicate_LicenseType',422);
            }


            $authId = $request->attributes->get('user')['UserID'];
            $inputs['ModifiedByID'] = $authId;
            $inputs['ModifiedDate'] = $this->currentDateTime;
            $inputs['Deleted'] = 0;

            $licenseType =  LicenseType::find($id);
            $licenseType->fill($inputs);
            $licenseType->save();
            return $this->makeResponse('License Type Updated Successfully');
        }else if(isset($inputs['odr'])){
            $position =  LicenseType::find($id);
            $position->odr = $inputs['odr'];
            $position->save();
            return $this->makeResponse('Order Updated');
        }else{
            return $this->makeResponse('update_failed',422);
        }
    }

    public function destroy(Request $request, $id)
    {

        $authId = $request->attributes->get('user')['UserID'];
        $inputs['ModifiedByID'] = $authId;
        $inputs['ModifiedDate'] = $this->currentDateTime;
        $inputs['Deleted'] = 1;

        $licenseType =  LicenseType::find($id);
        $licenseType->fill($inputs);
        $licenseType->save();
        return $this->makeResponse('License Type Deleted Successfully');
    }
}