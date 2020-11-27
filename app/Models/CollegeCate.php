<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CollegeCate extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'college_cate';

    public static function myInsert($params)
    {
        $data = [
            'cate_name' => $params['cate_name'] ?? '',
            'sort' => $params['sort'] ?? 0,
            'status' => $params['status'] ?? 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $result = CollegeCate::insert($data);
        return $result;
    }

    public static function MyEdit($params)
    {
        $data = [
            'cate_name' => $params['cate_name'] ?? '',
            'sort' => $params['sort'] ?? 0,
            'status' => $params['status'] ?? 1,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $result = CollegeCate::where('id', $params['id'])->update($data);
        return $result;
    }
}
