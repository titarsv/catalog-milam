<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\AttributeValue;

class ModulebrandsController extends Controller
{
    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index(){
        $module = Module::where('alias_name', 'brands')->first();
        $settings = json_decode($module->settings, true);
        $brands = AttributeValue::where('attribute_id', 2)->with('image', 'localization')->get();

        return view('admin.modules.brands')
            ->with('module', $module)
            ->with('settings', $settings)
            ->with('brands', $brands);
    }

    public function save(){
        $modules = Module::all();
        $module = Module::where('alias_name', 'brands')->first();
        $settings = json_encode([
                'home' => $this->request->home,
                'menu' => $this->request->menu
            ]
        );

        $module->settings = $settings;
        $module->save();

        return redirect('admin/modules')
            ->with('modules', $modules)
            ->with('message-success', 'Настройки модуля ' . $module->name . ' успешно обновлены!');
    }
}
