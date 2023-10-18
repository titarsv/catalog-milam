<?php

namespace App\Http\Controllers;

use App\Models\UserSession;
use App\Models\Order;
use Carbon\Carbon;
use App\Models\Setting;
use PulkitJalan\Google\Facades\Google;

class AdminController extends Controller
{
    public function dash(UserSession $session, Setting $settings)
    {
        $current = Carbon::now();
        $end_date = $current->subWeek(2);

        $weekly_orders = Order::where('created_at', '>', $end_date)->get();

        $order_stat = [];
        for($date = $end_date; $date->diffInDays(); $date->addDay()) {
            $order_stat[$date->format('d.m')] = [];
        }

        $sales_stat = [];
        foreach ($weekly_orders as $order) {
            $order_stat[$order->created_at->format('d.m')]['quantity'][] = $order;

            if ($order->status_id == 4) {
                $sales_stat[] = $order->total_price;
                $order_stat[$order->created_at->format('d.m')]['sales'][] = $order->total_price;
            } else {
                $order_stat[$order->created_at->format('d.m')]['sales'][] = 0;
            }
        }

        foreach ($order_stat as $date => $date_orders) {
            if (isset($date_orders['quantity'])) {
                $order_stat[$date]['quantity'] = count($date_orders['quantity']);
            } else {
                $order_stat[$date]['quantity'] = 0;
            }

            if (isset($date_orders['sales'])) {
                $order_stat[$date]['sales'] = array_sum($date_orders['sales']);
            } else {
                $order_stat[$date]['sales'] = 0;
            }
        }

        $total_sales = Order::where('status_id', 4)->pluck('total_price')->toArray();

        $stat = [
            'week_order' => $weekly_orders->count(),
            'all_orders' => Order::count(),
            'new_orders' => Order::where('status_id', 1)->count(),
            'finished'   => Order::where('status_id', 4)->count(),
            'weekly_sales' => array_sum($sales_stat),
            'total_sales'  => array_sum($total_sales)
        ];

//        $users = $session->getUserActivity();
//
//        $online_users = [];
//
//        foreach ($users as $user) {
//            $url = 'http://ipinfo.io/' . $user->ip_address . '/json';
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            $ipinfo = curl_exec($ch);
//
//            if (!is_null($user->user_id)) {
//                $user_info = Sentinel::findById($user->user_id);
//            } else {
//                $user_info = null;
//            }
//
//            if ($user->user_agent) {
//                $browser = $session->getBrowser($user->user_agent);
//            } else {
//                $browser = null;
//            }
//
//            $online_users[] = [
//                'ipinfo' => json_decode($ipinfo),
//                'userinfo' => $user_info,
//                'browserinfo' => $browser
//            ];
//
//        }

	    /**
	     * Создаём сервисный аккаунт тут https://console.developers.google.com/iam-admin/serviceaccounts?project=larchik&supportedpurview=project
	     * скачиваем ключ и ложим в storage/app/google_service
	     * включаем для него API тут https://console.developers.google.com/apis/api/analytics.googleapis.com/overview?project=larchik
	     * идём в панель аналитики - администратор - управление доступом и добавляем мыло сервисного аккаунта
	     * скоуп - https://www.googleapis.com/auth/analytics.readonly
	     * поля для статистики https://developers.google.com/analytics/devguides/reporting/core/dimsmets#mode=api
	     */
//	    if(is_file(storage_path('app/google_service.json'))) {
//		    $token_data = $settings->get_setting('ga_token');
//		    if (empty($token_data) || time() + 30 > $token_data->created + $token_data->expires_in) {
//			    $googleClient = Google::getClient();
//			    if ($googleClient->isAccessTokenExpired()) {
//				    $googleClient->refreshTokenWithAssertion();
//			    }
//			    $token_data = $googleClient->getAccessToken();
//			    $settings->update_setting('ga_token', $token_data, false);
//			    $token = $token_data['access_token'];
//		    } else {
//			    $token = $token_data->access_token;
//		    }
//	    }else{
		    $token = false;
//	    }

        return view('admin.dashboard', [
            'orders' => $order_stat,
            'stat'   => $stat,
            'token' => $token
        ]);
    }
}