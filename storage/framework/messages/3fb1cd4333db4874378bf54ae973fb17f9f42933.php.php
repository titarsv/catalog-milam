<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Http\Request;
use App\Models\SiteReview;
use Validator;
use App\Models\User;
use App\Models\UserData;
use Carbon\Carbon;
use App\Models\Setting;
use Mail;

class SiteReviewsController extends Controller
{
    public function index()
    {
        $all_shop_reviews = SiteReview::orderBy('updated_at', 'desc')->paginate(20);

        $new_shop_reviews = [];
        $shop_reviews = [];

        foreach ($all_shop_reviews as $review) {
            if($review->new) {
                $new_shop_reviews[] = $review;
            } else {
                $shop_reviews[] = $review;
            }
        }

        return view('admin.sitereviews.index', [
            'new'           => $new_shop_reviews,
            'reviews'       => $shop_reviews,
            'all_reviews'   => $all_shop_reviews
        ]);
    }

    public function show($id)
    {
        $review = SiteReview::find($id);

        return view('admin.sitereviews.show', ['review' => $review]);
    }


    public function update(Request $request, $id)
    {

        $review = SiteReview::find($id);

        $review->update(['published' => $request->published, 'answer' =>$request->answer, 'new' => 0]);

        return redirect('/admin/sitereviews')
            ->with('message-success', 'Отзыв успешно обновлен!');
    }

    public function destroy($id)
    {
        $review = SiteReview::find($id);
        $review->delete();
        return redirect('/admin/sitereviews')
            ->with('message-success', 'Отзыв успешно удален!');
    }

    public function add(Request $request, SiteReview $review, UserData $user_data, Setting $setting)
    {
        $rules = [
            'review' => 'required',
            'name' => 'required',
            'email' => 'required|email',
        ];

        $messages = [
            'review.required'   => 'Оставьте текст отзыва!',
            'name.required'     => 'Введите имя!',
            'email.required'    => 'Введите email!',
            'email.email'       => 'Введите корректный email-адрес!'
        ];

        if($request->type == 'review') {
            $rules['grade'] = 'required';
            $messages['grade.required'] = 'Вы не поставили оценку!';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages(), 'type' => $request->type], 200);
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

            $user_data->create([
                'user_id'   => $user->id,
                'image_id'  => 1,
                'subscribe' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        $review->fill($request->except('_token'));
        $review->user_id = $user->id;
        $review->published = 0;
        $review->new = 1;
        $review->author = $request->name;
        $review->save();

        return response()->json(['success' => 'Ваш отзыв успешно добавлен! Он появится на сайте после проверки администратором!', 'type' => $request->type]);
    }
}
