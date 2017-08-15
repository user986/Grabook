<?php

namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Models\Technology;
use Datatables;
class TechnologyController extends Controller {

    public function __construct(Technology $model) {
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
                            return $model->TechnologyID;
                })
                ->make(true);
        return $this->makeResponse($record);
    }

    public function store(Request $request)
    {
        $inputs = $request->input();

        if($this->model->where('Description','=',$inputs['Description'])->where('deleted','=','0')->count()){
            return $this->makeResponse('dublicate_technology',422);
        }
        $authId = $request->attributes->get('user')['UserID'];
        $inputs['EnteredByID'] = $authId;
        $inputs['EnteredDate'] = $this->currentDateTime;
        $inputs['ModifiedByID'] = $authId;
        $inputs['ModifiedDate'] = $this->currentDateTime;
        $inputs['Deleted'] = 0;

        $technology = new Technology();
        $technology->fill($inputs);
        $technology->save();
        return $this->makeResponse('Technology Added Successfully');
    }

    public function show($id)
    {
        
    }

    public function update(Request $request, $id)
    {
        $inputs = $request->input();
        if(isset($inputs['Description']) && !empty($inputs['Description'])){
            if($this->model->where('Description','=',$inputs['Description'])->where('deleted','=','0')->where('TechnologyID','!=',$id)->count()){
                return $this->makeResponse('dublicate_technology',422);
            }


            $authId = $request->attributes->get('user')['UserID'];
            $inputs['ModifiedByID'] = $authId;
            $inputs['ModifiedDate'] = $this->currentDateTime;
            $inputs['Deleted'] = 0;

            $technology =  Technology::find($id);
            $technology->fill($inputs);
            $technology->save();
            return $this->makeResponse('Technology Updated Successfully');
        }else if(isset($inputs['odr'])){
            $position =  Technology::find($id);
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

        $technology =  Technology::find($id);
        $technology->fill($inputs);
        $technology->save();
        return $this->makeResponse('Technology Deleted Successfully');
    }

    public function getTechnologyList() {
        $records = Technology::select(['TechnologyID', 'Description'])->where('Deleted', '=', '0')->orderBy('Description')->get()->toArray();
        return $this->makeResponse($records);
    }

}
