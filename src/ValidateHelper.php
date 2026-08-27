<?php
/**
 * User: zhengze
 * Date: 2019/7/2
 * Time: 17:45
 */

namespace Helper;


use Helper\exception\BaseException;
use Helper\exception\SystemException;
use Helper\traits\InstanceTrait;

/**
 * 验证类
 * 用法:
 * $rule = ['phone' => ['require' => TRUE, 'regex' => '/^[1][0-9]{10}$/']];
 * $translate = ['phone' => ['name' => '手机号', 'regex' => '格式不正确']];
 * $validator = new ValidateHelper($rule, $translate);
 * $result = $validator->validate($data);
 * validate 返回字符串即错误提示,返回数组即校验通过(且已按翻译表过滤字段)
 * Class ValidateHelper
 * @package Helper
 */
class ValidateHelper
{
    /**
     * 规则表,key为字段名,value为规则数组(功能=>规则值)
     * 现有功能:require(必填),regex(正则)
     * @var array
     */
    protected $ruleMap = [];

    /**
     * 翻译表,key为字段名,value为翻译数组(功能=>提示文案)
     * 现有功能:name(字段显示名),regex(正则错误提示)
     * @var array
     */
    protected $translateMap = [];

    /**
     * ValidateHelper constructor.
     *
     * @param array $rule 规则表,如['phone' => ['require' => TRUE, 'regex' => '/^[1][0-9]{10}$/']]
     * @param array $translate 翻译表,如['phone' => ['name' => '手机号', 'regex' => '格式不正确']]
     */
    public function __construct(array $rule, array $translate)
    {
        if (!empty($rule)) {
            $this->ruleMap = $rule;
        }
        if (!empty($translate)) {
            $this->translateMap = $translate;
        }
    }

    /**
     * 添加翻译<br>
     * 功能现有:name,regex<br>
     * addTranslate("certify_id",["name" => "ID", "regex" => "ID为数字"])
     *
     * @param string $field 字段名
     * @param array $translate 字段翻译,是一个数组,功能=>功能翻译
     *
     * @return void
     */
    public function addTranslate(string $field, array $translate)
    {
        if (empty($this->translateMap[$field])) {
            $this->translateMap[$field] = [];
        }
        $this->translateMap[$field] = array_merge($this->translateMap[$field], $translate);
    }

    /**
     * 添加规则<br>
     * 功能现有:require,regex<br>
     * addRule("phone",["require" => "TRUE", "regex" => '/^[1][0-9]{10}$/'])<br>
     *
     * @param string $field 字段名
     * @param array $rule 规则,是一个数组,功能=>功能描述
     *
     * @return void
     */

    public function addRule(string $field, array $rule)
    {
        if (empty($this->ruleMap[$field])) {
            $this->ruleMap[$field] = [];
        }
        $this->ruleMap[$field] = array_merge($this->ruleMap[$field], $rule);
    }

    /**
     * 数据校验,会根据翻译字段对原数据进行过滤<br>
     * 校验顺序:先必填检测(require),后正则检测(regex),命中任意一条规则即返回错误提示<br>
     * 通过后仅保留翻译表中出现的字段
     *
     * @param array $data 待校验数据,如$_POST
     *
     * @return array|string 返回字符串,就是错误提示,返回数组就是校验通过
     */
    public function validate($data)
    {
        if (count($this->ruleMap) != count($this->translateMap)) {
            BaseException::throwException(SystemException::EMPTY_VALIDATE, "规则字典和翻译字典数量不相等");
        }
        foreach ($this->ruleMap as $field => $rule) {
            //必填检测
            if (!empty($rule['require']) && is_null($data[$field])) {
                return ($this->translateMap[$field]["name"] ?? "") . "必填";
            }
            //正则检测
            if (!empty($rule["regex"]) && isset($data[$field]) &&
                FALSE == preg_match($rule["regex"], $data[$field])) {
                return ($this->translateMap[$field]["name"] ?? "") . ($this->translateMap[$field]["regex"] ?? "");
            }
        }
        return ArrayHelper::arrayColumn($data, array_keys($this->translateMap));
    }
}
