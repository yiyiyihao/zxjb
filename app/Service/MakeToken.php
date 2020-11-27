<?php
namespace App\Service;

/**
 * 生成token
 */
class MakeToken
{
    /**
     * 生成AToken
     * @return string
     */
    public static function token($lenght = 32)
    {
        //生成AccessToken
        $str_pol = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789abcdefghijklmnopqrstuvwxyz";
        return substr(str_shuffle($str_pol), 0, $lenght);

    }

}