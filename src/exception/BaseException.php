<?php

namespace Helper\exception;


/**
 * Class BaseException
 * 自定义异常类的基类
 */
class BaseException extends \Exception
{

    /**
     * 构造函数，设置异常的信息(用于自定义)
     *
     * @param array $params 关联数组应包含code、msg和data，且不应该是空值
     */
    public function __construct($message = [], $code = '', Throwable $previous = null)
    {
        parent::__construct($message,$code,$previous);
    }


    /**
     * 获取异常返回信息 code,msg,data
     * @return array
     */
    public function getInfo()
    {
        return $info = [
            'code' => $this->getCode(),
            'msg' => $this->getMessage(),
            'data' => [],
        ];
    }

    /**
     * 设置异常信息并抛出
     *
     * @param array $exception_config
     *
     */
    public static function throwException(array $exception_config, $msg = '', $code = '')
    {
        $the_msg = '';
        $the_code = '';
        if (!empty($exception_config)) {
            if (!empty($exception_config['code'])) {
                $the_code = $exception_config['code'];
            }
            if (!empty($exception_config['msg'])) {
                $the_msg = $exception_config['msg'];
            }
        }

        if (!empty($msg)) {
            $the_msg = $msg;
        }

        if (!empty($code)) {
            $the_code = $code;
        }
        $exception = new static($the_msg,$the_code);
        throw $exception;
    }


}

class SystemException extends BaseException
{
    const INVALID_USE_REDIS = ['msg' => '错误使用缓存', 'code' => 10001,];
    const INVALID_OPERATE = ['msg' => '非法操作', 'code' => 10002,];
    const INVALID_PARAMETER = ['msg' => '非法参数', 'code' => 10003,];
    const EMPTY_CONFIG = ['msg' => '加载配置项失败', 'code' => 10004,];
    const CROS_CONFIG = ['msg' => 'CROS成功', 'code' => 10005,];
    const INVALID_ROUTE = ['msg' => '路由地址不正确', 'code' => 10006,];
    const CREATE_FAILE = ['msg' => '添加失败', 'code' => 10007,];
    const UPDATE_FAILE = ['msg' => '编辑失败', 'code' => 10008,];
    const SPECIAL_ERROR = ['msg' => '对不起,有一个特殊错误出现,请联系运营', 'code' => 10009,];
    const NO_CAPTCHA = ['msg' => '请输入验证码', 'code' => 10010,];
    const CAPTCHA_ERROR = ['msg' => '验证码错误', 'code' => 10011,];
    const FIELD_CONFILICT = ['msg' => '字段冲突', 'code' => 10012,];
    const EMPTY_CONFIG_APP_NAME = ['msg' => '.env项目名称未设置', 'code' => 10013,];
    const RELOGIN = ['msg' => '请重新登录', 'code' => 10014,];
    const EMPTY_VALIDATE = ['msg' => 'Validate未完善', 'code' => 10015,];
}
