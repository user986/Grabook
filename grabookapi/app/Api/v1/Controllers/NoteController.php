<?php
namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
class NoteController extends Controller
{
    

    
    protected $model;
    public function __construct(Note $model)
    {
        $this->model = $model;
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
        $inputs = $request->input();
        $note = $this->model->find($id);
        $note->NoteText = $inputs['NoteText'];
        $note->ModifiedByID = $request->attributes->get('user')['UserID'];
        $note->ModifiedDate = $this->currentDateTime;
        $note->save();
        return $this->makeResponse('Notes Updated Successfully');
    }

    public function destroy(Request $request, $id)
    {

    }

}
