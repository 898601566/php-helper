<?php
/**
 * User: zhengze
 * Date: 2019/7/2
 * Time: 17:45
 */

namespace Helper;



/**
 * Class CurlFunc
 * @package common
 */
class CurlHelper
{

    /**
 * @param string $url post请求地址
 * @param array $params
 * @return mixed
 */
public static function curl_post($url, array $params = array(), $token = '')
{
    $data_string = json_encode($params);
    $ch = curl_init();
//   设置url
    curl_setopt($ch, CURLOPT_URL, $url);
//    移除返回的头信息
    curl_setopt($ch, CURLOPT_HEADER, 0);
//    信息以字符串返回
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//    在尝试连接时等待的秒数
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
//    请求类型,true为post
    curl_setopt($ch, CURLOPT_POST, 1);
//    证书校验
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//    传送数据
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
//    设置 HTTP 头字段的数组
    $header = array(
        'Content-Type: application/json'
    );
    if (!empty($token)) {
        $header['token'] = "token:$token";
    }
    curl_setopt(
        $ch, CURLOPT_HTTPHEADER, $header
    );
    $data = curl_exec($ch);
    curl_close($ch);
    return ($data);
}

public static function curl_post_raw($url, $rawData)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $rawData);
    curl_setopt(
        $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: text'
        )
    );
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

/**
 * @param string $url get请求地址
 * @param int $httpCode 返回状态码
 * @return mixed
 */
public static function curl_get($url, &$httpCode = 0)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //不做证书校验,部署在linux环境下请改为true
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $file_contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $file_contents;
}
}
