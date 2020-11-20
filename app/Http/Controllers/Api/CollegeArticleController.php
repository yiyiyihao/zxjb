<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CollegeArticle;
use Illuminate\Http\Request;

class CollegeArticleController extends Controller
{
    //文章列表
    public function collegeArticleList()
    {
        $data = CollegeArticle::get();



    }
    //添加文章
    //编辑文章
    //删除文章
    //发布文章
}
