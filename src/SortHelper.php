<?php

namespace Helper;



use Helper\traits\InstanceTrait;

/**
 * 排序类
 * Class SortHelper
 * @package Helper
 */
class SortHelper
{
    use InstanceTrait;

    /**
     * 交换数组的值
     * @param $arr
     * @param $index1
     * @param $index2
     */
    public function array_swap(&$arr, $index1, $index2)
    {
        $temp = $arr[$index1];
        $arr[$index1] = $arr[$index2];
        $arr[$index2] = $temp;
    }

    /**
     * 合并数组
     * @param $arr1
     * @param $arr2
     *
     * @return mixed
     */
    public function array_union($arr1, $arr2)
    {
        foreach ($arr2 as $key => $value) {
            $arr1[] = $value;
        }
        return $arr1;
    }

    /**
     * 创建随机数字数组
     * @param $num
     * @param $max
     *
     * @return array
     */
    public function createArr($num, $max)
    {
        $arr = [];
        for ($i = 0; $i < $num; $i++) {
            $arr[] = rand(0, $max);
        }
        return $arr;
    }

    /**
     * 打印数组
     * @param $arr
     * @param string $name
     *
     * @return bool
     */
    public function printArr($arr, $name = "")
    {
        if ($name) {
            echo "$name<br>";
        }
        foreach ($arr as $key => $value) {
            echo "$value,";
        }
        echo '<br>';
        return TRUE;
    }

    /**
     * 泡沫排序(冒泡排序)
     * 相邻元素两两比较,每轮将最大值冒泡到末尾
     * 时间复杂度 O(n²),原地排序
     * @param $arr
     *
     * @return mixed
     */
    public function bubble(&$arr)
    {
        $end_index = count($arr) - 1;
        for ($i = 0; $i <= $end_index; $i++) {
            for ($j = 0; $j <= $end_index - $i - 1; $j++) {
                if ($arr[$j + 1] < $arr[$j]) {
                    $this->array_swap($arr, $j, $j + 1);
                }
            }
        }
        return $arr;
    }

    /**
     * 快速排序获取支点
     * @param $arr
     * @param $left_index
     * @param $right_index
     *
     * @return int
     */
    protected function getPivot(&$arr, $left_index, $right_index)
    {
        $flag = $arr[$left_index];
        while ($left_index < $right_index) {
            while ($left_index < $right_index && $flag <= $arr[$right_index]) {
                $right_index--;
            }
            $this->array_swap($arr, $left_index, $right_index);
            while ($left_index < $right_index && $arr[$left_index] <= $flag) {
                $left_index++;
            }
            $this->array_swap($arr, $left_index, $right_index);
        }
        return $left_index;
    }

    /**
     * 快速排序
     * 以支点元素为界分成两部分,递归排序后合并
     * 时间复杂度平均 O(nlogn),原地排序
     * @param $arr
     * @param $left_index 排序区间左边界
     * @param $right_index 排序区间右边界
     *
     * @return mixed
     */
    public function quickSort(&$arr, $left_index, $right_index)
    {
        if ($right_index > $left_index) {
            $pivot = $this->getPivot($arr, $left_index, $right_index);
            $left = $this->quickSort($arr, $left_index, $pivot - 1);
            $right = $this->quickSort($arr, $pivot + 1, $right_index);
        }
        return $arr;
    }

    /**
     * 桶排序
     * 以最小值到最大值为桶编号,同值元素入同桶,依桶号顺序输出
     * 适用于非负整数且值域跨度不大的场景
     * @param $arr
     *
     * @return array|mixed
     */
    public function bucket(&$arr)
    {
        $max = max($arr);
        $min = min($arr);
        $bucket = array_fill($min, $max - $min + 1, []);
        foreach ($arr as $key => $value) {
            $bucket[$value][] = $value;
        }
        $arr = [];
        foreach ($bucket as $key => $value) {
            $arr = $this->array_union($arr, $value);
        }
        return $arr;
    }

    /**
     * 基排步进
     * @param $arr
     * @param $offset
     *
     * @return array|mixed
     */
    protected function radixSortStep(&$arr, $offset)
    {
        $bucket = array_fill(0, 10, []);
        foreach ($arr as $key => $value) {
            $num = strlen($value) < $offset ? 0 : substr($value, -$offset, 1);
            $bucket[$num][] = $value;
        }
        $arr = [];
        foreach ($bucket as $key => $value) {
            $arr = $this->array_union($arr, $value);
        }
        return $arr;
    }

    /**
     * 基排(基数排序)
     * 从个位到最高位按位依次分桶收集,时间复杂度 O(d·n)
     * 适用于非负整数
     * @param $arr
     *
     * @return array|mixed
     */
    public function radixSort(&$arr)
    {
        $max_length = strlen(max($arr));
        for ($i = 1; $i <= $max_length; $i++) {
            $arr = $this->radixSortStep($arr, $i);
        }
        return $arr;
    }

    /**
     * 选择排序
     * 每轮从未排序区间选出最小值,放到已排序区间末尾
     * 时间复杂度 O(n²),原地排序
     * @param $arr
     *
     * @return mixed
     */
    public function selectSort(&$arr)
    {
        $end_index = count($arr) - 1;
        for ($i = 0; $i <= $end_index; $i++) {
            $min_index = $i;
            for ($j = $i; $j <= $end_index; $j++) {
                if ($arr[$j] <= $arr[$min_index]) {
                    $min_index = $j;
                }
            }
            $this->array_swap($arr, $i, $min_index);
        }
        return $arr;
    }

    /**
     * 插入排序
     * 将当前元素插入前面已排序区间的正确位置
     * 时间复杂度 O(n²),对近乎有序的数据表现较好
     * @param $arr
     *
     * @return mixed
     */
    public function insertSort(&$arr)
    {
        $end_index = count($arr) - 1;
        for ($i = 0; $i <= $end_index; $i++) {
            for ($j = $i - 1; $j >= 0 && $arr[$j] > $arr[$j + 1]; $j--) {
                $this->array_swap($arr, $j, $j + 1);
            }
        }
        return $arr;
    }

    /**
     * 希尔排序
     * 插入排序的改进版,按增量gap分组做插入排序,增量逐轮减半
     * 时间复杂度取决于增量序列,约 O(n^1.3)
     * @param $arr
     *
     * @return mixed
     */
    public function shellSort(&$arr)
    {
        $count = count($arr);
        $end_index = $count - 1;
        for ($gap = intval($count / 2); $gap >= 1; $gap = intval($gap / 2)) {
            for ($i = $gap; $i <= $end_index; $i++) {
                for ($j = $i - $gap; $j >= 0 && $arr[$j] > $arr[$j + $gap]; $j -= $gap) {
                    $this->array_swap($arr, $j, $j + $gap);
                }
            }
        }
        return $arr;
    }

    /**
     * 变换堆
     * @param $arr
     * @param $top_index
     * @param $end_index
     *
     * @return mixed
     */
    protected function changeHeap(&$arr, $top_index, $end_index)
    {
        $largest_index = $top_index;
        $left_index = $top_index * 2 + 1;
        $right_index = $left_index + 1;
        if ($right_index <= $end_index && $arr[$right_index] > $arr[$largest_index]) {
            $largest_index = $right_index;
        }
        if ($left_index <= $end_index && $arr[$left_index] > $arr[$largest_index]) {
            $largest_index = $left_index;
        }
        if ($top_index != $largest_index) {
            $this->array_swap($arr, $top_index, $largest_index);
            $this->changeHeap($arr, $largest_index, $end_index);
        }
        return $arr;
    }

    /**
     * 堆排(堆排序)
     * 先建大顶堆,再逐次交换堆顶与末尾元素并调整堆
     * 时间复杂度 O(nlogn),原地排序
     * @param $arr
     *
     * @return mixed
     */
    public function heapSort(&$arr)
    {
        $count = count($arr);
        $end_index = $count - 1;
        for ($i = ceil($count / 2); $i >= 0; $i--) {
            $arr = $this->changeHeap($arr, $i, $end_index);
        }
        for ($i = $end_index; $i >= 0; $i--) {
            $this->array_swap($arr, 0, $i);
            $this->changeHeap($arr, 0, $i - 1);
        }
        return $arr;
    }

    /**
     * 合并排序步进
     * @param $left
     * @param $right
     *
     * @return mixed
     */
    protected function mergeSortStep(&$left, &$right)
    {
        $left_index = 0;
        $right_index = 0;
        $left_end_index = count($left) - 1;
        $right_end_index = count($right) - 1;
        $temp_arr = [];
        while ($left_index <= $left_end_index && $right_index <= $right_end_index) {
            if ($left[$left_index] > $right[$right_index]) {
                $temp_arr[] = $right[$right_index++];
            } else {
                $temp_arr[] = $left[$left_index++];
            }
        }
        $temp_arr = $this->array_union($temp_arr, array_slice($left, $left_index));
        $temp_arr = $this->array_union($temp_arr, array_slice($right, $right_index));
        return $temp_arr;
    }

    /**
     * 合并排序(归并排序)
     * 递归二分数组,再逐层合并两个有序数组
     * 时间复杂度稳定 O(nlogn),非原地,返回新数组
     * @param $arr
     *
     * @return mixed
     */
    public function mergeSort($arr)
    {
        if (count($arr) <= 1) {
            return $arr;
        } else {
            $count = count($arr);
            $mid = intval($count / 2);
            $left = $this->mergeSort(array_slice($arr, 0, $mid));
            $right = $this->mergeSort(array_slice($arr, $mid));
            return $this->mergeSortStep($left, $right);
        }
    }
}

