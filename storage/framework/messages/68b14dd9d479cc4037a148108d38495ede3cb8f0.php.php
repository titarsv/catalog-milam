<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Action;
use App\Models\Product;
use App\Models\News;
use App\Models\Sale;
use App\Models\ModuleLatest;
use App\Models\ModuleBestsellers;

class AjaxController extends Controller
{
	public function front(){

	}

	public function back(){

	}

    public function index(Request $request)
    {
        if($request->action == 'query-attachments'){
            return $this->queryAttachments($request->toArray());
        }elseif($request->action == 'image-editor'){
            return $this->imageEditor($request->toArray());
        }elseif($request->action == 'imgedit-preview'){
            return $this->imgeditPreview($request->toArray());
        }elseif($request->action == 'delete-post'){
	        $id = intval($request->id);
            return $this->deletePost($id);
        }elseif($request->action == 'save-attachment'){
	        $id = intval($request->id);
	        if($request->changes['status'] == 'trash'){
		        return $this->deletePost($id);
	        }
        }elseif($request->action == 'send-attachment-to-editor'){
	        return $this->imageHtml($request->attachment['id']);
        }elseif($request->action == 'filter'){
            return $this->filter($request->toArray());
        }

        return response()->json(['success' => false]);
    }

    public function queryAttachments($request){
        $files = new File();
        $response = ['success' => true];
        $orderby = 'date';
        $order = 'DESC';
        $posts_per_page = 40;
        $paged = 1;
        if(!empty($request['query'])){
            if(!empty($request['query']['orderby'])){
                $orderby = $request['query']['orderby'];
            }
            if(!empty($request['query']['order'])){
                $order = $request['query']['order'];
            }
            if(!empty($request['query']['posts_per_page'])){
                $posts_per_page = $request['query']['posts_per_page'];
            }
            if(!empty($request['query']['paged'])){
                $paged = $request['query']['paged'];
            }
        }
        if($orderby == 'date'){
            $orderby = 'created_at';
        }
        $offset = $posts_per_page * ($paged - 1);

        $query = $files->orderBy($orderby, $order)->offset($offset)->take($posts_per_page);

        if(!empty($request['query']['year']) && $request['query']['year'] != 'false' && !empty($request['query']['monthnum']) && $request['query']['monthnum'] != 'false'){
            $monthnum = $request['query']['monthnum'];
            $timfrom = $request['query']['year'].'-'.($monthnum < 10 ? '0'.$monthnum : $monthnum).'-01 00:00:00';
            if($request['query']['monthnum'] == 12){
                $timto = ($request['query']['year']+1).'-01-01 00:00:00';
            }else{
                $monthnum++;
                $timto = $request['query']['year'].'-'.($monthnum < 10 ? '0'.$monthnum : $monthnum).'-01 00:00:00';
            }
            $query->whereBetween('created_at',[$timfrom,$timto]);
        }

	    if(!empty($mime_type)){
		    $types = [''];
		    foreach($mime_type as $type){
		    	$types = array_merge($types, [$type]);
		    }
		    $query->whereIn('type', $types);
	    }

        if(!empty($request['query']['s'])){
            $query->where('title', 'like', '%'.$request['query']['s'].'%');
        }

        if(!empty($request['query']['trash']) && $request['query']['trash'] == 'true'){
	        $query->onlyTrashed();
        }

        $files = $query->get();
        foreach($files as $file){
            if(empty($file->data)){
                $file->updateData();
            }
        }
        $data = $files->pluck('data')->toArray();

        $response['data'] = $data;
        return response()->json($response);
    }

    public function imageEditor($request){
        if(!empty($request['postid'])){
            $image = File::where(['id' => $request['postid'], 'type' => 'image']);
        }

        return view('admin.media.editor')
            ->with('image', !empty($image) ? $image : null)
            ->with('nonce', $request['_ajax_nonce']);
    }

    public function imgeditPreview($request) {
        $post_id = intval($request['postid']);
        if ( empty($post_id) )
            return '-1';

        $image = File::find($post_id);
        $path = public_path() . '/uploads/' . $image->href;
        $data = $image->fileData();
        $im = $image->imagecreatefromfile($path);

        switch ( $data['mime'] ) {
            case 'image/jpeg':
                header( 'Content-Type: image/jpeg' );
                return imagejpeg( $im, null, 90 );
            case 'image/png':
                header( 'Content-Type: image/png' );
                return imagepng( $im );
            case 'image/gif':
                header( 'Content-Type: image/gif' );
                return imagegif( $im );
            default:
                return '';
        }
    }

    public function deletePost($id){
        if(empty($id))
            return '-1';
        $file = File::withTrashed()->find($id);

        if($file !== null) {
            Action::deleteEntity($file);

        	if($file->trashed()){
		        $file_path = public_path($file->path);
		        if(is_file($file_path)){
			        unlink($file_path);
		        }
		        $file->forceDelete();
	        }else{
		        DB::transaction(function() use($file){
			        foreach ($file->images as $image ) {
				        $image->delete();
			        }
			        $file->delete();
		        });
	        }

            return 1;
        } else {
            return 0;
        }
    }

	/**
	 * Вставка изображения в эдитор
	 *
	 * @param $id
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function imageHtml($id){
		$file = File::find($id);

		return response()->json(['success' => true, 'data' => $file->webp('full', ['alt' => $file->title, 'title' => $file->title], 'editor')]);
	}

	public function adminLiveSearchAction(Request $request, Product $products){
        $search_text = $request->input('search');
        if(strpos($search_text, '%') === 0){
            $search_text = urlencode($search_text);
        }

        // Установка текущей страницы пагинации
        $results = $products->search(trim($search_text), 1, 12, false, !empty($request->input('sale_id')) ? $request->input('sale_id') : null, !empty($request->input('news_id')) ? $request->input('news_id') : null,!empty($request->input('category_id')) ? $request->input('category_id') : null);

        foreach($results as $result){
            if ($result) {
                $json[] = [
                    'product_id'   => $result->id,
                    'name'         => $result->name,
                    'sku'          => $result->sku,
                    'price'        => $result->original_price,
                    'image'        => !empty($result->image) ? $result->image->url() : '/uploads/no_image.jpg'
                ];
            }
        }

        if (!empty($json)) {
            return response()->json($json);
        } else {
            return response()->json([['empty' => 'Ничего не найдено!']]);
        }
    }

    public function adminAddSaleProduct(Request $request){
        $sale = Sale::find($request->sale_id);
        $product = Product::find($request->product_id);
        if(!empty($sale) && !empty($product)){
            if(!empty($sale->sale_percent)){
                $price = $product->original_price * (100 - $sale->sale_percent) / 100;
            }else{
                $price = $product->original_price;
            }
            $sale->products()->attach($request->product_id, ['sale_price' => $price]);

            return response()->json(['result' => 'success', 'html' => view('admin.sales.products')->with('products', $sale->productsList(1))->render()]);
        }

        return response()->json(['result' => 'error']);
    }

    public function adminRemoveSaleProduct(Request $request){
        $sale = Sale::find($request->sale_id);
        $product = Product::find($request->product_id);
        if(!empty($sale) && !empty($product)){
            if($product->sale && strtotime($product->sale_from) >= time() && strtotime($product->sale_to) >= time()){
                $product->price = $product->sale_price;
            }else{
                $product->price = $product->original_price;
            }
            $product->save();
            $sale->products()->detach($product->id);

            return response()->json(['result' => 'success']);
        }

        return response()->json(['result' => 'error']);
    }

    public function adminAddNewsProduct(Request $request){
        $news = News::find($request->news_id);
        $product = Product::find($request->product_id);
        if(!empty($news) && !empty($product)){
            $news->products()->attach($request->product_id);
            return response()->json(['result' => 'success', 'html' => view('admin.modules.products')->with('products', $news->productsList(1))->render()]);
        }

        return response()->json(['result' => 'error']);
    }

    public function adminRemoveNewsProduct(Request $request){
        $news = News::find($request->news_id);
        $product = Product::find($request->product_id);
        if(!empty($news) && !empty($product)){
            $news->products()->detach($product->id);
            return response()->json(['result' => 'success']);
        }

        return response()->json(['result' => 'error']);
    }

    public function adminAddLatestProduct(Request $request){
        $product = Product::find($request->product_id);
        if(!empty($product)){
            $latest = new ModuleLatest();
            $latest->insert(['product_id' => $request->product_id]);
            return response()->json(['result' => 'success', 'html' => view('admin.modules.products')->with('products', $latest->productsList(1))->render()]);
        }

        return response()->json(['result' => 'error']);
    }

    public function adminRemoveLatestProduct(Request $request){
        $product = Product::find($request->product_id);
        if(!empty($product)){
            $latest = new ModuleLatest();
            $latest->where(['product_id' => $request->product_id])->delete();
            return response()->json(['result' => 'success']);
        }

        return response()->json(['result' => 'error']);
    }

    public function adminAddBestsellerProduct(Request $request){
        $product = Product::find($request->product_id);
        if(!empty($product)){
            $bestsellers = new ModuleBestsellers();
            $bestsellers->insert(['product_id' => $request->product_id]);
            return response()->json(['result' => 'success', 'html' => view('admin.modules.products')->with('products', $bestsellers->productsList(1))->render()]);
        }

        return response()->json(['result' => 'error']);
    }

    public function adminRemoveBestsellerProduct(Request $request){
        $product = Product::find($request->product_id);
        if(!empty($product)){
            $bestsellers = new ModuleBestsellers();
            $bestsellers->where(['product_id' => $request->product_id])->delete();
            return response()->json(['result' => 'success']);
        }

        return response()->json(['result' => 'error']);
    }
}
