<?php

namespace Helper\exception;

use Exception;
use Helper\DebugHelper;
use Helper\ResponseHelper;

/*
 * 重写Handle的render方法，实现自定义异常消息
 * 如果要使用先调用 set_exception_handler([\Helper\exception\ExceptionHandler::class, 'render']);
 * 如果要用于线上建议先重写render方法;
 */

class ExceptionHandler
{
    /**
     * 渲染异常
     *
     * @param Exception $e
     *
     */
    public static function render(\Throwable $e)
    {

//        $result = [
//            'code' => 500,
//            'msg' => '对不起,有一个特殊错误出现,请联系运营',
//            'data' => [],
//        ];
        if ($e instanceof BaseException) {
            $result = $e->getInfo();
            ResponseHelper::json($result);
        }
        static::printException($e);
        return TRUE;
    }

    /**
     * 测试的时候打印异常
     *
     * @param Exception $e
     * @param $log
     *
     * @return bool
     */
    public static function printException(\Throwable $e)
    {
        ob_start();
        $log = sprintf("<p style='font-size: 36px;'>%s</p>", $e->getMessage());
        $log .= sprintf("<p style='font-size: 20px;'>%s (%s) %s</p>", $e->getFile(), $e->getLine(), "\n<br>");
        DebugHelper::print("异常信息(message): {$e->getMessage()}");
        DebugHelper::print("异常文件(file): {$e->getFile()}");
        DebugHelper::print("异常行号(line): {$e->getLine()}");
        $trace = $e->getTrace();
        foreach ($trace as $key => $value) {
            if (!empty($value['file']) && !empty($value['line'])) {
                $log .= sprintf("<p style='font-size: 20px;'>%s (%s) %s</p>", $value['file'], $value['line'], "\n");
                DebugHelper::print("异常路径(trace): {$value['file']}-{$value['line']}");
            }
        }
        $html_content = ob_get_clean();
        return ResponseHelper::html($html_content);
    }


}
