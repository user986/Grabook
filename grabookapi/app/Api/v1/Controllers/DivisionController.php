<?php
namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use Datatables;
class DivisionController extends Controller
{

    protected $model;
    public function __construct(Division $model)
    {
        $this->model = $model;
        $this->currentDateTime = $this->currentDateTime();
    }

    public function index(Request $request)
    {
        // $query = $this->model->where('deleted','=','0');
        // $record = Datatables::of($query)
        //         ->filter(function ($result) use ($request) {
        //             $result->orderBy('ModifiedDate', 'desc');
        //          })->addColumn('id', function ($model) {
        //                     return $model->DivisionID;
        //         })
        //         ->make(true);
        // return $this->makeResponse($record);
    }

    public function store(Request $request)
    {
        // $inputs = $request->input();

        // if($this->model->where('Description','=',$inputs['Description'])->where('deleted','=','0')->count()){
        //     return $this->makeResponse('dublicate_Division',422);
        // }
        // $authId = $request->attributes->get('user')['UserID'];
        // $inputs['EnteredByID'] = $authId;
        // $inputs['EnteredDate'] = $this->currentDateTime;
        // $inputs['ModifiedByID'] = $authId;
        // $inputs['ModifiedDate'] = $this->currentDateTime;
        // $inputs['Deleted'] = 0;

        // $Division = new Division();
        // $Division->fill($inputs);
        // $Division->save();
        // return $this->makeResponse('Division Added Successfully');
    }

    public function show($id)
    {
        
    }

    public function update(Request $request, $id)
    {
        // $inputs = $request->input();
        // if($this->model->where('Description','=',$inputs['Description'])->where('deleted','=','0')->where('DivisionID','!=',$id)->count()){
        //     return $this->makeResponse('dublicate_Division',422);
        // }


        // $authId = $request->attributes->get('user')['UserID'];
        // $inputs['ModifiedByID'] = $authId;
        // $inputs['ModifiedDate'] = $this->currentDateTime;
        // $inputs['Deleted'] = 0;

        // $Division =  Division::find($id);
        // $Division->fill($inputs);
        // $Division->save();
        // return $this->makeResponse('Division Updated Successfully');
    }

    public function destroy(Request $request, $id)
    {

        // $authId = $request->attributes->get('user')['UserID'];
        // $inputs['ModifiedByID'] = $authId;
        // $inputs['ModifiedDate'] = $this->currentDateTime;
        // $inputs['Deleted'] = 1;

        // $Division =  Division::find($id);
        // $Division->fill($inputs);
        // $Division->save();
        // return $this->makeResponse('Division Deleted Successfully');
    }

    public function getDivision() {
        $division = Division::select(['DivisionID','Description'])->where('Deleted','=','0')->get()->toArray();
        return $this->makeResponse($division);
    }

    public function getAllDivisions() {
       // $division = Division::with('position','skill','industryType');->where('Deleted','=','0')->get()->toArray();
       // return $this->makeResponse($division);

        $division = Division::select(['DivisionID','Description'])->with(
                array(
                    'Position' => function($query)
                        {
                            $query->select(['PositionID','Description','DivisionID'])->where('Position.Deleted', '0')->orderBy('Position.Description', 'ASC');

                        },
                        'Skill' => function($query)
                        {
                             $query->select(['SkillID','Description','DivisionID'])->where('Deleted', '0')->orderBy('Description', 'ASC');

                        },
                        'IndustryType' => function($query)
                        {
                             $query->select(['IndustryTypeID','Description','DivisionID'])->where('Deleted', '0')->orderBy('Description', 'ASC');
                        }

                )
            )
            ->where('Deleted','=','0')
            ->orderBy('Description', 'ASC')
            ->get();

            return $this->makeResponse($division);



    }
}