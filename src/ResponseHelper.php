<?php
/**
 * User: zhengze
 * Date: 2019/7/2
 * Time: 17:45
 */

namespace Helper;

/**
 * Class ResponseHelper
 * @package common
 */
class ResponseHelper
{

    /**
     * Response格式
     *
     * @param array $data
     * @param int $system 是否返回系统参数
     *
     * @return array
     */
    public static function getResponseExample($data = [], $system = 0)
    {
        $ret = [
            'code' => 0,
            'msg' => 'success',
            'data' => $data,
        ];
        if (!empty($system)) {
            if (env('app_debug', FALSE)) {
                $runtime = static::getRuntime();
                $ret['system'] = [
                    'request_time' => \request()->time(),
                    'runtime' => $runtime,
                ];

            }
        }
        return $ret;
    }

    /**
     * 获取运行时间
     */
    public static function getRuntime()
    {
        $app = app();
        $runtime = number_format(microtime(TRUE) - $app->getBeginTime(), 10, '.', '');
        $runtime = number_format((float)$runtime, 6) . 's';
        return $runtime;
    }

}
