<?php
require_once __DIR__ . "/vendor/autoload.php";
error_reporting(E_ALL);
ini_set('display_errors', 'ON');
$a = [
    [
        'aaa' => 1,
        'bbb' => [
            ['a' => 'a1', 'b' => 1],
            ['a' => 'a2', 'b' => 2],
            ['a' => 'a3', 'b' => 3],
        ],
    ],
    [
        'aaa' => 2,
        'bbb' => [
            ['a' => 'a11', 'b' => 21],
            ['a' => 'a12', 'b' => 22],
            ['a' => 'a13', 'b' => 23],
        ],
    ],
    [
        'aaa' => 3,
        'bbb' => [
            ['a' => 'a21', 'b' => 221],
            ['a' => 'a22', 'b' => 222],
            ['a' => 'a23', 'b' => 223],
        ],
    ],
];

$ret = Helper\ArrayHelper::arrayColumnMerge($a, 'bbb', 'a');
sdump($a, $ret);

