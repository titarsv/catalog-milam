<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\ModuleLatest;

class ModuleLatestController extends Controller
{
    protected $request;

    public function __construct(Request $request, ModuleLatest $latest) {
        $this->request = $request;
        $this->latest = $latest;
    }

    public function index(){
        $module = Module::where('alias_name', 'latest')->first();
        $settings = json_decode($module->settings);

        return view('admin.modules.latest')
            ->with('module', $module)
            ->with('products', $this->latest->productsList(1))
            ->with('settings', $settings);
    }

    public function save(){
        $modules = Module::all();
        $module = Module::where('alias_name', 'latest')->first();
//        $settings = json_encode([
//                'quantity'      => $this->request->quantity
//            ]
//        );

        $module->status = $this->request->status;
//        $module->settings = $settings;
        $module->save();

        return redirect('admin/modules')
            ->with('modules', $modules)
            ->with('message-success', 'Настройки модуля ' . $module->name . ' успешно обновлены!');
    }
}
