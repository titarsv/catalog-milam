<?php

namespace App\Http\Controllers;

use App\Models\UserSession;
use App\Models\Order;
use Carbon\Carbon;
use App\Models\Setting;

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

        $token = false;

        return view('admin.dashboard', [
            'orders' => $order_stat,
            'stat'   => $stat,
            'token' => $token
        ]);
    }
}