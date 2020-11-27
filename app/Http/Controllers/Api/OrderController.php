<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //订单列表
    public function orderList(Request $request)
    {
        $params = $request->input();
        $page = $params['page'] ?? 1;
        $size = $params['size'] ?? 10;

        $data = Order::orderBy('created_at', 'desc')->paginate($size);

        return dataFormat(0, '成功！', $data);
    }
}
