<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminRoleController extends Controller
{
    //角色列表
    public function adminRoleList(Request $request)
    {
        $params = $request->input();
        $page = $params['page'] ?? 1;
        $size = $params['size'] ?? 10;

        $data = AdminRole::orderBy('created_at', 'desc')->paginate($size);

        return dataFormat(0, '成功！', $data);
    }

    //添加角色
    public function adminRoleAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'required',
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }

        $params = $request->input();
        $result = AdminRole::MyInsert($params);

        if ($result === false) {
            return dataFormat('1', '失败！');
        }
        return dataFormat('0', '成功！');
    }

    //编辑角色
    public function adminRoleEdit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|int',
            'role_name' => 'required',
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }

        $params = $request->input();
        $result = AdminRole::MyEdit($params);

        if ($result === false) {
            return dataFormat('1', '失败！');
        }
        return dataFormat('0', '成功！');
    }

    //删除角色
    public function adminRoleDel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }
        $id = $request->input('id');
        $result = AdminRole::where('id', $id)->delete();

        if ($result === false) {
            return dataFormat('1', '失败！');
        }
        return dataFormat('0', '成功！');
    }
}
