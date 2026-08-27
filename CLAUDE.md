# CLAUDE.md

本文件为 Claude Code (claude.ai/code) 在此代码库中工作时提供指导。

## 项目概述

php-helper 是一个 PHP 工具类集合库（Composer 包 `alex-lee/php-helper`），提供数组、字符串、日期、数字、请求、响应、日志、配置、调试、校验、排序等日常开发常用功能。

- **定位**：无框架依赖的通用工具库，可集成到任意 PHP 项目（原生 PHP / Laravel / ThinkPHP 等）
- **语言要求**：PHP >= 7.1
- **运行时依赖**：无第三方 Composer 依赖；`YaconfHelper` 需要已安装 [yaconf](https://github.com/laruence/yaconf) PECL 扩展
- **许可证**：MIT

## 目录结构

```
php-helper/
├── src/                          # 源码目录(PSR-4 命名空间 Helper\)
│   ├── ArrayHelper.php           # 数组工具(静态):分组/树形转换/字段提取/一对多关联等
│   ├── ConfigHelper.php          # 配置文件加载(静态+单例):目录+点分语法加载 PHP 配置
│   ├── CurlHelper.php            # HTTP 请求(静态):curlGet/curlPost(JSON/Raw)
│   ├── DateHelper.php            # 日期时间(静态):年/季/月/周/日区间、时间戳与日期互转
│   ├── DebugHelper.php           # 调试输出(静态):sdump 系列、文件加载轨迹打印
│   ├── EnvHelper.php             # 环境变量(.env,实例+ArrayAccess,ThinkPHP 风格)
│   ├── LogHelper.php             # 日志(静态):按分类分目录、按小时切分文件
│   ├── NumberHelper.php          # 数字(静态):金额格式化、正整数检测、手机号打码
│   ├── RequestHelper.php         # 请求输入(静态):$_GET/$_POST/$_REQUEST 等超全局封装
│   ├── ResponseHelper.php        # 响应(静态):json/html 输出并终止、统一响应结构
│   ├── SortHelper.php            # 排序算法(实例):冒泡/快速/桶/基数/选择/插入/希尔/堆/归并
│   ├── StringHelper.php          # 字符串(静态):驼峰转换、随机串、多字节替换、密码加密
│   ├── ValidateHelper.php        # 数据校验(实例):require/regex 规则+字段翻译
│   ├── YaconfHelper.php          # Yaconf 配置(静态):按项目名前缀读取 yaconf 配置
│   ├── helper.php                # 全局函数: value()/sdump()/env()/yaconf()
│   ├── exception/                # 异常体系
│   │   ├── BaseException.php     # 异常基类 + SystemException 错误码常量表
│   │   ├── ErrorHandler.php      # set_error_handler 处理器
│   │   └── ExceptionHandler.php  # set_exception_handler 处理器
│   └── traits/
│       └── InstanceTrait.php     # 单例模式 Trait(instance/getInstance/setInstance)
├── test.php                      # 手动冒烟测试脚本(非 PHPUnit)
├── composer.json
├── README.md
└── CLAUDE.md
```

## 架构说明

### 自动加载

- PSR-4 映射：`Helper\` => `src/`
- `src/helper.php` 通过 composer `files` 字段自动加载，提供 4 个全局函数：`value()`、`sdump()`、`env()`、`yaconf()`（均用 `function_exists` 防重复定义）

### 调用风格约定

| 风格 | 适用类 |
|------|--------|
| 纯静态调用 | ArrayHelper、DateHelper、DebugHelper、NumberHelper、RequestHelper、ResponseHelper、StringHelper、CurlHelper、LogHelper |
| 静态属性 + 静态方法 | ConfigHelper、YaconfHelper（使用前需先 setDir/setAppName） |
| 实例（InstanceTrait 单例） | EnvHelper、ConfigHelper、SortHelper |
| 实例（构造传参） | ValidateHelper（构造时传入规则表和翻译表） |

### 核心机制

- **配置加载（ConfigHelper）**：先 `ConfigHelper::setDir($dir)` 设置配置目录，再 `load('file.key1.key2')` 以点分语法读取，文件级结果缓存在静态属性 `static::$file` 中（`require_once` 只执行一次）
- **环境变量（EnvHelper）**：`EnvHelper::instance()->load('.env路径')` 或直接用全局 `env('APP_DEBUG')`；变量名统一转大写，支持 `PHP_` 前缀的 getenv 兜底
- **Yaconf（YaconfHelper）**：项目级配置自动加 `app.name` 前缀（读取 `env('app.name')`），公用配置直接 `YaconfHelper::get()`
- **日志（LogHelper）**：先 `setPathName($root, $types)` 注册分类目录（目录不存在会自动创建），再 `writeLog($log, $type)`；已注册分类写入 `<root>/<type>/Y-m-d H.log`，未注册分类写入 `<root>/Y-m-d H.<type>.log`
- **异常体系**：`BaseException::throwException(SystemException::XXX[, $msg, $code])` 抛出预定义错误；`ExceptionHandler::render` 捕获后以 JSON 输出（仅处理 BaseException 子类）。使用方式：
  ```php
  set_exception_handler([\Helper\exception\ExceptionHandler::class, 'render']);
  set_error_handler([\Helper\exception\ErrorHandler::class, 'render']);
  ```
- **数据校验（ValidateHelper）**：规则表与翻译表字段数量必须一致，否则抛错；校验通过返回经字段过滤后的数组，失败返回错误提示字符串

## 代码规范

- **命名**：类名大驼峰且以 `Helper` 结尾；方法名小驼峰；命名空间 `Helper`、`Helper\traits`、`Helper\exception`
- **注释**：中文注释为主，类和方法使用 PHPDoc 块；方法内关键步骤加 `//` 行注释
- **静态调用**：类内部调用统一使用 `static::`（晚期静态绑定），不用 `self::`
- **布尔字面量**：历史代码混用 `TRUE/FALSE` 大写风格，修改现有文件时保持该文件原有风格，不要全局替换
- **缩进**：4 空格
- **文件头部**：历史文件带有 `User/Date/Time` 注释头，新文件无需模仿
- **严格类型**：项目未启用 `declare(strict_types=1)`，新代码不要擅自添加，避免与存量行为不一致

### 历史遗留问题（修改相关代码时注意）

2026-08 已修复：`StringHelper::isEmptyString()` 递归调用、`ValidateHelper::validate()` 异常类、`YaconfHelper::formatAppConfigName()` 异常类与 void 赋值、`CurlHelper` header 条件写反、README 安装命令、`BaseException::__construct()` 的 `\Throwable` 反斜杠、`ResponseHelper::getResponseExample()` 的 `$code/$msg` 参数生效。

以下问题仍存在于存量代码中，未经用户明确要求**不要顺手修复**，但新增代码不要延续：

- `LogHelper::writeLog()` 写入文件的同时会 `echo` 日志内容，可能污染 CLI/Web 输出

## 开发工作流程

### 本地运行

```powershell
composer install        # 安装(无第三方依赖,仅生成 autoload)
php test.php            # 运行冒烟脚本(依赖 xdebug 扩展的调试函数)
```

`test.php` 是手动维护的冒烟脚本，非正式测试套件；新增功能时建议在其中追加调用示例。

### 变更检查清单

1. 修改 `src/` 下代码后，确认 `php test.php` 无语法错误（`php -l src/文件名.php`）
2. 保持 PSR-4：类名与文件名一致，新增类放入 `src/` 对应位置
3. 新增全局函数必须放在 `src/helper.php` 且用 `function_exists` 包裹
4. 新增 Helper 后同步更新 `README.md` 功能列表和 `CLAUDE.md` 目录结构
5. 提交信息使用中文短句描述（参考 git log 现有风格）

### 文档同步

- `README.md`：面向使用者——功能列表、安装、各 Helper 用法示例
- `CLAUDE.md`：面向 AI 协作——架构、规范、工作流程（本文件）
