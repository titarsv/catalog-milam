<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Native\Facades\Sentinel;
use App\Models\Action;
use Config;

class ActionsController extends Controller
{
	private $user;

	function __construct(){
		$this->user = Sentinel::check();
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('admin.actions.index')->with('actions', Action::orderBy('id', 'DESC')->paginate(20));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $action = Action::find($id);

        return view('admin.actions.show')
            ->with('action', $action)
	        ->with('languages', Config::get('app.locales_names'));
    }
}
