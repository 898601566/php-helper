<?php
/**
 * User: zhengze
 * Date: 2019/7/2
 * Time: 17:45
 */

namespace Helper;

/**
 * Class ArrayHelper
 * @package common
 */
class ArrayHelper
{
    /**
     * 返回数组指定字段的值组成的数组
     * @param $array array 原数组
     * @param $key string 组成新数组的主键的字段
     * @return array
     */
    public static function array_under_reset(array $array, $key = "id")
    {
        if (!is_array($array)) return $array;
        foreach ($array as $k => $val) {
            if (isset($val[$key])) {
                $new_key = $val[$key];
                unset($array[$k]);
                $array[$new_key] = $val;
            }
        }
        return $array;
    }

    /**
     * 转换关联数组为索引数组可用格式(饼图专用)
     * @param array $arr
     * @param type $mode 0标题位置后面跟着数字,1标题位置仅标题
     * @return Arr  exp[0=>[$name, $num],1=>[$name, $num]]
     */
    public static function to_pie(array $arr, $mode = 0)
    {
        $formatPieChart = [];
        foreach ($arr as $key => $value) {
            $value = money_float($value);
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
     * @param array $source_arr 原数组
     * @param string $key_map 索引名映射数组 e.g.[src_key=>dest_key]
     * @return array
     */
    public static function array_keys_replace(array &$source_arr, array $key_map = [])
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
     * array_merge但是保留key(当索引是数字时,array_merge会重新设置索引)
     * @param array $source_arr1 数组1
     * @param string $source_arr2 数组2
     * @return array 目标数组
     */
    public static function array_merge_plus(array $source_arr1)
    {
        $args_num = func_num_args();
        $args_arr = func_get_args();
        for ($i = 1; $i < $args_num; $i++) {
            foreach ($args_arr[$i] as $key => $value) {
                $source_arr1[$key] = $value;
            }
        }
        return $source_arr1;
    }

    /**
     * 数组合并,只合并数组一值为空,数组二值不为空的项
     * @param $arr1
     * @param $arr2
     * @return mixed
     */
    public function array_merge_not_empty($arr1, $arr2)
    {
        foreach ($arr1 as $key => $value) {
            if (empty($arr1[$key]) && !empty($arr2[$key])) {
                $arr1[$key] = $arr2[$key];
            }

        }
        return $arr1;
    }

    /**
     * 多数组合并,不理会键名
     * @param array $source_arr
     * @return array
     */
    public static function array_expand(array $source_arr)
    {
        $args_num = func_num_args();
        $args_arr = func_get_args();
        for ($i = 1; $i < $args_num; $i++) {
            $source_arr = array_merge($source_arr, $args_arr[$i]);
        }
        return $source_arr;
    }


    /**
     * 判断数组是否为索引数组
     */
    public static function is_indexed_array($arr)
    {
        if (is_array($arr)) {
            return count(array_filter(array_keys($arr), 'is_string')) === 0;
        }
        return false;
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
    public static function is_continuous_indexed_array($arr)
    {
        if (is_array($arr)) {
            $keys = array_keys($arr);
            return $keys == array_keys($keys);
        }
        return false;
    }


    /**
     * 判断数组是否为关联数组
     */
    public static function is_assoc_array($arr)
    {
        if (is_array($arr)) {
            // return !is_indexed_array($arr);
            return count(array_filter(array_keys($arr), 'is_string')) === count($arr);
        }
        return false;
    }


    /**
     * 判断数组是否为混合数组
     */
    public static function is_mixed_array($arr)
    {
        if (is_array($arr)) {
            $count = count(array_filter(array_keys($arr), 'is_string'));
            return $count !== 0 && $count !== count($arr);
        }
        return false;
    }

    /**
     * 提取数组中的部分字段
     * @param array $source_arr
     * @param array $fields ,可以传多个字符串,也可以传一个key组成的数组,e.g. ['key1','key2','key3']
     * @return array
     */
    public static function array_column_plus($source_arr, $fields)
    {
        if (empty($source_arr)) {
            return $source_arr;
        }
        if (is_string($fields)) {
            if (false === strpos($fields, ',')) {
                return array_column($source_arr, $fields);
            }
            $fields = explode(',', $fields);
        }
        if (is_array($fields)) {
            $destination = array_map(function ($one) use ($fields) {
                $temp = $this->array_value_filter($one, $fields);
                return $temp;
            }, $source_arr);
            return $destination;
        }
    }

    /**
     * 提取数组中的部分字段
     * @param array $source_arr
     * @param array $fields ,可以传多个字符串,也可以传一个key组成的数组,e.g. ['key1','key2','key3']
     * @return array
     */
    public static function array_value_filter($source_arr, $fields)
    {
        if (empty($source_arr)) {
            return $source_arr;
        }
        if (is_string($fields)) {
            if (false === strpos($fields, ',')) {
                return $source_arr;
            }
            $fields = explode(',', $fields);
        }
        if (is_array($fields)) {
            foreach ($source_arr as $key => $value) {
                if (false === in_array($key, $fields)) {
                    unset($source_arr[$key]);
                }
            }
            return $source_arr;
        }
    }

    /**
     * 二维数组分组
     */
    public static function array_group($arr, $key)
    {
        $grouped = [];
        foreach ($arr as $value) {
            $new_key = $value[$key] ?? '';
            $grouped[$new_key][] = $value;
        }
        if (func_num_args() > 2) {
            $args = func_get_args();
            foreach ($grouped as $index => $value) {
                $parms = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$index] = call_user_func_array('array_group', $parms);
            }
        }
        return $grouped;
    }

    /**
     * 二维数组提取数组(如:角色s->菜单s)
     * @param $source 原二维数组
     * @param $column_field 提取的数组字段名
     * @param null $unique_id 用来做unique的字段
     * @return array
     */
    public static function array_column_merge($source, $column_field, $unique_id = null)
    {
        $ret = [];
        foreach ($source as $key => $value) {
            foreach ($value[$column_field] as $key2 => $value2) {
                if (!empty($unique_id)) {
                    //unique的
                    $index = $value2[$unique_id];
                    $ret[$index] = $value2;
                } else {
                    //不unique的
                    $ret[] = $value2;
                }
            }
        }
        return array_values($ret);
    }

    /**
     * 数组分级(父子)排序
     * @param array $array
     * @param array $ret
     * @param array $param
     * @return array|null
     */
    public static function array_sort_child(array &$array, array &$ret, $param = [])
    {
//      初始化
        $default_param = ['title_name' => 'title', 'id_name' => 'id', 'pid_name' => 'pid', 'pid_root' => 0, 'level' => 0];
        $param = array_merge($default_param, $param);
        $id_name = $param['id_name'];
        $pid_name = $param['pid_name'];
        $title_name = $param['title_name'];
        $pid_root = $param['pid_root'];
        $level = $param['level'];
        $level++;
//        循环的作用是为了找到$pid_root下的所有child
        foreach ($array as $key => $value) {
            if (false == isset($value[$pid_name])) {
                return null;
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
                static::array_sort_child($array, $ret, $param);
            }
        }
        return $ret;
    }

    /**
     * 数组转换成树
     * @param array $list 要转换的数据集
     * @param string $pk ID标记字段
     * @param string $pid parent标记字段
     * @param string $child 子代key名称
     * @param string $root 返回的根节点ID
     * @param string $strict 默认严格模式
     * @return array
     */
    public static function array_to_tree($list, $pk = 'id', $pid = 'pid', $child = 'child', $root = 0, $strict = true)
    {
        // 创建Tree
        $tree = array();
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parent_id = $data[$pid];
                //是否根节点
                if ($parent_id === null || (int)$root === $parent_id) {
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
                        if ($strict === false) {
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
     * @param $value
     * @param string $needle
     * @return array|string[]
     */
    public static function to_array($value, $needle = ',')
    {
        if (empty($value)) {
            return [];
        }
        if (is_array($value)) {
            return $value;
        }
        if (is_string($value)) {
            return strpos($value, $needle) == false ? [$value] : explode($needle, $value);
        }
        if (is_numeric($value)) {
            return [$value];
        }
        return [];
    }


    /**
     * 返回json数据的时候,map格式可能出现顺序问题
     * @param $arr
     * @return array [ 'id' => $key,'val' => $value]
     */
    public static function mapToArray($arr)
    {
        $ret = [];
        if (!empty($arr)) {
            if (static::is_assoc_array($arr)) {
                foreach ($arr as $key => $value) {
                    $ret[$key] = static::mapToArray($value);
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
}
