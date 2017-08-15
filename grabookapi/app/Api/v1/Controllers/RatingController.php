<?php
namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use Datatables;
use DB;
class RatingController extends Controller
{

    protected $model;
    public function __construct(Rating $model)
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
                            return $model->RatingID;
                })
                ->make(true);
        return $this->makeResponse($record);
    }

    public function store(Request $request)
    {
        $inputs = $request->input();

        if($this->model->where('Description','=',$inputs['Description'])->where('deleted','=','0')->count()){
            return $this->makeResponse('dublicate_rating',422);
        }
        $authId = $request->attributes->get('user')['UserID'];
        $inputs['EnteredByID'] = $authId;
        $inputs['EnteredDate'] = $this->currentDateTime;
        $inputs['ModifiedByID'] = $authId;
        $inputs['ModifiedDate'] = $this->currentDateTime;
        $inputs['Deleted'] = 0;

        $rating = new Rating();
        $rating->fill($inputs);
        $rating->save();
        return $this->makeResponse('Rating Added Successfully');
    }

    public function show($id)
    {
        
    }

    public function update(Request $request, $id)
    {
        $inputs = $request->input();
        if(isset($inputs['Description']) && !empty($inputs['Description'])){
            if($this->model->where('Description','=',$inputs['Description'])->where('deleted','=','0')->where('RatingID','!=',$id)->count()){
                return $this->makeResponse('dublicate_rating',422);
            }
            $authId = $request->attributes->get('user')['UserID'];
            $inputs['ModifiedByID'] = $authId;
            $inputs['ModifiedDate'] = $this->currentDateTime;
            $inputs['Deleted'] = 0;

            $rating =  Rating::find($id);
            $rating->fill($inputs);
            $rating->save();
            return $this->makeResponse('Rating Updated Successfully');
        }else if(isset($inputs['odr'])){
            $position =  Rating::find($id);
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

        $rating =  Rating::find($id);
        $rating->fill($inputs);
        $rating->save();
        return $this->makeResponse('Rating Deleted Successfully');
    }
}