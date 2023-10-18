<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'modules';

    public function set($request){
        if (empty($request['settings']['quantity']))
            $request['settings']['quantity'] = config('view.product_quantity');

        $this->settings = json_encode($request['settings']);
        $this->status = $request['status'];

        return $this->update();
    }

	public function getSlideshow($module_name){
		return $this->where('alias_name', $module_name)->first();
	}

	public function updateModule($module_name, $module_param){
		$this->where('alias_name', $module_name)
		     ->update($module_param);
	}
}
