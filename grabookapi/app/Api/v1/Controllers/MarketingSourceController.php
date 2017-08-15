<?php
namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Models\MarketingSource;
use Datatables;
class MarketingSourceController extends Controller
{

    protected $model;
    public function __construct(MarketingSource $model)
    {
        $this->model = $model;
        $this->currentDateTime = $this->currentDateTime();
    }

    public function index(Request $request)
    {
        $query = $this->model->where('deleted','=','0');
        $record = Datatables::of($query)
                ->filter(function ($result) use ($request) {
                    $filter = (array) json_decode($request->get('filter'));
                    if (isset($filter['Description']) && !empty($filter['Description'])) {
                        $result->where('Description', 'LIKE', '%'.$filter['Description'].'%');
                    }
                    $result->orderBy('odr', 'desc');
                    $result->orderBy('ModifiedDate', 'desc');
                 })->addColumn('id', function ($model) {
                            return $model->MarketingSourceID;
                })
                ->make(true);
        return $this->makeResponse($record);
    }

    public function store(Request $request)
    {
        $inputs = $request->input();

        if($this->model->where('Description','=',$inputs['Description'])->where('deleted','=','0')->count()){
            return $this->makeResponse('dublicate_marketingsource',422);
        }
        $authId = $request->attributes->get('user')['UserID'];
        $inputs['EnteredByID'] = $authId;
        $inputs['EnteredDate'] = $this->currentDateTime;
        $inputs['ModifiedByID'] = $authId;
        $inputs['ModifiedDate'] = $this->currentDateTime;
        $inputs['Deleted'] = 0;

        $marketingsource = new MarketingSource();
        $marketingsource->fill($inputs);
        $marketingsource->save();
        return $this->makeResponse('Marketing Source Added Successfully');
    }

    public function show($id)
    {
        
    }

    public function update(Request $request, $id)
    {
        $inputs = $request->input();
        if(isset($inputs['Description']) && !empty($inputs['Description'])){
            if($this->model->where('Description','=',$inputs['Description'])->where('deleted','=','0')->where('MarketingSourceID','!=',$id)->count()){
                return $this->makeResponse('dublicate_marketingsource',422);
            }


            $authId = $request->attributes->get('user')['UserID'];
            $inputs['ModifiedByID'] = $authId;
            $inputs['ModifiedDate'] = $this->currentDateTime;
            $inputs['Deleted'] = 0;

            $marketingsource =  MarketingSource::find($id);
            $marketingsource->fill($inputs);
            $marketingsource->save();
            return $this->makeResponse('Marketing Source Updated Successfully');
        }else if(isset($inputs['odr'])){
            $position =  MarketingSource::find($id);
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

        $marketingsource =  MarketingSource::find($id);
        $marketingsource->fill($inputs);
        $marketingsource->save();
        return $this->makeResponse('Marketing Source Deleted Successfully');
    }
}