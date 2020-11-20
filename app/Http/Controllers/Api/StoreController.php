<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;


class StoreController extends Controller
{
    //门店列表
    public function storeList(Request $request)
    {
        $data = Store::get();
        return $data;
    }

    //添加门店
    public function addStore(Request $request)
    {
        //添加到bi
        //添加到zxj


    }

    //删除门店
    public function editStore(Request $request)
    {
        //删除bi
        //删除zxj

    }

}
