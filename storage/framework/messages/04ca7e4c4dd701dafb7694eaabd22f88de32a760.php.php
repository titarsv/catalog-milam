<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Http\Request;
use App\Models\ShopReview;
use Validator;
use App\Models\User;
use App\Models\UserData;
use Carbon\Carbon;
use App\Models\Setting;
use Mail;

class ShopReviewsController extends Controller
{
    public function index()
    {
        $all_shop_reviews = ShopReview::orderBy('updated_at', 'desc')->paginate(20);

        $new_shop_reviews = [];
        $shop_reviews = [];

        foreach ($all_shop_reviews as $review) {
            if($review->new) {
                $new_shop_reviews[] = $review;
            } else {
                $shop_reviews[] = $review;
            }
        }

        return view('admin.shopreviews.index', [
            'new'           => $new_shop_reviews,
            'reviews'       => $shop_reviews,
            'all_reviews'   => $all_shop_reviews
        ]);
    }

    public function show($id)
    {
        $review = ShopReview::find($id);

        return view('admin.shopreviews.show', ['review' => $review]);
    }


    public function update(Request $request, $id)
    {

        $review = ShopReview::find($id);

       /* if($request->published && $review->new) {
            $grades = ShopReview::where('product_id', $review->product_id)
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

            $product = Products::find($request->product_id);
            $product->update(['rating' => $rating]);

        }*/

        $review->update(['published' => $request->published, 'answer' => !empty($request->answer) ? $request->answer : '', 'new' => 0]);

        return redirect('/admin/shopreviews')
            ->with('message-success', 'Отзыв успешно обновлен!');
    }

    public function destroy($id)
    {
        $review = ShopReview::find($id);
        $review->delete();
        return redirect('/admin/shopreviews')
            ->with('message-success', 'Отзыв успешно удален!');
    }

    public function addAction(Request $request, ShopReview $review, UserData $user_data, Setting $setting)
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
        $review->answer = '';
        $review->save();

        $subject = 'Новый отзыв о компании milam.ua';
        Mail::send('emails.review', ['review' => $review, 'page' => $request->url], function($msg) use ($setting, $subject){
            $msg->from('admin@'.str_replace(['http://', 'https://'], '', env('APP_URL')), 'Интернет-магазин Milam');
            $msg->to($setting->get_setting('notify_emails'));
            $msg->subject($subject);
        });

        $settings = new Setting();
        $telegram = (array)$settings->get_setting('telegram');
        if(!empty($telegram['token'])){
            $bot = new \TelegramBot\Api\Client($telegram['token']);

            $text = "Новый отзыв на сайте\n";
            $text .= "E-mail: ".$review->user->email."\n";
            $text .= "Текст отзыва: ".$review->review."\n";

            foreach($telegram['clients'] as $id => $client){
                if($client->moderated){
                    $bot->sendMessage($client->chat, $text);
                }
            }
        }

        return response()->json(['success' => 'Ваш отзыв успешно добавлен! Он появится на сайте после проверки администратором!', 'type' => $request->type]);
    }
}
