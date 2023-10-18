<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\ModuleBestsellers;

class ModuleBestsellersController extends Controller
{
    protected $request;
    protected $bestsellers;

    public function __construct(Request $request, ModuleBestsellers $bestsellers) {
        $this->request = $request;
        $this->bestsellers = $bestsellers;
    }

    public function index(){
        $module = Module::where('alias_name', 'bestsellers')->first();
        $settings = json_decode($module->settings);

        return view('admin.modules.bestsellers')
            ->with('module', $module)
            ->with('products', $this->bestsellers->productsList(1))
            ->with('settings', $settings);
    }

    public function save(){
        $modules = Module::all();
        $module = Module::where('alias_name', 'bestsellers')->first();
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
