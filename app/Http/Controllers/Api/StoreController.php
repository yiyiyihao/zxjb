<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;


class StoreController extends Controller
{
    //门店列表
    public function storeList(Request $request)
    {
        $params = $request->input();
        $page = $params['page'] ?? 1;
        $size = $params['size'] ?? 10;

        $data = Store::orderBy('created_at', 'desc')->paginate($size);

        return dataFormat(0, '成功！', $data);
    }

    //添加门店
    public function storeAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_back' => 'required',
            'id_front' => 'required',
            'userphone' => 'required',
            'certification' => 'required'
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }

        $params = $request->input();
        $result = Store::MyInsert($params);

        if ($result === false) {
            return dataFormat('1', '失败！');
        }
        return dataFormat('0', '成功！');
    }

    //编辑门店
    public function storeEdit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'id_back' => 'required',
            'id_front' => 'required',
            'userphone' => 'required',
            'certification' => 'required'
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }

        $params = $request->input();
        $result = Store::MyEdit($params);

        if ($result === false) {
            return dataFormat('1', '失败！');
        }
        return dataFormat('0', '成功！');
    }

    //删除门店
    public function storeDel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }

        $id = $request->input('id');
        $result = Store::where('id', $id)->delete();

        if ($result === false) {
            return dataFormat('1', '失败！');
        }
        //删除门店下的角色
        DB::table('shop_role')->where('shop_id', $id)->delete();
        return dataFormat('0', '成功！');
    }

}
