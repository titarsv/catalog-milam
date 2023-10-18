<?php

namespace App\Http\Controllers;

use App\Models\Module;

class ModulesController extends Controller
{
    public function index(Module $modules){
        return view('admin.modules.index')
            ->with('modules', $modules->all());
    }
}
