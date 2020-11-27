<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Service\BiService;

class StatisticsController extends Controller
{
    //bi首页数据
    public function homeData(Request $request)
    {
        $url = 'http://bi.api.worthcloud.net/v1/data/homedata';
        $params = $request->input();
        $date = $params['date'] ?? date('Y-m-d H:i:s');
        $storeId = $params['store_id'] ?? 18;
        $token = BiService::getToken();
        $post_data = ['date'=>$date,'store_id'=>$storeId,'token'=>$token['token']];
        $data = curl_post_https($url, $post_data);
        return $data;
    }

    //bi门店客流分析
    public function storeData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'startDate' => 'required|date',
            'endDate' => 'required|date',
        ]);
        if ($validator->fails()) {
            return dataFormat('1',$validator->errors()->first());
        }
        $url = 'http://bi.api.worthcloud.net/v1/data/storedata';
        $params = $request->input();
        $startDate = $params['startDate'];
        $endDate = $params['endDate'];
        $token = BiService::getToken();
        $post_data = ['startDate'=>$startDate,'endDate'=>$endDate,'token'=>$token['token']];
        $data = curl_post_https($url, $post_data);
        return $data;
    }

    protected function _token()
    {
        $url = 'http://bi.api.worthcloud.net/v1/token/token';
        $access_key = 'WhwTOCRu6A3sMrjrgojUnfhDx_jLOe57';
        $secret_key = 'iH_sdhD12ctZQKDg6OfFUQB_bOc3IAT_';
        $post_data = ['access_key'=>$access_key,'secret_key'=>$secret_key];
        $data = curl_post_https($url, $post_data);
        return $data;
    }

}
