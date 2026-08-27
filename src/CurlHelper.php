<?php
/**
 * User: zhengze
 * Date: 2019/7/2
 * Time: 17:45
 */

namespace Helper;


/**
 * Curl调用网络资源
 * Class CurlFunc
 * @package Helper
 */
class CurlHelper
{

    /**
     * 发送 POST 请求,参数以 JSON 编码后作为请求体
     * 默认 Content-Type: application/json,连接超时 10 秒
     *
     * @param string $url post请求地址
     * @param array $params 请求参数数组,会被 json_encode 后发送
     * @param array $header 附加 HTTP 头,如['Content-Type'=>'application/json']
     *
     * @return mixed 响应内容(curl_exec 原样返回,失败返回false)
     */
    public static function curlPost($url, array $params = [], $header = [])
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
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
//    传送数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
//    设置 HTTP 头字段的数组
        if (empty($header['Content-Type'])) {
            $header['Content-Type'] = 'application/json';
        }
        foreach ($header as $key => $value) {
            $header[$key] = "$key:$value";
        }
        if (!empty($header)) {
            curl_setopt(
                $ch, CURLOPT_HTTPHEADER, array_values($header)
            );
        }
        $data = curl_exec($ch);
        curl_close($ch);
        return ($data);
    }

    /**
     * 发送 POST 请求,原样发送请求体字符串(不做 json_encode)
     * 固定 Content-Type: text,连接超时 10 秒
     *
     * @param string $url post请求地址
     * @param string $rawData 原始请求体内容
     *
     * @return mixed 响应内容(curl_exec 原样返回,失败返回false)
     */
    public static function curlPostRaw($url, $rawData)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1);
//    证书校验
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $rawData);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, [
                'Content-Type: text',
            ]
        );
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * 发送 GET 请求
     * $data 为数组时自动 http_build_query 拼接到 url,连接超时 10 秒
     *
     * @param string $url get请求地址
     * @param array $data 查询参数,数组或已拼接的查询字符串
     * @param array $header 附加 HTTP 头,如['Authorization'=>'Bearer xxx']
     *
     * @return mixed 响应内容(curl_exec 原样返回,失败返回false)
     */
    public function curlGet($url, array $data = [], $header = [])
    {
        $ch = curl_init();
        if (!empty($data)) {
            $data = is_array($data) ? http_build_query($data) : trim($data, '&');
            $url = rtrim($url, '&') . (strpos($url, '?') === FALSE ? '?' : '&') . $data;
            $url = rtrim($url, '&');
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//    设置 HTTP 头字段的数组
        if (!empty($header)) {
            foreach ($header as $key => $value) {
                $header[$key] = "$key:$value";
            }
            curl_setopt(
                $ch, CURLOPT_HTTPHEADER, array_values($header)
            );
        }
//    证书校验
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }
}
