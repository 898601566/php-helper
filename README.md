# php-helper

PHP 工具类集合库，无框架依赖，可集成到任意 PHP 项目。

## 环境要求

- PHP >= 7.1
- Composer
- `YaconfHelper` 需要可选的 [yaconf](https://github.com/laruence/yaconf) PECL 扩展

## 安装

``` bash
composer require alex-lee/php-helper
```

安装后自动注册 PSR-4 命名空间 `Helper\`（指向 `src/`）和全局辅助函数文件 `src/helper.php`。

## 功能列表

| 类 | 说明 |
|----|------|
| ArrayHelper | 数组工具：键重组、分组、树形/层级转换、字段提取删除、一对多关联、去重等 |
| ConfigHelper | 配置加载：设置目录后以点分语法读取 PHP 配置文件，带文件级缓存 |
| CurlHelper | HTTP 请求：GET / POST(JSON) / POST(Raw) |
| DateHelper | 日期时间：年/季/月/周/日区间起止、日期与时间戳互转、时间段交集判断 |
| DebugHelper | 调试输出：sdump 打印并退出、文件加载轨迹打印 |
| EnvHelper | 环境变量：加载 .env 文件，支持 ArrayAccess 与属性访问 |
| LogHelper | 日志：按分类分目录、按小时切分文件写入 |
| NumberHelper | 数字：金额格式化、正整数检测、手机号打码 |
| RequestHelper | 请求输入：$_GET/$_POST/$_REQUEST/$_SESSION 等超全局变量封装 |
| ResponseHelper | 响应输出：JSON / HTML 输出并终止、统一响应结构 |
| SortHelper | 排序算法：冒泡、快速、桶、基数、选择、插入、希尔、堆、归并 |
| StringHelper | 字符串：驼峰与下划线互转、随机串、多字节替换、密码加密、缓存 key 生成 |
| ValidateHelper | 数据校验：require/regex 规则 + 字段名翻译，校验即过滤 |
| YaconfHelper | Yaconf 配置：按项目名前缀读取，或读取公用配置 |
| exception/* | 异常体系：BaseException/SystemException 错误码常量、错误与异常处理器 |
| traits/InstanceTrait | 单例模式 Trait |

## 使用示例

### 数组 ArrayHelper

``` php
use Helper\ArrayHelper;

// 用指定字段值作为 key 重组数组
 ArrayHelper::arrayResetKey($list, 'id');

// 二维数组按字段分组
 ArrayHelper::arrayGroup($list, 'status');

// 列表一对多关联(主表字段支持逗号隔开字符串)
 ArrayHelper::listOneToMulti($list, $children, 'stu_list', 'stu_id', 'stu_id');

// 列表转树
 ArrayHelper::arrayToTree($list, 'id', 'pid', 'child');
```

### 配置 ConfigHelper

``` php
use Helper\ConfigHelper;

// 先设置配置目录(如 APP_PATH.'config/')
 ConfigHelper::setDir(__DIR__ . '/config/');
// 点分语法读取 config/database.php 里的 connections->host
 ConfigHelper::load('database.connections.host');
```

### 环境变量 EnvHelper

``` php
use Helper\EnvHelper;

 EnvHelper::instance()->load(__DIR__ . '/.env');
$debug = EnvHelper::instance()->get('app_debug', false);

// 或直接使用全局函数
$debug = env('app_debug', false);
```

### 日志 LogHelper

``` php
use Helper\LogHelper;

// 注册日志根目录与分类目录(自动创建)
 LogHelper::setPathName(__DIR__ . '/runtime/log', ['mysql', 'request']);
// 写入分类日志 -> runtime/log/mysql/2024-01-01 10.log
 LogHelper::writeLog(['sql' => '...'], 'mysql');
// 写入默认日志 -> runtime/log/2024-01-01 10.log
 LogHelper::writeLog('普通日志');
```

### 请求与响应

``` php
use Helper\RequestHelper;
use Helper\ResponseHelper;

// 批量获取请求参数(索引数组+默认值)
$data = RequestHelper::input(['name', 'age' => 18]);

// 统一 JSON 响应并终止
 ResponseHelper::json(ResponseHelper::getResponseExample($data));
```

### 数据校验 ValidateHelper

``` php
use Helper\ValidateHelper;

$rule = [
    'phone' => ['require' => true, 'regex' => '/^[1][0-9]{10}$/'],
];
$translate = [
    'phone' => ['name' => '手机号', 'regex' => '格式不正确'],
];
$validator = new ValidateHelper($rule, $translate);

$result = $validator->validate($_POST);
// 返回字符串 => 错误提示;返回数组 => 校验通过(且已按翻译表过滤字段)
```

### 排序 SortHelper

``` php
use Helper\SortHelper;

$sorter = SortHelper::instance();
$arr = [5, 3, 8, 1, 9];
$sorter->bubble($arr);      // 冒泡(原地排序)
$arr = $sorter->mergeSort($arr); // 归并(返回新数组)
```

## 全局函数

安装后 `src/helper.php` 自动加载，提供以下函数（均防重复定义）：

| 函数 | 说明 |
|------|------|
| `value($value)` | 闭包则执行返回结果，否则原样返回 |
| `sdump(...$param)` | 打印变量并终止程序（调试用） |
| `env($var, $default)` | 读取 .env 环境变量 |
| `yaconf($var, $default)` | 读取 yaconf 公用配置 |

## License

MIT
