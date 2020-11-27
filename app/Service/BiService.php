<?php
/**
 * Created by huangyihao.
 * User: Administrator
 * Date: 2020/11/23 0023
 * Time: 18:21
 */
namespace App\Service;

class BiService
{
    public static function getToken()
    {
        $url = 'http://bi.api.worthcloud.net/v1/token/token';
        $access_key = 'WhwTOCRu6A3sMrjrgojUnfhDx_jLOe57';
        $secret_key = 'iH_sdhD12ctZQKDg6OfFUQB_bOc3IAT_';
        $post_data = ['access_key' => $access_key, 'secret_key' => $secret_key];
        $data = cache('biToken');
        if(!$data){
            $data = curl_post_https($url, $post_data);
            cache(['biToken' => $data], 6000);
        }

        return $data;
    }
}