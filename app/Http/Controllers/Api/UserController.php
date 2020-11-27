<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //用户列表
    public function userList(Request $request)
    {
        $params = $request->input();
        $page = $params['page'] ?? 1;
        $size = $params['size'] ?? 10;

        $data = User::with('adminRole')
            ->join('user_group', 'user_group.user_id','=', 'user.id')
            ->where('user_group.user_type', 'sys_admin')
            ->orderBy('user.created_at', 'desc')
            ->paginate($size);

        return dataFormat(0, '成功！', $data);
    }

    //添加用户
    public function userAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'realname' => 'required',
            'phone' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required|same:password'
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }

        $params = $request->input();
        $result = User::MyInsert($params);

        if ($result === false) {
            return dataFormat('1', User::$error);
        }
        return dataFormat('0', User::$msg);
    }

    //编辑用户
    public function userEdit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|int',
            'username' => 'required',
            'realname' => 'required',
            'phone' => 'required',
            'password' => 'nullable|confirmed',
            'password_confirmation' => 'nullable|same:password'
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }

        $params = $request->input();
        $result = User::MyEdit($params);

        if ($result === false) {
            return dataFormat('1', User::$error);
        }
        return dataFormat('0', User::$msg);
    }

    //删除用户
    public function userDel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }
        $id = $request->input('id');
        $result = User::where('id', $id)->delete();


        if ($result === false) {
            return dataFormat('1', '失败！');
        }
        $result = DB::table('user_group')->where('user_id', $id)->delete();
        return dataFormat('0', '成功！');
    }
}
