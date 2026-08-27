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
     * 生成统一 Response 格式
     * 结构为 ['code'=>业务状态码, 'msg'=>提示信息, 'data'=>数据]
     * $data 为空数组时返回空对象 stdClass(便于前端 JSON 解析)
     *
     * @param array $data 业务数据
     * @param int|string $code 业务状态码,默认0
     * @param string $msg 提示信息,默认'success'
     *
     * @return array
     */
    public static function getResponseExample(array $data = [], $code = 0, $msg = 'success')
    {
        $data = !empty($data) ? $data : new \stdClass();
        $ret = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ];
        return $ret;
    }

    /**
     * html格式返回
     * 输出内容后直接 exit 终止程序
     *
     * @param string $response 要输出的内容
     * @param int $code HTTP 状态码,不为0时发送
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
     * 设置 Content-Type: application/json 后输出 JSON 并 exit 终止程序
     * 使用 JSON_UNESCAPED_UNICODE,中文不会被转义
     *
     * @param array $response 要输出的数组
     * @param int $code HTTP 状态码,不为0时发送
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
