<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Service\MakeToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PublicController extends Controller
{
    static $expires = 7200;
    //登入
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return dataFormat('1', $validator->errors()->first());
        }
        $params = $request->input();
        $username = $params['username'];
        $password = $params['password'];

        $userInfo = User::where('username', $username)->first()->toArray();
        if (empty($userInfo)) {
            return dataFormat('1', '非法用户名！');
        }

        if ($password != decrypt($userInfo['password'])) {
            return dataFormat('1', '密码错误！');
        }

        $token = MakeToken::token();
        $tokenInfo = [
            'token' => $token,
            'expires_time' => time() + self::$expires,
            'userInfo' => $userInfo  #todo 加前端显示权限
        ];
        $res = cache([$token=>$tokenInfo], self::$expires);

        return dataFormat('0','登入成功！', $tokenInfo);
    }

    //刷新token的接口
    public function refreshToken(Request $request)
    {
        $token = $request->input('token');
        $newToken = MakeToken::token();
        $tokenInfo = [
            'token' => $newToken,
            'expires_time' => time() + self::$expires,
            'userInfo' => cache($token)['userInfo']  #todo 加前端显示权限
        ];
        cache([$token=>null],0);
        cache([$newToken=>$tokenInfo],self::$expires);
        return dataFormat('0','刷新成功！', $tokenInfo);
    }

    //登出
    public function logout(Request $request)
    {
        $token = $request->input('token');

        cache([$token=>null],0);
        return dataFormat('0','退出成功！');
    }
}
