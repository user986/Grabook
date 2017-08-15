<?php

namespace App\Repositories;
use Carbon\Carbon;
use App\Helpers\CommonHelper;
abstract class BaseRepository {
    use CommonHelper;
    /**
     * The Model instance.
     *
     * @var Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Get number of records.
     *
     * @return array
     */
    public function getNumber() {
        $total = $this->model->count();

        $new = $this->model->whereSeen(0)->count();

        return compact('total', 'new');
    }

    /**
     * Destroy a model.
     *
     * @param  int $id
     * @return void
     */
    public function destroy($id) {
        $this->getById($id)->delete();
    }

    /**
     * Get Model by id.
     *
     * @param  int  $id
     * @return App\Models\Model
     */
    public function getById($id) {
        return $this->model->find($id);
    }

    /**
     * Delete a model row.
     *
     * @param  array  $inputs
     * @param  $id 
     * @return void
     */
    public function destroyRow($id) {
    	$model = $this->getById($id);
    	if($model){
	        $model->status = '4'; // deleted
	        $model->save();
            return true;
    	}else{
            return false;
        }
        
    }

}
