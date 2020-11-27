<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CollegeCate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CollegeCateController extends Controller
{
    //文章分类列表
    public function collegeCateList(Request $request)
    {
        $params = $request->input();
        $page = $params['page'] ?? 1;
        $size = $params['size'] ?? 10;

        $data = CollegeCate::orderBy('sort', 'desc')->paginate($size);

        return dataFormat(0, '成功！', $data);
    }

    //添加文章分类
    public function collegeCateAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cate_name' => 'required',
            'status' => 'required|in:0,1'
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }

        $params = $request->input();
        $result = CollegeCate::MyInsert($params);

        if ($result === false) {
            return dataFormat('1', '失败！');
        }
        return dataFormat('0', '成功！');
    }

    //编辑文章分类
    public function collegeCateEdit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'cate_name' => 'required',
            'status' => 'required|in:0,1'
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }

        $params = $request->input();
        $result = CollegeCate::MyEdit($params);

        if ($result === false) {
            return dataFormat('1', '失败！');
        }
        return dataFormat('0', '成功！');
    }

    //删除文章分类
    public function collegeCateDel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|int',
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }
        $id = $request->input('id');
        $result = CollegeCate::where('id', $id)->delete();

        if ($result === false) {
            return dataFormat('1', '失败！');
        }
        return dataFormat('0', '成功！');
    }
}
