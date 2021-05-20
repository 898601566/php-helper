<?php
/**
 * User: zhengze
 * Date: 2019/7/2
 * Time: 17:45
 */

namespace Helper;

/**
 * 响应类
 * Class ResponseHelper
 * @package Helper
 */
class ResponseHelper
{

    /**
     * Response格式
     *
     * @param array $data
     *
     * @return array
     */
    public static function getResponseExample(array $data = [], $code = '0', $msg = 'success')
    {
        $data = !empty($data) ? $data : new \stdClass();
        $ret = [
            'code' => 0,
            'msg' => 'success',
            'data' => $data,
        ];
        return $ret;
    }

    /**
     * html格式返回
     *
     * @param string $response
     * @param int $code
     *
     * @return bool
     */
    public static function html(string $response='', $code = 0)
    {
        if (!empty($code)) {
            // 发送状态码
            http_response_code($code);
        }
        echo $response;
        exit;
        return TRUE;
    }

    /**
     * json格式返回
     *
     * @param array $response
     * @param int $code
     *
     * @return bool
     */
    public static function json(array $response, $code = 0)
    {
        header("Content-Type:application/json; charset=utf-8;");
        if (!empty($code)) {
            // 发送状态码
            http_response_code($code);
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
        return TRUE;
    }

}
