<?php
namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationLanguage;
use Datatables;
class ApplicationLanguageController extends Controller
{

    protected $model;
    public function __construct(ApplicationLanguage $model)
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
                            return $model->ApplicationLanguageID;
                })
                ->make(true);
        return $this->makeResponse($record);
    }

    public function store(Request $request)
    {
        $inputs = $request->input();

        if($this->model->where('Description','=',$inputs['Description'])->where('deleted','=','0')->count()){
            return $this->makeResponse('dublicate_applicationlanguage',422);
        }
        $authId = $request->attributes->get('user')['UserID'];
        $inputs['EnteredByID'] = $authId;
        $inputs['EnteredDate'] = $this->currentDateTime;
        $inputs['ModifiedByID'] = $authId;
        $inputs['ModifiedDate'] = $this->currentDateTime;
        $inputs['Deleted'] = 0;

        $applicationLanguage = new ApplicationLanguage();
        $applicationLanguage->fill($inputs);
        $applicationLanguage->save();
        return $this->makeResponse('Application Language Added Successfully');
    }

    public function show($id)
    {
        
    }

    public function update(Request $request, $id)
    {
        $inputs = $request->input();
        if(isset($inputs['Description']) && !empty($inputs['Description'])){

            if($this->model->where('Description','=',$inputs['Description'])->where('deleted','=','0')->where('ApplicationLanguageID','!=',$id)->count()){
                return $this->makeResponse('dublicate_applicationlanguage',422);
            }


            $authId = $request->attributes->get('user')['UserID'];
            $inputs['ModifiedByID'] = $authId;
            $inputs['ModifiedDate'] = $this->currentDateTime;
            $inputs['Deleted'] = 0;

            $applicationLanguage =  ApplicationLanguage::find($id);
            $applicationLanguage->fill($inputs);
            $applicationLanguage->save();
            return $this->makeResponse('Application Language Updated Successfully');
        }else if(isset($inputs['odr'])){
            $position =  ApplicationLanguage::find($id);
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

        $applicationLanguage =  ApplicationLanguage::find($id);
        $applicationLanguage->fill($inputs);
        $applicationLanguage->save();
        return $this->makeResponse('Application Language Deleted Successfully');
    }
}