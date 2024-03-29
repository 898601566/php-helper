<?php

namespace Helper\exception;

use Exception;
use Helper\DebugHelper;
use Helper\ResponseHelper;

/*
 * 重写Handle的render方法，实现自定义异常消息
 * 如果要使用先调用 set_error_handler([\Helper\exception\ErrorHandler::class, 'render']);
 * 触发错误用trigger_error("notice, go on!", E_USER_NOTICE);
 */
class ErrorHandler
{
    /**
     * 渲染异常
     *
     * @param Exception $e
     *
     * @return string
     */
    public static function render($errno, $errstr, $errfile, $errline)
    {
        ob_start();
        DebugHelper::printBr("错误编号(errno): $errno");
        DebugHelper::printBr("错误信息(errstr): $errstr", 0);
        DebugHelper::printBr("出错文件(errfile): $errfile", 0);
        DebugHelper::printBr("出错行号(errline): $errline", 0);
        $html_content = ob_get_clean();
        return ResponseHelper::html($html_content);
    }
}
