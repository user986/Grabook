<?php
namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use Datatables;
class PositionController extends Controller
{

    protected $model;
    public function __construct(Position $model)
    {
        $this->model = $model;
        $this->currentDateTime = $this->currentDateTime();
    }

    public function index(Request $request)
    {
        $query = $this->model->select(['Position.*','Division.Description as Division'])->join('Division','Division.DivisionID','=','Position.DivisionID')->where('Position.deleted','=','0');
        $record = Datatables::of($query)
                ->filter(function ($result) use ($request) {
                    $filter = (array) json_decode($request->get('filter'));
                    if (isset($filter['Description']) && !empty($filter['Description'])) {
                        $result->where('Description', 'LIKE', '%'.$filter['Description'].'%');
                    }
                    $result->orderBy('odr', 'desc');
                    $result->orderBy('ModifiedDate', 'desc');
                 })->addColumn('id', function ($model) {
                            return $model->PositionID;
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

        $position = new Position();
        $position->fill($inputs);
        $position->save();
        return $this->makeResponse('Position Added Successfully');
    }

    public function show($id)
    {
        
    }

    public function update(Request $request, $id)
    {
        $inputs = $request->input();
        if(isset($inputs['Description']) && !empty($inputs['Description'])){
            if($this->model->where('Description','=',$inputs['Description'])->where('DivisionID','=',$inputs['DivisionID'])->where('deleted','=','0')->where('PositionID','!=',$id)->count()){
                return $this->makeResponse('dublicate_position',422);
            }
            $authId = $request->attributes->get('user')['UserID'];
            $inputs['ModifiedByID'] = $authId;
            $inputs['ModifiedDate'] = $this->currentDateTime;
            $inputs['Deleted'] = 0;

            $position =  Position::find($id);
            $position->fill($inputs);
            $position->save();
            return $this->makeResponse('Position Updated Successfully');
        }else if(isset($inputs['odr'])){
            $position =  Position::find($id);
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

        $position =  Position::find($id);
        $position->fill($inputs);
        $position->save();
        return $this->makeResponse('Position Deleted Successfully');
    }
}