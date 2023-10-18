<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\File;
use App\Models\Action;
use App\Models\UserData;
use App\Models\Product;
use Carbon\Carbon;

class ReviewsController extends Controller
{
    public function index(){
        $all_reviews = Review::orderBy('updated_at', 'desc')->paginate(20);

        $new_reviews = [];
        $reviews = [];

        foreach ($all_reviews as $review) {
            if($review->new) {
                $new_reviews[] = $review;
            } else {
                $reviews[] = $review;
            }
        }

        return view('admin.reviews.index', [
            'new'           => $new_reviews,
            'reviews'       => $reviews,
            'all_reviews'   => $all_reviews
        ]);
    }

    public function show($id){
        $review = Review::find($id);

        return view('admin.reviews.show', ['review' => $review]);
    }


    public function update(Request $request, $id){
        $review = Review::find($id);

        if($request->published && $review->new) {
            $grades = Review::where('product_id', $review->product_id)
                ->where('published', 1)
                ->pluck('grade');

            if(!$grades->isEmpty()) {
                $sum = 0;
                foreach ($grades as $grade) {
                    $sum += $grade;
                }

                $average = $sum / $grades->count();
                $rating = round($average, 2, PHP_ROUND_HALF_UP);
            } elseif (!is_null($review->grade)) {
                $rating = $review->grade;
            } else {
                $rating = null;
            }

            $product = Product::find($request->product_id);
            $product->update(['rating' => $rating]);
        }

        $review->update([
            'published' => $request->published,
            'answer_subject' => $request->answer_subject,
            'answer' => $request->answer,
            'confirmed_purchase' => $request->confirmed_purchase,
            'new' => 0
        ]);

        return redirect('/admin/reviews')
            ->with('message-success', 'Отзыв успешно обновлен!');
    }

    public function destroy($id){
        $review = Review::find($id);
        $review->delete();
        return redirect('/admin/reviews')
            ->with('message-success', 'Отзыв успешно удален!');
    }

    public function addAction(Request $request, Review $review, File $files){
        $rules = [
            'review' => 'required',
            'author' => 'required',
            'email' => 'required|email',
            'grade' => 'required'
        ];

        $messages = [
            'review.required'   => 'Оставьте текст отзыва!',
            'author.required'   => 'Введите имя!',
            'email.required'    => 'Введите email!',
            'email.email'       => 'Введите корректный email-адрес!',
            'grade.required'    => 'Вы не поставили оценку!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return response()->json(['error' => $validator->messages()], 200);
        }

        $user = User::where('email', $request->email)->first();
        if($user == null) {
            $user = Sentinel::registerAndActivate(array(
                'email'    => $request->email,
                'password' => 'null',
                'first_name' => $request->name,
                'permissions' => [
                    'unregistered' => true
                ]
            ));

            $role = Sentinel::findRoleBySlug('unregistered');
            $role->users()->attach($user);

            UserData::create([
                'user_id'   => $user->id,
                'image_id'  => 1,
                'subscribe' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        $user = Sentinel::check();
        if($user == null) {
            return response()->json(['result' => 'error', 'errors' => ['Необходима авторизация']], 200);
        }

        $data = $request->only(['product_id', 'grade', 'author', 'email', 'phone', 'notification', 'review']);
        $data['user_id'] = $user->id;
        if(empty($data['author'])){
            $data['author'] = $user->first_name.' '.$user->last_name;
        }
        if(empty($data['email'])){
            $data['email'] = $user->email;
        }

        $review_id = Review::insertGetId($data);
        $review = Review::find($review_id);

        if(!empty($request->photos)){
            $photos = [];
            foreach($request->files as $f){
                foreach($f as $file){
                    $destinationPath = public_path().DIRECTORY_SEPARATOR.env('UPLOADS_DIR', 'uploads');

                    $type = $file->guessExtension();
                    $originalName = str_replace(' ', '_', translit($file->getClientOriginalName()));
                    $newFileName = $files->generate_filename($originalName, $type);
                    $hash = md5_file($file->getPathName());
                    $isset = File::where('hash', $hash)->first();
                    if(empty($isset)){
                        $file->move($destinationPath, $newFileName);
                        $file = $files->createFile([
                            'title' => $file->getClientOriginalName(),
                            'path' => env('UPLOADS_DIR', 'uploads').DIRECTORY_SEPARATOR.$newFileName,
                            'type' => in_array($type, ['jpg', 'jpeg', 'png', 'gif']) ? 'image' : ($type == 'mp4' ? 'video' : $type),
                            'hash' => $hash
                        ]);

                        Action::createEntity($file);

                        $photos[] = $file->id;
                    }else{
                        $photos[] = $isset->id;
                    }
                }
            }

            if(!empty($photos))
                $review->saveGalleries($photos);
        }

        return response()->json(['result' => 'success', 'msg' => 'Ваш отзыв успешно добавлен! Он появится на сайте после проверки администратором!']);
    }
}