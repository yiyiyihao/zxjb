<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class User extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'user';
    static $error;
    static $msg = '成功！';

    public function adminRole()
    {
        return $this->belongsToMany('App\Models\AdminRole', 'admin_manager_role', 'manager_id','role_id');
    }

//    public function auth()
//    {
//        return $this->hasOne('App\Models\UserAuth','user_id', 'id');
//    }

    public static function MyInsert($params)
    {
        $data = [
            'username' => $params['username'] ?? '',
            'realname' => $params['realname'] ?? '',
            'phone' => $params['phone'] ?? '',
            'avatar' => $params['avatar'] ?? 'http://img.smarlife.cn/20200925/nkd_8e6f2202009251046352196.jpg',
            'password' => encrypt($params['password']),
            'status' => $params['status'] ?? 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        $exist = User::where('username', $params['username'])->first();
        $exist1 = User::where('phone', $params['phone'])->first();
        DB::beginTransaction();
        try {
            if ($exist) {
                $exist2 = DB::table('user_group')->where('user_id', $exist->id)->where('user_type', 'sys_admin')->first();
                if ($exist2) {
                    User::$error = '用户已存在！';
                    DB::rollBack();
                    return false;
                }
                User::$msg = '密码为app密码！';
                $userId = $exist->id;
            }elseif($exist1){
                $exist2 = DB::table('user_group')->where('user_id', $exist1->id)->where('user_type', 'sys_admin')->first();
                if ($exist2) {
                    User::$error = '手机号已经存在！';
                    DB::rollBack();
                    return false;
                }
                User::$msg = '密码为app密码！';
                $userId = $exist1->id;
            }else{
                $userId = User::insertGetId($data);
                if (!$userId) {
                    User::$error = '添加失败！';
                    DB::rollBack();
                    return false;
                }
            }

            //添加到用户组
            $data = [
                'user_id' => $userId,
                'realname' => $params['realname'],
                'user_type' => 'sys_admin',
                'created_at' => date('Y-m-d H:i:s')
            ];
            DB::table('user_group')->insert($data);

            $roleIds = $params['roleIds'] ?? [];
            $data = [];
            //写入用户角色表中间表
            if (!empty($roleIds)) {
                foreach ($roleIds as $k => $v) {
                    $data[] = ['manager_id' => $userId, 'role_id' => $v];
                }
            }
            DB::table('admin_manager_role')->insert($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e;
            die;
        }

        return true;
    }

    public static function MyEdit($params)
    {
        $data = [
//            'username' => $params['username'] ?? '',
            'realname' => $params['realname'] ?? '',
            'phone' => $params['phone'] ?? '',
            'status' => $params['status'] ?? 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        if($params['password']){
            $data['password'] = encrypt($params['password']);
        }
        $userId = $params['id'];

        $exist = User::where('phone', $params['phone'])->where('id','!=',$userId)->first();
        if($exist){
            User::$error = '手机号已经存在！';
            return false;
        }
        DB::beginTransaction();
        try {
            $userId = User::where('id', $userId)->update($data);
            //添加到用户组
            $data = [
                'user_id' => $userId,
                'realname' => $params['realname'],
                'user_type' => 'sys_admin',
                'updated_at' => date('Y-m-d H:i:s')
            ];
            DB::table('user_group')->where('user_id',$userId)->update($data);

            $roleIds = $params['roleIds'] ?? [];
            $data = [];
            //写入用户角色表中间表
            if (!empty($roleIds)) {
                foreach ($roleIds as $k => $v) {
                    $data[] = ['manager_id' => $userId, 'role_id' => $v];
                }
            }
            DB::table('admin_manager_role')->where('manager_id', $userId)->delete();
            DB::table('admin_manager_role')->insert($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e;
            die;
        }

        return true;
    }



}
