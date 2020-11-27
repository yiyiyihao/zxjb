<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Service\BiService;

class DeviceController extends Controller
{
    //设备列表
    public function deviceList(Request $request)
    {
        $url = 'http://bi.api.worthcloud.net/v1/device/devicelist';
        $params = $request->input();
        $name = $params['name'] ?? '';
        $page = $params['page'] ?? 1;
        $size = $params['size'] ?? 10;
        $token = BiService::getToken();
        $post_data = ['name' => $name, 'page' => $page, 'size' => $size, 'token' => $token['token']];
        $data = curl_post_https($url, $post_data);
        return $data;
    }

    //添加设备
    public function deviceAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_code' => 'required',
            'name' => 'required'
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }

        $url = 'http://bi.api.worthcloud.net/v1/device/deviceadd';
        $params = $request->input();
        $token = BiService::getToken();
        $post_data = [
            'device_code' => $params['device_code'],
            'name' => $params['name'],
            'store_id' => $params['store_id'] ?? '',
            'block_id' => $params['block_id'] ?? '',
            'position_type' => $params['position_type'] ?? '',
            'token' => $token['token']
        ];
        $data = curl_post_https($url, $post_data);
        return $data;
    }

    //编辑设备
    public function deviceEdit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'store_id' => 'required',
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }

        $url = 'http://bi.api.worthcloud.net/v1/device/deviceedit';
        $params = $request->input();
        $token = BiService::getToken();
        $post_data = [
            'id' => $params['id'],
            'name' => $params['name'] ?? '',
            'status' => $params['status'] ?? 1,
            'sort_order' => $params['sort_order'] ?? 255,
            'store_id' => $params['store_id'] ?? '',
            'block_id' => $params['block_id'] ?? '',
            'position_type' => $params['position_type'] ?? '',
            'token' => $token['token']
        ];
        $data = curl_post_https($url, $post_data);
        return $data;
    }

    //删除设备
    public function deviceDel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }

        $url = 'http://bi.api.worthcloud.net/v1/device/devicedel';
        $params = $request->input();
        $token = BiService::getToken();
        $post_data = [
            'code' => $params['code'],
            'token' => $token['token']
        ];
        $data = curl_post_https($url, $post_data);
        return $data;
    }
}
