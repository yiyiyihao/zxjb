<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    //bi首页数据
    public function homeData(Request $request)
    {
        $url = 'http://bi.api.worthcloud.net/v1/data/homedata';
        $params = $request->input();
        $date = '';
        $storeId = '';
        $token = '';
        $post_data = ['date'=>$date,'store_id'=>$storeId,'token'=>$token];
        $data = curl_post_https($url, $post_data);
        return $data;
    }

    //bi门店客流分析
    public function storeData(Request $request)
    {
        $url = 'http://bi.api.worthcloud.net/v1/data/storedata';
        $params = $request->input();
        $startDate = '';
        $endDate = '';
        $token = '';
        $post_data = ['startDate'=>$startDate,'endDate'=>$endDate,'token'=>$token];
        $data = curl_post_https($url, $post_data);
        return $data;
    }

}
