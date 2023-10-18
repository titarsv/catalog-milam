<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\Moduleslideshow;
use Validator;

class ModuleslideshowController extends Controller
{

    protected $request;
    protected $slideshow;
    protected $modules;

    public $module_name = 'slideshow';

    public function __construct(Request $request, ModuleSlideshow $slideshow, Module $modules) {
        $this->request = $request;
        $this->slideshow = $slideshow;
        $this->modules = $modules;
    }

    public function index(){
        $module = $this->modules->getSlideshow($this->module_name);
        $settings = json_decode($module->settings);

        $image_size = config('image.sizes.slide');

        return view('admin.modules.slideshow')
            ->with('module', $module)
            ->with('settings', $settings)
            ->with('image_size', $image_size)
            ->with('slideshow', $this->slideshow->all());
    }

    public function save(){
        $module = $this->modules->getSlideshow($this->module_name);
        $update_param = [
            'status' => $this->request->status
        ];
        $this->modules->updateModule($this->module_name, $update_param);

        $this->slideshow->truncate();

        if(!empty($this->request->slide)){
            foreach($this->request->slide as $slide){
                if(empty($slide['file_id'])){
                    $slide['file_id'] = 1;
                };
//                if(empty($slide['file_xs_id'])){
//                    $slide['file_xs_id'] = 1;
//                };
                if(empty($slide['sort_order']))
                    $slide['sort_order'] = count($this->request->slide);
                $slide_data['slide_title'] = $slide['slide_title'];
                $slide_data['slide_description'] = $slide['slide_description'];
//                $slide_data['slide_color'] = $slide['slide_color'];
                $slide_data['button_text'] = $slide['button_text'];
                $slide_data['lang'] = $slide['lang'];
                $slide['slide_data'] = json_encode($slide_data);

                $this->slideshow->create($slide);
            }
        }

        return redirect('admin/modules')
            ->with('modules', $this->modules->all())
            ->with('message-success', 'Настройки модуля ' . $module->name . ' успешно обновлены!');
    }
}
