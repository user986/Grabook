<?php
namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Models\IndustryType;
use Datatables;
class IndustryTypeController extends Controller
{

    protected $model;
    public function __construct(IndustryType $model)
    {
        $this->model = $model;
        $this->currentDateTime = $this->currentDateTime();
    }

    public function index(Request $request)
    {
        $query = $this->model->select(['IndustryType.*','Division.Description as Division'])->join('Division','Division.DivisionID','=','IndustryType.DivisionID')->where('IndustryType.deleted','=','0');
        $record = Datatables::of($query)
                ->filter(function ($result) use ($request) {
                    $result->orderBy('odr', 'desc');
                    $result->orderBy('ModifiedDate', 'desc');
                 })->addColumn('id', function ($model) {
                            return $model->IndustryTypeID;
                })
                ->make(true);
        return $this->makeResponse($record);
    }

    public function store(Request $request)
    {
        $inputs = $request->input();

        if($this->model->where('Description','=',$inputs['Description'])->where('DivisionID','=',$inputs['DivisionID'])->where('deleted','=','0')->count()){
            return $this->makeResponse('dublicate_position',422);
        }
        $authId = $request->attributes->get('user')['UserID'];
        $inputs['EnteredByID'] = $authId;
        $inputs['EnteredDate'] = $this->currentDateTime;
        $inputs['ModifiedByID'] = $authId;
        $inputs['ModifiedDate'] = $this->currentDateTime;
        $inputs['Deleted'] = 0;

        $industryType = new IndustryType();
        $industryType->fill($inputs);
        $industryType->save();
        return $this->makeResponse('Industry Type Added Successfully');
    }

    public function show($id)
    {
        
    }

    public function update(Request $request, $id)
    {
        $inputs = $request->input();
        if(isset($inputs['Description']) && !empty($inputs['Description'])){
            if($this->model->where('Description','=',$inputs['Description'])->where('DivisionID','=',$inputs['DivisionID'])->where('deleted','=','0')->where('IndustryTypeID','!=',$id)->count()){
                return $this->makeResponse('dublicate_industrytype',422);
            }


            $authId = $request->attributes->get('user')['UserID'];
            $inputs['ModifiedByID'] = $authId;
            $inputs['ModifiedDate'] = $this->currentDateTime;
            $inputs['Deleted'] = 0;

            $industryType =  IndustryType::find($id);
            $industryType->fill($inputs);
            $industryType->save();
            return $this->makeResponse('Industry Type Updated Successfully');
        }else if(isset($inputs['odr'])){
            $position =  IndustryType::find($id);
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

        $industryType =  IndustryType::find($id);
        $industryType->fill($inputs);
        $industryType->save();
        return $this->makeResponse('Industry Type Deleted Successfully');
    }
}