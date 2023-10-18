<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Http\Request;
use App\Models\Wishlist;

class WishListController extends Controller
{

    public function index(){
        $wish_list = Wishlist::all();
        return view('admin.wishlist.index')->with('wish_list', $wish_list);
    }

    public function update(Request $request){
        $user = Sentinel::check();

        $product_id = $request->product_id;
        $action = $request->action;

        if(empty($user)){
            $wishlist = json_decode($request->cookie('wishlist'), true);

            if(!is_array($wishlist)){
                $wishlist = [];
            }

            if($action == 'add' && !in_array($product_id, $wishlist)){
                $wishlist[] = $product_id;
            }elseif($action == 'remove' && in_array($product_id, $wishlist)){
                unset($wishlist[array_search($product_id, $wishlist)]);
            }

            return response()->json(['result' => 'success', 'count' => count($wishlist)])->withCookie(cookie()->forever('wishlist', json_encode($wishlist)));
        }

        $user_id = $user->id;

        if($action == 'add'){
            Wishlist::create(['user_id' => $user_id, 'product_id' => $product_id])->save();
            $wishlist = Wishlist::where('user_id', $user_id)->count();
            return response()->json(['result' => 'success', 'count' => $wishlist]);
        }

        if($action == 'remove'){
            Wishlist::where('user_id', $user_id)->where('product_id', $product_id)->delete();
            $wishlist = Wishlist::where('user_id', $user_id)->count();
            return response()->json(['result' => 'success', 'count' => $wishlist]);
        }

        return response()->json(['result' => 'error']);
    }
}
