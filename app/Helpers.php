<?php

function curl_post_https($url, $post_data, $header = [])
{
    if (empty($url)) {
        return false;
    }
    //初始化
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_TIMEOUT, 0);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 0);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    if (!empty($header)) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    }
    //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 信任任何证书
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 检查证书中是否设置域名（为0也可以，就是连域名存在与否都不验证了）
    //设置post数据
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    //执行命令
    $data = curl_exec($curl);
    $error = '';
    if ($data === false) {
        $error = curl_error($curl);
        echo 'Curl error: ' . $error;
    }
    //关闭URL请求
    curl_close($curl);
    $json = json_decode($data, true);
    if (empty($json)) {
        return $data = $data ? $data : $url . ':' . $error;
    }
    //显示获得的数据
    return $json;
}

function dataFormat($code = 0, $msg = '', $data = null)
{
    return ['code'=>$code, 'msg'=>$msg, 'data'=>$data];
}