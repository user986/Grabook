<?php
namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;
use Datatables;
class SkillController extends Controller
{

    protected $model;
    public function __construct(Skill $model)
    {
        $this->model = $model;
        $this->currentDateTime = $this->currentDateTime();
    }

    public function index(Request $request)
    {
        $query = $this->model->select(['Skill.*','Division.Description as Division'])->join('Division','Division.DivisionID','=','Skill.DivisionID')->where('Skill.deleted','=','0');
        $record = Datatables::of($query)
                ->filter(function ($result) use ($request) {
                    $result->orderBy('odr', 'desc');
                    $result->orderBy('ModifiedDate', 'desc');
                 })->addColumn('id', function ($model) {
                            return $model->SkillID;
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

        $skill = new Skill();
        $skill->fill($inputs);
        $skill->save();
        return $this->makeResponse('Skill Added Successfully');
    }

    public function show($id)
    {
        
    }

    public function update(Request $request, $id)
    {
        $inputs = $request->input();
        if(isset($inputs['Description']) && !empty($inputs['Description'])){
            if($this->model->where('Description','=',$inputs['Description'])->where('DivisionID','=',$inputs['DivisionID'])->where('deleted','=','0')->where('SkillID','!=',$id)->count()){
                return $this->makeResponse('dublicate_skill',422);
            }


            $authId = $request->attributes->get('user')['UserID'];
            $inputs['ModifiedByID'] = $authId;
            $inputs['ModifiedDate'] = $this->currentDateTime;
            $inputs['Deleted'] = 0;

            $skill =  Skill::find($id);
            $skill->fill($inputs);
            $skill->save();
            return $this->makeResponse('Skill Updated Successfully');
        }else if(isset($inputs['odr'])){
            $position =  Skill::find($id);
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

        $skill =  Skill::find($id);
        $skill->fill($inputs);
        $skill->save();
        return $this->makeResponse('Skill Deleted Successfully');
    }
}