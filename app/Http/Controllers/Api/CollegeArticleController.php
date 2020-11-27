<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CollegeArticle;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CollegeArticleController extends Controller
{
    //文章列表
    public function collegeArticleList(Request $request)
    {
        $params = $request->input();
        $page = $params['page'] ?? 1;
        $size = $params['size'] ?? 10;

        $data = CollegeArticle::orderBy('sort', 'desc')->paginate($size);

        return dataFormat(0, '成功！', $data);
    }

    //添加文章
    public function collegeArticleAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }

        $params = $request->input();
        $result = CollegeArticle::MyInsert($params);

        if ($result === false) {
            return dataFormat('1', '失败！');
        }
        return dataFormat('0', '成功！');
    }

    //编辑文章
    public function collegeArticleEdit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'title' => 'required',
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }

        $params = $request->input();
        $result = CollegeArticle::MyEdit($params);

        if ($result === false) {
            return dataFormat('1', '失败！');
        }
        return dataFormat('0', '成功！');
    }

    //删除文章
    public function collegeArticleDel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }
        $id = $request->input('id');
        $result = CollegeArticle::where('id', $id)->delete();

        if ($result === false) {
            return dataFormat('1', '失败！');
        }
        return dataFormat('0', '成功！');
    }

    //发布文章
    public function collegeArticlePublish(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required|in:0,1',
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }

        $params = $request->input();
        $result = CollegeArticle::where('id', $params['id'])->update(['status' => $params['status']]);

        if ($result === false) {
            return dataFormat('1', '失败！');
        }
        return dataFormat('0', '成功！');
    }
}
