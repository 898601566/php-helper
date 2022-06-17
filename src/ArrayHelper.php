<?php
/**
 * User: zhengze
 * Date: 2019/7/2
 * Time: 17:45
 */

namespace Helper;

/**
 * 数组类
 * Class ArrayHelper
 * @package Helper
 */
class ArrayHelper
{

    /**
     * 判断一个变量是否可以数组形式访问
     *
     * @param $value
     *
     * @return bool
     */
    public static function accessible($value): bool
    {
        return is_array($value) || $value instanceof \ArrayAccess;
    }

    /**
     * Return the first element in an array passing a given truth test.
     *
     * @param null|mixed $default
     */
    public static function first(array $array, callable $callback = NULL, $default = NULL)
    {
        if (is_null($callback)) {
            if (empty($array)) {
                return value($default);
            }
            foreach ($array as $item) {
                return $item;
            }
        }
        foreach ($array as $key => $value) {
            if (call_user_func($callback, $value, $key)) {
                return $value;
            }
        }
        return value($default);
    }

    /**
     * Return the last element in an array passing a given truth test.
     *
     * @param null|mixed $default
     */
    public static function last(array $array, callable $callback = NULL, $default = NULL)
    {
        if (is_null($callback)) {
            return empty($array) ? value($default) : end($array);
        }
        return static::first(array_reverse($array, TRUE), $callback, $default);
    }

    /**
     * 用键名(key)重新组合数组
     *
     * @param $array array 原数组
     * @param $key string 组成新数组的主键的字段
     *
     * @return array
     */
    public static function arrayResetKey(array $array, $key = "id")
    {
        $ret = [];
        if (!static::accessible($array)) {
            return $array;
        }
        foreach ($array as $k => $val) {
            if (isset($val[$key])) {
                $new_key = $val[$key];
                unset($array[$k]);
                $ret[$new_key] = $val;
            }
        }
        return $ret;
    }

    /**
     * 转换关联数组为索引数组可用格式(饼图专用)
     *
     * @param array $arr
     * @param integer $mode 0标题位置后面跟着数字,1标题位置仅标题
     *
     * @return array  exp[0=>[$name, $num],1=>[$name, $num]]
     */
    public static function toPie(array $arr, $mode = 0)
    {
        $formatPieChart = [];
        foreach ($arr as $key => $value) {
            $value = NumberHelper::moneyFloat($value);
            $name = $key;
            if ($mode == 0) {
                $name = $key . "     " . $value;
            }
            $num = $value;
            $formatPieChart[] = [$name, $num];
        }
        return $formatPieChart;
    }


    /**
     * 使用$key_map替换数组中的索引名字
     *
     * @param array $source_arr 原数组
     * @param string $key_map 索引名映射数组 e.g.[src_key=>dest_key]
     *
     * @return array
     */
    public static function arrayKeysReplace(array &$source_arr, array $key_map = [])
    {
        if (empty($key_map)) {
            return $source_arr;
        }
        foreach ($source_arr as $key => $value) {
            if (isset($key_map[$key]) && $key_map[$key] != $key) {
                unset($source_arr[$key]);
                $source_arr[$key_map[$key]] = $value;
            }
        }
        return $source_arr;
    }

    /**
     * 数组求交集,跳过所有空数组和非数组
     *
     * @param array ...$arrays
     *
     * @return array
     */
    public static function arrayIntersectNotEmpty(array ...$arrays)
    {
        $ret_arr = [];
        foreach ($arrays as $index => $array) {
            if (empty($array) || FALSE == is_array($array)) {
                continue;
            } else {
                if (empty($ret_arr)) {
                    $ret_arr = $array;
                } else {
                    $ret_arr = array_intersect($ret_arr, $array);
                }
            }
        }
        return $ret_arr;
    }

    /**
     * 数组合并<br>
     * 合并第一个数组(key,val)为空,剩余数组(key=>val)不为空的项
     *
     * @param array ...$arrays
     *
     * @return array
     */
    public static function arrayMergeNotEmpty(array ...$arrays)
    {
        $ret_arr = [];
        foreach ($arrays as $index => $array) {
            if ($index == 0) {
                $ret_arr = $array;
                continue;
            }
            foreach ($ret_arr as $key => $value) {
                if (empty($ret_arr[$key]) && !empty($array[$key])) {
                    $ret_arr[$key] = $array[$key];
                }
            }
        }
        return $ret_arr;
    }

    /**
     * 判断数组是否为索引数组(key是数字)
     *
     * @param $arr
     *
     * @return bool
     */
    public static function isIndexedArray($arr)
    {
        if (static::accessible($arr)) {
            return count(array_filter(array_keys($arr), 'is_string')) === 0;
        }
        return FALSE;
    }

    /**
     * 判断数组是否为连续的索引数组
     * 以下这种索引数组为非连续索引数组
     * [
     *   0 => 'a',
     *   2 => 'b',
     *   3 => 'c',
     *   5 => 'd',
     * ]
     */
    public static function isContinuousIndexedArray($arr)
    {
        if (static::accessible($arr)) {
            $keys = array_keys($arr);
            return $keys == array_keys($keys);
        }
        return FALSE;
    }


    /**
     * 判断数组是否为关联数组(key是字符串)
     */
    public static function isAssocArray($arr)
    {
        if (static::accessible($arr)) {
            return count(array_filter(array_keys($arr), 'is_string')) === count($arr);
        }
        return FALSE;
    }


    /**
     * 判断数组是否为混合数组
     */
    public static function isMixedArray($arr)
    {
        if (static::accessible($arr)) {
            $count = count(array_filter(array_keys($arr), 'is_string'));
            return $count !== 0 && $count !== count($arr);
        }
        return FALSE;
    }

    /**
     * 剔除数组中的部分字段
     *
     * @param array $source_arr
     * @param array $fields ,['key1','key2','key3'] or "key1,key2,key3"
     * @param array $mode 1:仅保留$fields里面的字段,2:删除$fields里面的字段
     *
     * @return array
     */
    public static function arrayColumn($source_arr, $fields, $mode = 1)
    {
        if (empty($source_arr)) {
            return $source_arr;
        }
        if (is_string($fields)) {
            //"key1,key2,key3"转换为数组
            if (FALSE === strpos($fields, ',')) {
                return $source_arr;
            }
            $fields = explode(',', $fields);
        }
        if (static::accessible($fields) || static::accessible($source_arr)) {
            if (!empty(self::isContinuousIndexedArray($source_arr))) {
                //索引数组
                foreach ($source_arr as $key => $value) {
                    $source_arr[$key] = self::arrayColumn($value, $fields, $mode);
                }
            } else {
                //混合数组或者关联数组
                if ($mode == 1) {
                    //模式一,提取模式
                    foreach ($source_arr as $key => $value) {
                        if (FALSE === in_array($key, $fields)) {
                            unset($source_arr[$key]);
                        }
                    }
                } else {
                    //模式二,删除模式
                    foreach ($fields as $value) {
                        unset($source_arr[$value]);
                    }
                }
            }
        }
        return $source_arr;
    }


    /**
     * 分组为二维数组
     *
     * @param array $arr
     * @param string $key
     *
     * @return array
     */
    public static function arrayGroup(array $arr, string $key)
    {
        $grouped = [];
        foreach ($arr as $value) {
            $new_key = !empty($value[$key]) ? $value[$key] : '';
            $grouped[$new_key][] = $value;
        }
        if (func_num_args() > 2) {
            $args = func_get_args();
            foreach ($grouped as $index => $value) {
                $parms = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$index] = static::arrayGroup($parms);
            }
        }
        return $grouped;
    }

    /**
     * 返回列表load后的列表
     *
     * @param $list 列表
     * @param $load_key load的数据字段名
     *
     * @return array 合并后的load_key对应的load_list
     */
    public static function arrayColumnMerge($list, $load_key)
    {
        $ret = [];
        foreach ($list as $key => $value) {
            foreach ($value[$load_key] as $key2 => $value2) {
                $ret[] = $value2;
            }
        }
        return array_values($ret);
    }

    /**
     * 数组分级(父子)排序
     *
     * @param array $array
     * @param array $ret
     * @param array $param
     *
     * @return array|null
     */
    public static function arraySortChild(array &$array, array &$ret, $param = [])
    {
//      初始化
        $default_param =
            ['title_name' => 'title', 'id_name' => 'id', 'pid_name' => 'pid', 'pid_root' => 0, 'level' => 0];
        $param = array_merge($default_param, $param);
        $id_name = $param['id_name'];
        $pid_name = $param['pid_name'];
        $title_name = $param['title_name'];
        $pid_root = $param['pid_root'];
        $level = $param['level'];
        $level++;
//        循环的作用是为了找到$pid_root下的所有child
        foreach ($array as $key => $value) {
            if (FALSE == isset($value[$pid_name])) {
                return NULL;
            }
            if ($value[$pid_name] == $pid_root) {
                unset($array[$key]);
                //二级开始才需要改title
                for ($i = 2; $i <= $level; $i++) {
                    $value[$title_name] = '｜　　　' . $value[$title_name];
                }
                $ret[] = $value;
                //递归找$value['id']下的所有child
                $param['pid_root'] = $value[$id_name];
                $param['level'] = $level;
                static::arraySortChild($array, $ret, $param);
            }
        }
        return $ret;
    }

    /**
     * 数组转换成树
     *
     * @param array $list 要转换的数据集
     * @param string $pk ID标记字段
     * @param string $pid parent标记字段
     * @param string $child 子代key名称
     * @param string $root 返回的根节点ID
     * @param string $strict 默认严格模式
     *
     * @return array
     */
    public static function arrayToTree($list, $pk = 'id', $pid = 'pid', $child = 'child', $root = 0, $strict = TRUE)
    {
        // 创建Tree
        $tree = [];
        if (static::accessible($list)) {
            // 创建基于主键的数组引用
            $refer = [];
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parent_id = $data[$pid];
                //是否根节点
                if ($parent_id === NULL || (int)$root === $parent_id) {
                    // 根节点
                    $tree[] =& $list[$key];
                } else {
                    //根据parent_id找到parent
                    if (isset($refer[$parent_id])) {
                        $parent =& $refer[$parent_id];
                        //初始化parent的$child字段
                        if (empty($parent[$child])) {
                            $parent[$child] = [];
                        }
                        $parent[$child][] =& $list[$key];
                    } else {
                        //严格模式,没有找到parent就删除
                        if ($strict === FALSE) {
                            $tree[] =& $list[$key];
                        }
                    }
                }
            }
        }
        return $tree;
    }

    /**
     * 返回类型一定是数组
     *
     * @param $value
     * @param string $needle
     *
     * @return array|string[]
     */
    public static function ToTree($value, $needle = ',')
    {
        if (empty($value)) {
            return [];
        }
        if (static::accessible($value)) {
            return $value;
        }
        if (is_string($value)) {
            return strpos($value, $needle) == FALSE ? [$value] : explode($needle, $value);
        }
        if (is_numeric($value)) {
            return [$value];
        }
        return [];
    }


    /**
     * 返回json数据的时候,map格式可能出现顺序问题
     *
     * @param $arr
     *
     * @return array [ 'id' => $key,'val' => $value]
     */
    public static function mapToList($arr, $name1 = 'id', $name2 = 'val')
    {
        $ret = [];
        if (!empty($arr)) {
            if (static::isAssocArray($arr)) {
                foreach ($arr as $key => $value) {
                    $ret[$key] = static::mapToList($value);
                }
            } else {
                foreach ($arr as $key => $value) {
                    $ret[] = [
                        'id' => $key,
                        'val' => $value,
                    ];
                }
            }
            return $ret;
        }
        return $arr;
    }


    /**
     * 使数组元素唯一,可递归
     *
     * @param array $array
     *
     * @return array
     */
    public static function unique(array $array): array
    {
        return static::arrayUnique($array);
    }
    /**
     * 使数组元素唯一,可递归
     *
     * @param array $array
     *
     * @return array
     */
    public static function arrayUnique(array $array): array
    {
        $result = [];
        $array = !empty($array) ? $array : [];
        foreach ($array as $key => $item) {
            if (FALSE === in_array($item, $result, TRUE)) {
                $result[$key] = $item;
            }
        }
        return $result;
    }

    /**
     * 无限极列表
     *
     * @param $user_message_list
     * @param $list
     * @param string $master_id
     * @param string $parent_field
     * @param string $chield_field
     */
    public static function unlimitList(&$user_message_list, &$ret, $master_id = '0', $parent_field = 'id',
        $chield_field = 'pid')
    {
        foreach ($user_message_list as $key => $value) {
            if ($value[$chield_field] == $master_id) {
                $ret[] = $value;
                unset($user_message_list[$key]);
                static::unlimitList($user_message_list, $ret, $value[$parent_field],
                    $parent_field, $chield_field);
            }
        }
    }

    /**
     * 无限极树
     *
     * @param $list
     * @param $ret
     * @param string $master_id
     * @param string $parent_field
     * @param string $chield_field
     */
    public static function unlimitTree(&$list, &$ret, $master_id = '0', $parent_field = 'id',
        $chield_field = 'pid')
    {
        $temp_list = [];
        foreach ($list as $key => $value) {
            if ($value[$chield_field] == $master_id) {
                static::unlimitTree($list, $value, $value[$parent_field],
                    $parent_field, $chield_field);
                $temp_list[] = $value;
                unset($list[$key]);
            }
        }
        if (!empty($ret)) {
            $ret['child'][] = $temp_list;
        } else {
            $ret = $temp_list;
        }
    }
}
