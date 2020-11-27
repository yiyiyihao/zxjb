<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminRole extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = 'admin_role';

    public static function MyInsert($params)
    {
        $data = [
            'role_name' => $params['role_name'],
            'describe' => $params['describe'] ?? '',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $result = AdminRole::insert($data);
        return $result;
    }

    public static function MyEdit($params)
    {
        $data = [
            'role_name' => $params['role_name'],
            'describe' => $params['describe'] ?? '',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $result = AdminRole::where('id', $params['id'])->update($data);
        return $result;
    }
}
