<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Goods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GoodsController extends Controller
{
    //商品列表
    public function GoodsList(Request $request)
    {
        $params = $request->input();
        $page = $params['page'] ?? 1;
        $size = $params['size'] ?? 10;

        $data = Goods::orderBy('sort', 'desc')->paginate($size);

        return dataFormat(0, '成功！', $data);
    }

    //添加商品
    public function GoodsAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cate_id' => 'required',
            'goods_sn' => 'required',
            'goods_name' => 'required',
            'banner' => 'required',
            'preview' => 'required',
            'skuIds' => 'required',
            'is_door_support' => 'required'
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }

        $params = $request->input();
        $result = Goods::MyInsert($params);

        if ($result === false) {
            return dataFormat('1', '失败！');
        }
        return dataFormat('0', '成功！');
    }

    //编辑商品
    public function GoodsEdit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'cate_id' => 'required',
            'goods_sn' => 'required',
            'goods_name' => 'required',
            'banner' => 'required',
            'preview' => 'required',
            'skuIds' => 'required',
            'is_door_support' => 'required'
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }

        $params = $request->input();
        $result = Goods::MyEdit($params);

        if ($result === false) {
            return dataFormat('1', '失败！');
        }
        return dataFormat('0', '成功！');
    }

    //删除商品
    public function GoodsDel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }
        $id = $request->input('id');
        $result = Goods::where('id', $id)->delete();

        if ($result === false) {
            return dataFormat('1', '失败！');
        }
        return dataFormat('0', '成功！');
    }

    //上下架商品
    public function goodsShelf(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required|in:0,1',
        ]);
        if ($validator->fails()) {
            return dataFormat('1',$validator->errors()->first());
        }

        $params = $request->input();
        $result = Goods::where('id',$params['id'])->update(['status'=>$params['status']]);

        if($result === false){
            return dataFormat('1','失败！');
        }
        return dataFormat('0','成功！');
    }
}
