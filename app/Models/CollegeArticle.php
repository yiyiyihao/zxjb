<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CollegeArticle extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'college_article';

    public static function MyInsert($params)
    {
        $data = [
            'cate_id' => $params['cate_id'] ?? '',
            'title' => $params['title'] ?? '',
            'summary' => $params['summary'] ?? '',
            'content' => $params['content'] ?? '',
            'image' => $params['image'] ?? '',
            'status' => $params['status'] ?? 1,
            'sort' => $params['sort'] ?? 0,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $result = CollegeArticle::insert($data);
        return $result;
    }

    public static function MyEdit($params)
    {
        $data = [
            'cate_id' => $params['cate_id'] ?? '',
            'title' => $params['title'] ?? '',
            'summary' => $params['summary'] ?? '',
            'content' => $params['content'] ?? '',
            'image' => $params['image'] ?? '',
            'status' => $params['status'] ?? 1,
            'sort' => $params['sort'] ?? 0,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $result = CollegeArticle::where('id', $params['id'])->update($data);
        return $result;
    }
}
